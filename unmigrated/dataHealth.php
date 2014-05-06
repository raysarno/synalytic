<?php
	$pageTitle = "Contact Data Health"; 
	include 'header.php'; 

	//VARS AND INITILIZATION FOUND IN PAGEBAR FOR THIS FILE
?>
<style>
td {
	width:300px;
	padding:2px;
}
td.title {text-align: center;padding:10px 2px 10px;border-bottom:1px solid #CCC;}
td.NA {color:#AAA;}
td.good {color:#0F0;}
td.bad {color:#F00;}
td.warn {color:#FF0;}
tr:nth-child(2n) {
	background:#444;
}
span.aside {
	color:#888;
	font-size:9px;
}
#CRM_Data {
	font-size:9px;
}
#CRM_Data td:first-of-type {text-align:left;}
#CRM_Data td:first-of-type,
#CRM_Data td:nth-child(3),
#CRM_Data td:nth-child(5)
 {border-right:1px solid #888;}
#CRM_Data td:last-of-type {border-left:1px solid #888;}
</style>
<table style="background:#222;width:100%;min-width:inherit;height:100%;">
	<tr>
		<td colspan="4" class="title">
			Contact Data From CRM and Scraped Tables Connected with LinkedIn ID
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<table class="innerList" style="width:100%; max-width:inherit;" id="CRM_Data">
				<tr style="border-bottom:1px solid #888;text-align:center;">
					<td>
					</td>
					<td colspan="2">
						Contact Tables
					</td>
					<td colspan="2">
						Company Tables
					</td>
					<td>
					</td>
					<td>
					</td>
				</tr>
				<tr style="border-bottom:1px solid #666;text-align:center;">
					<td>
						Table
					</td>
					<td>
						CRM_ctt
					</td>
					<td>
						contacts (Scraped)
					</td>
					<td>
						CRM_cmp
					</td>
					<td>
						companies (Scraped)
					</td>
					<td>
						CRM_lcn
					</td>
					<td style="text-overflow:ellipsis;">
						Status
					</td>
				</tr>
				<tr>
					<td>
						ID
					</td>
					<td>
						<?php echo $ctt['ID'] ; ?>
					</td>
					<td>
						<?php echo $cttScrpd['contact_id'] ; ?>
					</td>
					<td>
						<?php echo $ctt['cmp'] ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td class="NA">
						N/A
					</td>
				</tr>
				<tr>
					<td>
						First Name
					</td>
					<td>
						<?php echo $ctt['ctt_f_name'] ; ?>
					</td>
					<td>
						<?php echo $cttScrpd['f_name'] ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
						<?php 
							$statusCode = $ctt['ctt_f_name'] == $cttScrpd['f_name'] ? 'good' : 'bad' ; 
							$statusText = $statusCode == 'good' ? 'First Names Match' : 'First Names Do Not Match';
						?>
					<td class="<?php echo $statusCode; ?>">
						<?php echo $statusText; ?>
					</td>
				</tr>
				<tr>
					<td>
						Last Name
					</td>
					<td>
						<?php echo $ctt['ctt_l_name'] ; ?>
					</td>
					<td>
						<?php echo $cttScrpd['l_name'] ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
						<?php 
							$statusCode = $ctt['ctt_l_name'] == $cttScrpd['l_name'] ? 'good' : 'bad' ; 
							$statusText = $statusCode == 'good' ? 'Last Names Match' : 'Last Names Do Not Match';
						?>
					<td class="<?php echo $statusCode; ?>">
						<?php echo $statusText; ?>
					</td>
				</tr>
				<tr>
					<td>
						LinkedIn ID
					</td>
					<td>
						<?php echo $ctt['ctt_linkedin'] ; ?>
					</td>
					<td>
						<?php echo $cttScrpd['external_linkedin_id'] ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
						<?php 
							$statusCode = $ctt['ctt_linkedin'] == $cttScrpd['external_linkedin_id'] ? 'good' : 'bad';
							$statusText = 'These LinkedIn IDs should always match.';
						?>
					<td class="<?php echo $statusCode; ?>">
						<?php echo $statusText; ?>
					</td>
				</tr>
				<tr>
					<td>
						Company ID (CRM)
					</td>
					<td>
						<?php echo $ctt['cmp'] ; ?>
					</td>
					<td class="NA">
						N/A
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
						<?php 
							$statusCode = $ctt['cmp'] == 0 ? 'bad' : 'good';
							$statusText = $statusCode == 'bad' ? 'Contact not Associated with a Company' : 'Contact Associated with a Company';
						?>
					<td class="<?php echo $statusCode; ?>">
						<?php echo $statusText; ?>
					</td>
				</tr>
				<tr>
					<td>
						Company LinkedIn ID
					</td>
					<td>
						<?php 
							if($ctt['cmp'] == 0) {
								$cmpSearchSQL = 'SELECT cmp_linkedin FROM CRM_cmp WHERE cmp_linkedin = "' . $cttScrpd['external_company_linkedin_id'] . '";';
								$cmpSearchQRY = db_query($cmpSearchSQL);
								$cmpSearchARR = mysqli_fetch_row($cmpSearchQRY);
								
								$cmp_linkedin = $cmpSearchARR[0] ? $cmpSearchARR[0] : false;

								echo $cmp_linkedin ? $cmp_linkedin : 'Company Not found in CRM<span class="aside"> (by LinkedIn ID)</span>';
							}
							else {
								$cmp_linkedin = getDataFromID('CRM_cmp','cmp_linkedin',$ctt['cmp']);
								echo $cmp_linkedin . ' <span class="aside"> (Looked Up in CRM_cmp)</aside>';
							}
						?>
					</td>
					<td>
						<?php echo $cttScrpd['external_company_linkedin_id'] . ' <span class="aside"> (Raw Scraped)</aside>'; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
						<?php 
							$recommend_cmpAdd = false;

							if($cmp_linkedin === false) {
								$statusCode = 'warn';
								$statusText = 'Company may need to be added to CRM';
								$recommend_cmpAdd = true;
							}
							else {
								$statusCode = ($cttScrpd['external_company_linkedin_id'] == $cmp_linkedin ? 'good' : 'bad') ; 
								$statusText = $statusCode == 'good' ? 'Company LinkedIn IDs Match' : 'Company LinkedIn IDs Do Not Match';
							}

							$cmp_linkedin = $cmp_linkedin === false ? $cttScrpd['external_company_linkedin_id'] : $cmp_linkedin;
						?>
					<td class="<?php echo $statusCode; ?>">
						<?php echo $statusText; ?>
					</td>
				</tr>
				<tr>
					<td>
						Company Name <span class="aside">(Lookup by Contact's cmp)</span>
					</td>
					<td>
						<?php 
							$cmp_name = getDataFromID('CRM_cmp','cmp_name',$ctt['cmp']) ; 
							echo $cmp_name . ' <span class="aside"> (Looked Up in CRM_cmp)</aside>';
						?>
					</td>
					<td>
						<?php echo $cttScrpd['company'] . ' <span class="aside"> (Raw Scraped)</aside>' ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
						<?php 
							$statusCode = $cmp_name == $cttScrpd['company'] ? 'good' : 'warn' ; 
							$statusText = $statusCode == 'good' ? 'Company Names Match' : 'Company Names Do Not Match';
						?>
					<td class="<?php echo $statusCode; ?>">
						<?php echo $statusText; ?>
					</td>
				</tr>
				<tr>
					<td>
						Company Name <span class="aside">(Lookup by LinkedIn ID)</span>
					</td>
					<td>
						<?php
							$cmpNameSQL = 'SELECT cmp_name FROM CRM_cmp WHERE cmp_linkedin = "' . $cmp_linkedin .'";';
							$cmpNameQRY = db_query($cmpNameSQL);
							$cmpNameARR = mysqli_fetch_array($cmpNameQRY);
							echo $cmpNameARR[0] ? $cmpNameARR[0] : 'Not Found';
						?>
					</td>
					<td>
						<?php
							$cmpNameSQL = 'SELECT company_name FROM companies WHERE company_Linkedin_id = "' . $cmp_linkedin .'";';
							$cmpNameQRY = db_query($cmpNameSQL);
							$cmpNameARR = mysqli_fetch_array($cmpNameQRY);
							echo $cmpNameARR[0] ? $cmpNameARR[0] : 'Not Found';
						?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td class="">
					</td>
				</tr>
				<tr>
					<td>
						Location ID (CRM)
					</td>
					<td>
						<?php echo $ctt['lcn'] ; ?>
					</td>
					<td class="NA">
						N/A
					</td>
						<?php 
							$statusCode = $ctt['lcn'] == 0 ? 'bad' : 'good';
							$statusText = $statusCode == 'bad' ? 'Contact not Associated with a Location' : 'Contact Associated with a Location';
						?>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td class="<?php echo $statusCode; ?>">
						<?php echo $statusText; ?>
					</td>
				</tr>
				<tr>
					<td>
						Location Name
					</td>
					<td>
						<?php 
							$ctt_lcn_name = $ctt['lcn'] == 0 ? getDataFromID('CRM_ctt','ctt_clean_lcn',$ctt_ID) : getDataFromID('CRM_lcn','lcn_name',$ctt['lcn']) ;
							
							echo $ctt_lcn_name ;
							echo $ctt['lcn'] == 0 ? '<span class="aside"> (cleaned scraped lcn)</aside>' : '<span class="aside"> (Lookup in CRM_lcn)</aside>'; 
						?>
					</td>
					<td id="ctt_raw_lcn">
						<?php echo $cttScrpd['location'] ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td class="NA">
						N/A
					</td>
				</tr>
				<tr>
					<td>
						Title
					</td>
					<td>
						<?php echo $ctt['ctt_title'] ; ?>
					</td>
					<td>
						<?php echo $cttScrpd['title'] ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						Role
					</td>
					<td>
						<?php echo $ctt['ctt_role'] ; ?>
					</td>
					<td>
						<?php echo $cttScrpd['role'] ; ?>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
					<td>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="title">
			Select a Company / Location for this contact
		</td>
	</tr>
	<tr>
		<td>
			<?php
				//DETERMINE WHICH COMPANY STRING TO USE FOR SUGGESTIONS / MATCHING
				if(strlen($cttScrpd['company']) > 2) {
					$cmp_match_str = $cttScrpd['company'];
				}
				else if ($ctt['cmp'] != 0) {
					$cmp_match_str = getDataFromID('CRM_cmp','cmp_name',$ctt['cmp']);
				}

			?>

			Company Suggestions based on: <span id="ctt_cmp_name" style="color:#0F0;"><? echo $cmp_match_str; ?></span>
		</td>
		<td>
			Location Suggestions based on: <span id="ctt_lcn_name" style="color:#0F0;"><?php echo $ctt_lcn_name ; ?></span>
		</td>
		<td>
			<input id="add_cmp_select" form="assoc_ctt_cmp" type="radio" class="ctt_cmp_select inline" name="ctt_cmp_select" value="add_cmp" title="Add Company" />
			Add Company to CRM
			<?php
				echo $ctt['cmp'] == 0 ? '<span style="float:right;">(RECOMMENDED!)</span>' : '(Not Recommended)'; 
			?>
		</td>
		<td>
			Additional Info
		</td>
	</tr>
	<tr style="height:150px;">
		<td>
			<div class="innerListWrapper">
				<table class="innerList ">
				<?php
					//QUERY AND STORE LIST OF COMPANIES
					$cmpLstSQL = 'SELECT ID, cmp_name FROM CRM_cmp;';
					$cmpLstQRY = db_query($cmpLstSQL);
					$cmp_suggested = array();

					while ($row = mysqli_fetch_assoc($cmpLstQRY)) {
						$cmpLst[$row['ID']] = $row['cmp_name'];
					}

					//STORE LIST OF COMPARISON RATINGS
					foreach( $cmpLst as $key => $cmp ) {
						$cmpX[$key] = string_compare($cmp, $cmp_match_str);
						$cmpLVN[$key] = levenshtein($cmp, $cmp_match_str);
						$cmpSMT[$key] = similar_text($cmp, $cmp_match_str);
					}

					arsort($cmpX);
					asort($cmpLVN);
					asort($cmpSMT);

					if($ctt['cmp']) {
						$cmp_suggested[] = $ctt['cmp'];

						makeSelectRow('assoc_ctt_cmp', 'radio','ctt_cmp_select','ctt_cmp_select inline',$ctt['cmp'],$cmpLst[$ctt['cmp']],'(cmp of ctt)',true);
						?>
							<script type="text/javascript">
								$("")
							</script>
						<?php
					}
					$cmpLstSearch = array_search($cmp_match_str,$cmpLst);
					if ($cmpLstSearch && !in_array($cmpLstSearch,$cmp_suggested)) {
						$cmp_suggested[] = $cmpLstSearch;

						makeSelectRow('assoc_ctt_cmp','radio','ctt_cmp_select','ctt_cmp_select inline',$cmpLstSearch,$cmpLst[$cmpLstSearch],'(cmp str =)');
					}
					foreach($cmpLst as $key => $val) {
						if (strpos($val,$cmp_match_str) !== false && !in_array($key,$cmp_suggested)) {
							$cmp_suggested[] = $key;

							makeSelectRow('assoc_ctt_cmp', 'radio','ctt_cmp_select','ctt_cmp_select inline',$key,$val,'(cmp str IN)');
						}
					}


					$LC = 0;
					foreach($cmpX as $key => $val) {
						if(!in_array($key,$cmp_suggested)) {
							$cmp_suggested[] = $key;

							makeSelectRow('assoc_ctt_cmp', 'radio','ctt_cmp_select','ctt_cmp_select inline',$key,$cmpLst[$key],(number_format($val,2) . ' (strX)'));
						}

						$LC++;
						if($LC >= 2) {break;}
					}
					$LC = 0;
					foreach($cmpLVN as $key => $val) {
						if(!in_array($key,$cmp_suggested)) {
							$cmp_suggested[] = $key;

							makeSelectRow('assoc_ctt_cmp', 'radio','ctt_cmp_select','ctt_cmp_select inline',$key,$cmpLst[$key],($val . ' (LVN)'));
						}

						$LC++;
						if($LC >= 2) {break;}
					}
					$LC = 0;
					foreach($cmpSMT as $key => $val) {
						if(!in_array($key,$cmp_suggested)) {
							$cmp_suggested[] = $key;

							makeSelectRow('assoc_ctt_cmp', 'radio','ctt_cmp_select','ctt_cmp_select inline',$key,$cmpLst[$key],($val . ' (smTxt)'));
						}

						$LC++;
						if($LC >= 2) {break;}
					}

					makeSelectRow('assoc_ctt_cmp', 'radio','ctt_cmp_select','ctt_cmp_select inline',0,'No Company');

				?>
				</table>
			</div>
		</td>
		<td>
			<div class="innerListWrapper" style="position:relative;">
				<table class="innerList" id="lcn_suggested_list">
					<?php
						if($ctt['cmp']) {
							$reqType = 'lcn_suggested';
							$cmp_ID = $ctt['cmp'];
							$lcn_raw = $ctt_lcn_name;

							include ABSPATH . 'include/dataHealth_ajax.php';
						}
					?>
				</table>
			</div>
		</td>
		<td>
			<div class="innerListWrapper" style="position:relative;">
				<table class="innerList" id="add_cmp_list" style="display:none;">
					<?php
						makeSelectRow('assoc_ctt_cmp', 'text','add_cmp_name','small',$cmp_match_str,'', 'Company Name');
						makeSelectRow('assoc_ctt_cmp', 'text','add_cmp_full_name','small',$cmp_match_str,'', 'Company Full Name');
						makeSelectRow('assoc_ctt_cmp', 'text','add_cmp_linkedin','small',$cmp_linkedin,'', 'Company LinkedIn ID');
					?>
					<tr>
						<td>
							CHECK COMPANY LINKEDIN PAGE BEFORE ADDING COMPANY / UPDATING CONTACT ->
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td>
			<div class="innerListWrapper">
				<table class="innerList">
					<?php
						//HOW MANY MATCHES USING ctt_raw_cmp & cmp = 0
						$ctt_cmp_match3SQL = 'SELECT COUNT(*) FROM CRM_ctt WHERE ' .
											'cmp = "0" AND ' .
											'ctt_raw_cmp = "' . $cmp_match_str . '";';
						$ctt_cmp_match3QRY = db_query($ctt_cmp_match3SQL);
						$ctt_cmp_match3ARR = mysqli_fetch_row($ctt_cmp_match3QRY);
						$ctt_cmp_match3CNT = $ctt_cmp_match3ARR[0];

						//HOW MANY MATCHES USING ctt_raw_cmp
						$ctt_cmp_matchSQL = 'SELECT COUNT(*) FROM CRM_ctt WHERE ' .
											//'ctt_cmp_linkedin = "' . $cmp_linkedin . '" AND ' .
											'ctt_raw_cmp = "' . $cmp_match_str . '";';
						$ctt_cmp_matchQRY = db_query($ctt_cmp_matchSQL);
						$ctt_cmp_matchARR = mysqli_fetch_row($ctt_cmp_matchQRY);
						$ctt_cmp_matchCNT = $ctt_cmp_matchARR[0];

						//HOW MANY MATCHES USING cmp_linkedin, ctt_raw_cmp
						$ctt_cmp_match2SQL = 'SELECT COUNT(*) FROM CRM_ctt WHERE ' .
											'ctt_cmp_linkedin = "' . $cmp_linkedin . '" AND ' .
											'ctt_raw_cmp = "' . $cmp_match_str . '";';
						$ctt_cmp_match2QRY = db_query($ctt_cmp_match2SQL);
						$ctt_cmp_match2ARR = mysqli_fetch_row($ctt_cmp_match2QRY);
						$ctt_cmp_match2CNT = $ctt_cmp_match2ARR[0];

						//HOW MANY MATCHES USING cmp_linkedin, ctt_raw_cmp, AND cmp, AND ctt_clean_lcn
						$ctt_lcn_matchSQL = 'SELECT COUNT(*) FROM CRM_ctt WHERE ' .
											'ctt_cmp_linkedin = "' . $cmp_linkedin . '" AND ' .
											'cmp = "' . $ctt['cmp'] . '" AND ' .
											'ctt_raw_cmp = "' . $cmp_match_str . '" AND ' . 
											'ctt_clean_lcn = "' . $ctt_lcn_name . '";';
						$ctt_lcn_matchQRY = db_query($ctt_lcn_matchSQL);
						$ctt_lcn_matchARR = mysqli_fetch_row($ctt_lcn_matchQRY);
						$ctt_lcn_matchCNT = $ctt_lcn_matchARR[0];

						//HOW MANY MATCHES USING cmp_linkedin, ctt_raw_cmp, NOT cmp, AND ctt_clean_lcn
						$ctt_lcn_match2SQL = 'SELECT COUNT(*) FROM CRM_ctt WHERE ' .
											//'ctt_cmp_linkedin = "' . $cmp_linkedin . '" AND ' .
											'ctt_raw_cmp = "' . $cmp_match_str . '" AND ' . 
											'ctt_clean_lcn = "' . $ctt_lcn_name . '";';
						$ctt_lcn_match2QRY = db_query($ctt_lcn_match2SQL);
						$ctt_lcn_match2ARR = mysqli_fetch_row($ctt_lcn_match2QRY);
						$ctt_lcn_match2CNT = $ctt_lcn_match2ARR[0];
					?>
					<tr>
						<td>
							<?php echo $ctt_cmp_match3CNT . ' Matched Contacts (cmp) <span class="aside">(ctt_raw_cmp=, cmp=0)</span>' ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $ctt_cmp_matchCNT . ' Matched Contacts (cmp) <span class="aside">(ctt_raw_cmp=)</span>' ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $ctt_cmp_match2CNT . ' Matched Contacts (cmp) <span class="aside">(ctt_raw_cmp=, ctt_cmp_linkedin=)</span>' ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $ctt_lcn_matchCNT . ' Matched Contacts (lcn) <span class="aside">(Above + ctt_clean_lcn=, cmp=)</span>' ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $ctt_lcn_match2CNT . ' Matched Contacts (lcn) <span class="aside">(ctt_raw_cmp=, ctt_clean_lcn=)</span>' ?>
						</td>
					</tr>
					<tr>
						<td>
							<button class="applyFilters"><a href="<?php echo 'http://www.linkedin.com/profile/view?id=' . $ctt['ctt_linkedin'] ; ?>" target="_blank">Contact's LinkedIn Page</a></button>
						</td>
					</tr>
					<tr>
						<td>
							<button class="applyFilters"><a href="<?php echo 'http://www.linkedin.com/company/' . $cttScrpd['external_company_linkedin_id'] ; ?>" target="_blank">Company's LinkedIn Page (Scraped)</a></button>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			Other Companies: <span style="float:right;">strX / LVN / smTx</span>
		</td>
		<td id="lcn_list_title">
			Other Locations for Selected Company:
		</td>
		<td>
			<input id="add_lcn_select" form="assoc_ctt_cmp" type="radio" class="ctt_lcn_select inline" name="ctt_lcn_select" value="add_lcn" title="Add Location">
			Add Location to CRM
		</td>
		<td>
			Options & Actions
		</td>
	</tr>
	<tr style="height:100%;">
		<td>
			<div class="innerListWrapper">
				<table class="innerList">
				<?php
					foreach($cmpLst as $key => $val) {
						makeSelectRow('assoc_ctt_cmp', 'radio','ctt_cmp_select','ctt_cmp_select inline',$key,$val,(number_format($cmpx[$key],2) . ' / ' . $cmpLVN[$key] . ' / ' . $cmpSMT[$key]));
					}
				?>
				</table>
			</div>
		</td>
		<td>
			<div class="innerListWrapper" style="position:relative;">
				<table class="innerList" id="lcn_list">
					<?php
						if($ctt['cmp']) {
							$reqType = 'lcn';
							$cmp_ID = $ctt['cmp'];

							include ABSPATH . 'include/dataHealth_ajax.php';
						}
					?>
				</table>
			</div>
		</td>
		<td>
			<div class="innerListWrapper" style="position:relative;">
				<table class="innerList" id="add_lcn_list" style="display:none;">
					<?php
						makeSelectRow('assoc_ctt_cmp', 'text','add_lcn_name','small',trim($ctt_lcn_name),'', 'Location Name');
						makeSelectRow('assoc_ctt_cmp', 'text','add_lcn_locality','small',trim($ctt_lcn_name),'', 'Location City');
						makeSelectRow('assoc_ctt_cmp', 'text','add_lcn_region','small',trim($ctt_lcn_name),'', 'Location Region');
						makeSelectRow('assoc_ctt_cmp', 'text','add_lcn_country','small',trim($ctt_lcn_name),'', 'Location Country');
					?>
				</table>
			</div>
		</td>
		<td>
			<div class="innerListWrapper" style="position:relative;">
				<table class="innerList" id="options_list" >
					<?php 
					//makeSelectRow($formID, $type, $name, $classes, $val, $text, $text_right, $checked)

						makeSelectRow('assoc_ctt_cmp','radio','ctt_update_matches',' inline','match_none','Only Update this Contact','',true);
						makeSelectRow('assoc_ctt_cmp','radio','ctt_update_matches',' inline','match_cmp','Update Company for matching Contacts');
						makeSelectRow('assoc_ctt_cmp','radio','ctt_update_matches',' inline','match_lcn','Update Company and Location for matching Contacts');

						/* SELECT HOW MATCHES ARE DETERMINED. REMOVED BECAUSE I DON'T THINK IT'S NEEDED
						makeSelectRow('assoc_ctt_cmp','radio','match_ctt_type',' inline','match_cmp_raw','Match by cmp_linkedin, ctt_raw_cmp','',true);
						makeSelectRow('assoc_ctt_cmp','radio','match_ctt_type',' inline','match_cmp_rawANDcmp','Match by above + cmp');
						*/
						
						makeSelectRow('assoc_ctt_cmp', 'submit','ctt_update','small','Update Contact & Go To Next');
						makeSelectRow('assoc_ctt_cmp', 'submit','ctt_skip','small','Skip Contact & Go To Next');
						makeSelectRow('assoc_ctt_cmp', 'submit','ctt_delete','small','Delete Contact & Go To Next');
					?>
					<tr style="height:0px !important;">
						<td>
							<form id="assoc_ctt_cmp"  method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<input type="text" name="update_ctt_ID" value="<?php echo $ctt_ID ; ?>" style="display:none;" />
								<input type="text" name="update_ctt_raw_cmp" value="<?php echo $cmp_match_str ; ?>" style="display:none;" />
								<input type="text" name="update_ctt_clean_lcn" value="<?php echo $ctt['ctt_clean_lcn'] ; ?>" style="display:none;" />
							</form>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
<?php include 'footer.php'; ?>