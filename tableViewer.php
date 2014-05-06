<?php include $_SERVER['DOCUMENT_ROOT'] . '/include/header.php'; ?>
<div id="content">
	<?php
    if(isset($_REQUEST['chooseTable'])) {
		display_table(("SELECT * FROM " . $_REQUEST['tables'] . ";"));
	}
	?>
</div>
<?php include ('include/footer.php'); ?>