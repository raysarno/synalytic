<?php
	$ctt_ID = $_GET['ID'];

	if(db_connection()) {
		//GET COMPANY INFO FROM DATABASE
		$cmpSQL = 'SELECT * FROM CRM_ctt WHERE ID = "' . $ctt_ID . '";';
		$cmpQuery = db_query($cmpSQL);
		$row = mysqli_fetch_assoc($cmpQuery);
	}

	$pageBar = false;
	
?>