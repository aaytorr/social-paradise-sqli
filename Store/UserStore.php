<?php
include_once ($_SERVER['DOCUMENT_ROOT'] . "/Header.php");
$Setting = array("PerPage" => 18);
$Page = isset($_GET['Page']) ? $_GET['Page'] : 1;
if ($Page < 1) {
	$Page = 1;
}
if (!is_numeric($Page)) {
	$Page = 1;
}
$Minimum = ($Page - 1) * $Setting["PerPage"];
// Fetch total number of rows
$getall = mysqli_query($connection, "SELECT * FROM UserStore");
$all = mysqli_num_rows($getall);
// Fetch all users with pagination
$allusers = mysqli_query($connection, "SELECT * FROM UserStore ORDER BY ID DESC");
$num = mysqli_num_rows($allusers);
$i = 0;
$Num = ($Page + 8);
$a = 1;
$Log = 0;
?>

<?php
if ($User) {
	if ($myU->Premium == 0) {
		echo "
		<div style='top:300;left:50px;position:fixed;'>
		<a href='../upgrades.php' border='0'><img src='../Images/upgrade_promo.png' width='125' border='0'></a>
		</div>
		";
	}
}
?>

<form action='' method='POST'>
	<table cellspacing='0' cellpadding='0' width='100%'>
		<tr>
			<td width='50%'>
				<div id='LargeText'>
					User Store
				</div>
			</td>
			<td>
				<table cellspacing='0' cellpadding='0'>
					<tr>
						<td>
							<input type='text' name='q' style='width:200px;' value="<?php echo isset($_POST['q']) ? $_POST['q'] : ''; ?>">
						</td>
						<td>
							<input type='submit' name='s' value='Search'>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table cellspacing='0' cellpadding='0' width='100%'>
		<tr>
			<td valign='top'>
				<table width='98%'>
					<tr>
						<td width='40%'>
							<?php
if (isset($myU->Rank) && $myU->Rank >= 0) {
	echo "
									<a href='UserUpload.php'>Upload Item</a>
								<br />
								";
}
echo "
								<table>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Eyes' style='font-weight:bold;color:#777;'>Eyes</a> 
										</td>
									</tr>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Mouths' style='font-weight:bold;color:#777;'>Mouths</a>
										</td>
									</tr>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Hair' style='font-weight:bold;color:#777;'>Hair</a>
										</td>
									</tr>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Pants' style='font-weight:bold;color:#777;'>Pants</a>
										</td>
									</tr>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Shirts' style='font-weight:bold;color:#777;'>Shirts</a>
										</td>
									</tr>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Hats' style='font-weight:bold;color:#777;'>Hats</a>
										</td>
									</tr>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Shoes' style='font-weight:bold;color:#777;'>Shoes</a>
										</td>
									</tr>
									<tr>
										<td>
											<a href='/Store/../UserShop/?Sort=Accessories' style='font-weight:bold;color:#777;'>Accessories</a>
										</td>
									</tr>
								</table>
						</td>
						<td>
		
						</td>
					</tr>
				</table>
			</td>
			<td align='center'>";
echo "<center><table><tr>";
$q = isset($_POST['q']) ? $_POST['q'] : '';
$s = isset($_POST['s']) ? $_POST['s'] : '';
$Sort = isset($_GET['Sort']) ? $_GET['Sort'] : '';
if (!$Sort) {
	if (!$s) {
		// Fetch items without search and sorting
		$getItems = mysqli_prepare($connection, "SELECT * FROM UserStore WHERE Active='1' AND itemDeleted='0' ORDER BY ID DESC LIMIT ?, ?");
		mysqli_stmt_bind_param($getItems, "ii", $Minimum, $Setting["PerPage"]);
		mysqli_stmt_execute($getItems);
		$result = mysqli_stmt_get_result($getItems);
	} elseif ($s) {
		// Fetch items with search query
		$getItems = mysqli_prepare($connection, "SELECT * FROM UserStore WHERE Name LIKE ? AND Active='1' AND itemDeleted='0' ORDER BY ID DESC");
		$q = "%$q%";
		mysqli_stmt_bind_param($getItems, "s", $q);
		mysqli_stmt_execute($getItems);
		$result = mysqli_stmt_get_result($getItems);
	}
} else {
	// Fetch items with sorting
	$getItems = mysqli_prepare($connection, "SELECT * FROM UserStore WHERE Active='1' AND itemDeleted='0' AND Type=? ORDER BY ID DESC LIMIT ?, ?");
	mysqli_stmt_bind_param($getItems, "sii", $Sort, $Minimum, $Setting["PerPage"]);
	mysqli_stmt_execute($getItems);
	$result = mysqli_stmt_get_result($getItems);
}
$counter = 0;
while ($gI = mysqli_fetch_object($result)) {
	$counter++;
	$getCreator = mysqli_query($connection, "SELECT * FROM Users WHERE ID='" . $gI->CreatorID . "'");
	$gC = mysqli_fetch_object($getCreator);
	echo "
						<td style='font-size:11px;' align='left' width='100' valign='top'><a href='../Store/UserItem.php?ID=" . $gI->ID . "' border='0' style='color:black;'>
							<div style='border:1px solid #ccc;border-radius:5px;padding:5px;'>
								<img src='/Store/Dir/" . $gI->File . "' width='100' height='200'>
							</div>
							<b>" . $gI->Name . "</b></a>
							<br />
							<font style='font-size:10px;'>Creator: <a href='/user.php?ID=" . $gC->ID . "'>" . $gC->Username . "</a>
							<br />
							<font color='green'><b>Bux: " . $gI->Price . "</b></font>
						</td>
					";
	if ($counter >= 6) {
		echo "</tr><tr>";
		$counter = 0;
	}
}
echo "</tr></table></td></tr></table><center>";
$amount = ceil($num / $Setting["PerPage"]);
if ($Page > 1) {
	echo '<a href="../UserShop/?Page=' . ($Page - 1) . '">Prev</a> - ';
}
echo '' . $Page . '/' . (ceil($num / $Setting["PerPage"]));
if ($Page < ($amount)) {
	echo ' - <a href="../UserShop/?Page=' . ($Page + 1) . '">Next</a>';
}
include_once ($_SERVER['DOCUMENT_ROOT'] . "/Footer.php");
?>