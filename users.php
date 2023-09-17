<?php
include_once "Header.php";
$Setting = array(
	"PerPage" => 12
);
$PerPage = 12;
$Page = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Page'])));
if ($Page < 1) { $Page = 1; }
if (!is_numeric($Page)) { $Page = 1; }
$Minimum = ($Page - 1) * $Setting["PerPage"];
$allusers = mysqli_query($connection, "SELECT * FROM Users");
$num = mysqli_num_rows($allusers);

// search
$q = mysqli_real_escape_string($connection, strip_tags(stripslashes($_POST['q'])));
$s = mysqli_real_escape_string($connection, strip_tags(stripslashes($_POST['s'])));

echo "
<form action='' method='POST'>
	<center><div id='aB' style='width:90%;border-top-left-radius:5px;border-top-right-radius:5px;'>
		<center>
			<table>
				<tr>
					<td style='padding-right:90px;'>
						<b>Users: </b>";
$Users = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM Users"));
echo "" . number_format($Users) . "
					</td>
					<td>
						<b>Search:</b>
					</td>
					<td>
						<input type='text' name='q'> <input type='submit' name='s' value='Search'/>
					</td>
					<td style='padding-left:90px;'>
					<a href='online.php'><b>Online (" . $NumOnline = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM Users WHERE $date < expireTime")) . ")</b></a>
					</td>
				</tr>
			</table>
		</center>
	</div>
</form>
<Br />
";

echo "<center>
<table width='95%'><tr>";
if (!$s) {
	$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE Ban = '0' ORDER BY ID ASC LIMIT ?, ?");
	mysqli_stmt_bind_param($stmt, 'ii', $Minimum, $Setting["PerPage"]);
	mysqli_stmt_execute($stmt);
	$getMembers = mysqli_stmt_get_result($stmt);
} else {
	$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE Username LIKE ? ORDER BY ID");
	$searchQuery = "%" . $q . "%";
	mysqli_stmt_bind_param($stmt, 's', $searchQuery);
	mysqli_stmt_execute($stmt);
	$getMembers = mysqli_stmt_get_result($stmt);
}

$counter = 0;
if (!$s) {
	while ($gM = mysqli_fetch_object($getMembers)) {
		$counter++;
		echo "<td width='125'><center><a href='user.php?ID=$gM->ID' border='0'><img src='Avatar.php?ID=$gM->ID' border='0'><br /><smalltextlink>$gM->Username</smalltextlink></a></td>";
		if ($counter >= 6) {
			echo "</tr><tr>";
			$counter = 0;
		}
	}
} else {
	while ($gM = mysqli_fetch_object($getMembers)) {
		$counter++;
		echo "<td width='125'><center><a href='user.php?ID=$gM->ID' border='0'><img src='Avatar.php?ID=$gM->ID' border='0'><br />$gM->Username</a></td>";
		if ($counter >= 6) {
			echo "</tr><tr>";
			$counter = 0;
		}
	}
}
echo "</tr></table><center>";
$amount = ceil($num / $Setting["PerPage"]);
if ($Page > 1) {
	echo '<a href="users.php?Page=' . ($Page - 1) . '">Prev</a> - ';
}
echo '' . $Page . '/' . (ceil($num / $Setting["PerPage"]));
if ($Page < ($amount)) {
	echo ' - <a href="users.php?Page=' . ($Page + 1) . '">Next</a>';
}

include_once "Footer.php";
?>