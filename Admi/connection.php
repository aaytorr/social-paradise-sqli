<?
session_id();
session_start();
ob_start();
date_default_timezone_set('America/Chicago');
$connection = mysql_pconnect("191.101.79.1","u358429928_database","gT/ntIWY[6") or die("Error connecting to database, hang tight, we are working on it.");
mysql_select_db("u358429928_database") or die("Error connecting to database, hang tight, we are working on it.");
	/*Filter */
	

	/* Session */
	$User = $_SESSION['Username'];
	$Password = $_SESSION['Password'];
	 
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $IP=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $IP=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $IP=$_SERVER['REMOTE_ADDR'];
		}
		$IP = $_SERVER['REMOTE_ADDR'];

	if ($User) {
	
		$MyUser = mysql_query("SELECT * FROM Users WHERE Username='".$User."'");
		$myU = mysql_fetch_object($MyUser);
		$UserExist = mysql_num_rows($MyUser);
		
			if ($UserExist == "0") {
			
				session_destroy();
				header("Location: /index.php");
			
			}
			mysql_query("UPDATE Users SET IP='$IP' WHERE Username='$myU->Username'");
			
			$checkifInDatabase = mysql_query("SELECT * FROM UserIPs WHERE IP='$IP' AND UserID='$myU->ID'");
			$cii = mysql_num_rows($checkifInDatabase);
			
				if ($cii == "0") {
				mysql_query("INSERT INTO UserIPs (UserID, IP) VALUES('$myU->ID','$IP')");
				}
				
			if ($Password != $myU->Password) {
			session_destroy();
			}
			
			
	
	}
	
	if (!$User) {
	header("Location: /index.php");
	}
	else {
	if ($myU->PowerAdmin != "true") {
	header("Location: /index.php");
	}
	}