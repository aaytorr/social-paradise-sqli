<?php
include_once "Header.php";

$ID = isset($_GET['ID']) ? $_GET['ID'] : '';
$Method = isset($_GET['Method']) ? $_GET['Method'] : '';
$Body = isset($_POST['Body']) ? $_POST['Body'] : '';
$SubmitReply = isset($_POST['SubmitReply']) ? $_POST['SubmitReply'] : '';

if (!$User) {
	header("Location: index.php");
	exit;
}

if (!$ID) {
	echo "<b>Please include an ID!</b>";
	exit;
}

$ID = mysqli_real_escape_string($connection, strip_tags(stripslashes($ID)));

$getMessage = mysqli_prepare($connection, "SELECT * FROM PMs WHERE ID = ?");
mysqli_stmt_bind_param($getMessage, "s", $ID);
mysqli_stmt_execute($getMessage);
$getResult = mysqli_stmt_get_result($getMessage);
$gM = mysqli_fetch_object($getResult);

if ($gM->ReceiveID != $myU->ID) {
	echo "<b>Error.</b>";
	exit;
}

if ($gM->LookMessage == "0") {
	$updateMessage = mysqli_prepare($connection, "UPDATE PMs SET LookMessage='1' WHERE ID = ?");
	mysqli_stmt_bind_param($updateMessage, "s", $ID);
	mysqli_stmt_execute($updateMessage);
	header("Location: ViewMessage.php?ID=$ID");
	exit;
}

$getSender = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
mysqli_stmt_bind_param($getSender, "s", $gM->SenderID);
mysqli_stmt_execute($getSender);
$getSenderResult = mysqli_stmt_get_result($getSender);
$gS = mysqli_fetch_object($getSenderResult);

if (!$Method) {
	?>
	<div id='LargeText'>
		<?php echo htmlspecialchars($gM->Title); ?>
	</div>
	<div id=''>
		<table>
			<tr>
				<td width='125' valign='top'><center>
					<a href='user.php?ID=<?php echo $gS->ID; ?>' style='color:black;'>
					<img src='Avatar.php?ID=<?php echo $gS->ID; ?>'>
					<br />
					<b><?php echo htmlspecialchars($gS->Username); ?></b>
					</a>
				</td>
				<td valign='top'>
					<?php echo nl2br(htmlspecialchars($gM->Body)); ?>
					<br />
					<br />
					<a href='ViewMessage.php?ID=<?php echo $ID; ?>&Method=Reply' style='color:blue;font-weight:bold;'>Reply</a> | <a href='ViewMessage.php?ID=<?php echo $ID; ?>&Method=Delete' style='color:blue;font-weight:bold;'>Delete</a>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

if ($Method == "Reply") {
	?>
	<div id='ProfileText'>
		Reply to <?php echo htmlspecialchars($gS->Username); ?>
	</div>
	<center>
	<div id='aB'>
	<form action='' method='POST'>
		<table>
			<tr>
				<td>
					<textarea name='Body' rows='6' cols='40' style='width:500px;'>
					
_________________________________________
Sent by <?php echo htmlspecialchars($gS->Username); ?>
<?php echo htmlspecialchars($gM->Body); ?>
</textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type='submit' name='SubmitReply' value='Send'>
				</td>
			</tr>
		</table>
	</form>
	</div>
	<?php

	if ($SubmitReply) {
		$CleanBody = mysqli_real_escape_string($connection, strip_tags(stripslashes($Body)));
		$CleanBody = filter($CleanBody);
		$insertReply = mysqli_prepare($connection, "INSERT INTO PMs (SenderID, ReceiveID, Title, Body, `time`) VALUES (?, ?, ?, ?, ?)");
		$params = [$myU->ID, $gM->SenderID, "RE: " . $gM->Title, $CleanBody, $now];
		mysqli_stmt_bind_param($insertReply, "sssss", ...$params);
		mysqli_stmt_execute($insertReply);

		header("Location: user.php?ID=".$gS->ID."");
		exit;
	}
} elseif ($Method == "Delete") {
	$deleteMessage = mysqli_prepare($connection, "DELETE FROM PMs WHERE ID = ?");
	mysqli_stmt_bind_param($deleteMessage, "s", $ID);
	mysqli_stmt_execute($deleteMessage);

	header("Location: inbox.php");
	exit;
}

include_once "Footer.php";
?>