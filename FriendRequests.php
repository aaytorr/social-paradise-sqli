<?php
include_once "Header.php";

echo <<<HTML
<table width='98%'>
	<tr>
		<td>
			<div id='LargeText'>
				Friend Requests ($FriendsPending)
			</div>
			<div id='' style='text-align:left;'>
				<table>
					<tr>
HTML;

$getFriendR = mysqli_query($connection, "SELECT * FROM FRs WHERE Active <> 1");

while ($gF = mysqli_fetch_object($getFriendR)) {
	$senderID = $gF->SenderID;

	echo "<td width='125' align='center'>";
	echo "<img src='../../Avatar.php?ID=$senderID'>";

	$getSender = mysqli_query($connection, "SELECT * FROM Users WHERE ID=$senderID");
	$gS = mysqli_fetch_object($getSender);

	echo "<br />";
	echo "
		{$gS->Username}
		<br />
		<br />
		<a href='?Action=Accept&ID=$senderID'>Accept</a> - <a href='?Action=Deny&ID=$senderID'>Deny</a>
	";

	echo "</td>";
}

$Action = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Action'] ?? '')));
$ID = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['ID'] ?? '')));

if ($Action == "Accept" && $ID) {
	$getRequest = mysqli_query($connection, "SELECT * FROM FRs WHERE ReceiveID={$myU->ID} AND SenderID=$ID");
	$exist = mysqli_num_rows($getRequest);
	$gR = mysqli_fetch_object($getRequest);

	if ($exist == 1) {
		if ($gR->Active == "0") {
			// Accept
			mysqli_query($connection, "UPDATE FRs SET Active='1' WHERE ReceiveID={$myU->ID} AND SenderID=$ID");
			mysqli_query($connection, "INSERT INTO FRs (SenderID, ReceiveID, Active) VALUES ({$myU->ID}, {$gR->SenderID}, '1')");

			// PM
			mysqli_query($connection, "INSERT INTO PMs (SenderID, ReceiveID, Title, Body, time) VALUES(1, {$gR->SenderID}, 'Friend Request Accepted', '{$myU->Username}', '" . time() . "')");

			// Redirect
			header("Location: ../../My/FRs/");
		} else {
			echo "You are already friends.";
		}
	} else {
		echo "error";
	}
}

if ($Action == "Deny" && $ID) {
	$getRequest = mysqli_query($connection, "SELECT * FROM FRs WHERE ReceiveID={$myU->ID} AND SenderID=$ID");
	$exist = mysqli_num_rows($getRequest);
	$gR = mysqli_fetch_object($getRequest);

	if ($exist == 1) {
		if ($gR->Active == "0") {
			// Delete
			mysqli_query($connection, "DELETE FROM FRs WHERE ReceiveID={$myU->ID} AND SenderID=$ID");

			// PM
			mysqli_query($connection, "INSERT INTO PMs (SenderID, ReceiveID, Title, Body, time) VALUES(1, {$gR->SenderID}, 'Friend Request Declined', '{$myU->Username}', '" . time() . "')");

			// Redirect
			header("Location: ../../My/FRs/");
		} else {
			echo "You are already friends.";
		}
	} else {
		echo "error";
	}
}

echo <<<HTML
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
HTML;

include_once "Footer.php";
?>