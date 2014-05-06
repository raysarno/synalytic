<div class='lineitem <?php echo $listSize; ?>' companyid="<?php echo $row['ID']; ?>">
	<div class="lineitem-content">
		<div class="selector centered-content">
			<input type='checkbox'>
		</div>
		<div class="QA_Wrapper centered-content">
			<div class="actionClose" style="display:none;">x</div>
			<div class="actionSelect" style="display:block;">+
				<div class="actionList">
					<a class="actionItem">No Actions</a>
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
		<div class="infoColumn small">
			<div class="name">
				<?php echo $row['lcn_name']; ?>
			</div>
			<div class="tel_number indented">
				<?php echo getBestTel('lcn',$row['ID']); ?>
			</div>
			<div class="">
			</div>
		</div>
		<div class="infoColumn">
			<div class="address1">
				<?php echo $row['lcn_address1']; ?>
			</div>
			<div class="address2">
				<?php echo $row['lcn_address2']; ?>
			</div>
			<div class="address3">
				<?php echo $row['lcn_address3']; ?>
			</div>
		</div>
		<div class="infoColumn small">
			<div class="locality">
				<?php echo $row['lcn_locality']; ?>, <?php echo $row['lcn_region_code']; ?>
			</div>
			<div class="country">
				<?php echo $row['lcn_country']; ?>
			</div>
			<div class="postcode">
				<?php echo $row['lcn_postcode']; ?>
			</div>
		</div>			
	</div>
	<div class="quickAction" style="display:none;">
	</div>
</div>