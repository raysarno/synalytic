<?php
/* 	Synalytic
*	Version 0.1.0
*	Author: Ray Sarno
*	Copyright 2014 Ray Sarno. 
*	Terms of use: MIT License
*
*	https://github.com/raysarno/synalytic
*/

  /*/ 	config.php 						 							  /*/
 /*/	Synalytic initialization 									 /*/
/*/		Essential vars and configurable options found here.			/*/

session_start();

/*/		INITILIALIZE GLOBALS	/*/

// Directory constants
define('ABSPATH', $_SERVER['DOCUMENT_ROOT'] . '/' );

define('DIR_CORE', ABSPATH . 'core/');
define('DIR_LOG', ABSPATH . 'log/');
define('DIR_CSS', DIR_CORE . 'css/');
define('DIR_JS', DIR_CORE . 'js/');


// Database variables
$db_conn    = NULL;									//GLOBAL REFERENCE TO THE DATABASE CONNECTION

$db_host	= "";									//ENTER YOUR DATABASE INFORMATION HERE!
$db_user	= "";
$db_pass	= "";
$db_name    = "";

define('DB_PREFIX','SYN_');							//ENTER YOUR DB TABLE PREFIX HERE


/*/		CORE CONFIGURATION		/*/

ini_set("auto_detect_line_endings", true);			// Critical option for PHPExcel data import to work 


/*/		CONFIGURABLE OPTIONS	/*/

// Debug Options
define('LOG_QUERIES', false);						// Queries logged in db_query() function (all queries funneled through db_query())
define('LOG_ERRORS', false);

if(LOG_ERRORS) {
	ini_set("error_log", DIR_LOG . "logs/error-" . getFileName() . ".log"); 
	ini_set("log_errors", 1);
	ini_set("display_errors", 0);
	error_reporting(E_ALL & ~E_NOTICE);
}

function getFileName() {
	return substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],"/")+1,((strrpos($_SERVER['PHP_SELF'],".")-strrpos($_SERVER['PHP_SELF'],"/")-1)));
}

?>