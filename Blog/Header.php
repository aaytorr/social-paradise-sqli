<?php
session_id();
session_start();
ob_start();
date_default_timezone_set('America/Chicago');
$connection = mysql_pconnect("191.101.79.1","u358429928_database","gT/ntIWY[6") or die("Error connecting to database, hang tight, we are working on it.");
mysql_select_db("u358429928_database") or die("Error connecting to database, hang tight, we are working on it.");
?>
<html>
	<head>
		<title>Blog - Social-Paradise</title>
		<link rel='stylesheet' href='style.css'>
	</head>
	<body>
		<div id='TopMenu'>
			<table><tr><td>
			<img src="../Images/SPNewLogo.png" height="40">
			</td>
			<td>
			<a href='../'>Back to Social-Paradise</a>
			</td></tr></table>
		</div>
		<br />
		<center>
		<div id='MainContainer'>
		