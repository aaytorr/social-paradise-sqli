<?php
$connection = mysql_pconnect("191.101.79.1","u358429928_database","gT/ntIWY[6") or die("Error connecting to database, hang tight, we are working on it.");
mysql_select_db("u358429928_database") or die("Error connecting to database, hang tight, we are working on it.");
$getAds = mysql_query("SELECT * FROM Ads WHERE Active='1' ORDER BY RAND() LIMIT 1");

while ($gA = mysql_Fetch_object($getAds)) {

	$now = time();
	
	if ($now > $gA->TimeRun) {
	mysql_query("DELETE FROM Ads WHERE ID='$gA->ID'");
	}

	echo "
	<a href='$gA->Link'>
		<div style='padding-top:1px;'></div>
		<img height='100' width='800' src='$gA->Image'>
		<div style='padding-top:1px;'></div>
	</a>
	";

}
?>
