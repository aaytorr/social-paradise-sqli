<?php
include_once "Header.php";
if ($myU->PowerAdmin == "true"||$myU->PowerMegaModerator == "true") {

	$ID = mysql_real_escape_string(strip_tags(stripslashes($_GET['ID'])));
	
	if (!$ID) {
	
		echo "<b>Please include_once an ID!</b>";
		exit;
	
	}
	$getAlts = mysql_query("SELECT * FROM Users WHERE ID='$ID' ORDER BY ID");
	$gA = mysql_fetch_object($getAlts);
	
	$getAlts = mysql_query("SELECT * FROM Users WHERE IP='$gA->IP'");
	while ($gA = mysql_fetch_object($getAlts)) {
	
		echo "<a href='user.php?ID=$gA->ID'>$gA->Username</a><br />";
	
	}

}