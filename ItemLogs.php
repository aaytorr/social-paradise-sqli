<?php
include_once "Header.php";

if (!$User) {
	header("Location: index.php");
	exit();
}

$view = isset($_GET['view']) ? $_GET['view'] : 'all';
$view = mysqli_real_escape_string($connection, strip_tags(stripslashes($view)));

if (!$view) {
	header("Location: ItemLogs.php?view=all");
	exit();
}

if ($view == "all") {
	echo "<div id='LargeText'>Item Purchases</div><div id=''>";

	$getLogs = mysqli_prepare($connection, "SELECT * FROM PurchaseLog WHERE UserID = ? ORDER BY ID DESC");
	mysqli_stmt_bind_param($getLogs, "i", $myU->ID);
	mysqli_stmt_execute($getLogs);
	$result = mysqli_stmt_get_result($getLogs);

	while ($gL = mysqli_fetch_object($result)) {
		$TypeStore = ($gL->TypeStore == "store") ? "Store" : "User Store";
		
		$Message = "";
		switch ($gL->Action) {
			case "store_purchase":
				$Message = "Purchased";
				break;
			case "sold_limited":
				$Message = "Sold";
				break;
			case "purchased_limited":
				$Message = "Purchased";
				break;
		}

		echo "
		<table style='padding:5px;background:#E1E1E1;border:1px solid #aaa;width:1100px;'>
			<tr>
				<td width='200'>
					".$Message." $gL->Item
				</td>
				<td width='100'>
					<font color='green'>
						".$gL->Price." BUX
					</font>
				</td>
				<td width='300'>
					In $gL->TypeStore
				</td>
				<td>";
		echo ($gL->SellerID != $myU->Username) ? "From $gL->SellerID" : "Automated";
		echo "
				</td>
			</tr>
		</table>
		<br />";
	}
	echo "</div>";
}

include_once "Footer.php";
?>