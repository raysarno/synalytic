<?php
	session_start();
	require 'include/globals.php';

	if(!isset($_SESSION['username']) && ($pageType != 'login')) {
		header ( 'Location: index.php' );
	}
	else if(isset($_SESSION['username'])) {
		require ABSPATH . 'include/db.php';
		require ABSPATH . 'include/functions.php';
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<link rel="stylesheet" href="<?php echo CSS_DIR . 'style.css'?>" />

		<title><?php echo $pageTitle; ?> - CRM System</title>

			<link rel="stylesheet" href="<?php echo CSS_DIR . 'quickAction.css' ?>" />
			<!--link rel="stylesheet" href="<?php //echo CSS_DIR . 'lineItem/' . getFileName() . '_lineItem.css' ?>" /-->
			<link rel="stylesheet" href="<?php echo CSS_DIR . 'lineitem.css' ?>" />
			<script type="text/javascript" src="<?php echo JS_DIR . 'main.js'?>"></script>

			<?php //INCLUDE PAGE SPECIFIC JAVASCRIPT IF EXISTS
		        $jsFiles = scandir(JS_DIR);
		        if(array_search(getFileName() . '.js',$jsFiles) != FALSE) {
		        	echo '<script type="text/javascript" src="' . JS_DIR . getFileName() . '.js"></script>';
		        }
	        ?>
	</head>
	<body>
			<header>
            	<div id="titleBar">
                    <h1>9K9 CRM System</h1>
                    
                    <nav>
                        <ul>
                            <li><a href="home.php">Home</a></li>
                            <li><a href="contacts.php">Contacts</a></li>
                            <li><a href="companies.php">Companies</a></li>
                            <li><a href="groups.php">Groups</a></li>
                            <!--li><a href="events.php">Events</a></li-->
                            <li><a href="#">Tools</a>
                            	<ul>
                            		<li><a href="locationator.php">Locationator</a></li>
                            		<li><a href="dbadmin.php">DB Admin</a></li>
                            	</ul>
                            </li>
                           
                            <li id="signOut"><a href="logout.php">SIGN OUT</a></li>   
                        </ul>
                    </nav>
                    </div>
                     
                    
                	
                	<?php //INCLUDE PAGEBAR IF EXISTS
				        $pageBarFiles = scandir(ABSPATH . 'pageBars/');
				        if(array_search(getFileName() . '_pageBar.php',$pageBarFiles) != FALSE) {
				        	$pageBar = true; //USED TO CHANGE CLASS OF #CONTENT

				        	echo '<div id="pageBar">';
				        	include ABSPATH . 'pageBars/' . getFileName() . '_pageBar.php';
				        	echo '</div>';
				        }
				        else {$pageBar = false;}
			        ?>
              
			</header>
			<div id="content" class="<?php echo ($pageBar ? 'with-pageBar' : ''); ?>"> <!-- BEGIN #CONTENT, THIS TAG IS TERMINATED IN footer.php -->
				
            
    