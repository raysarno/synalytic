<?php
//INITILIALIZE GLOBALS
define('ABSPATH', $_SERVER['DOCUMENT_ROOT'] . '/crm/');

define('JS_DIR', './js/');
define('CSS_DIR', './css/');

define('DB_PREFIX','CRM_');


define('LOG_QUERIES',true);
define('LOG_ERRORS', true);

if(LOG_ERRORS) {
	ini_set("error_log", ABSPATH . "logs/php-error-" . getFileName() . ".log");
	ini_set("log_errors", 1);
	ini_set("display_errors", 0);
	error_reporting(E_ALL & ~E_NOTICE);
}

$_SESSION['lastDoc'] = (isset($_SESSION['curDoc']) ? $_SESSION['curDoc'] : getFileName());
$_SESSION['curDoc'] = getFileName();

function getFileName() {
	return substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],"/")+1,((strrpos($_SERVER['PHP_SELF'],".")-strrpos($_SERVER['PHP_SELF'],"/")-1)));
}

//UNSET FILTERS IF CHANGING PAGES
if ($_SESSION['curDoc'] != $_SESSION['lastDoc']) {
	unset($_SESSION['filters']);
	$_SESSION['filters'] = array();
}

?>