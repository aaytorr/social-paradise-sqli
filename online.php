<?php
include_once "Header.php";

$Setting = array(
	"PerPage" => 16
);

$PerPage = 12;
$Page = isset($_GET['Page']) ? (int)$_GET['Page'] : 1;
$Page = max(1, $Page);
$Minimum = ($Page - 1) * $Setting["PerPage"];

// Calculate the current time
$now = time();
$currentDateTime = date('Y-m-d H:i:s', $now);

// Query
$query = "SELECT * FROM Users WHERE expireTime > ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $currentDateTime);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);
$Num = ($Page + 8);

$query = "SELECT * FROM Users WHERE expireTime > ? LIMIT ?, ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "sii", $currentDateTime, $Minimum, $Setting["PerPage"]);
mysqli_stmt_execute($stmt);
$OnlineNow = mysqli_stmt_get_result($stmt);
$NumOnline = mysqli_num_rows($OnlineNow);

echo "
<div id='SeeWrap'>
	<div id='LargeText'>
		Online ($num)
	</div>
	<table>
		<tr>
";
$counter = 0;

while ($O = mysqli_fetch_object($OnlineNow)) {
	$counter++;
	echo "
	<td width='125'>
		<center>
		<a href='user.php?ID=$O->ID' style='border:0;' title='".htmlspecialchars($O->Username, ENT_QUOTES, 'UTF-8')."'>
		<img src='../Avatar.php?ID=$O->ID'  style='border:0;' title='".htmlspecialchars($O->Username, ENT_QUOTES, 'UTF-8')."'>
		<br />
		<smalltextlink>".htmlspecialchars($O->Username, ENT_QUOTES, 'UTF-8')."</smalltextlink>
		</a>
	</td>
	";
	if ($counter >= 8) {
		echo "</tr><tr>";
		$counter = 0;
	}
}

echo "
	</tr>
	</table>
	<center>
";

$amount = ceil($num / $Setting["PerPage"]);
if ($Page > 1) {
	echo '<a href="online.php?Page='.($Page - 1).'">Prev</a> - ';
}
echo 'Page '.$Page.' out of '.(ceil($num / $Setting["PerPage"]));
if ($Page < $amount) {
	echo ' - <a href="online.php?Page='.($Page + 1).'">Next</a>';
}

echo "
</div>
";

include_once "Footer.php";
?>
