<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ob_start();
	
	header("Cache-Control: must-revalidate");

	$offset = 60 * 60 * 60 * 60;
	$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
	header($ExpStr);
	
	require_once('GradientGD.php');
	$image = new gd_gradient_fill($_GET['w'],$_GET['h'],$_GET['d'],$_GET['start'],$_GET['end']);
?>