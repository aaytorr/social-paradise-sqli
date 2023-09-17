<?php
/* Session */
$User = $_SESSION['Username'];

if ($User) {
	$stmt = $connection->prepare("SELECT * FROM Users WHERE Username = ?");
	$stmt->bind_param("s", $User);
	$stmt->execute();
	$result = $stmt->get_result();
	$myU = $result->fetch_object();
}

if (!$myU || $myU->PowerAdmin !== "true") {
	header("Location: /index.php");
	exit();
} elseif ($myU->Username !== "Isaac") {
	if ($myU->Username !== "Isaac" && $myU->Username !== "Ellernate" && $myU->Username !== "Niko") {
		header("Location: /index.php");
		exit();
	}
}
?>

<form action='' method='POST'>
	<table cellspacing='0' cellpadding='0'>
		<tr>
			<td>
				<font id='Text'>Give Item to User</font><br /><br />
				<table>
					<tr>
						<td>
							User ID:
							<input type='text' name='UserID' />
						</td>
					</tr>
					<tr>
						<td>
							Item ID:
							<input type='text' name='ItemID'>
							<input type='submit' name='Submit' />
							<?php
							if ($_SERVER["REQUEST_METHOD"] === "POST") {
								$UserID = $_POST['UserID'];
								$ItemID = $_POST['ItemID'];
								$Submit = $_POST['Submit'];

								if ($Submit) {
									$stmt = $connection->prepare("SELECT * FROM Items WHERE ID = ?");
									$stmt->bind_param("i", $ItemID);
									$stmt->execute();
									$result = $stmt->get_result();
									$gI = $result->fetch_object();

									$stmt = $connection->prepare("SELECT * FROM Users WHERE ID = ?");
									$stmt->bind_param("i", $UserID);
									$stmt->execute();
									$result = $stmt->get_result();
									$gG = $result->fetch_object();

									$code1 = sha1($gI->File);
									$code2 = sha1($myU->ID);

									$stmt = $connection->prepare("INSERT INTO Inventory (UserID, ItemID, File, Type, code1, code2, SerialNum) VALUES (?, ?, ?, ?, ?, ?, ?)");
									$stmt->bind_param("iisssss", $UserID, $ItemID, $gI->File, $gI->Type, $code1, $code2, $Output);
									$stmt->execute();

									$stmt = $connection->prepare("INSERT INTO Logs (UserID, Message, Page) VALUES (?, ?, ?)");
									$logMessage = "gave the item " . $gI->Name . " to " . $gG->Username;
									$stmt->bind_param("iss", $myU->ID, $logMessage, $_SERVER['PHP_SELF']);
									$stmt->execute();

									header("Location: /Admin/?tab=item");
									exit();
								}
							}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>