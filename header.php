<?php
	ini_set("auto_detect_line_endings", true);
	session_start();
	require $_SERVER['DOCUMENT_ROOT'] . '/include/common/globals.php';
	
	if(!isset($_SESSION['user']['ID']) && ($pageType != 'login')) {
		header ( 'Location: index.php' );
	}
	else if(isset($_SESSION['user']['ID'])) {
		require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';
	}

	ini_set("error_log", "./php-error-" . getFileName() . ".log");
	ini_set("log_errors", 1);
	error_reporting(E_ALL);
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Synalytic</title>

		<link href="styles/ametrix-jqui-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet">
		<link rel="stylesheet" href="styles/style.css" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<!-- script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script -->
		
		<!-- script src="/js/jquery-2.0.3.min.js"></script -->
		<script src="/js/jquery-ui-1.10.3.custom.min.js"></script>

        <?php
	        $pageIncludeFiles = scandir($_SERVER['DOCUMENT_ROOT'] . '/content/page-custom/');
	        if(array_search(getFileName() . '_pageIncludes.php',$pageIncludeFiles) != FALSE) {
	        	include $_SERVER['DOCUMENT_ROOT'] . '/content/page-custom/' . getFileName() . '_pageIncludes.php';
	        }
        ?>
	</head>
	<body>
		<header>
        	<div id="titleBar">
                <img src="../images/logos/acento.png">
                <h1>Synalytic</h1>
                
                <div id="topMenu">
                	<a class="logOut" href="logout.php">Log out</a>

                	<?php if(isset($_SESSION['user']['ID'])) : ?>
	                	<div>Account: <?php echo $_SESSION['clientCode']; ?> &nbsp;|</div>
	                	<div>Hello, <?php echo $_SESSION['user']['firstName']; ?> &nbsp;|</div>
	                <?php endif; ?>
            	</div>
                
                <nav>
                	<ul>
                    	<li><a href="index.php">Home</a></li>
                        <li><a href="importSelect.php">Import Data</a></li>
                        <li><a href="dataCleaner.php">Data Cleaner</a></li>
                        <li><a href="reports.php">Reports</a></li>

                        <?php if(isset($_SESSION['user']['ID'])) { ?>
                        <?php if($_SESSION['user']['permissions'] > 100) { ?>
	                        <li><a href="tableViewer.php">Table Viewer</a></li>
	                        <li><a href="customQuery.php">Custom Query</a></li>
	                    <?php }; ?>
	                    <?php }; ?>
                    </ul>
                </nav>
            </div>
            
            <div id="pageBar">
            	<?php
			        $pageBarFiles = scandir($_SERVER['DOCUMENT_ROOT'] . '/content/page-custom/');
			        if(array_search('pageBar_' . getFileName() . '.php',$pageBarFiles) != FALSE) {
			        	include $_SERVER['DOCUMENT_ROOT'] . '/content/page-custom/pageBar_' . getFileName() . '.php';
			        }
		        ?>
			</div>
		</header>