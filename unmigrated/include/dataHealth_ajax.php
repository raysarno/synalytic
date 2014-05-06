<?php

	if(isset($_POST['reqType'])) {
		include 'globals.php';
		include 'db.php';
		include 'functions.php';

		$reqType = $_POST['reqType'];
		$cmp_ID = $_POST['cmp_ID'];

		if(isset($_POST['lcn_ID'])) {$lcn_ID = $_POST['lcn_ID'];}

		if($reqType == 'lcn_suggested') {
			$lcn_raw = $_POST['lcn_raw'];
		}
	}

	if(substr($reqType,0,3) == 'lcn' && db_connection()) {
		$lcnSQL = 'SELECT ID, lcn_name FROM CRM_lcn WHERE cmp = ' . $cmp_ID . ' ORDER BY lcn_name;';
		$lcnQRY = db_query($lcnSQL);

		while ($row = mysqli_fetch_assoc($lcnQRY)) {
			$lcnLst[$row['ID']] = $row['lcn_name'];
		}

		if($reqType == 'lcn') {
			foreach($lcnLst as $key => $val) {
				makeSelectRow('assoc_ctt_cmp', 'radio','ctt_lcn_select','ctt_lcn_select inline',$key,$val);
			}
		}
		else if($reqType == 'lcn_suggested') {
			$lcn_suggested = array();

			//STORE LIST OF COMPARISON RATINGS
			foreach( $lcnLst as $key => $lcn ) {
				$lcnX[$key] = string_compare($lcn, $lcn_raw);
				$lcnLVN[$key] = levenshtein($lcn, $lcn_raw);
				$lcnSMT[$key] = similar_text($lcn, $lcn_raw);
			}

			arsort($lcnX);
			asort($lcnLVN);
			asort($lcnSMT);

			if($lcn_ID) {
				$lcn_suggested[] = $lcn_ID;

				makeSelectRow('assoc_ctt_cmp', 'radio','ctt_lcn_select','ctt_lcn_select inline',$lcn_ID,$lcnLst[$lcn_ID],'(lcn of ctt)',true);
			}
			$lcnLstSearch = array_search($lcn_raw,$lcnLst);
			if ($lcnLstSearch && !in_array($lcnLstSearch,$lcn_suggested)) {
				$lcn_suggested[] = $lcnLstSearch;

				makeSelectRow('assoc_ctt_cmp','radio','ctt_lcn_select','ctt_lcn_select inline',$lcnLstSearch,$lcnLst[$lcnLstSearch],'(lcn str =)');
			}
			foreach($lcnLst as $key => $val) {
				if (strpos($val,$lcn_raw) !== false && !in_array($key,$lcn_suggested)) {
					$lcn_suggested[] = $key;

					makeSelectRow('assoc_ctt_cmp', 'radio','ctt_lcn_select','ctt_lcn_select inline',$key,$val,'(lcn str IN)');
				}
			}


			$LC = 0;
			foreach($lcnX as $key => $val) {
				if(!in_array($key,$lcn_suggested)) {
					$lcn_suggested[] = $key;

					makeSelectRow('assoc_ctt_cmp', 'radio','ctt_lcn_select','ctt_lcn_select inline',$key,$lcnLst[$key],(number_format($val,2) . ' (strX)'));
				}

				$LC++;
				if($LC >= 2) {break;}
			}
			$LC = 0;
			foreach($lcnLVN as $key => $val) {
				if(!in_array($key,$lcn_suggested)) {
					$lcn_suggested[] = $key;

					makeSelectRow('assoc_ctt_cmp', 'radio','ctt_lcn_select','ctt_lcn_select inline',$key,$lcnLst[$key],($val . ' (LVN)'));
				}

				$LC++;
				if($LC >= 2) {break;}
			}
			$LC = 0;
			foreach($lcnSMT as $key => $val) {
				if(!in_array($key,$lcn_suggested)) {
					$lcn_suggested[] = $key;

					makeSelectRow('assoc_ctt_cmp', 'radio','ctt_lcn_select','ctt_lcn_select inline',$key,$lcnLst[$key],($val . ' (smTxt)'));
				}

				$LC++;
				if($LC >= 2) {break;}
			}

			makeSelectRow('assoc_ctt_cmp', 'radio','ctt_lcn_select','ctt_lcn_select inline',0,'No Location');
		}
	}

?>