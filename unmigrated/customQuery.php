<?php include $_SERVER['DOCUMENT_ROOT'] . '/include/header.php'; ?>
<div id="content">
	<?php
    if(isset($_REQUEST['quickQuery'])) 
	{
		if (db_connection()) {			
			$query = mysqli_query($db_conn,$_REQUEST['quickQueryText']);
			
			display_table($query);
			
			db_connection("CLOSE");
		}
	}
	?>
</div>
<?php include ('include/footer.php'); ?>