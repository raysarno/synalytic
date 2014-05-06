<div class='lineitem'>
	
	<div class='social'>

			<a class='view' href='viewgroup.php?id=<?php echo $row['ID'];?>'>VIEW</a>


			<a class='edit'>EDIT</a>

			<a class='delete'>DELETE</a>
	</div>
	<div class='name-company'>
		<div class='name'><?php echo $row['grp_name']; ?></div>
	</div>
	
	<!-- add more rows from result set here.. see frm V1-->


	<div class='title-role'>
		<div><?php echo $row['grp_type']; ?></div>
	</div>

	<div class='title-role'>
	<div><?php echo $row['grp_approx_count']; ?></div>
	</div>
	
	

</div>