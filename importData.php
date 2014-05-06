<?php
	session_start();
	set_time_limit(3600);
	ignore_user_abort(true);
	ini_set("error_log", "./php-error-importData.log");
	ini_set("log_errors", 1);
	error_reporting(E_ALL);
	ini_set('auto_detect_line_endings',true); //NEEDED FOR CSV FILE IMPORT TO WORK


	//GRAB NECESSARY SESSION VARIABLES BEFORE DISCONNECTING THIS SCRIPT FROM THE SESSION
	//SESSION IS CLOSED FOR PERFORMANCE AND TO AVOID MULTIPLE SCRIPTS WITH THE SAME SESSION OPEN
	$sourceFilePath 	= $_SESSION['import']['file_path'];
	$logFilePath		= $_SESSION['import']['log_file_path'];
	$reportType			= $_SESSION['import']['reportType'];
	$importID			= $_SESSION['import']['ID'];
	$clientID 			= $_SESSION['import']['client_ID'];

	session_write_close();

	require './include/common/db.php';
	require './data/importFunctions.php';
	require './include/common/globals.php';
	require './include/Classes/PHPExcel.php';
	require './data/dataRules/CTL.php';
	
	importLog(3,4); //Log Ametrix Data Processor Initialized
	singleField($db_importSeshTbl, 'status', $importID, "SET", "Working");

	//PHPExcel INITIALIZATION
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

	class chunkReadFilter implements PHPExcel_Reader_IReadFilter { 
	    private $_startRow = 0; 
	    private $_endRow   = 0; 

	    //**  Set the list of rows that we want to read  
	    public function setRows($startRow, $chunkSize) { 
	        $this->_startRow = $startRow; 
	        $this->_endRow   = $startRow + $chunkSize; 
	    } 

	    public function readCell($column, $row, $worksheetName = '') { 
	        //  Only read the configured rows 
	        if ($row >= $this->_startRow && $row < $this->_endRow) { 
	            return true; 
	        } 
	        return false; 
	    } 
	} 

	//SET UP THE PHPEXCEL READER
		
	//Create Reader
	$fileType = PHPExcel_IOFactory::identify($sourceFilePath);  // Identify File Type
	$reader   = PHPExcel_IOFactory::createReader($fileType);   // Create a new Reader of the type defined in $inputFileType

	//Store necessary meta data of worksheets then clean up $spreadsheetMeta
	$spreadsheetMeta = $reader->listWorksheetInfo($sourceFilePath);
	$totalRows = $spreadsheetMeta[ $dataRules[ $reportType ]['worksheetToRead'] ]['totalRows'];
	$totalCols = $spreadsheetMeta[ $dataRules[ $reportType ]['worksheetToRead'] ]['totalColumns'];
	$sheetName = $spreadsheetMeta[ $dataRules[ $reportType ]['worksheetToRead'] ]['worksheetName'];
	unset($spreadsheetMeta); //CLEANUP!

	//Use $dataRules to set the reader to only load the desired worksheet
	$reader->setLoadSheetsOnly( $sheetName );
	$reader->setReadDataOnly(true);

	//Check that input data has expected number of columns
	$expNumCols = $dataRules[ $reportType ]['expNumCols'];
	if ( $totalCols == $expNumCols ) {importLog(4,4,$expNumCols);}
	else {importLog(4,2,$totalCols);importAbort(4);}

	if(db_connection()) {
		importLog(5,4);

		//READY VARIABLES FOR MYSQL IMPORT
		$destinationTable = $clientID . "_DAT_" . $reportType . "_" . $dataRules[ $reportType ]['tableNameSuffix'];
		$sourceFlag = $importID;
		$conversionErrors = array();

		//STORE NAMES OF FIELDS FOR USE AND DUPLICATE CHECKING
		$fieldNamesToUse = array();
		//$fieldNamesDuplCheck = array();
		foreach ( $dataRules[$reportType]['sourceCols'] as $fieldRules) {
			if ($fieldRules['use']) {
				$fieldNamesToUse[] = $fieldRules['AmetrixField'];
			}
			/*if ($fieldRules['checkDupl'] == true && $dataRules[$reportType]['checkDuplMethod'] == "normal") {
				$fieldNamesDuplCheck[] = $fieldRules['AmetrixField'];
			}*/
		}

		//BUILD SQL INSERT STRING PREFIX
		$insertSQLprefix = 'INSERT INTO ' . $destinationTable . '(ID, source_flag, ' . implode(", ", $fieldNamesToUse) . ') VALUES("';

		//Get the highest Unique ID of the destination table, so that data can be added after it
		$maxID = getMaxID($destinationTable);

		//GET READY FOR DATA READING AND CLEANING
		require './data/validate.php';

		//SET CHUNK READER
		$chunkSize = 4096;
		$chunkFilter = new chunkReadFilter();
		$reader->setReadFilter($chunkFilter);

		importLog(6,1,0);

		$sourceRowIndex = 1;
		$chunkIteration = 0;
		for ($startRow = $sourceRowIndex; $startRow <= $totalRows; $startRow += $chunkSize) { 
			$chunkIteration++;

			try {
				//**  Tell the Read Filter which rows we want this iteration  *
			    $chunkFilter->setRows($startRow,$chunkSize); 
			    //**  Load only the rows that match our filter  **
			    $objPHPExcel = $reader->load( $sourceFilePath ); 

			    $sheetData = $objPHPExcel->getActiveSheet()->toArray("",true,false,false);
			}
			catch (Exception $e) {
				importLog(6,2,$chunkIteration,(" Error Loading Chunk " . $chunkIteration . ". Check source Data."));
				importAbort(6);
			}
		    

		    //DELETE ALL OF THE EMPTY ROWS IN SHEETDATA THAT ARE AUTOMATICALLY PUT THERE BY THE READER
		    //THIS MAKES EVERY SHEETDATA ARRAY THE SAME SIZE (CHUNKSIZE) BEFORE IT GETS TO THE PROCESSING LOOPS
		    $preChunkRow = 1;
		    foreach ($sheetData as $arrayKey => $row) {
		    	if ( $preChunkRow <= (($chunkIteration - 1) * $chunkSize) ) { 
		    		unset($sheetData[$arrayKey]);
		    		$preChunkRow++;
		    	}
		    	else {break;}
		    }
		    
		    foreach ($sheetData as $row) {
		    	if ($sourceRowIndex == 1) {$sourceRowIndex++;continue;}

		    	$currSQLID = $maxID + $sourceRowIndex - 1; //-1 Because Header Row is skipped in source file
		    	$insertSQL = $insertSQLprefix . $currSQLID . '","' . $sourceFlag . '"';
		    	

		    	$cellIndex = 1;
		    	foreach ($row as $cell) {
		    		if($cellIndex > $expNumCols) {break;}

		    		if($dataRules[ $reportType ]['sourceCols'][$cellIndex]['use']) {
		    	   		$insertSQL .= ',"' . dataClean($cell, $dataRules[ $reportType ]['sourceCols'][$cellIndex]['dataType']) . '"';
		    		}

		    		$cellIndex++;
		    	}

		    	$insertSQL .= ');';

				if(!mysqli_query($db_conn,$insertSQL)) {  //WARNING GENERATED IF INSERT DOES NOT WORK
					importLog(6,3,$sourceRowIndex,mysqli_error($db_conn));
					$conversionErrors[] = $currSQLID;

					if(count($conversionErrors) >= 30) {
						importLog(6,93,30);
						importAbort(6);
					}
				}

				if($sourceRowIndex % 500 == 0) {  //PROGRESS UPDATE GENERATED
					$conversionPercent = round(($sourceRowIndex / $totalRows) * 100);
					importLog(6,1,$conversionPercent);
				}
				
		    	$sourceRowIndex++;
		    }
		    
		    //VERY IMPORTANT CLEANUP!!
		    $objPHPExcel->disconnectWorksheets(); 
		    unset($sheetData);
			unset($objPHPExcel); 
		} 

		importLog(6,4);
		importLog(6,5,count($conversionErrors),"Report: Total Source Data Rows with Data Conversion Warnings: ");

		//DATA VERIFICATION: CHECK THAT TEMP IMPORTED DATA HAS CORRECT NUMBER OF ROWS
		$expNumRows = $totalRows - 1 - count($conversionErrors); //The temp data should have all of the rows from the source file ($totalRows) minus the header row and minuse the number of rows that had errors
		$SQLstr = 'SELECT COUNT(*) FROM ' . $destinationTable . ' WHERE source_flag = "' . $importID . '";';
		$tempCountQuery = mysqli_query($db_conn, $SQLstr);
		$tempCountArray = mysqli_fetch_array($tempCountQuery);
		$tempDataCount = $tempCountArray[0];
		unset($tempCountQuery, $tempCountArray); //CLEANUP!
		if($tempDataCount == $expNumRows) {importLog(7,4, $tempDataCount);}
		else {importLog(7,2, $tempDataCount);importAbort(7);}


		session_start();

		$_SESSION['import']['varTransfer'] = array(
			'totalRows' 			=> $totalRows,
			'expNumRows' 			=> $expNumRows,
			'maxID' 				=> $maxID,
			'conversionErrors'	 	=> $conversionErrors,
			'duplCheckChunk' 		=> 0,
			'duplCheckChunkSize'	=> -1,
			'deletedRowCount' 		=> 0
		);

		session_write_close();
		

		/*/DELETE DUPLICATES
		//BUILD DUPLICATE DELETION SQL
		if ($dataRules[$reportType]['checkDuplMethod'] == "normal") {
			importLog(8,1,0);
			$deletedRowCount = 0;

			mysqli_query($db_conn,'SET SESSION SQL_BIG_SELECTS=1');

			//BUILD THE DUPLICATE DELETION SQL PREFIX (EVERYTHING BUT THE CHUNK FILTER(THE LAST STATEMENT IN THE LAST WHERE CLAUSE THAT FILTERS BETWEEN ID))
			$deleteDuplSQLprefix = 'DELETE T1 FROM ' . $destinationTable . ' T1 LEFT JOIN ';
			$deleteDuplSQLprefix .= '(SELECT ' . implode(", ",$fieldNamesDuplCheck) . ' FROM ' . $destinationTable . ' GROUP BY ' . implode(", ",$fieldNamesDuplCheck) . ' HAVING COUNT(*) = 1) T2 ON ';

			foreach ($fieldNamesDuplCheck as $fieldName) {
				$deleteDuplSQLprefix .= 'T1.' . $fieldName . ' = T2.' . $fieldName . ' AND ';
			}
			$deleteDuplSQLprefix = rtrim($deleteDuplSQLprefix, "AND "); //REMOVE LAST AND THAT WAS PUT ON BY THE PRECEDING FOREACH LOOP

			$deleteDuplSQLprefix .= ' WHERE T1.source_flag = "' . $importID . '" AND ';

			foreach ($fieldNamesDuplCheck as $fieldName) {
				$deleteDuplSQLprefix .= 'T2.' . $fieldName . ' IS NULL AND ';
			}

			//LOOP THROUGH CHUNKS OF THE IMPORTED DATA 
			//THIS AVOIDS ERRORS FROM HUGE JOINS
			$duplCheckChunkSize = 500;
			for($i = 0;$i <= (ceil($expNumRows / $duplCheckChunkSize) * $duplCheckChunkSize);$i += $duplCheckChunkSize) {
				//BUILD THE LAST PART OF THE DUPLICATE DELETION SQL
				$deleteDuplSQL = $deleteDuplSQLprefix . '(T1.ID BETWEEN "' . ($maxID + 1 + $i) . '" AND "' . ($maxID + 1 + $i + $duplCheckChunkSize) . '");';

				//QUERY IT UP! BOOM!
				if(mysqli_query($db_conn,$deleteDuplSQL)) {
					$deletedRowCount += mysqli_affected_rows($db_conn);

					$duplCheckPercent = ( round((($i + 1) / $expNumRows) * 100) >= 100 ? 100 : round((($i + 1) / $expNumRows) * 100) );
					importLog(8,1,$duplCheckPercent);
				}
				else {
					importLog(8,2,0,mysqli_error($db_conn));
					importAbort(8);
				}
			}

			importLog(8,4);
			importLog(8,5,"normal", "REPORT: Duplicate Check Method: ");
			importLog(8,5,$deletedRowCount, "REPORT: Number of Duplicate Rows Removed: ");
		}

		//DETECT ACTUAL COUNT OF IMPORTED ROWS
		$SQLstr = 'SELECT COUNT(*) FROM ' . $destinationTable . ' WHERE source_flag = "' . $importID . '";';
		$finalCountQuery = mysqli_query($db_conn, $SQLstr);
		$finalCountArray = mysqli_fetch_array($finalCountQuery);
		$detectedRows = $finalCountArray[0]; 
		unset($finalCountQuery, $finalCountArray); //CLEANUP!

		$expectedRows = $totalRows - 1 - count($conversionErrors) - $deletedRowCount;

		if ($detectedRows == $expectedRows) {
			importLog(9,4,$detectedRows,('Source Rows (' . ($totalRows - 1) . ') - Conversion Errors (' . count($conversionErrors) . ') - Duplicates Removed (' . $deletedRowCount . ') = Expected Rows (' . $expectedRows . ')'));
		}
		else {
			importLog(9,2,0);
			importAbort(9);
		}

		//FINAL REPORTS AND CLEAN UP
		//Calculate Import Session Elapsed Time
		$importEndDT = date('Y-m-d H:i:s');
		singleField($db_importSeshTbl, 'end_dt', $importID, "SET", $importEndDT); //Update End Datetime in Import Session Table
		$importStartDT = singleField($db_importSeshTbl, 'start_dt', $importID);
		$importTime = date_interval_format(date_diff(date_create_from_format('Y-m-d H:i:s', $importStartDT), date_create_from_format('Y-m-d H:i:s', $importEndDT)), '%H:%I:%S');

		//Log Final Info
		importLog(10,5,"", "IMPORT SESSION ID " . $importID . " SUMMARY");
		importLog(10,5,$importTime, 'Time Elapsed: ');
		importLog(10,5,($totalRows - 1), "Total Rows in Source Data: ");
		importLog(10,5,count($conversionErrors), "Total Ametrix Processing Warnings and Errors: ");
		importLog(10,5,$deletedRowCount, "Total Duplicate Rows Removed: ");
		importLog(10,5,($totalRows - 1 - count($conversionErrors) - $deletedRowCount), "Expected Number of Rows Imported: ");
		importLog(10,5,$detectedRows,"Detected Number of Rows Imported: ");
		importLog(10,4);

		singleField($db_importSeshTbl, 'status', $importID, "SET", "Complete");*/
	}
	else {
		importLog(5,3);
		importAbort(5);
	}

	
?>