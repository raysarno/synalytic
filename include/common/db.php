<?php

	$db_conn    = NULL;						//GLOBAL REFERENCE TO THE DATABASE CONNECTION

	//DATABASE INFORMATION
	$db_host	= "";
	$db_user	= "";
	$db_pass	= "";
	$db_name    = "";

	//IMPORT TABLE NAMES
	$db_importSeshTbl = "AMX_IMP_SESH_importSessions";
	$db_importLogTbl = 'AMX_IMP_LOG_importLogs';

	function db_connection($db_action = "OPEN") {
		
		global $db_conn, $db_host, $db_user, $db_pass, $db_name;

		if(!isset($db_lastAction)) {
			static $db_lastAction = "NONE";
		}
		
 
		if ($db_action != $db_lastAction) //PROTECT FROM TRYING TO OPEN AN ALREADY OPEN CONNECTION OR CLOSE AN ALREADY CLOSED CONNECTION
		{ 
			if ($db_action == "OPEN")
			{
				$db_conn = new mysqli ($db_host,$db_user,$db_pass,$db_name);
				
				if (mysqli_connect_errno ($db_conn))
				{
					echo '<div class="error">Failed to connect to database.</div>';
				}
				else {
					$db_conn->set_charset('utf8');
					return TRUE;
				}
			}
			elseif ($db_action == "CLOSE" && $db_conn)
			{
				mysqli_close($db_conn);
			}
			
			$db_lastAction = $db_action;
		}
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
	
	function display_table($SQLstr) {
		global $db_conn;

		if(db_connection()) {
			$query = mysqli_query($db_conn,$SQLstr);

			$rowCount = $query->num_rows;
			$fieldCount = mysqli_num_fields($query);
			
			echo '<table class="dataTable">
			<tr>
			';
			
			for ($i=0;$i<$fieldCount;$i++) {
				echo "<td>";
				$fieldInfo = mysqli_fetch_field_direct($query,$i);
				echo $fieldInfo->name;
				echo "</td>";
			}
			echo "</tr>";
			
			while ($row = $query->fetch_array()) {
				echo "<tr>";			
				
				for($i=0;$i<$fieldCount;$i++) 
				{
					echo "<td>". $row[$i] . "</td>";
				}
				
				echo "</tr>";
			}
			
			echo "</table>";
		}
	}

	function jsonDataTable($SQLstr) {
		global $db_conn;
		$jsonTable = array("cols" => array(),'rows'=>array());

		if(db_connection()) {
			$query = mysqli_query($db_conn,$SQLstr);

			$rowCount = $query->num_rows;
			$fieldCount = mysqli_num_fields($query);
			
			echo '<table class="dataTable">
			<tr>
			';
			
			for ($i=0;$i<$fieldCount;$i++) {
				echo "<td>";
				$fieldInfo = mysqli_fetch_field_direct($query,$i);
				echo $fieldInfo->name;
				echo "</td>";
			}
			echo "</tr>";
			
			while ($row = $query->fetch_array()) {
				echo "<tr>";			
				
				for($i=0;$i<$fieldCount;$i++) 
				{
					echo "<td>". $row[$i] . "</td>";
				}
				
				echo "</tr>";
			}
			
			echo "</table>";
		}
	}

	function buildFilterSelectTable($filterType, $dbTable, $fields, $selectType = "checkbox", $formID = "reportData") {
		//THIS FUNCTION IS USED BY: 
		//reports.php 		- USED TO GENERATE FILTER SELECTION TABLES IN FILTER SELECT TABS
		//dataCleaner.php 	- USED TO GENERATE SELECTABLE LOOKUP TABLE
		global $db_conn;

		//Opening Table Tag
		echo '<table id="' . $filterType . 'FilterSelectTable" class="filterSelectTable">';

		//First Row (Select All Row) ONLY IF $selectType == "checkbox"
		if ($selectType == "checkbox") {
			echo '<tr class="filterSelectAllRow">';
			echo 	'<td><input type="checkbox" id="' . $filterType . '_all" class="selectAll" form="' . $formID . '" name="' . $filterType . '_all" filterType="' . $filterType . '" checked /></td>';
			echo 	'<td colspan="' . count($fields) . '">Select/Deselect All</td>';
			echo '</tr>';
		}
		
		//Second Row (Headings)
		echo '<tr class="filterSelectTitleRow">';
		echo 	'<td></td>';
		foreach($fields as $field) {
			echo 	'<td>' . getComment($dbTable, $field) . '</td>';
		}
		echo '</tr>';

		//Build Body of Table

		//Build SQL
		$filterSQL = 'SELECT ID, ';
		foreach($fields as $field) {
			$filterSQL .= $field . ', ';
		}
		$filterSQL = rtrim($filterSQL, ", ");
		$filterSQL .= ' FROM ' . $dbTable . ' GROUP BY ' . $fields[0] . ' ORDER BY ID;';

		$filterQuery = mysqli_query($db_conn, $filterSQL);
				
		//$firstIsSelectAll REMOVED FROM FUNCTIONALITY BECAUSE RADIO BUTTON FILTER SELECTS DO NOT NEED A SELECT ALL
		//$firstIsSelectAll = $selectType == "radio" ? "_all" : ""; //If $selectType is 'radio', then the first choice is the 'Select All' choice
		while ($row = mysqli_fetch_row($filterQuery)) {
			$property = ($row[0] == "1" ? "checked" : "");
			$property = ($selectType == "checkbox" ? "checked" : $property);

			echo '<tr>';
			echo '<td><input type="' . $selectType . '" form="' . $formID . '" class="' . $filterType . '" name="' . $filterType /*. $firstIsSelectAll*/ . '" value="' . $row[0] . '" ' . $property . ' /></td>';

			//$firstIsSelectAll = ""; //Only the first radio button can be select all. This line has no effect if $selectType == "checkbox"

			for ($i = 1; $i < count($row); $i++) {
				echo '<td>' . $row[$i] . '</td>';
			}

			echo '</tr>';
		}

		echo '</table>';
	}
?>