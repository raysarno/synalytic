<?php
	$cmp_ID = $_GET['ID'];

	if(db_connection()) {
		//SAVE CHANGES IF THEY WERE MADE
		if($_POST['saveInfo'] && ($cmp_ID = $_POST['cmp_ID'])) {
			$updateSQL = 	'UPDATE CRM_cmp SET ' . 
								'cmp_name = "' . safeString($_POST['cmp_name']) . '", ' .
								'cmp_URL = "' . safeString($_POST['cmp_URL']) . '", ' .
								'cmp_description = "' . safeString($_POST['cmp_description']) . '" ' .  
							'WHERE ID = ' . $cmp_ID . ' ;';

			$updateSuccess = db_query($updateSQL) ? true : false;
		}

		//GET NUMBER OF CONTACTS IN CRM FOR THIS COMPANY
		$COUNTcttSQL = 'SELECT COUNT(*) FROM CRM_ctt WHERE cmp = "' . $cmp_ID . '";';
		$COUNTcttQuery = db_query($COUNTcttSQL);
		$COUNTcttArray = mysqli_fetch_array($COUNTcttQuery);
		$cmp_COUNT_ctt = $COUNTcttArray[0];

		//GET COMPANY INFO FROM DATABASE
		$cmpSQL = 'SELECT * FROM CRM_cmp WHERE ID = "' . $cmp_ID . '";';
		$cmpQuery = db_query($cmpSQL);
		$row = mysqli_fetch_assoc($cmpQuery);
	}

	$pageBar = false;
	
?>