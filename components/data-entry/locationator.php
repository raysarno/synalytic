<?php 
	$pageTitle = "Locationator";
	include 'header.php'; 
?>
<?php 

	if(db_connection()) {
		$cmpSQL = "SELECT ID AS cmpID, cmp_name, cmp_URL, (SELECT COUNT(cmp) FROM CRM_lcn WHERE cmp = cmpID) AS 'lcn_count' FROM CRM_cmp;";
		$cmpQRY = db_query($cmpSQL);

		$lcnSQL = 'SELECT ID, lcn_name FROM CRM_lcn WHERE cmp = ' . $cmp_ID . ' ORDER BY lcn_name;';
		$lcnQRY = db_query($lcnSQL);

		if($editMode) {
			$telSQL = 'SELECT * FROM CRM_tel WHERE cmp = ' . $cmp_ID . ' AND (lcn = ' . $lcn_ID . ' OR lcn = 0);';
			$telQRY = db_query($telSQL);
		}	
	}
	
?>

	<table class="contentTable left" style="max-height:100%;">
		<tr class="divider">
			<td colspan="2"><h3 style="text-align:center;">Select A Company</h3></td>
			<td colspan="2">
				<form id="cmpSelect" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input id="cmp" type="submit" name="cmp_select" value="Add Location to Selected Company ->" style="font-size:8px;float:right;" />
				</form>
			</td>
		</tr>
		<tr class="subdivider">
			<td style="width:25px;"></td>
			<td style="width:120px;">Cmp. Name</td>
			<td style="width:180px;">Cmp. URL</td>
			<td style="width:75px;"># Locations</td>
		</tr>
		<tr style="max-height:100%;">
			<td colspan="4">
				<div class="innerListWrapper">
					<table id="cmp_count_lcn" class="innerList">
					<?php
						while ($row = mysqli_fetch_assoc($cmpQRY)) {
							$checked = ($row['cmpID'] == $cmp_ID) ? 'checked' : "";

							echo '<tr class="' . $checked . '"">';
							echo '<td><input type="radio" form="cmpSelect" value="' . $row['cmpID'] . '" name="cmp_ID" ' . $checked . '="' . $checked . '" /></td>';
							echo '<td>' . $row['cmp_name'] . '</td>';
							echo '<td>' . $row['cmp_URL'] . '</td>';
							echo '<td>' . $row['lcn_count'] . '</td>';
							echo '</tr>';
						}
					?>
					</table>
				</div>
			</td>
		</tr>
	</table>

	<table class="contentTable centered">
		<tr class="divider">
			<td colspan="2"><h3 style="text-align:center;"><u><?php echo $editMode ? 'Edit' : 'Add' ; ?></u> Location For Selected Company</h3></td>
		</tr>
		<tr>
			<td>Current Company:</td>
			<td>
				<?php echo $cmp_name ? $cmp_name : '' ; ?>
			</td>
		</tr>
		<tr class="subdivider">
			<td>Company Website:</td>
			<td style="overflow-x:scroll;text-overflow:scroll;max-width:160px;text-overflow:ellipsis;">
				<?php
					if($cmp_URL) {
						echo '<a href="' . $cmp_URL . '" target="_blank">' . $cmp_URL . '</a>';
					}
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;"><?php echo $editMode ? 'Edit' : 'Add' ; ?> a Location (* = Required)</td>
		</tr>
		<tr class="condensed">
			<td>Address Line 1:</td>
			<td>
				<input type="text" form="locationator" name="lcn_address1" value="<?php echo $lcn_address1 ? $lcn_address1 : ''; ?>" />
			</td>
		</tr>
		<tr class="condensed">
			<td>Address Line 2:</td>
			<td>
				<input type="text" form="locationator" name="lcn_address2" value="<?php echo $lcn_address2 ? $lcn_address2 : ''; ?>" />
			</td>
		</tr>
		<tr class="condensed">
			<td>Address Line 3:</td>
			<td>
				<input type="text" form="locationator" name="lcn_address3" value="<?php echo $lcn_address3 ? $lcn_address3 : ''; ?>" />
			</td>
		</tr>
		<tr class="condensed">
			<td>*Locality (City):</td>
			<td>
				<input type="text" form="locationator" name="lcn_locality" value="<?php echo $lcn_locality ? $lcn_locality : ''; ?>" />
			</td>
		</tr>
		<tr class="condensed">
			<td>Region (State; ex. "Texas"):</td>
			<td>
				<input type="text" form="locationator" name="lcn_region" value="<?php echo $lcn_region ? $lcn_region : ''; ?>" />
			</td>
		</tr>
		<tr class="condensed">
			<td>Region Code (ex. "TX"): <a href="http://www.50states.com/abbreviations.htm" target="_blank" style="text-decoration:underline;">(Lookup)</a></td>
			<td>
				<input type="text" form="locationator" name="lcn_region_code" value="<?php echo $lcn_region_code ? $lcn_region_code : ''; ?>" />
			</td>
		</tr>
		<tr class="condensed">
			<td>Postcode (Zip Code):</td>
			<td>
				<input type="text" form="locationator" name="lcn_postcode" value="<?php echo $lcn_postcode ? $lcn_postcode : ''; ?>" />
			</td>
		</tr>
		<tr class="condensed">
			<td>*Country (ex. "United States"):</td>
			<td>
				<input type="text" form="locationator" name="lcn_country" value="<?php echo $lcn_country ? $lcn_country : 'United States'; ?>" />
			</td>
		</tr>
		<tr class="subdivider condensed">
			<td>*Country Code (ex. "US"): <a href="http://countrycode.org" target="_blank" style="text-decoration:underline;">(Lookup)</a></td>
			<td>
				<input type="text" form="locationator" name="lcn_country_code" value="<?php echo $lcn_country_code ? $lcn_country_code : 'US'; ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><h3 style="text-align:center;">Add / Edit Phone Numbers for this Location</h3></td>
		</tr>
		<tr class="subdivider condensed labels">
			<td class="telList">
				<div class="telDelete">Delete</div>
				<div class="tel_assoc_cmp">General Company #</div>
				<div class="tel_assoc_lcn">Location #</div>
			</td>
			<td style="text-align:center;">
				Phone Number
			</td>
		</tr>
		<tr class="divider" style="height:100%;">
			<td colspan="2">
				<div class="innerListWrapper">
					<table id="telList" class="innerList condensed">
						<?php
							$telIDs = array();

							if($editMode) {
								while ($row = mysqli_fetch_assoc($telQRY)) {
									$telIDs[] = $row['ID'];
									$tel4cmp = $row['lcn'] == 0 ? 'checked="checked"' : '';
									$tel4lcn = $row['lcn'] == $lcn_ID ? 'checked="checked"' : '';

									?>

									<tr class="telList">
										<td class="telDelete"><input type="checkbox" form="locationator" name="telDelete-<?php echo $row['ID'] ; ?>" value="<?php echo $row['ID'] ; ?>" /></td>
										<td class="tel_assoc_cmp"><input type="radio" form="locationator" name="tel4cmpORlcn-<?php echo $row['ID'] ; ?>" value="cmp" <?php echo $tel4cmp ; ?> /></td>
										<td class="tel_assoc_lcn"><input type="radio" form="locationator" name="tel4cmpORlcn-<?php echo$row['ID'] ; ?>" value="lcn" <?php echo $tel4lcn ; ?> /></td>
										<td class="tel_number">
											<input type="text" form="locationator" name="tel_number-<?php echo$row['ID'] ; ?>" value="<?php echo $row['tel_number'] ; ?>" placeholder="Phone Number Required" />
											<input type="text" form="locationator" name="tel_is_new-<?php echo$row['ID'] ; ?>" value="0" style="display:none;" />
										</td>
									</tr>

									<?php
								}

								?>
									<tr class="tel_addNew">
										<td colspan="4">
											<button id="tel_addNew">Add New Telephone Number</buttom>
										</td>
									</tr>
								<?php
							}
						?>
					</table>
				</div>
			</td>
		</tr>
		<tr style="height:auto;">
			<td colspan="2" style="text-align:center;">
				<input type="text" id="next_tel_ID" value="<?php echo $next_tel_ID; ?>" style="display:none;">

				<form id="locationator"  method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="text" form="locationator" name="lcn_ID" value="<?php echo $lcn_ID ? $lcn_ID : ''; ?>" style="display:none;">
					<input type="text" form="locationator" name="cmp_ID" value="<?php echo $cmp_ID ? $cmp_ID : ''; ?>" style="display:none;">
					<input type="text" form="locationator" id="tel_ID_list" name="tel_ID_list" value='<?php echo implode('-',$telIDs) ; ?>' style="display:none;">
					<input type="submit" form="locationator" class="<?php echo $cmp_ID ? '' : 'disabled'; ?>" name="lcnAddEdit" value="<?php echo $editMode ? 'Update' : 'Add' ; ?> Location" style="display:inline-block;" <?php echo $cmp_ID ? '' : 'disabled'; ?> />
					<?php if($editMode) : ?>
						<input type="submit" form="locationator" class="<?php echo $cmp_ID ? '' : 'disabled'; ?>" name="lcnDelete" value="Delete Location" style="display:inline-block;" <?php echo $cmp_ID ? '' : 'disabled'; ?> />
					<?php endif; ?>
				</form>
			</td>
		</tr>
	</table>

	<table class="contentTable right">
		<tr class="divider">
			<td>
				<form id="lcnSelect"  method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="text" form="lcnSelect" name="cmp_ID" value="<?php echo $cmp_ID ? $cmp_ID : ''; ?>" style="display:none;">
					<input id="lcnSelectSubmit" type="submit" class="<?php echo $cmp_ID ? '' : 'disabled'; ?>" name="lcn_select" value="<- View / Edit Selected Location" style="font-size:8px;float:left;" <?php echo $cmp_ID ? '' : 'disabled'; ?> />
				</form>
			</td>
			<td><h3 style="text-align:center;<?php if($cmp_name) {echo strlen($cmp_name) > 14 ? 'font-size:12px;' : '';} ?>"><?php echo $cmp_name ? 'Locations for ' . $cmp_name : 'No Company Selected'; ?></h3></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="innerListWrapper">
					<table class="innerList condensed">
					<?php
						if($cmp_ID) {
							if(mysqli_num_rows($lcnQRY) == 0) {
								echo '<tr><td colspan="2">No Locations for Selected Company</td></tr>';
							}
							else {
								while ($row = mysqli_fetch_assoc($lcnQRY)) {
									$checked = ($row['ID'] == $lcn_ID) ? "checked" : "";

									echo '<tr class="' . $checked . '">';
									echo '<td style="width:25px;"><input type="radio" form="lcnSelect" value="' . $row['ID'] . '" name="lcn_ID" ' . $checked . ' /></td>';
									echo '<td style="width:auto;">' . $row['lcn_name'] . '</td>';
									echo '</tr>';
								}
							}
						}
					?>
					</table>
				</div>
			</td>
		</tr>
	</table>

<?php include 'footer.php'; ?>