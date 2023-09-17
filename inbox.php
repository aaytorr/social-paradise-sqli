<?php
include_once "Header.php";

$Setting = [
	"PerPage" => 15
];

$Page = isset($_GET['Page']) ? (int)$_GET['Page'] : 1;
$Page = max(1, $Page);
$Minimum = ($Page - 1) * $Setting["PerPage"];

// Query
$query = mysqli_prepare($connection, "SELECT COUNT(*) FROM PMs WHERE ReceiveID = ?");
mysqli_stmt_bind_param($query, "i", $myU->ID);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $numRows);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

if (!$User) {
	header("Location: index.php");
	exit;
}

echo "<div id='LargeText'>My PMs ($PMs)</div><center>";

$amount = ceil($numRows / $Setting["PerPage"]);
if ($Page > 1) {
	echo "<a href='inbox.php?Page=" . ($Page - 1) . "'>Prev</a> - ";
}
echo "$Page/$amount";
if ($Page < $amount) {
	echo " - <a href='inbox.php?Page=" . ($Page + 1) . "'>Next</a>";
}
echo "</center><table cellspacing='0' cellpadding='0'><tr><td style='width:50px;'><b>Action</b></td><td style='width:200px;'><b>Sender</b></td><td style='width:600px;'><b>Title</b></td><td><b>Date Sent</b></td></tr></table>";

echo "<div style='border-bottom:1px solid #ddd;width:99%;'></div>";

// While loop
$getPMs1 = mysqli_prepare($connection, "SELECT * FROM PMs WHERE ReceiveID = ? ORDER BY ID DESC LIMIT ?, ?");
mysqli_stmt_bind_param($getPMs1, "iii", $myU->ID, $Minimum, $Setting["PerPage"]);
mysqli_stmt_execute($getPMs1);
$result = mysqli_stmt_get_result($getPMs1);

while ($gP = mysqli_fetch_object($result)) {
	echo "<table cellspacing='0' cellpadding='0' id='aBForum' style='border-top:0;font-size:10pt;padding:10px;' width='99%'><tr><td style='width:50px;'><input type='checkbox' name='Test' value='$gP->ID'></td><td style='width:200px;'>";
	$getSender = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
	mysqli_stmt_bind_param($getSender, "i", $gP->SenderID);
	mysqli_stmt_execute($getSender);
	$senderResult = mysqli_stmt_get_result($getSender);
	$gS = mysqli_fetch_object($senderResult);
	echo "<a href='../user.php?ID=$gS->ID'>$gS->Username</a></td><td style='width:600px;'>";
	$Title = "<a href='../ViewMessage.php?ID=$gP->ID' style='" . ($gP->LookMessage == "0" ? "" : "font-weight:normal;") . "'>$gP->Title</a>";
	echo "$Title</td><td>";
	$Display1 = date("F j, Y g:iA", $gP->time);
	echo "$Display1</td></tr></table>";
}

echo "<center>";
if ($Page > 1) {
	echo "<a href='inbox.php?Page=" . ($Page - 1) . "'>Prev</a> - ";
}
echo "$Page/$amount";
if ($Page < $amount) {
	echo " - <a href='inbox.php?Page=" . ($Page + 1) . "'>Next</a>";
}
echo "</center>";

include_once "Footer.php";
?>