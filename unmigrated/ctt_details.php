<?php 
	$pageTitle = "Contact Details";
	include 'header.php'; 
?>

	<div class="fullWidth">
		<div class="titleBar">
			<h2>Contact Details - <?php echo $row['ctt_f_name'] . " " . $row['ctt_l_name']; ?></h2>
		</div>
		<div class="details">
			<div class="sidebar">
				<img class="mainPic" src="<?php echo $row['ctt_img_URL']; ?>" />
				<img src="img/score.png" />
				<div id="createdupdated">
					Created: <?php echo substr($row['created'],0,10); ?> <br>
					Updated: <?php echo substr($row['updated'],0,10); ?>
				</div>
			</div>
			<div class="mainInfo">
				<div class="infoFieldWrapper">
					<div class="halfWidth">
						<label for="ctt_f_name">First Name</label>
						<input id="ctt_f_name" type="text" value="<?php echo $row['ctt_f_name']; ?>" />
					</div>
					<div class="halfWidth" style="float:right">
						<label for="ctt_l_name">Last Name</label>
						<input id="ctt_l_name" type="text" value="<?php echo $row['ctt_l_name']; ?>" />
					</div>
				</div>
				<div class="infoFieldWrapper">
					<div class="halfWidth">
						<label for="ctt_raw_cmp">Company</label>
						<input id="ctt_raw_cmp" type="text" value="<?php echo $row['ctt_raw_cmp'] ; ?>" />
					</div>
					<div class="halfWidth" style="float:right">
						<label for="ctt_raw_lcn">Location</label>
						<input id="ctt_raw_lcn" type="text" value="<?php echo $row['ctt_raw_lcn'] ; ?>" />
					</div>
				</div>
				<div class="infoFieldWrapper">
					<div class="halfWidth">
						<label for="ctt_title">Title</label>
						<input id="ctt_title" type="text" value="<?php echo $row['ctt_title']; ?>" />
					</div>
					<div class="halfWidth" style="float:right">
						<label for="ctt_role">Role</label>
						<input id="ctt_role" type="text" value="<?php echo $row['ctt_role']; ?>" />
					</div>
				</div>
				<div class="infoFieldWrapper">
					<div class="halfWidth">
						<label for="ctt_best_phone">Primary Phone Number</label>
						<input id="ctt_best_phone" type="text" value="" />
					</div>
					<div class="halfWidth" style="float:right">
						<label for="ctt_best_email">Primary Email Address</label>
						<input id="ctt_best_email" type="text" value="" />
					</div>
				</div>
			</div>
			<div class="mainInfo">
				<div class="infoFieldWrapper" style="height:80px;">
					<label for="ctt_notes">Contact Notes</label>
					<textarea id="ctt_notes" placeholder="No Contact Notes">
						<?php 
							if ( $row['ctt_notes'] != "" ) {echo $row['ctt_notes'];}
						?>
					</textarea>
				</div>
				<div class="socialWrapper">
					<table>
						<tr>
							<td>
								<h2>CRM</h2>
							</td>
							<td>
								<?php if ( $row['ctt_linkedin'] != "" && $row['ctt_linkedin'] != 0 ) : ?>
									<div class=''><a href="http://www.linkedin.com/profile/view?id=<?php echo $row['ctt_linkedin'] ?>" target="_blank"><img src='img/linkedin.png' /></a></div>
								<?php else : ?>
									<div class=''><img src='img/linkedin_none.png' /></div>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( $row['ctt_facebook'] != "" ) : ?>
									<div class=''><a href="http://www.facebook.com/<?php echo $row['ctt_facebook'] ?>" target="_blank"><img src='img/facebook.png' /></a></div>
								<?php else : ?>
									<div class=''><img src='img/facebook_none.png' /></div>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( $row['ctt_twitter'] != "" ) : ?>
									<div class=''><a href="http://www.twitter.com/<?php echo $row['ctt_twitter'] ?>" target="_blank"><img src='img/twitter.png' /></a></div>
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
									if ( $row['ctt_linkedin'] != "" && $row['cmp_linkedin'] != 0 ) {echo 'LinkedIn Contacts';}
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
		</div>
	</div>

	<div class="listWrapper">
	</div>

<?php include 'footer.php'; ?>