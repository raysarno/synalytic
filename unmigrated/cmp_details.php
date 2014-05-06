<?php 
	$pageTitle = "Company Details";
	include 'header.php'; 
?>

	<div class="fullWidth">
		<div class="titleBar">
			<h2>Company Details - <?php echo $row['cmp_name']; ?></h2>
		</div>
		<div class="details">
			<div class="sidebar">
				<img class="mainPic" src="img/company_default_small.gif" />
				<img src="img/score.png" />
				<div id="createdupdated">
					Created: <?php echo substr($row['created'],0,10); ?> <br>
					Updated: <?php echo substr($row['updated'],0,10); ?>
				</div>
			</div>
			<div class="mainInfo">
				<div class="infoFieldWrapper">
					<label for="cmp_name">Company Name</label>
					<input id="cmp_name" name="cmp_name" form="editInfo" type="text" value="<?php echo $row['cmp_name'] ; ?>" disabled="disabled" />
				</div>
				<div class="infoFieldWrapper">
					<label for="cmp_URL">Company URL - <a href="<?php echo $row['cmp_URL'] ; ?>" target="_blank" >Visit  Site</a></label>
					<input id="cmp_URL" name="cmp_URL" form="editInfo" type="text" value="<?php echo $row['cmp_URL'] ; ?>" disabled="disabled" />
				</div>
				<div class="socialWrapper">
					<table>
						<tr>
							<td>
								<h2>CRM</h2>
							</td>
							<td>
								<?php if ( $row['cmp_linkedin'] != "" && $row['cmp_linkedin'] != 0 ) : ?>
									<div class=''><a href="http://www.linkedin.com/company/<?php echo $row['cmp_linkedin'] ?>" target="_blank"><img src='img/linkedin.png' /></a></div>
								<?php else : ?>
									<div class=''><img src='img/linkedin_none.png' /></div>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( $row['cmp_facebook'] != "" ) : ?>
									<div class=''><a href="http://www.facebook.com/<?php echo $row['cmp_facebook'] ?>" target="_blank"><img src='img/facebook.png' /></a></div>
								<?php else : ?>
									<div class=''><img src='img/facebook_none.png' /></div>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( $row['cmp_twitter'] != "" ) : ?>
									<div class=''><a href="http://www.twitter.com/<?php echo $row['cmp_twitter'] ?>" target="_blank"><img src='img/twitter.png' /></a></div>
								<?php else : ?>
									<div class=''><img src='img/twitter_none.png' /></div>
								<?php endif; ?>
							</td>
						</tr>
						<tr style="font-size:9px;">
							<td>
								Contacts in CRM
							</td>
							<td>
								<?php 
									if ( $row['cmp_linkedin'] != "" && $row['cmp_linkedin'] != 0 ) {echo 'LinkedIn Contacts';}
									else {echo 'No LinkedIn Info';} 
								?>
							</td>
							<td>
								<?php 
									if (  $row['cmp_facebook'] != "" ) {echo 'Facebook Likes';}
									else {echo 'No Facebook Info';} 
								?>
							</td>
							<td>
								<?php 
									if (  $row['cmp_facebook'] != "" ) {echo 'Twitter Followers';}
									else {echo 'No Twitter Info';} 
								?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $cmp_COUNT_ctt ; ?>
							</td>
							<td>
								<?php 
									if ( $row['cmp_linkedin'] != "" && $row['cmp_linkedin'] != 0 ) {echo $row['cmp_linkedin_contact_count'];}
									else {echo '-';} 
								?>
							</td>
							<td>
								-
							</td>
							<td>
								-
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="mainInfo">
				<div class="infoFieldWrapper double">
					<label for="cmp_description">Company Description</label>
					<textarea id="cmp_description" name="cmp_description" form="editInfo" placeholder="No Company Description" disabled="disabled"><?php if ( $row['cmp_description'] ) {echo $row['cmp_description'];}?></textarea>
				</div>
				<div class="infoFieldWrapper double">
					<button id="editInfoButton" style="width:150px;">Edit Info</button>

					<form id="editInfo" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?ID=' . $row['ID']; ?>" >
						<input type="text" id="editInfo_cmp_ID" name="cmp_ID" value="<?php echo $row['ID'] ; ?>" class="hidden" />
						<input type="submit" id="saveInfoButton" name="saveInfo" value="Save Changes" class="hidden buttonStyle" style="width:150px;" />
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="tabList">
		<div class="tab selected" id="ctt_tab">Contacts</div>
		<div class="tab" id="lcn_tab">Offices</div>
		<div class="tab" id="tel_tab">Phone Numbers</div>
		<div class="tab" id="evt_tab">Events</div>
		<div class="tab" id="grp_tab">Groups</div>
		<div class="tab" id="cat_tab">Categories / Tags</div>
	</div>
	<div class="listWrapper" id="ctt_listWrapper">
		<script>loadList({listType: 'ctt', filters : [['cmp','<?php echo $row['ID']; ?>']]}, '#ctt_listWrapper');</script>
	</div>
	<div class="listWrapper hidden" id="lcn_listWrapper">
		<script>loadList({listType: 'lcn', filters : [['cmp','<?php echo $row['ID']; ?>']]}, '#lcn_listWrapper');</script>
	</div>
	<div class="listWrapper hidden" id="tel_listWrapper">
	</div>
	<div class="listWrapper hidden" id="evt_listWrapper">
	</div>
	<div class="listWrapper hidden" id="grp_listWrapper">
	</div>
	<div class="listWrapper hidden" id="cat_listWrapper">
	</div>
<?php include 'footer.php'; ?>