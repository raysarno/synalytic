<?php 
	$pageTitle = "Events List";
	include 'header.php';
?>

	<?php 
		display_table('SELECT * FROM events_phone;');

		//LIST PARAMETERS
		/*$listType = 'events_phone';
		$orderBy = 'event_id';
		$pagination = TRUE;

		//include ('include/list/list.php'); */
	?>

<?php include 'footer.php'; ?>