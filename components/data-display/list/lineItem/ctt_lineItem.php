<div class='lineitem full' contactid="<?php echo $row['ID']; ?>">
	<div class="lineitem-content">
		<div class="selector centered-content">
			<input type='checkbox'>
		</div>
		<div class="QA_Wrapper centered-content">
			<div class="actionClose" style="display:none;">x</div>
			<div class="actionSelect" style="display:block;">+
				<div class="actionList">
					<a class="actionItem" href="ctt_details.php?ID=<?php echo $row['ID']; ?>">View Contact Details</a>
					<a class="actionItem" href="contactData.php?ID=<?php echo $row['ID']; ?>">Inspect Data Health</a>
					<a id="QA-newPhoneEvent" class="actionItem">New Phone Event</a>
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
			<img src='<?php echo $row['ctt_img_URL']; ?>' />
		</div>
		<div class="infoColumn">
			<div class="name">
				<?php echo $row['ctt_f_name'] . " " . $row['ctt_l_name']; ?>
			</div>
			<div class="company indented">
				<?php 
					$vars = array('cmp_ID' => $row['cmp'], 'ctt_raw_cmp' => $row['ctt_raw_cmp']);
					echo getBestData('ctt',$row['ID'],'cmp',$vars);
				?>
			</div>
			<div class="location indented">
				<?php 
					$vars = array('lcn_ID' => $row['lcn'], 'ctt_clean_lcn' => $row['ctt_clean_lcn']);
					echo getBestData('ctt',$row['ID'],'lcn',$vars);
				?>
			</div>
		</div>
		<div class="infoColumn">
			<div class="">
			</div>
			<div class="role">
				<?php echo $row['ctt_role']; ?>
			</div>
			<div class="title">
				<?php echo $row['ctt_title']; ?>
			</div>
		</div>		
		<div class="infoColumn small">
			<div class="">
			</div>
			<div class="eml">
				email
			</div>
			<div class="tel">
				<?php 
					$vars = array('lcn_ID' => $row['lcn']);
					echo getBestData('ctt',$row['ID'],'tel',$vars);
				?>
			</div>
		</div>		
		<div class="icons">
			<?php if ( $row['ctt_linkedin'] != 0 ) : ?>
				<a href="http://www.linkedin.com/profile/view?id=<?php echo $row['ctt_linkedin'] ?>" target="_blank"><img src='img/linkedin.png' /></a>
			<?php else : ?>
				<img src='img/linkedin_none.png' />
			<?php endif; ?>
			
			<?php if ( $row['ctt_facebook'] != "" ) : ?>
				<a href="http://www.facebook.com/<?php echo $row['facebook'] ?>" target="_blank"><img src='img/facebook.png' /></a>
			<?php else : ?>
				<img src='img/facebook_none.png' />
			<?php endif; ?>
			
			<?php if ( $row['ctt_twitter'] != "" ) : ?>
				<a href="http://www.twitter.com/<?php echo $row['twitter'] ?>" target="_blank"><img src='img/twitter.png' /></a>
			<?php else : ?>
				<img src='img/twitter_none.png' />
			<?php endif; ?>
		</div>	
	</div>
	<div class="quickAction" style="display:none;">
	</div>
</div>