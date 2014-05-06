<?php
//INITIALIZE VARS
db_connection();

/*/ HANDLE FORM SUBMITTAL FIRST /*/

$noticeType = "success";
$noticeText = '';

//STORE POST VARS IF FORM WAS SUBMITTED
if(isset($_POST['ctt_update']) || isset($_POST['ctt_skip']) || isset($_POST['ctt_delete'])) {
	$update_ctt_ID = $_POST['update_ctt_ID'];

	//SKIP AND GO TO NEXT
	if(isset($_POST['ctt_skip'])) {
		$skip_ctt_SQL = 'UPDATE CRM_ctt SET cleaned = "' . date('Y-m-d H:i:s') . '" WHERE ID = "' . $update_ctt_ID .'";';

		//SKIP CONTACT CLEANING
		if(db_query($skip_ctt_SQL)) {
			$noticeText .= 'Skipped Contact cleaning, ID: ' . $update_ctt_ID . '. ';
		}
		else {
			$noticeType = 'failure';
			$noticeText .= 'Failed to skip contact cleaning. ID: ' . $update_ctt_ID . '. ';
		}
	}
	//DELETE AND GO TO NEXT
	else if(isset($_POST['ctt_delete'])) {
		$delete_ctt_SQL = 'DELETE FROM CRM_ctt WHERE ID = "' . $update_ctt_ID .'";';

		//DELETE CONTACT FROM DATABASE
		if(db_query($delete_ctt_SQL)) {
			$noticeText .= 'Deleted Contact, ID: ' . $update_ctt_ID . '. ';
		}
		else {
			$noticeType = 'failure';
			$noticeText .= 'Failed to delete contact from Database. ID: ' . $update_ctt_ID . '. ';
		}
	}
	//UPDATE CONTACT(S) AND GO TO NEXT
	else if(isset($_POST['ctt_update'])) {
		//COMPANY AND LOCATION SELECTIONS FROM LIST
		$ctt_cmp_select = $_POST['ctt_cmp_select'];
		$ctt_lcn_select = $_POST['ctt_lcn_select'];

		//GET VARS FOR NEW COMPANY IF ADD COMPANY WAS SELECTED
		if($ctt_cmp_select == 'add_cmp') {
			$add_cmp_name 		= safeString($_POST['add_cmp_name']);
			$add_cmp_full_name	= safeString($_POST['add_cmp_full_name']);
			$add_cmp_linkedin 	= safeString($_POST['add_cmp_linkedin']);

			$update_ctt_cmp_ID = getMaxID('CRM_cmp') + 1;

			$add_cmp_SQL = 'INSERT INTO CRM_cmp (ID, cmp_name, cmp_full_name, cmp_linkedin) VALUES("' .
							$update_ctt_cmp_ID . '" , "' .
							$add_cmp_name .'", "' .
							$add_cmp_full_name .'", "' .
							$add_cmp_linkedin . '");';

			//ADD COMPANY TO DATABASE
			if(db_query($add_cmp_SQL)) {
				$noticeText .= 'Added Company, ID: ' . $update_ctt_cmp_ID . '. ';
			}
			else {
				$noticeType = 'failure';
				$noticeText .= 'Failed to Add Company to Database.';
			}
		}
		else {
			$update_ctt_cmp_ID = $ctt_cmp_select;
		}

		//GET VARS FOR NEW LOCATION IF ADD LOCATION WAS SELECTED
		if($_POST['ctt_lcn_select'] && $ctt_lcn_select == 'add_lcn' && $noticeType == "success") {
			$add_lcn_name 		= safeString($_POST['add_lcn_name']);
			$add_lcn_locality 	= safeString($_POST['add_lcn_locality']);
			$add_lcn_region 	= safeString($_POST['add_lcn_region']);
			$add_lcn_country 	= safeString($_POST['add_lcn_country']);

			$update_ctt_lcn_ID = getMaxID('CRM_lcn') + 1;

			$add_lcn_SQL = 'INSERT INTO CRM_lcn (ID, cmp, lcn_name, lcn_locality, lcn_region, lcn_country) VALUES("' .
							$update_ctt_lcn_ID . '", "' .
							$update_ctt_cmp_ID . '", "' .
							$add_lcn_name .'", "' .
							$add_lcn_locality .'", "' .
							$add_lcn_region .'", "' .
							$add_lcn_country . '");';
			
			//ADD LOCATION TO DATABASE
			if(db_query($add_lcn_SQL)) {
				$noticeText .= 'Added Location, ID: ' . $update_ctt_lcn_ID . '. ';
			}
			else {
				$noticeType = 'failure';
				$noticeText .= 'Failed to Add Location to Database.';
			}
		}
		else if ($noticeType == "success") {
			$update_ctt_lcn_ID = $ctt_lcn_select;
		}


		//UPDATE COMPANY FIRST. BUILD CONTACT UPDATE SQL
		if ($noticeType == "success") {
			$update_ctt_cmp_SQL = 	'UPDATE CRM_ctt SET ' . 
										'cleaned = "' . date('Y-m-d H:i:s') . '", ' .
										'cmp = "' . $update_ctt_cmp_ID . '" ' .
									'WHERE ' .
										'cleaned = 0 AND ' .
										'ID = "' . $update_ctt_ID . '" ' ;

			//ADD TO SQL FOR MASS EDITS IF SELECTED
			if ($_POST['ctt_update_matches'] != 'match_none') {
				$update_ctt_cmp_SQL .= ' OR ctt_raw_cmp = "' . $_POST['update_ctt_raw_cmp'] . '"';
			}
			$update_ctt_cmp_SQL .= ';';

			//UPDATE COMPANY FOR CONTACT(S)
			if(db_query($update_ctt_cmp_SQL)) {
				$noticeText .= 'Updated Company for ' . mysqli_affected_rows($db_conn) . ' contact(s), cmp_ID: ' . $update_ctt_cmp_ID . '. ';
			}
			else {
				$noticeType = 'failure';
				$noticeText .= 'Failed to update Company for contact(s).';
			}


			//UPDATE LOCATION SECOND. BUILD CONTACT UPDATE SQL
			if($_POST['ctt_lcn_select'] && $noticeType == "success") {
				$update_ctt_lcn_SQL = 	'UPDATE CRM_ctt SET ' . 
											'cleaned = "' . date('Y-m-d H:i:s') . '", ' .
											'lcn = "' . $update_ctt_lcn_ID . '" ' .
										'WHERE ' .
											'ID = "' . $update_ctt_ID . '" ' ;
			
				//ADD TO SQL FOR MASS EDITS IF SELECTED
				if ($_POST['ctt_update_matches'] == 'match_lcn') {
					$update_ctt_lcn_SQL .= ' OR (cmp = "' . $update_ctt_cmp_ID . '" AND ctt_clean_lcn = "' . $_POST['update_ctt_clean_lcn'] . '")';
				}
				$update_ctt_lcn_SQL .= ';';

				//UPDATE COMPANY FOR CONTACT(S)
				if(db_query($update_ctt_lcn_SQL)) {
					$noticeText .= 'Updated Location for ' . mysqli_affected_rows($db_conn) . ' contact(s), lcn_ID: ' . $update_ctt_lcn_ID . '. ';
				}
				else {
					$noticeType = 'failure';
					$noticeText .= 'Failed to update Location for contact(s).';
				}
			}
		}
	}
}

/*/ READY VARS FOR PAGE /*/

//CHECK IF IN CLEANING MODE ELSE VIEWING SPECIFIC CONTACT
if(!isset($_GET['ID'])) {
	$cttID_SQL = 'SELECT ID FROM CRM_ctt WHERE cleaned = 0 LIMIT 0,1;';
	$cttID_QRY = db_query($cttID_SQL);
	$cttID_ARR = mysqli_fetch_row($cttID_QRY);
	$ctt_ID = $cttID_ARR[0];
}
else {
	$ctt_ID = safeString($_GET['ID']);
}

//GET CONTACT ROW FROM CRM_ctt
$cttSQL = 'SELECT * FROM CRM_ctt WHERE ID = ' . $ctt_ID . ';';
$cttQRY = db_query($cttSQL);
$ctt = mysqli_fetch_assoc($cttQRY);

//GET CONTACT ROW FROM Scraped Tables
$cttScrapedSQL = 'SELECT * FROM contacts WHERE external_linkedin_id = "' . $ctt['ctt_linkedin'] . '";';
$cttScrapedQRY = db_query($cttScrapedSQL);
$cttScrpd = mysqli_fetch_assoc($cttScrapedQRY);

//BUILD AND ECHO NOTICE BOX
echo '<div class="notice ' . $noticeType . '"><h3>' . ucfirst($noticeType) . '</h3>' . $noticeText . '</div>';
?>