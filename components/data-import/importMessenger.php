<?php
	session_start();

	require './include/common/db.php';

	if(db_connection()) {
		//BUILD SQL AND GET OLDEST UNSENT IMPORT LOG ENTRY FOR THIS IMPORT SESSION
		$SQLstr = 	'SELECT last_updated, step_index, step_text, step_status, step_value, ID FROM ' . 
					$db_importLogTbl . 
					' WHERE ' . 
					'ID = (' .  
						'SELECT MIN(ID) FROM ' . 
						$db_importLogTbl . 
						' WHERE import_session_ID = "' . $_SESSION['import']['ID'] . '" AND sent_to_user = "0");';

		$importLogRow 	= mysqli_query($db_conn, $SQLstr);
		$importLogArray	= mysqli_fetch_array($importLogRow);

		//PUT QUERIED VALUES INTO AN ASSOCIATIVE ARRAY AND JSON IT TO importProgress.php
		$importLogValues = array(
			'timestamp' 	=> $importLogArray[0],
			'stepIndex' 	=> $importLogArray[1],
			'stepText'		=> $importLogArray[2],
			'stepStatus'	=> $importLogArray[3],
			'stepValue'		=> $importLogArray[4]
		);
		echo json_encode($importLogValues);

		//MARK THE IMPORT LOG ENTRY AS SENT TO USER
		singleField($db_importLogTbl, "sent_to_user", $importLogArray[5], "SET", "1");
	}
?>