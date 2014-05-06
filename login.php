<?php
	session_start();
	require $_SERVER['DOCUMENT_ROOT'] . '/include/common/globals.php';
	require $_SERVER['DOCUMENT_ROOT'] . '/include/common/db.php';

	if(db_connection()) {
		$SQLstr = 'SELECT * FROM AMX_SYS_users WHERE email_address = "' . mysqli_real_escape_string($db_conn, $_POST['email']) . '" AND password = "' . md5(mysqli_real_escape_string($db_conn, $_POST['email']) . '.' . mysqli_real_escape_string($db_conn, $_POST['password'])) . '";';
		//echo $SQLstr; //DEV
		$query = mysqli_query($db_conn,$SQLstr);

		//echo '<br><br><Br>' . mysqli_num_rows($query); //DEV
		if(mysqli_num_rows($query) == 1) {
			session_regenerate_id(TRUE);
			$userInfo = mysqli_fetch_assoc($query);
			//echo '<br><br><Br>' . var_dump($userInfo); //DEV

			$_SESSION['user']['ID'] 			= $userInfo["ID"];
			$_SESSION['user']['account']		= $userInfo["account_id"];
			$_SESSION['user']['permissions']	= $userInfo["permissions"];
			$_SESSION['user']['lastName']		= $userInfo["last_name"];
			$_SESSION['user']['firstName']		= $userInfo["first_name"];
			$_SESSION['user']['email']			= $userInfo["email_address"];

			//echo '<br><br><Br>' . var_dump($_SESSION['user']); //DEV

			$_SESSION['clientCode']				= singleField('AMX_SYS_accounts', 'account_code', $_SESSION['user']['account']);

			//echo '<br><br><Br>' . var_dump($_SESSION['clientCode']); //DEV
		} 
		
		session_write_close();
		db_connection("CLOSE");
		header ( 'Location: index.php' );
	}
	/*
	$db_loginTest = new mysqli ( 'ametrixdb.9k9.com', $_REQUEST['email'], $_REQUEST['password'], 'ametrix' );
	
	if ( mysqli_connect_errno() ) {
		echo '<div class="error">Login Failed.</div>';
	} else {
		$_SESSION['username'] = $_POST['email'];
		$_SESSION['password'] = $_POST['password'];
		
		mysqli_close ( $db_loginTest );
		
		header ( 'Location: index.php' );
	}*/
	
?>