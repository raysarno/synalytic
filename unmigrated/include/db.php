<?php

	$db_conn    = NULL;



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

	function safeString($str) {
		global $db_conn;
		db_connection();
		return mysqli_real_escape_string($db_conn, $str);
	}

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

	function getBestTel($type, $ID) {
		global $db_conn;
		db_connection();

		$telNumStr = '-';

		if($type = 'lcn') {$cmp_ID = getDataFromID('CRM_lcn','cmp',$ID) ; }

		$SQLstr = 'SELECT * FROM CRM_tel WHERE ' . $type . ' = ' . $ID . ' AND cmp = ' . $cmp_ID . ';';

		$query = db_query($SQLstr);
		$numRows = mysqli_num_rows($query);

		if($row = mysqli_fetch_assoc($query)) {
			
			$telNumStr = $row['tel_number'];
			$telNumStr .= $row['tel_extension'] ? (' ext. ' . $row['tel_extension']) : '';
			$telNumStr .= $numRows > 1 ? ('[' . $numRows . ']') : '';
		}

		return $telNumStr;
	}

	function getPrimaryKey($table) {
		global $db_conn;
		db_connection();

		$SQLstr = 'SELECT primary_key FROM primary_keys WHERE TABLE_NAME = "' . $table . '";';

		$primaryKeyQuery = db_query($SQLstr);
		$result = mysqli_fetch_assoc($primaryKeyQuery);
		return $result['primary_key'];
	}

	function getMaxID($table) { //NEEDS TO BE GENERALIZED!!!
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
?>