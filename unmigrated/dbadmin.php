<?php 
	$pageTitle = "Database Administration";
	include 'header.php'; 
?>
<div class="fullWidth">
	<div class="titleBar">
			<h2>CRM Database Administration</h2>
	</div>
	<div class="mainInfo">
		<table class="contentTable centered">
			<tr>
				<td>Total Contacts:</td>
				<td>
					<?php
						db_connection();

						$cttCountSQL = "SELECT COUNT(*) FROM CRM_ctt;";
						$cttCountQRY = db_query($cttCountSQL);
						$cttCountARR = mysqli_fetch_row($cttCountQRY);
						echo $cttCountARR[0];
					?>
				</td>
			</tr>
			<tr>
				<td>Contacts Associated with Companies:</td>
				<td>
					<?php

						$cttCountSQL = "SELECT COUNT(*) FROM CRM_ctt WHERE cmp != 0;";
						$cttCountQRY = db_query($cttCountSQL);
						$cttCountARR = mysqli_fetch_row($cttCountQRY);
						echo $cttCountARR[0];
					?>
				</td>
			</tr>
			<tr>
				<td>Contacts with Company LinkedIn IDs:</td>
				<td>
					<?php

						$cttCountSQL = "SELECT COUNT(*) FROM CRM_ctt WHERE ctt_cmp_linkedin != '';";
						$cttCountQRY = db_query($cttCountSQL);
						$cttCountARR = mysqli_fetch_row($cttCountQRY);
						echo $cttCountARR[0];
					?>
				</td>
			</tr>
			<tr class="divider">
				<td>Contacts Associated with Locations:</td>
				<td>
					<?php

						$cttCountSQL = "SELECT COUNT(*) FROM CRM_ctt WHERE lcn != 0;";
						$cttCountQRY = db_query($cttCountSQL);
						$cttCountARR = mysqli_fetch_row($cttCountQRY);
						echo $cttCountARR[0];
					?>
				</td>
			</tr>
			<tr>
				<td>
					Total Companies:
				</td>
				<td>
					<?php

						$cmpCountSQL = "SELECT COUNT(*) FROM CRM_cmp;";
						$cmpCountQRY = db_query($cmpCountSQL);
						$cmpCountARR = mysqli_fetch_row($cmpCountQRY);
						echo $cmpCountARR[0];
					?>
				</td>
			</tr>
			<tr>
				<td>
					Total Locations:
				</td>
				<td>
					<?php
						db_connection();

						$lcnCountSQL = "SELECT COUNT(*) FROM CRM_lcn;";
						$lcnCountQRY = db_query($lcnCountSQL);
						$lcnCountARR = mysqli_fetch_row($lcnCountQRY);
						echo $lcnCountARR[0];
					?>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="listWrapper">
</div>

<?php include 'footer.php'; ?>