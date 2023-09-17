<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Set it to 0 in production
session_set_cookie_params([
	'lifetime' => 0,
	'path' => '/',
	'domain' => $_SERVER['HTTP_HOST'],
	'secure' => true, // Set this to true if using HTTPS, false otherwise
	'httponly' => true,
	'samesite' => 'Lax',
]);
session_start();
date_default_timezone_set('America/Chicago');

$host = "host";
$username = "username";
$password = "password";
$database = "database";

// Establish database connection
try {
	$connection = mysqli_connect("p:$host", $username, $password, $database);
} catch (Exception $e) {
	http_response_code(503);
	header("Retry-After: 3600");
	include_once("offline.html");
	die();
}

// IP bans
$stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM IPBans WHERE IP=?");
mysqli_stmt_bind_param($stmt, "s", $_SERVER['REMOTE_ADDR']);
mysqli_stmt_execute($stmt);
$getIPBans = mysqli_stmt_get_result($stmt);
$IPBan = mysqli_fetch_row($getIPBans)[0];

if ($IPBan > 0) {
	http_response_code(403);
	include_once "Error/403.shtml";
	die();
}

/* Filter */
function securePost($var) {
	global $connection;
	return mysqli_real_escape_string($connection, strip_tags(stripslashes($var)));
}

function properdie($message) {
	echo($message);
	include_once "Footer.php";
	die();
}

function filter($string) {
	return $string;
}

function secureString($value) {
	global $connection;
	$value = mysqli_real_escape_string($connection, stripslashes(strip_tags($value)));
	return $value;
}

/* Session */
$User = $_SESSION['Username'] ?? null;
$Password = $_SESSION['Password'] ?? null;
$MaintenanceBypass = $_SESSION['MaintenanceBypass'] ?? false;

if (isset($User)) {
	$stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM Users WHERE Username=?");
	mysqli_stmt_bind_param($stmt, "s", $User);
	mysqli_stmt_execute($stmt);
	$UserExist = mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0];

	if ($UserExist == 0) {
		session_destroy();
		header("Location: /index.php");
		exit();
	}

	mysqli_query($connection, "UPDATE Users SET IP='" . mysqli_real_escape_string($connection, $_SERVER['REMOTE_ADDR']) . "' WHERE Username='" . mysqli_real_escape_string($connection, $User) . "'");

	$stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM UserIPs WHERE IP=? AND UserID=(SELECT ID FROM Users WHERE Username=?)");
	mysqli_stmt_bind_param($stmt, "ss", $_SERVER['REMOTE_ADDR'], $User);
	mysqli_stmt_execute($stmt);
	$cii = mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0];

	if ($cii == 0) {
		$stmt = mysqli_prepare($connection, "INSERT INTO UserIPs (UserID, IP) VALUES ((SELECT ID FROM Users WHERE Username=?), ?)");
		mysqli_stmt_bind_param($stmt, "ss", $User, $_SERVER['REMOTE_ADDR']);
		mysqli_stmt_execute($stmt);
	}

	$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE Username=?");
	mysqli_stmt_bind_param($stmt, "s", $User);
	mysqli_stmt_execute($stmt);
	$MyUser = mysqli_stmt_get_result($stmt);
	$myU = mysqli_fetch_object($MyUser);

	if ($Password != $myU->Password) {
		session_destroy();
		header("Location: /index.php");
		exit();
	}
}

// Referrals
$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE SuccessReferrer >= 3");
mysqli_stmt_execute($stmt);
$getReferrals = mysqli_stmt_get_result($stmt);

$stmt = mysqli_prepare($connection, "SELECT * FROM Badges WHERE UserID=? AND Position='Referrer'");
$insertBadge = mysqli_prepare($connection, "INSERT INTO Badges (UserID, Position) VALUES (?, 'Referrer')");

while ($gR = mysqli_fetch_object($getReferrals)) {
	mysqli_stmt_bind_param($stmt, "i", $gR->ID);
	mysqli_stmt_execute($stmt);
	$getBadge = mysqli_stmt_get_result($stmt);
	$Badge = mysqli_num_rows($getBadge);

	if ($Badge == 0) {
		mysqli_stmt_bind_param($insertBadge, "i", $gR->ID);
		mysqli_stmt_execute($insertBadge);
	}
}

// Update user hash
$stmt = mysqli_prepare($connection, "UPDATE Users SET Hash=MD5(CONCAT(Username, Password))");
mysqli_stmt_execute($stmt);

// Set default avatar if not set
$stmt = mysqli_prepare($connection, "UPDATE Users SET Body='Avatar.png' WHERE Body=''");
mysqli_stmt_execute($stmt);

$stmt = mysqli_prepare($connection, "SELECT * FROM Banner");
mysqli_stmt_execute($stmt);
$getBanner = mysqli_stmt_get_result($stmt);
$gB = mysqli_fetch_object($getBanner);

// Get reports count
$R = mysqli_query($connection, "SELECT COUNT(*) FROM Reports");
$NumR = mysqli_fetch_row($R)[0];

// Get pending user store count
$stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM UserStore WHERE active='0'");
mysqli_stmt_execute($stmt);
$getPending = mysqli_stmt_get_result($stmt);
$NumPending = mysqli_fetch_row($getPending)[0];

// Get unread PM count
$NumPMs = 0;
$PMs = 0;

if (isset($myU->ID)) {
	$stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM PMs WHERE ReceiveID=? AND LookMessage='0'");
	mysqli_stmt_bind_param($stmt, "i", $myU->ID);
	mysqli_stmt_execute($stmt);
	$NumPMs = mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0];

	$stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM PMs WHERE ReceiveID=?");
	mysqli_stmt_bind_param($stmt, "i", $myU->ID);
	mysqli_stmt_execute($stmt);
	$getPMs = mysqli_stmt_get_result($stmt);
	$PMs = mysqli_fetch_row($getPMs)[0];
}

$Rep = "Reports";
if ($NumR > 0) {
	$Rep = "Reports ($NumR)";
}

// Check maintenance status
$stmt = mysqli_prepare($connection, "SELECT MaintenanceType FROM Configuration");
mysqli_stmt_execute($stmt);
$Configuration = mysqli_stmt_get_result($stmt);
$Configuration = mysqli_fetch_object($Configuration);
$Maintenance = $Configuration->MaintenanceType;

if ($Maintenance === "Lockdown" && $_SERVER['PHP_SELF'] !== '/Maintenance.php') {
	if ($MaintenanceBypass !== true && (!isset($myU->PowerAdmin) || $myU->PowerAdmin != true)) {
		header("Location: /Maintenance.php");
		die();
	}
}

$now = time();
$date = date("Y-m-d H:i:s");
$timeout = 5;
$xp = 60;
$expires = date('Y-m-d H:i:s', $now + ($timeout * $xp));

if (isset($User)) {
	$stmt = mysqli_prepare($connection, "UPDATE Users SET visitTick=?, expireTime=? WHERE Username=?");
	mysqli_stmt_bind_param($stmt, "iss", $now, $expires, $User);
	mysqli_stmt_execute($stmt);
}

if (isset($myU->Ban) && $myU->Ban == "1" && $_SERVER['PHP_SELF'] !== "/Banned.php") {
	header("Location: ../Banned.php");
	exit();
}

$Bux = "";
if (isset($myU->Bux)) {
	$Bux = $myU->Bux;

	if ($Bux >= 100 && $Bux <= 99999) {
		$Bux = number_format($Bux);
	} elseif ($Bux >= 1000000000) {
		$Bux = "&#8734;";
	} else {
		$suffixes = array('K+', 'M+', 'B+', 'T+');
		$suffixIndex = max(0, floor(log10($Bux) / 3) - 1);
		$BuxShort = substr($Bux, 0, 3);
		$Bux = $BuxShort . $suffixes[$suffixIndex];
	}
}

// Rich badges
$query = "
	SELECT Users.ID
	FROM Users
	LEFT JOIN Badges ON Users.ID = Badges.UserID AND Badges.Position = 'Rich'
	WHERE Users.Bux > 9999 AND Badges.UserID IS NULL
";
$result = mysqli_query($connection, $query);

$insertBadge = mysqli_prepare($connection, "INSERT INTO Badges (UserID, Position) VALUES (?, 'Rich')");

while ($user = mysqli_fetch_object($result)) {
	mysqli_stmt_bind_param($insertBadge, "i", $user->ID);
	mysqli_stmt_execute($insertBadge);
}

$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE Premium='1'");
mysqli_stmt_execute($stmt);
$getPremium = mysqli_stmt_get_result($stmt);

$insertBadge = mysqli_prepare($connection, "INSERT INTO Badges (UserID, Position) VALUES (?, 'Premium')");
$insertPM = mysqli_prepare($connection, "INSERT INTO PMs (SenderID, ReceiveID) VALUES ('1', ?)");

while ($gP = mysqli_fetch_object($getPremium)) {
	$stmt = mysqli_prepare($connection, "SELECT COUNT(*) FROM Badges WHERE UserID=? AND Position='Premium'");
	mysqli_stmt_bind_param($stmt, "i", $gP->ID);
	mysqli_stmt_execute($stmt);
	$checkBadge = mysqli_stmt_get_result($stmt);
	$Badge = mysqli_fetch_row($checkBadge)[0];

	if ($Badge == 0) {
		mysqli_stmt_bind_param($insertBadge, "i", $gP->ID);
		mysqli_stmt_execute($insertBadge);

		mysqli_stmt_bind_param($insertPM, "i", $gP->ID);
		mysqli_stmt_execute($insertPM);
	}

	if ($gP->PremiumExpire != "unlimited" && $now > $gP->PremiumExpire) {
		mysqli_query($connection, "UPDATE Users SET Premium='0' WHERE ID='" . mysqli_real_escape_string($connection, $gP->ID) . "'");
		mysqli_query($connection, "DELETE FROM Badges WHERE UserID='" . mysqli_real_escape_string($connection, $gP->ID) . "' AND Position='Premium'");
		mysqli_query($connection, "INSERT INTO PMs (SenderID, ReceiveID, Title, Body, time) VALUES ('1','" . mysqli_real_escape_string($connection, $gP->ID) . "','Premium Expired','Your premium membership has expired.','" . mysqli_real_escape_string($connection, $now) . "')");
	}
}

if (isset($myU->getBux)) {
	$now = time();
	if ($now > $myU->getBux) {
		$NewBux = $now + 86400;
		$AmountToAdd = isset($myU->Premium) && $myU->Premium == 0 ? 100 : 250;

		$stmt = mysqli_prepare($connection, "UPDATE Users SET Bux=Bux + ? WHERE ID=?");
		mysqli_stmt_bind_param($stmt, "ii", $AmountToAdd, $myU->ID);
		mysqli_stmt_execute($stmt);

		$stmt = mysqli_prepare($connection, "UPDATE Users SET getBux=? WHERE ID=?");
		mysqli_stmt_bind_param($stmt, "ii", $NewBux, $myU->ID);
		mysqli_stmt_execute($stmt);
	}

	$getFriendR = mysqli_prepare($connection, "SELECT COUNT(*) FROM FRs WHERE ReceiveID=? AND Active='0'");
	mysqli_stmt_bind_param($getFriendR, "i", $myU->ID);
	mysqli_stmt_execute($getFriendR);
	$result = mysqli_stmt_get_result($getFriendR);
	$FriendsPending = mysqli_fetch_row($result)[0];
}
?>