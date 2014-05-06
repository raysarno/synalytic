<?php
	session_start();
	
	$db_loginTest = new mysqli ( 'crm.9k9.com', $_REQUEST['username'], $_REQUEST['password'], 'crm' );
	
	if ( mysqli_connect_errno() ) {
		echo '<div class="error">Login Failed.</div>';
	} else {
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['password'] = $_POST['password'];
		
		mysqli_close ( $db_loginTest );
		
		header ( 'Location: home.php' );
	}
	
?>