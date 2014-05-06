<?php

$rootDir = 'http://' . $_SERVER['HTTP_HOST'] . '/';

function getFileName() {
	return substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],"/")+1,((strrpos($_SERVER['PHP_SELF'],".")-strrpos($_SERVER['PHP_SELF'],"/")-1)));
}

?>