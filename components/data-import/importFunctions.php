<?php

function importLog($stepIndex, $stepStatus = 4, $stepValue = -1, $stepText = "") {
	//NOTE: $stepStatus codes:
	// VALUE	STATUS				DESCRIPTION
	// 1 		Working 			Working on Current Step
	// 2 		Error				Error occurred at Current Step
	// 3 		Warning (Error)		Warning while working on Current Step (Caused by a non-fatal error)
	// 4 		Complete 			Finished Current Step Successfully
	// 5 		Report 				Reported Information after completing Step
	// 7		Jumping Scripts		Duplicate Protection Script is restarting to prevent server timeout issues
	// 93		Aborted (Warnings)	Import Session Aborted because of too many Warning at Current Step
	// 99		Aborted (Error)		Import Session Aborted because of an error

	//NOTE on $stepValue:
	// if $stepStatus == 1 (Working), then $stepValue = the progress of the step as a percentage
	// if $stepStatus == 3 (Warning), then $stepValue = the row in the Source Data File of the warning
	// if $stepStatus == 5 (Report),  then $stepValue = the relevant variable for the report

	global $db_conn, $db_importLogTbl;

	if(!isset($_SESSION['import']['log_file_path'])) {
		global $logFilePath, $importID;
	}
	else {
		$logFilePath 	= $_SESSION['import']['log_file_path'];
		$importID		= $_SESSION['import']['ID'];
	}

	//PART 1 OF 3: BUILD $stepText
	switch ($stepIndex) {
		case 1:
			if ($stepStatus == 4) {$stepText = "Data Import Session initialized successfully. Import Session ID " . $stepValue;}
			else if ($stepStatus == 2) {$stepText = "Failed to initialize Data Import Session.";}
		break;
		case 2:
			if ($stepStatus == 4) {$stepText = "Source Data File uploaded and saved successfully.";}
			else if ($stepStatus == 2) {$stepText = "Failed to save Source Data File.";}
		break;
		case 3:
			if ($stepStatus == 4) {$stepText = "Universal Data Processor initialized successfully.";}
			else if ($stepStatus == 2) {$stepText = "Failed to initialize Universal Data Processor.";}
		break;
		case 4:
			if ($stepStatus == 4) {$stepText = "Data Verification Success: Source Data File contains expected number of columns (" . $stepValue . ").";}
			else if ($stepStatus == 2) {$stepText = "Data Verification Fail: Source Data File contains unexpected number of columns. Check Source Data.";}
		break;
		case 5:
			if ($stepStatus == 4) {$stepText = "Connection to Database established successfully.";}
			else if ($stepStatus == 2) {$stepText = "Failed to connect to Database.";}
		break;
		case 6:
			if ($stepStatus == 1) {$stepText = "Analyzing Source Data and converting to valid format: " . $stepValue . "%";}
			else if ($stepStatus == 3) {$stepText = "Warning: There is a problem in the Source Data at row " . $stepValue . " " . $stepText;}
			else if ($stepStatus == 4) {$stepText = "Source Data converted to valid format successfully.";}
			else if ($stepStatus == 5) {$stepText = $stepText . $stepValue;}
			else if ($stepStatus == 2) {$stepText = "Error Converting Source Data to valid Format. " . $stepText;}
			else if ($stepStatus == 93) {$stepText = "Import Session Aborted: Warning Limit Exceeded while Converting Data to valid Format.";}
		break;
		case 7:
			if ($stepStatus == 4) {$stepText = "Data Verification Success: Converted Data contains expected number of rows.";}
			else if ($stepStatus == 2) {$stepText = "Data Verification Fail: Converted Data contains unexpected number of rows.";}
		break;
		case 8:
			if ($stepStatus == 1) {$stepText = "Duplicate Data Protection: Checking Converted Data against existing Data: " . $stepValue . "%";}
			else if ($stepStatus == 4) {$stepText = "Duplicate Data Protection Complete.";}
			else if ($stepStatus == 5) {$stepText = $stepText . $stepValue;}
			else if ($stepStatus == 7) {$stepText = "Server Timeout Protection Alert: Jumping Scripts.";}
			else if ($stepStatus == 2) {$stepText = "There was an error during the Duplicate Protection Check. " . $stepText;}
		break;
		case 9:
			if ($stepStatus == 1) {$stepText = "Beginning Final Row Count Verficition.";}
			else if ($stepStatus == 4) {$stepText = "Final Row Count Verification Success.";}
			else if ($stepStatus == 5) {$stepText = $stepText . $stepValue;}
			else if ($stepStatus == 2) {$stepText = "Final Row Count Verification Fail: Unexpected number of Rows.";}
		break;
		case 10:
			if ($stepStatus == 4) {$stepText = "Data Import Session Completed Successfully.";}
			else if ($stepStatus == 5) {$stepText = $stepText . $stepValue;}
			else if ($stepStatus == 2) {$stepText = "Error during Data Import Session Wrap-Up.";}
		break;
		case 99: //ABORTED!
			if ($stepStatus == 4) {$stepText = "Data Import Session Aborted Successfully at Step " . $stepValue . ".";} //stepValue = step Index reached before abort
			else if ($stepStatus == 2) {$stepText = "There was an error trying to Abort this Data Import Session.  There may be extraneous data in the Database.";}
		break;
	}
	$stepText = ( $stepIndex == 3 ? $stepText : mysqli_real_escape_string($db_conn, $stepText) );

	//PART 2 OF 3: ADD IMPORT LOG ENTRY TO DATABASE
	$SQLstr = 	'INSERT INTO ' . 
				$db_importLogTbl .
				' (ID, import_session_ID, step_index, step_text, step_status, step_value, sent_to_user) VALUES("' . 
				(getMaxID($db_importLogTbl) + 1) 	. '","' .
				$importID							. '","' .
				$stepIndex							. '","' .
				$stepText							. '","' .
				$stepStatus							. '","' .
				$stepValue							. '",0);';  //THE TRAILING ZERO IS FOR THE 'sent_to_user' field
	if(db_connection()) {mysqli_query($db_conn,$SQLstr);}

	//PART 3 OF 3: ADD IMPORT LOG ENTRY TO IMPORT LOG FILE
	$logStr = date( 'Y-m-d H:i:s') . ',' . $stepIndex . ',' . $stepText . ',' . $stepStatus . ',' . $stepValue . ',' . memory_get_usage() . "\n";
	file_put_contents($logFilePath, $logStr, FILE_APPEND);
}

function importAbort($stepIndex) {
	global $db_conn, $destinationTable, $importID, $db_importSeshTbl;

	if ($stepIndex >= 6) { //Clean up any data already imported
		if (mysqli_query($db_conn, ('DELETE FROM ' . $destinationTable . ' WHERE source_flag = "' . $importID . '";'))) {importLog(99,4,$stepIndex);}
		else {importLog(99,2,$stepIndex);}
	}
	else {
		importLog(99,4,$stepIndex);
	}

	singleField($db_importSeshTbl, 'end_dt', $importID, "SET", date('Y-m-d H:i:s')); 	//Update End Datetime in Import Session Table
	singleField($db_importSeshTbl, 'status', $importID, "SET", "Aborted");				//Update Import Session Status
	exit();
}

?>