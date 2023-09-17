<?php
// Session
$User = $_SESSION['Username'];

if ($User) {
	$query = "SELECT * FROM Users WHERE Username=?";
	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, 's', $User);
	mysqli_stmt_execute($stmt);
	$MyUser = mysqli_stmt_get_result($stmt);
	$myU = mysqli_fetch_object($MyUser);
}

if (!isset($myU) || $myU->PowerAdmin !== "true") {
	header("Location: /index.php");
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$Position = mysqli_real_escape_string($connection, $_POST['Position']);
	$UserID = mysqli_real_escape_string($connection, $_POST['UserID']);

	$query = "INSERT INTO Badges (UserID, Position) VALUES (?, ?)";
	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, 'ss', $UserID, $Position);
	mysqli_stmt_execute($stmt);

	$powerMap = [
		'Administrator' => 'PowerAdmin',
		'Artist' => 'PowerArtist',
		'Forum Moderator' => 'PowerForumModerator',
		'Image Moderator' => 'PowerImageModerator',
		'Mega Moderator' => 'PowerMegaModerator',
	];

	if (array_key_exists($Position, $powerMap) && $Position !== 'Developer') {
		$Hire = $powerMap[$Position];

		$query = "UPDATE Users SET $Hire='true' WHERE ID=?";
		$stmt = mysqli_prepare($connection, $query);
		mysqli_stmt_bind_param($stmt, 's', $UserID);
		mysqli_stmt_execute($stmt);
	}

	$moderatorPositions = ["Image Moderator", "Forum Moderator", "Mega Moderator"];
	if (in_array($Position, $moderatorPositions)) {
		$query = "SELECT * FROM Inventory WHERE UserID=? AND ItemID='102'";
		$stmt = mysqli_prepare($connection, $query);
		mysqli_stmt_bind_param($stmt, 's', $UserID);
		mysqli_stmt_execute($stmt);
		$checkOwn = mysqli_stmt_get_result($stmt);
		$cO = mysqli_num_rows($checkOwn);

		if ($cO < 1) {
			$query = "SELECT * FROM Items WHERE ID='102'";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_execute($stmt);
			$getItem = mysqli_stmt_get_result($stmt);
			$gI = mysqli_fetch_object($getItem);

			$code1 = sha1($gI->File);
			$code2 = sha1($UserID);

			$query = "INSERT INTO Inventory (UserID, ItemID, File, Type, code1, code2, SerialNum)
					  VALUES (?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, 'sssssss', $UserID, '102', $gI->File, $gI->Type, $code1, $code2, $Output);
			mysqli_stmt_execute($stmt);

			// logs
			$query = "SELECT * FROM Users WHERE ID=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, 's', $UserID);
			mysqli_stmt_execute($stmt);
			$getNew = mysqli_stmt_get_result($stmt);
			$gN = mysqli_fetch_object($getNew);

			$query = "INSERT INTO Logs (UserID, Message, Page) VALUES (?, ?, ?)";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, 'sss', $myU->ID, 'hired ' . $gN->Username . ' as a ' . $Position, $_SERVER['PHP_SELF']);
			mysqli_stmt_execute($stmt);
		}
	}
}
?>

<form action='' method='POST'>
	<table cellspacing='0' cellpadding='0'>
		<tr>
			<td>
				<font id='Text'>Grant power to an user</font><br /><br />
				<table>
					<tr>
						<td>
							User ID:
							<input type='text' name='UserID' />
						</td>
					</tr>
					<tr>
						<td>
							Position:
							<select name='Position' style='padding:2px;'>
								<?php if ($myU->Username == "Isaac" || $myU->Username == "Niko") { ?>
									<option value='Administrator'>Administrator</option>
								<?php } ?>
								<option value='Artist'>Artist</option>
								<option value='Developer'>Developer</option>
								<?php if ($myU->Username == "Isaac" || $myU->Username == "Niko" || $myU->Username == "Shedietsky") { ?>
									<option value='Forum Moderator'>Forum Moderator</option>
									<option value='Image Moderator'>Image Moderator</option>
									<option value='Mega Moderator'>Mega Moderator</option>
								<?php } ?>
							</select>
							<br>
							</br>
							<input type='submit' name='Submit' />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>