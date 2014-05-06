<?php
	$pageTitle = "Contact Data Breakdown"; 
	include 'header.php'; 
?>
<?php
	db_connection();

	define('trNOTFOUND','<td style="color:#F00">!!!</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>');

	/*/ GET DATA FROM DIFFERENT TABLES IN VARIOUS WAYS FOR COMPARISON /*/

	//GET ROW FROM CRM_ctt BY ID
	$CRM_ctt_ID 		= safeString($_GET['ID']);
	$CRM_ctt_SQL		= 'SELECT * FROM CRM_ctt WHERE ID = ' . $CRM_ctt_ID . ' LIMIT 0, 1;';
	$CRM_ctt_QRY		= db_query($CRM_ctt_SQL);
	if(mysqli_num_rows($CRM_ctt_QRY)) {
		$CRM_ctt 		= mysqli_fetch_assoc($CRM_ctt_QRY);
	}
	else {$CRM_ctt 		= false;}

	//GET ROW FROM contacts BY CRM_ctt.ctt_linkedin
	$contacts_SQL		= 'SELECT * FROM contacts WHERE external_linkedin_id = ' . $CRM_ctt['ctt_linkedin'] . ' LIMIT 0,1;';
	$contacts_QRY		= db_query($contacts_SQL);
	if(mysqli_num_rows($contacts_QRY)) {
		$contacts 		= mysqli_fetch_assoc($contacts_QRY);
	}
	else {$contacts 	= false;}

	//GET ROW FROM CRM_cmp BY CRM_ctt.cmp
	$CRM_cmp_SQL		= 'SELECT * FROM CRM_cmp WHERE ID = ' . $CRM_ctt['cmp'] . ' LIMIT 0,1;';
	$CRM_cmp_QRY		= db_query($CRM_cmp_SQL);
	if(mysqli_num_rows($CRM_cmp_QRY)) {
		$CRM_cmp 		= mysqli_fetch_assoc($CRM_cmp_QRY);
	}
	else {$CRM_cmp 	= false;}

	//GET ROW FROM CRM_cmp BY CRM_ctt.ctt_cmp_linkedin
	$CRM_cmp2_SQL		= 'SELECT * FROM CRM_cmp WHERE cmp_linkedin = ' . $CRM_ctt['ctt_cmp_linkedin'] . ' LIMIT 0,1;';
	$CRM_cmp2_QRY		= db_query($CRM_cmp2_SQL);
	if(mysqli_num_rows($CRM_cmp2_QRY)) {
		$CRM_cmp2 		= mysqli_fetch_assoc($CRM_cmp2_QRY);
	}
	else {$CRM_cmp2 	= false;}

	//GET ROW FROM CRM_cmp BY contacts.external_company_linkedin_id
	$CRM_cmp3_SQL		= 'SELECT * FROM CRM_cmp WHERE cmp_linkedin = ' . $contacts['external_company_linkedin_id'] . ' LIMIT 0,1;';
	$CRM_cmp3_QRY		= db_query($CRM_cmp3_SQL);
	if(mysqli_num_rows($CRM_cmp3_QRY)) {
		$CRM_cmp3 		= mysqli_fetch_assoc($CRM_cmp3_QRY);
	}
	else {$CRM_cmp3 	= false;}

	//GET ROW FROM companies BY CRM_ctt.cmp_linkedin
	$companies_SQL		= 'SELECT * FROM companies WHERE company_Linkedin_id = ' . $CRM_ctt['ctt_cmp_linkedin'] . ' LIMIT 0,1;';
	$companies_QRY		= db_query($companies_SQL);
	if(mysqli_num_rows($companies_QRY)) {
		$companies 		= mysqli_fetch_assoc($companies_QRY);
	}
	else {$companies 	= false;}

	//GET ROW FROM companies BY contacts.external_company_linkedin_id
	$companies2_SQL		= 'SELECT * FROM companies WHERE company_Linkedin_id = ' . $contacts['external_company_linkedin_id'] . ' LIMIT 0,1;';
	$companies2_QRY		= db_query($companies2_SQL);
	if(mysqli_num_rows($companies2_QRY)) {
		$companies2 	= mysqli_fetch_assoc($companies2_QRY);
	}
	else {$companies2 	= false;}

	//GET ROW FROM CRM_lcn BY CRM_ctt.lcn
	$CRM_lcn_SQL		= 'SELECT * FROM CRM_lcn WHERE ID = ' . $CRM_ctt['lcn'] . ' LIMIT 0,1;';
	$CRM_lcn_QRY		= db_query($CRM_lcn_SQL);
	if(mysqli_num_rows($CRM_lcn_QRY)) {
		$CRM_lcn 	= mysqli_fetch_assoc($CRM_lcn_QRY);
	}
	else {$CRM_lcn 	= false;}

	/*/ DETERMINE KEY VARIABLES EVEN IF THERE ARE DATA CONFLICTS BASED ON PRIORITY /*/

	//COMPANY NAME
	if($CRM_ctt['cmp']) { //If CRM contact has a company, use its name
		$cmp_match_str = $CRM_cmp['cmp_name'];
	}

?>
<style>
#CRM_Data td {font-size:10px;padding:2px;}
#CRM_Data td:nth-child(2),
#CRM_Data td:nth-child(3),
#CRM_Data td:nth-child(6),
#CRM_Data td:nth-child(9),
#CRM_Data td:nth-child(11),
#CRM_Data td:nth-child(13) {
	border-right:1px solid #666;
}
tr.divider {
	border-bottom:1px solid #666;
}
span.aside {
	color:#888;
	font-size:9px;
}
</style>
<table class="innerList" style="width:100%; max-width:inherit;" id="CRM_Data">
	<tr class="divider">
		<td>
			Table Name
		</td>
		<td>
			Match / Lookup Method
		</td>
		<td>
			ID
		</td>
		<td>
			First Name
		</td>
		<td>
			Last Name
		</td>
		<td>
			LinkedIn
		</td>
		<td>
			cmp ID
		</td>
		<td>
			cmp Name
		</td>
		<td>
			cmp LinkedIn
		</td>
		<td>
			lcn ID
		</td>
		<td>
			lcn Name
		</td>
		<td>
			Title
		</td>
		<td>
			Role
		</td>
		<td>
			Last Updated
		</td>
	</tr>
	<tr>
		<td>
			CRM_ctt
		</td>
		<td>
			CRM_ctt.ID
		</td>
		<?php if($CRM_ctt) { ?>
			<td>
				<?php echo $CRM_ctt['ID']; ?>
			</td>
			<td>
				<?php echo $CRM_ctt['ctt_f_name']; ?>
			</td>
			<td>
				<?php echo $CRM_ctt['ctt_l_name']; ?>
			</td>
			<td>
				<a href="http://www.linkedin.com/profile/view?id=<?php echo $CRM_ctt['ctt_linkedin']; ?>" target="_blank"><?php echo $CRM_ctt['ctt_linkedin']; ?></a>
			</td>
			<td>
				<?php echo $CRM_ctt['cmp']; ?>
			</td>
			<td>
				<?php echo $CRM_ctt['ctt_raw_cmp']; ?> <span class="aside">(ctt_raw_cmp)</span>
			</td>
			<td>
				<a href="http://www.linkedin.com/company/<?php echo $CRM_ctt['ctt_cmp_linkedin']; ?>" target="_blank"><?php echo $CRM_ctt['ctt_cmp_linkedin']; ?></a>
			</td>
			<td>
				<?php echo $CRM_ctt['lcn']; ?>
			</td>
			<td>
				<?php echo $CRM_ctt['ctt_clean_lcn']; ?> <span class="aside">(ctt_clean_lcn)</span>
			</td>
			<td>
				<?php echo $CRM_ctt['ctt_title']; ?>
			</td>
			<td>
				<?php echo $CRM_ctt['ctt_role']; ?>
			</td>
			<td>
				<?php echo $CRM_ctt['updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
	<tr class="divider">
		<td>
			contacts
		</td>
		<td>
			CRM_ctt.ctt_linkedin
		</td>
		<?php if($contacts) { ?>
			<td>
				<?php echo $contacts['contact_id']; ?>
			</td>
			<td>
				<?php echo $contacts['f_name']; ?>
			</td>
			<td>
				<?php echo $contacts['l_name']; ?>
			</td>
			<td>
				<a href="http://www.linkedin.com/profile/view?id=<?php echo $contacts['external_linkedin_id']; ?>" target="_blank"><?php echo $contacts['external_linkedin_id']; ?></a>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $contacts['company']; ?>
			</td>
			<td>
				<a href="http://www.linkedin.com/company/<?php echo $contacts['external_company_linkedin_id']; ?>" target="_blank"><?php echo $contacts['external_company_linkedin_id']; ?></a>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $contacts['location']; ?>
			</td>
			<td>
				<?php echo $contacts['title']; ?>
			</td>
			<td>
				<?php echo $contacts['role']; ?>
			</td>
			<td>
				<?php echo $contacts['updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
	<tr>
		<td>
			CRM_cmp
		</td>
		<td>
			CRM_ctt.cmp
		</td>
		<?php if($CRM_cmp) { ?>
			<td>
				<?php echo $CRM_cmp['ID']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_cmp['ID']; ?>
			</td>
			<td>
				<?php echo $CRM_cmp['cmp_name']; ?>
			</td>
			<td>
				<a href="http://www.linkedin.com/company/<?php echo $CRM_cmp['cmp_linkedin']; ?>" target="_blank"><?php echo $CRM_cmp['cmp_linkedin']; ?></a>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_cmp['updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
	<tr>
		<td>
			CRM_cmp
		</td>
		<td>
			CRM_ctt.ctt_cmp_linkedin
		</td>
		<?php if($CRM_cmp2) { ?>
			<td>
				<?php echo $CRM_cmp2['ID']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_cmp2['ID']; ?>
			</td>
			<td>
				<?php echo $CRM_cmp2['cmp_name']; ?>
			</td>
			<td>
				<a href="http://www.linkedin.com/company/<?php echo $CRM_cmp2['cmp_linkedin']; ?>" target="_blank"><?php echo $CRM_cmp2['cmp_linkedin']; ?></a>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_cmp2['updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
	<tr class="divider">
		<td>
			CRM_cmp
		</td>
		<td>
			contacts.external_company_linkedin_id
		</td>
		<?php if($CRM_cmp3) { ?>
			<td>
				<?php echo $CRM_cmp3['ID']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_cmp3['ID']; ?>
			</td>
			<td>
				<?php echo $CRM_cmp3['cmp_name']; ?>
			</td>
			<td>
				<a href="http://www.linkedin.com/company/<?php echo $CRM_cmp3['cmp_linkedin']; ?>" target="_blank"><?php echo $CRM_cmp3['cmp_linkedin']; ?></a>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_cmp3['updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
	<tr>
		<td>
			companies
		</td>
		<td>
			CRM_ctt.ctt_cmp_linkedin
		</td>
		<?php if($companies) { ?>
			<td>
				<?php echo $companies['company_id']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $companies['company_id']; ?>
			</td>
			<td>
				<?php echo $companies['company_name']; ?>
			</td>
			<td>
				<a href="http://www.linkedin.com/company/<?php echo $companies['company_Linkedin_id']; ?>" target="_blank"><?php echo $companies['company_Linkedin_id']; ?></a>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $companies['Last_updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
	<tr class="divider">
		<td>
			companies
		</td>
		<td>
			contacts.external_company_linkedin_id
		</td>
		<?php if($companies2) { ?>
			<td>
				<?php echo $companies2['company_id']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $companies2['company_id']; ?>
			</td>
			<td>
				<?php echo $companies2['company_name']; ?>
			</td>
			<td>		
				<a href="http://www.linkedin.com/company/<?php echo $companies2['company_Linkedin_id']; ?>" target="_blank"><?php echo $companies2['company_Linkedin_id']; ?></a>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $companies2['Last_updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
	<tr>
		<td>
			CRM_lcn
		</td>
		<td>
			CRM_ctt.lcn
		</td>
		<?php if($CRM_lcn) { ?>
			<td>
				<?php echo $CRM_lcn['ID']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_lcn['cmp']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_lcn['ID']; ?>
			</td>
			<td>
				<?php echo $CRM_lcn['lcn_name']; ?>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<span class="aside">-</span>
			</td>
			<td>
				<?php echo $CRM_lcn['updated']; ?>
			</td>
		<?php } else {
			echo trNOTFOUND;
		} ?>
	</tr>
<table>

<table class="innerList" style="width:100%; max-width:inherit; border-top:2px solid #AAA;" id="Data_Issues">
	<tr>
		<td colspan="3" style="text-align:center;height:25px;">
			Data Issues and Recommendations
		</td>
	</tr>
	<tr class="divider">
		<td>
			Subject
		</td>
		<td>
			Evaluation
		</td>
		<td>
			Recommendation
		</td>
	</tr>
	<tr>
		<td>
			Contact LinkedIn ID
		</td>
		<td>
			Evaluation
		</td>
		<td>
			Recommendation
		</td>
	</tr>
	<tr>
		<td>
			Contact's Company (cmp ID)
		</td>
		<td>
			Evaluation
		</td>
		<td>
			Recommendation
		</td>
	</tr>
	<tr>
		<td>
			Company LinkedIn ID
		</td>
		<td>
			Evaluation
		</td>
		<td>
			Recommendation
		</td>
	</tr>
	<tr>
		<td>
			Company LinkedIn ID
		</td>
		<td>
			Evaluation
		</td>
		<td>
			Recommendation
		</td>
	</tr>
	<tr>
		<td>
			Contact's Location (lcn ID)
		</td>
		<td>
			Evaluation
		</td>
		<td>
			Recommendation
		</td>
	</tr>
</table>
<?php include 'footer.php'; ?>