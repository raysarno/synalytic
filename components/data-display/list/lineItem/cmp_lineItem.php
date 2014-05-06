<div class='lineitem <?php echo $listSize; ?>' companyid="<?php echo $row['ID']; ?>">
	<div class="lineitem-content">
		<div class="selector centered-content">
			<input type='checkbox'>
		</div>
		<div class="QA_Wrapper centered-content">
			<div class="actionClose" style="display:none;">x</div>
			<div class="actionSelect" style="display:block;">+
				<div class="actionList">
					<a class="actionItem" href="cmp_details.php?ID=<?php echo $row['ID']; ?>">View Company Details</a>
				</div>
			</div>
		</div>
		<div class="score">
			<div class=''><img src="img/score.png" style="margin:5px;" /></div>
		</div>
		<div class="badges">
			<img src="img/recent.png" style="margin-top:5px;" />
		</div>
		<div class="mainImage">
			<img src='img/company_default_small.gif' />
		</div>
		<div class="infoColumn">
			<div class="name">
				<?php echo $row['cmp_name']; ?>
			</div>
			<div class="">
			</div>
			<div class="URL">
				<a href="<?php echo $row['cmp_URL']; ?>" target="_blank"><?php echo $row['cmp_URL']; ?></a>
			</div>
		</div>
		<div class="infoColumn">
			<div class="role">
				<?php echo $row['cmp_linkedin_contact_count']; ?> LinkedIn Contacts
			</div>
			<div class="">
				-
			</div>
			<div class="location">
				-
			</div>
		</div>		
		<div class="icons">
			<img src='img/tel_none.png' />

			<?php if ( $row['cmp_linkedin'] != 0 ) : ?>
				<a href="http://www.linkedin.com/company/<?php echo $row['cmp_linkedin'] ?>" target="_blank"><img src='img/linkedin.png' /></a>
			<?php else : ?>
				<img src='img/linkedin_none.png' />
			<?php endif; ?>
			
			<?php if ( $row['cmp_facebook'] != "" ) : ?>
				<a href="http://www.facebook.com/<?php echo $row['facebook'] ?>" target="_blank"><img src='img/facebook.png' /></a>
			<?php else : ?>
				<img src='img/facebook_none.png' />
			<?php endif; ?>
			
			<?php if ( $row['cmp_twitter'] != "" ) : ?>
				<a href="http://www.twitter.com/<?php echo $row['twitter'] ?>" target="_blank"><img src='img/twitter.png' /></a>
			<?php else : ?>
				<img src='img/twitter_none.png' />
			<?php endif; ?>
		</div>	
	</div>
	<div class="quickAction" style="display:none;">
	</div>
</div>