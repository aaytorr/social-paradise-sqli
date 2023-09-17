<?php
include_once "../Header.php";
$Setting = array(
	"PerPage" => 14
);
$PerPage = 12;
$Page = isset($_GET['Page']) ? $_GET['Page'] : 1;
if ($Page < 1) { $Page = 1; }
if (!is_numeric($Page)) { $Page = 1; }
$Minimum = ($Page - 1) * $Setting["PerPage"];
$mysqli = $connection;

$getall = $mysqli->query("SELECT COUNT(*) AS total FROM Users");
$all = $getall->fetch_assoc()['total'];

$allusers = $mysqli->query("SELECT COUNT(*) AS total FROM Users");
$num = $allusers->fetch_assoc()['total'];

$currentDateTime = date("Y-m-d H:i:s");
$query = "SELECT * FROM Users WHERE expireTime > '$currentDateTime'";
$result = mysqli_query($connection, $query);
$NumOnline = mysqli_num_rows($result);

$i = 0;
$Num = ($Page + 8);
$a = 1;
$Log = 0;
$counter = 0;

echo "
<center>
<table width='95%'>
	<tr>
		<td style='padding-left:50px;' width='850' valign='top'>
			<div id='LargeText'>
				Browse Users (" . $all . ")
			</div>
			<div id=''><center>
			<form action='' method='POST'>
				<table cellspacing='0' cellpadding='0'>
					<tr>
						<td>
							<b>Search:</b> <input type='text' name='Q' value='".(isset($_POST['Q']) ? $_POST['Q'] : "")."'> <input type='submit' name='S' value='Search'>
						</td>
						<td style='padding-left:50px;'>
							<a href='../online.php'><b>Online (" . $NumOnline  . ")</b></a>
						</td>
					</tr>
				</table>
			</form>
			</div>
		</td>
	</tr>
</table>
<table>
	<tr>
";

$Q = isset($_POST['Q']) ? $mysqli->real_escape_string(strip_tags(stripslashes($_POST['Q']))) : "";
$S = isset($_POST['S']) ? $mysqli->real_escape_string(strip_tags(stripslashes($_POST['S']))) : "";

if ($S) {
	$stmt = $mysqli->prepare("SELECT * FROM Users WHERE Username LIKE ? ORDER BY ID ASC LIMIT ?, ?");
	$searchValue = "%$Q%";
	$stmt->bind_param("sii", $searchValue, $Minimum, $Setting["PerPage"]);
	$stmt->execute();
	$getMembers = $stmt->get_result();

	while ($gM = $getMembers->fetch_object()) {
		$counter++;
		echo "<td align='center' width='125'><a href='../user.php?ID=".$gM->ID."' border='0'>";
		echo "<img src='../Avatar.php?ID={$gM->ID}'><br />";
		echo "<b><font color='black' style='font-size:8pt;'>{$gM->Username}</font></b>";
		echo "</a></td>";

		if ($counter >= 7) {
			echo "</tr><tr>";
			$counter = 0;
		}
	}
} else {
	$stmt = $mysqli->prepare("SELECT * FROM Users ORDER BY ID ASC LIMIT ?, ?");
	$stmt->bind_param("ii", $Minimum, $Setting["PerPage"]);
	$stmt->execute();
	$getMembers = $stmt->get_result();

	$counter = 0;
	while ($gM = $getMembers->fetch_object()) {
		$counter++;
		echo "<td align='center' width='125'><a href='../user.php?ID={$gM->ID}' border='0'>";
		echo "<img src='../Avatar.php?ID={$gM->ID}'><br />";
		echo "<b><font color='black' style='font-size:8pt;'>{$gM->Username}</font></b>";
		echo "</a></td>";

		if ($counter >= 7) {
			echo "</tr><tr>";
			$counter = 0;
		}
	}
}

echo "</tr></table>";

$amount = ceil($num / $Setting["PerPage"]);
if ($Page > 1) {
	echo '<a href="/Users/?Page='.($Page-1).'">Prev</a> - ';
}
echo ''.$Page.'/'.(ceil($num / $Setting["PerPage"]));
if ($Page < $amount) {
	echo ' - <a href="/Users/?Page='.($Page+1).'">Next</a>';
}

include_once "../Footer.php";
?>