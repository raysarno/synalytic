<?php
//INITIALIZE SCRIPT IF IT IS BEING CALLED BY AJAX
if(!defined(ABSPATH)) {
	session_start();

	include '../include/globals.php';
	include '../include/db.php';
	include '../include/functions.php';

	$listType 		= $_POST['listType'];
	$pagination 	= $_POST['pagination'];
	$entPrPg 		= $_POST['entPrPg'];
	$curPg 			= $_POST['curPg'];
	$showFilters 	= $_POST['showFilters'];
	$listSorters 	= $_POST['listSorters'];
	$sortField 		= $_POST['sortField'];
	$sortDir 		= $_POST['sortDir'];
	$listSize 		= $_POST['listSize'];

	if($_POST['filters'] != '') {
		$POSTfilters 	= $_POST['filters'];

		foreach ($POSTfilters as $singleFilter) {
			$filters[$singleFilter[0]] = $singleFilter[1];
		}
	}
	else {
		unset($filters);
		unset($_SESSION['filters'][$listType]);
		$filters = array();
		$_SESSION['filters'][$listType] = array();
	}
}

db_connection();

if(!isset($listSize)) {$listSize = 'full';}

//Build SQL string or REQUEST it. 
if (!isset($_REQUEST['SQLstr'])) {
	//Build SQL Query String
	$SQLstr = 'SELECT * FROM ' . DB_PREFIX . $listType;

	//PROPERLY UPDATE (MERGE) FILTER ARRAYS
	if(isset($_SESSION['filters'][$listType]) || isset($filters)) {
		$filterArrayCode = 0;
		$filterArrayCode = is_array($_SESSION['filters'][$listType]) ? $filterArrayCode + 1 : $filterArrayCode;
		$filterArrayCode = is_array($filters) ? $filterArrayCode + 2 : $filterArrayCode;

		if($filterArrayCode == 2) {
			$_SESSION['filters'][$listType] = $filters;
		}
		else if($filterArrayCode == 3) {
			$_SESSION['filters'][$listType] = array_merge($_SESSION['filters'][$listType], $filters);
		}

		//ADD FILTER PARAMETERS TO SQL IF THERE ARE ANY
		if(count($_SESSION['filters'][$listType]) > 0) {
			$SQLstr .= ' WHERE ';

			foreach ($_SESSION['filters'][$listType] as $key => $value) {
				if(strlen($value)) {
					if(strlen($key) > 3) { //IF THE FILTER IS NOT A FOREIGN KEY (FOREIGN KEYS FIELDS SHOULD ALWAYS BE 3 CHARACTERS)
						$SQLstr .= $key . ' LIKE \'%' . $value . '%\' AND ';
					}
					else {
						$SQLstr .= $key . ' = "' . $value . '" AND ';
					}
				}
				else {
					unset($_SESSION['filters'][$listType][$key]);
				}
			}
			$SQLstr = rtrim($SQLstr," WHERE "); //BANDAID!! IT'S GETTING PAST THE COUNT(FILTERS) > 0 IF STATEMENT BUT SHOULDN'T BE
			$SQLstr = rtrim($SQLstr,"AND ");
		}
	}

	if($listSorters && $sortField != 'None' && ($sortDir == 'DESC' || $sortDir == 'ASC')) {
		$SQLstr .= ' ORDER BY ' . $sortField . ' ' . $sortDir;
	}

	$SQLstr .= ' ;';
}
else {$SQLstr = $_REQUEST['SQLstr'];}

if($showFilters) {
	include ABSPATH . 'list/filters.php';
}


//Build pagination vars and bar
if($pagination) {
	//Replace * by count(*) here in SQLstr;
	$SQLcount=str_replace("*","count(*)",$SQLstr);

	//Fetch and Store the Total number of Rows in the relevant table AS $totalEntryCount
	$totalEntryCount = db_query($SQLcount);
	$totalEntryCount = $totalEntryCount->fetch_row();
	$totalEntryCount = $totalEntryCount[0];


	//Calculate total number of pages AS $numPgs
	$numPgs = ceil ($totalEntryCount / $entPrPg);

	$curPg = ($curPg > $numPgs) ? $numPgs : $curPg;

	//ADD pagination limits to SQL query string
	$SQLstr = rtrim($SQLstr,";");
	$SQLstr .= ' LIMIT ' . (($curPg - 1) * $entPrPg ) . ',' . $entPrPg . ';' ;

	//Include and Build Pagination div
	include ABSPATH . 'list/pagination.php';
}


if($listSorters) {
	include ABSPATH . 'list/listSorters/' . $listType . '_listSorters.php';
}

//Query the database and display the results
$query = db_query($SQLstr);

echo '<div class="listBorder"></div>';
while ( $row = $query->fetch_assoc() ){
	include ABSPATH . 'list/lineItem/' . $listType . '_lineItem.php';
}
echo '<div class="listBorder"></div>';

if($pagination) {
	include ABSPATH . 'list/pagination.php';
}

?>