<div class='lineitem'>
	<div class='options'>
		<div class=''><input type='checkbox'></div>
		<div class='viewButton' onclick='loadInfoPage("<?php echo $row['phone_event_id'] ?>");'></div>
	</div>


	<!-- add more rows from result set here.. see frm V1-->
	<div class='name-company'>	
		<div><?php echo $row['phone_number']; ?></div>
		
	</div>

	<div class='title-role'>	
		<div><?php echo $row['event_dateTime']; ?></div>
		
	</div>


	<div class='title-role'>	
		<div><?php echo $row['contact_name']; ?></div>
		
	</div>

	<div class='title-role'>	
<div><?php echo $row['company_name']; ?></div>

		</div>

</div>

		