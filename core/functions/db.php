<?php
/*/ 	PRIMARY DATABASE FUNCTIONS 		/*/

//OPEN OR CLOSE DATABASE CONNECTION
function db_connection($db_action = "OPEN") {

	global $db_conn, $db_host, $db_user, $db_pass, $db_name;
	
	if(!isset($db_lastAction)) {static $db_lastAction = "NONE";}

	if ($db_action != $db_lastAction) //PROTECT FROM TRYING TO OPEN AN ALREADY OPEN CONNECTION OR CLOSE AN ALREADY CLOSED CONNECTION
	{ 
		if ($db_action == "OPEN")
		{
			$db_conn = new mysqli ($db_host,$db_user,$db_pass,$db_name);
			
			if (mysqli_connect_errno ($db_conn))
			{
				echo '<div class="error">Failed to connect to database.</div>';
			}
			else return TRUE;
		}
		elseif ($db_action == "CLOSE" && $db_lastAction != "NONE")
		{
			mysqli_close($db_conn);
		}
		
		$db_lastAction = $db_action;
	}
}

//QUERY THE DATABASE; ALL QUERIES SHOULD USE DB QUERY FOR SECURITY, DEBUGGING, AND UNIFORMITY
function db_query($SQL) {
	global $db_conn;
	db_connection();

	if(LOG_QUERIES) {
		$QRYlogPath = ABSPATH . 'logs/QRYlog_' . getFileName() . '.log';
		$SQLlog = date( 'Y-m-d H:i:s') . ' - ' . $SQL . "\n";
		file_put_contents($QRYlogPath, $SQLlog, FILE_APPEND);
	}

	$query = mysqli_query($db_conn, $SQL);

	if(mysqli_error($db_conn)) {
		$SQLlog = date( 'Y-m-d H:i:s') . ' - ' . mysqli_error($db_conn) . "\n";
		file_put_contents($QRYlogPath, $SQLlog, FILE_APPEND);
		return false;
	}
	else {
		return $query;
	}
}

//MAKE STRING SAFE FOR DATABASE (PREVENT SQL INJECTION)
function safeString($str) {
	global $db_conn;
	db_connection();
	return mysqli_real_escape_string($db_conn, $str);
}


/*/ 	READ & WRITE DATA TO & FROM DATABASE 		/*/

//DEV-NOTE ********************************************
//THE NEXT THREE FUNCTIONS CAN BE COMBINED INTO ONE

function getDataFromID($table, $targetField, $ID) {
	global $db_conn;
	db_connection();

	//$primary_key = getPrimaryKey($table);

	$SQLstr = 'SELECT ' . $targetField . ' FROM ' . $table . ' WHERE ID = ' . $ID . ';';

	$query = mysqli_query($db_conn,$SQLstr);
	$result = mysqli_fetch_assoc($query);
	return $result[$targetField];
}

function getIDFromData($table, $targetField, $dataValue) {
	global $db_conn;
	db_connection();

	$SQLstr = 'SELECT ID FROM ' . $table . ' WHERE ' . $targetField . ' = "' . $dataValue . '";';

	$query = db_query($SQLstr);
	$result = mysqli_fetch_row($query);
	return $result[0];
}

function singleField($table, $fieldName, $ID, $action = "GET" , $newValue = NULL) { //DEV: This function could be improved! (idea: check to make sure new value is same data type as old value)
	if (!isset($table)) {return false;}
	global $db_conn;

	if(db_connection()) {
		if ($action == "GET") {
			$SQLstr = 'SELECT ' . $fieldName . ' FROM ' . $table . ' WHERE ID = "' . $ID . '";';

			$query = mysqli_query($db_conn,$SQLstr);
			$value = mysqli_fetch_array($query);  
			return $value[0];
		}
		else if ($action == "SET") {
			$SQLstr = 'UPDATE ' . $table . ' SET ' . $fieldName . ' = "' . $newValue . '" WHERE ID = "' . $ID . '";';

			$query = mysqli_query($db_conn,$SQLstr);
			//DEV: ADD: IF NO ERROR THEN RETURN TRUE, MAYBE CHECK IF NEW VALUE IS CORRECT?
		}
	}
	else {
		//DEV: db_conn error handling
	}
}

function getPrimaryKey($table) {
	global $db_conn;
	db_connection();

	$SQLstr = 'SELECT primary_key FROM primary_keys WHERE TABLE_NAME = "' . $table . '";';

	$primaryKeyQuery = db_query($SQLstr);
	$result = mysqli_fetch_assoc($primaryKeyQuery);
	return $result['primary_key'];
}

function getMaxID($table) {
	if (!isset($table)) {return false;}
	global $db_conn;

	if(db_connection()) {
		$maxIDquery = mysqli_query($db_conn,'SELECT MAX(ID) FROM ' . $table . ';');
		$maxIDarray = mysqli_fetch_array($maxIDquery);
		$maxID = ($maxIDarray[0] == NULL ? 0 : $maxIDarray[0]);

		return intval($maxID);
	}
	else {
		//DEV: db_conn error handling
	}	
}

function getComment($table, $field) {
	if (!isset($table)) {return false;}
	global $db_conn;

	$commentSQL = 'SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_NAME = "' . $table . '" AND COLUMN_NAME = "' . $field . '";';
	$commentQuery = mysqli_query($db_conn, $commentSQL);
	$commentArray = mysqli_fetch_array($commentQuery);
	//DEV ADD ERROR HANDLING HERE!
	return $commentArray[0];
}


?>