<?php

/*/ DATA EVALUATION /*/
//FROM CRM DB.php
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

/*/ LISTS / DATA DISPLAY /*/
//LIST FILTER FUNCTIONALITY

$_SESSION['lastDoc'] = (isset($_SESSION['curDoc']) ? $_SESSION['curDoc'] : getFileName());
$_SESSION['curDoc'] = getFileName();


//UNSET FILTERS IF CHANGING PAGES
if ($_SESSION['curDoc'] != $_SESSION['lastDoc']) {
	unset($_SESSION['filters']);
	$_SESSION['filters'] = array();
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



/*/ DATA IMPORT  /*/

	//IMPORT TABLE NAMES
	$db_importSeshTbl = "AMX_IMP_SESH_importSessions";
	$db_importLogTbl = 'AMX_IMP_LOG_importLogs';


?>