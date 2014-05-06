<?php
	  //////////////////////////////////
	 //       INITIALIZE VARS        //
	//////////////////////////////////

	//THESE DEFAULTS ARE CHANGED IN THE DECISION TREE BELOW IF NEEDED
	$editMode = false;
	$lcn_ID = getMaxID('CRM_lcn') + 1;  //GET A NEW LCN ID (OVERRIDDEN BELOW FOR EDITING / DELETING LOCATIONS)
	$next_tel_ID = getMaxID('CRM_tel') + 1; //GET A NEW TEL ID (USED BY JAVASCRIPT FOR THE 'ADD NEW TELEPHONE NUMBER' BUTTON)

	//KEEP SELECTED COMPANY ON PAGE IF ONE HAS BEEN SELECTED
	if($_POST['cmp_ID']) {
		$cmp_ID 		= $_POST['cmp_ID'];
		$cmp_name 		= getDataFromID("CRM_cmp","cmp_name", $cmp_ID);
	 	$cmp_URL 		= getDataFromID("CRM_cmp","cmp_URL", $cmp_ID);
	}


	  //////////////////////////////////
	 //    DECISION TREE BY FORM     //
	//////////////////////////////////

	//COMPANY SELECT FORM WAS SUBMITTED
 	if($_POST['cmp_select']) {
 		if($_POST['cmp_ID']) {
 			$noticeType = "success";
 			$noticeText = "Company Selected: " . $cmp_name;
 		}
 		else {
 			$noticeType = "warning";
 			$noticeText = "Please Select a Company.";
 		}
 	}
 	//ADD LOCATION FORM WAS SUBMITTED
 	else if($_POST['lcnAddEdit'] == 'Add Location') {
 		if(!$_POST['cmp_ID']) {
			$noticeType = "failure";
			$noticeText = "No company selected!";
		}
		else if(!$_POST['lcn_locality'] || !$_POST['lcn_country'] || !$_POST['lcn_country_code']) {
			$noticeType = "failure";
			$noticeText = "Please fill in all required fields.";
		}
		else if ($_POST['tel_number'] && !$_POST['tel4cmpORlcn']) {
			$noticeType = "failure";
			$noticeText = "If a phone number is entered, you must select whether it is for the company in general or just the location that was entered.";
		}
		else {
			if(db_connection()) {
				$lcn_name = $_POST['lcn_locality'] . (safeString($_POST['lcn_country_code']) == 'US' ? '' : ', ' . safeString($_POST['lcn_country_code']));

				$lcnAddSQL = 	'INSERT INTO CRM_lcn(' . 
								'ID,' . 
								'cmp, ' .
								'lcn_name, ' . 
								'lcn_address1, ' . 
								'lcn_address2, ' . 
								'lcn_address3, ' . 
								'lcn_locality, ' . 
								'lcn_region, ' . 
								'lcn_region_code, ' .
								'lcn_postcode, ' .
								'lcn_country, ' .
								'lcn_country_code) ' . 
							'VALUES("' . 
								$lcn_ID . '", "' .
								$cmp_ID . '", "' .
								$lcn_name . '", "' .
								safeString($_POST['lcn_address1']) . '", "' .
								safeString($_POST['lcn_address2']) . '", "' .
								safeString($_POST['lcn_address3']) . '", "' .
								safeString($_POST['lcn_locality']) . '", "' .
								safeString($_POST['lcn_region']) . '", "' .
								safeString($_POST['lcn_region_code']) . '", "' .
								safeString($_POST['lcn_postcode']) . '", "' .
								safeString($_POST['lcn_country']) . '", "' .
								safeString($_POST['lcn_country_code']) . '");';

				if(db_query($lcnAddSQL)) {
					$noticeType = "success";
					$noticeText = 'Successfully added Location ' . $lcn_name . ' (ID: ' . $lcn_ID . ') for Company: ' . $cmp_name . '.';
				}
				else {
					$noticeType = "failure";
					$noticeText = "MySQL error: " . mysqli_error($db_conn) . "  Query: " . $lcnAddSQL;
				}
			}
		}
 	}
 	//EDIT LOCATION FORM WAS SUBMITTED
 	else if($_POST['lcnAddEdit'] == 'Update Location') {
 		$lcn_ID = $_POST['lcn_ID']; //GRAB EXISTING LCN ID IF WE ARE EDITING A LOCATION
		
		if(!$_POST['lcn_locality'] || !$_POST['lcn_country'] || !$_POST['lcn_country_code']) {
			$noticeType = "failure";
			$noticeText = "Please fill in all required fields.";
		}
		else if ($_POST['tel_number'] && !$_POST['tel4cmpORlcn']) {
			$noticeType = "failure";
			$noticeText = "If a phone number is entered, you must select whether it is for the company in general or just the location that was entered.";
		}
		else {
			if(db_connection()) {
				$lcn_name = $_POST['lcn_locality'] . (safeString($_POST['lcn_country_code']) == 'US' ? '' : ', ' . safeString($_POST['lcn_country_code']));

				$lcnEditSQL = 	'UPDATE CRM_lcn SET ' . 
								'cmp = ' . $cmp_ID . ', ' .
								'lcn_name = "' . $lcn_name . '", ' . 
								'lcn_address1 = "' . safeString($_POST['lcn_address1']) . '", ' .
								'lcn_address2 = "' . safeString($_POST['lcn_address2']) . '", ' .
								'lcn_address3 = "' . safeString($_POST['lcn_address3']) . '", ' .
								'lcn_locality = "' . safeString($_POST['lcn_locality']) . '", ' .
								'lcn_region = "'   . safeString($_POST['lcn_region']) . '", ' .
								'lcn_region_code = "' . safeString($_POST['lcn_region_code']) . '", ' .
								'lcn_postcode = "' . safeString($_POST['lcn_postcode']) . '", ' .
								'lcn_country = "' . safeString($_POST['lcn_country']) . '", ' .
								'lcn_country_code = "' . safeString($_POST['lcn_country_code']) . '" ' . 
							'WHERE ID = ' . $lcn_ID . ';';

				if(db_query($lcnEditSQL)) {
					$noticeType = "success";
					$noticeText = 'Successfully edited Location ' . $lcn_name . ' (ID: ' . $lcn_ID . ') for Company: ' . $cmp_name . '.';
				}
				else {
					$noticeType = "failure";
					$noticeText = "MySQL error: " . mysqli_error($db_conn) . "  Query: " . $lcnEditSQL;
				}
			}
		}
 	}
	//VIEW / EDIT LOCATION FORM WAS SUBMITTED
	else if($_POST['lcn_select']) {
		$editMode = true;

		if(db_connection()) {
			$lcn_ID = $_POST['lcn_ID'];

			$lcnSelectSQL = 'SELECT * FROM CRM_lcn WHERE ID = ' . $lcn_ID . ';';
			$lcnSelectQuery = db_query($lcnSelectSQL);

			$row = mysqli_fetch_assoc($lcnSelectQuery);

			$lcn_address1		= $row['lcn_address1'];
			$lcn_address2		= $row['lcn_address2'];
			$lcn_address3		= $row['lcn_address3'];
			$lcn_locality 		= $row['lcn_locality'];
			$lcn_region			= $row['lcn_region'];
			$lcn_region_code 	= $row['lcn_region_code'];
			$lcn_postcode 		= $row['lcn_postcode'];
			$lcn_country		= $row['lcn_country'];
			$lcn_country_code	= $row['lcn_country_code'];

			$noticeType = "success";
			$noticeText = "Now editing the " . $lcn_locality . " location for " . $cmp_name . " (Location ID: " . $lcn_ID . ")";
		}
	}
	//DELETE LOCATION FORM SUBMITTED
	else if($_POST['lcnDelete']) {
		$lcn_ID = $_POST['lcn_ID'];

		if(db_connection()) {
			$lcnDelSQL = 'DELETE FROM CRM_lcn WHERE ID = ' . $lcn_ID . ';';

			if(db_query($lcnDelSQL)) {
				$noticeType = "success";
				$noticeText = 'Successfully Deleted Location for Company: ' . $cmp_name . ' (Location ID: ' . $lcn_ID . ').';
			}
			else {
				$noticeType = "failure";
				$noticeText = "MySQL error: " . mysqli_error($db_conn) . "  Query: " . $lcnEditSQL;
			}
		}
	}


	if($_POST['lcnAddEdit']) {
		//ADD PHONE NUMBER IF IT WAS PROPERLY INCLUDED
		$telIDsSubmitted = explode("-",$_POST['tel_ID_list']);

		$telIDs_deleted = array();
		$telIDs_edited = array();
		$telIDs_added = array();
		$telIDs_nochange = array();
		$telIDs_error = array();

		foreach($telIDsSubmitted as $tel_ID) {
			//DELETE THIS TELEPHONE NUMBER
			if($_POST['telDelete-' . $tel_ID]) {
				$telSQL = 'DELETE FROM CRM_tel WHERE ID = ' . $tel_ID . ';';

				if(db_query($telSQL)) {
					$telIDs_deleted[] = $tel_ID;
				}
				else {
					$telIDs_error[] = $tel_ID;
				}
			}
			//ADD A NEW NUMBER
			else if($_POST['tel_is_new-' . $tel_ID] == '1' && strlen($_POST['tel_number-' . $tel_ID])) {
				$telAddSQL = 	'INSERT INTO CRM_tel(' . 
									'ID, ' .
									'tel_number, ' . 
									'cmp, ' . 
									'lcn) ' .
								'VALUES("' .
									$tel_ID . '", "' .
									$_POST['tel_number-' . $tel_ID] . '", "' .
									$cmp_ID . '", "' .
									($_POST['tel4cmpORlcn-' . $tel_ID] == 'lcn' ? $lcn_ID : '0') . '");';

				if(db_query($telAddSQL)) {
					$telIDs_added[] = $tel_ID;
				}
				else {
					$telIDs_error[] = $tel_ID;
				}
			}
			//EDIT AN EXISTING NUMBER
			else if($_POST['tel_is_new-' . $tel_ID] == '0' && strlen($_POST['tel_number-' . $tel_ID])) {
				//CHECK IF NUMBER WAS EDITED
				$telCheckSQL = 	'SELECT COUNT(*) FROM CRM_tel WHERE ID = ' . $tel_ID . 
								' AND cmp = ' . $cmp_ID . 
								' AND lcn = "' . ($_POST['tel4cmpORlcn-' . $tel_ID] == 'lcn' ? $_POST['lcn_ID'] : "0") . '"' .
								' AND tel_number = "' . $_POST['tel_number-'.$tel_ID] . '";';
				$telCheckQRY = db_query($telCheckSQL);
				$telCheckARR = mysqli_fetch_array($telCheckQRY);
				$telCheck = $telCheckARR[0];

				//IF THE COUNT IS ZERO, THE ENTRY WAS EDITED
				if($telCheck == 0) {
					$telUpdateSQL = 	'UPDATE CRM_tel SET lcn = "' . ($_POST['tel4cmpORlcn-' . $tel_ID] == 'lcn' ? $_POST['lcn_ID'] : "0") . '", ' .
										' tel_number = "' . $_POST['tel_number-' . $tel_ID] . '"' .
										' WHERE ID = ' . $tel_ID . ';';
					if(db_query($telUpdateSQL)) {
						$telIDs_edited[] = $tel_ID;
					}
					else {
						$telIDs_error[] = $tel_ID;
					}
				}
				else {
					$telIDs_nochange[] = $tel_ID;
				}
			}
		}

		$noticeText .= ' (Tel#s: ' . 
						count($telIDs_added) . ' Added, ' .
						count($telIDs_edited) . ' Updated, ' .
						count($telIDs_deleted) . ' Deleted, ' .
						count($telIDs_nochange) . ' Unchanged, ' .
						count($telIDs_error) . ' Errors)';
	}
	
	//KEEP FORM DATA UNLESS NO COMPANY HAS BEEN SELECTED OR THE FORM SHOULD BE CLEARED ( BECAUSE OF A SUCCESS)
	if($cmp_ID && $noticeType != 'success' && !$editMode) {
		$lcn_address1		= $_POST['lcn_address1'];
		$lcn_address2		= $_POST['lcn_address2'];
		$lcn_address3		= $_POST['lcn_address3'];
		$lcn_locality 		= $_POST['lcn_locality'];
		$lcn_region			= $_POST['lcn_region'];
		$lcn_region_code 	= $_POST['lcn_region_code'];
		$lcn_postcode 		= $_POST['lcn_postcode'];
		$lcn_country		= $_POST['lcn_country'];
		$lcn_country_code	= $_POST['lcn_country_code'];

		$tel_number			= $_POST['tel_number'];
		$tel4cmpORlcn		= $_POST['tel4cmpORlcn'];
	}

	//BUILD AND ECHO NOTICE BOX
	echo '<div class="notice ' . $noticeType . '"><h3>' . ucfirst($noticeType) . '</h3>' . $noticeText . '</div>';


?>