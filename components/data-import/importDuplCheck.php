<?php
	session_start();
	set_time_limit(3600);
	ignore_user_abort(true);
	ini_set("error_log", "./php-error.log");
	ini_set("log_errors", 1);
	error_reporting(E_ALL);
	ini_set('auto_detect_line_endings',true); //NEEDED FOR CSV FILE IMPORT TO WORK


	//GRAB NECESSARY SESSION VARIABLES BEFORE DISCONNECTING THIS SCRIPT FROM THE SESSION
	//SESSION IS CLOSED FOR PERFORMANCE AND TO AVOID MULTIPLE SCRIPTS WITH THE SAME SESSION OPEN
	$logFilePath		= $_SESSION['import']['log_file_path'];
	$reportType			= $_SESSION['import']['reportType'];
	$importID			= $_SESSION['import']['ID'];
	$clientID 			= $_SESSION['import']['client_ID'];

	//VARS TRANSFERRED FROM importProgress.php
	$totalRows 			= $_SESSION['import']['varTransfer']['totalRows'];
	$expNumRows 		= $_SESSION['import']['varTransfer']['expNumRows'];
	$maxID	 			= $_SESSION['import']['varTransfer']['maxID'];
	$conversionErrors 	= $_SESSION['import']['varTransfer']['conversionErrors'];
	$duplCheckChunk 	= $_SESSION['import']['varTransfer']['duplCheckChunk'];
	$duplCheckChunkSize	= $_SESSION['import']['varTransfer']['duplCheckChunkSize'];
	$deletedRowCount	= $_SESSION['import']['varTransfer']['deletedRowCount'];

	session_write_close();

	require './include/common/db.php';
	require './data/importFunctions.php';
	require './include/common/globals.php';
	require './data/dataRules/CTL.php';

	//INITIALIZE VARS
	$destinationTable = $clientID . "_DAT_" . $reportType . "_" . $dataRules[ $reportType ]['tableNameSuffix'];
	$sourceFlag = $importID;

	$fieldNamesDuplCheck = array();
	foreach ( $dataRules[$reportType]['sourceCols'] as $fieldRules) {
		if ($fieldRules['checkDupl'] == true && $dataRules[$reportType]['checkDuplMethod'] == "normal") {
			$fieldNamesDuplCheck[] = $fieldRules['AmetrixField'];
		}
	}

	if(db_connection()) {
		//DELETE DUPLICATES
		//BUILD DUPLICATE DELETION SQL
		if ($dataRules[$reportType]['checkDuplMethod'] == "normal") {
			if($duplCheckChunk == 0) {importLog(8,1,0);}
			else {importLog(8,5,$duplCheckChunk,"Ametrix Duplication Protection Reinitialized successfully at Chunk ");}

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

			//Calculate appropriate chunk size if it has not happened yet, otherwise keep using the same size as for the whole import
			$duplCheckChunkSize = ($duplCheckChunk == 0 ? max(max((500 - (floor(($maxID + $expNumRows) / 25000) * 100)),100),500) : $duplCheckChunkSize);

			for($i = $duplCheckChunk;$i <= (ceil($expNumRows / $duplCheckChunkSize) * $duplCheckChunkSize);$i += $duplCheckChunkSize) {
				//BUILD THE LAST PART OF THE DUPLICATE DELETION SQL
				$deleteDuplSQL = $deleteDuplSQLprefix . '(T1.ID BETWEEN "' . ($maxID + 1 + $i) . '" AND "' . ($maxID + 1 + $i + $duplCheckChunkSize) . '");';

				//QUERY IT UP! BOOM!
				if(mysqli_query($db_conn,$deleteDuplSQL)) {
					$deletedRowCount += mysqli_affected_rows($db_conn);

					$duplCheckPercent = ( round((($i + 1) / $expNumRows) * 100) >= 100 ? 100 : round((($i + 1) / $expNumRows) * 100) );
					importLog(8,1,$duplCheckPercent);

					//JUMP TO ANOTHER SCRIPT TO AVOID TIMEOUT (IF NEEDED)
					if ((time() - $_SERVER['REQUEST_TIME']) > 240) {
						session_start();
						//SAVE VARS FOR USE WHEN SCRIPT RESTARTS
						$_SESSION['import']['varTransfer']['duplCheckChunk'] 		= $i + $duplCheckChunkSize;
						$_SESSION['import']['varTransfer']['duplCheckChunkSize']	= $duplCheckChunkSize;	
						$_SESSION['import']['varTransfer']['deletedRowCount']		= $deletedRowCount;
						importLog(8,7);
						exit();
					}
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
		importLog(9,1);
		$SQLstr = 'SELECT COUNT(*) FROM ' . $destinationTable . ' WHERE source_flag = "' . $importID . '";';
		$finalCountQuery = mysqli_query($db_conn, $SQLstr);
		$finalCountArray = mysqli_fetch_array($finalCountQuery);
		$detectedRows = $finalCountArray[0]; 
		unset($finalCountQuery, $finalCountArray); //CLEANUP!

		$expectedRows = $totalRows - 1 - count($conversionErrors) - $deletedRowCount;

		importLog(9,5,$expectedRows,('Source Rows (' . ($totalRows - 1) . ') - Conversion Errors (' . count($conversionErrors) . ') - Duplicates Removed (' . $deletedRowCount . ') = Expected Row Count: '));
		importLog(9,5,$detectedRows,'Detected Row Count: ');

		if ($detectedRows == $expectedRows) {
			importLog(9,4);
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

		singleField($db_importSeshTbl, 'status', $importID, "SET", "Complete");
	}
	else {
		importLog(8,3);
		importAbort(8);
	}


?>