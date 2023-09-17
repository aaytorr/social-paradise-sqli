<?php
include_once "Header.php";

$configuration = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM Configuration"));

if ($configuration['Register'] !== "true") {
	echo "<b>Register has been temporarily disabled.</b>";
	include_once "Footer.php";
	exit;
} else {
	if (!$User) {
		function is_alphanumeric($username) {
			return ctype_alnum($username);
		}

		$UsernameIs = isset($_GET['UsernameIs']) ? mysqli_real_escape_string($connection, strip_tags($_GET['UsernameIs'])) : '';

		if (isset($_POST['_Submit'])) {
			$Username = isset($_POST['_Username']) ? htmlspecialchars($_POST['_Username']) : '';
			$Password = $_POST['_Password'];
			$ConfirmPassword = $_POST['_ConfirmPassword'];
			$Email = isset($_POST['_Email']) ? filter_var($_POST['_Email'], FILTER_VALIDATE_EMAIL) : '';
			$ref = isset($_GET['ref']) ? mysqli_real_escape_string($connection, strip_tags($_GET['ref'])) : '';

			if (empty($Username) || empty($Password) || empty($ConfirmPassword)) {
				echo "<div id='Error'>Please fill in all required fields.</div><br>";
			} else {
				$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE Username = ? OR OriginalName = ?");
				mysqli_stmt_bind_param($stmt, "ss", $Username, $Username);
				mysqli_stmt_execute($stmt);
				$userExist = mysqli_stmt_get_result($stmt);
				$userExistCount = mysqli_num_rows($userExist);
				$usernameLength = strlen($Username);
				$validUsername = true;

				if ($userExistCount > 0) {
					echo "<div id='Error'>That username already exists.</div><br>";
					$validUsername = false;
				} elseif ($usernameLength >= 15) {
					echo "<div id='Error'>Your username is above fifteen (15) characters!</div><br>";
					$validUsername = false;
				} elseif ($usernameLength < 3) {
					echo "<div id='Error'>Your username is under three (3) characters!</div><br>";
					$validUsername = false;
				} elseif (!is_alphanumeric($Username)) {
					echo "<div id='Error'>Only A-Z and 1-9 is allowed, or there is profanity in your username.</div><br>";
					$validUsername = false;
				} elseif ($ConfirmPassword != $Password) {
					echo "<div id='Error'>Your password and confirm password do not match.</div><br>";
					$validUsername = false;
				} elseif ($validUsername == true) {
					$hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
					$IP = $_SERVER['REMOTE_ADDR'];
					$stmt = mysqli_prepare($connection, "INSERT INTO Users (Username, Password, Email, IP) VALUES(?, ?, ?, ?)");
					mysqli_stmt_bind_param($stmt, "ssss", $Username, $hashedPassword, $Email, $IP);
					mysqli_stmt_execute($stmt);
					
					if (mysqli_stmt_error($stmt)) {
						echo "<div id='Error'>Something went wrong! Try again later.</div><br>";
					} else {
						$_SESSION['Username'] = $Username;
						$_SESSION['Password'] = $hashedPassword;
					
						if ($ref) {
							$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
							mysqli_stmt_bind_param($stmt, "s", $ref);
							mysqli_stmt_execute($stmt);
							$getRef = mysqli_stmt_get_result($stmt);
							$gR = mysqli_fetch_assoc($getRef);
							$RefExist = mysqli_num_rows($getRef);
					
							if ($RefExist > 0) {
								$stmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE Username = ?");
								mysqli_stmt_bind_param($stmt, "s", $Username);
								mysqli_stmt_execute($stmt);
								$userExist = mysqli_stmt_get_result($stmt);
								$userExist = mysqli_fetch_assoc($userExist);
								mysqli_query($connection, "UPDATE Users SET SuccessReferrer=SuccessReferrer + 1 WHERE ID='$ref'");
								mysqli_query($connection, "INSERT INTO Referrals (ReferredID, UserID) VALUES('$ref','$userExist[ID]')");
							}
						}
					}

					header("Location: index.php");
					die();
				}
			}
		}
	}
}
?>

<table>
	<tr>
		<td style="width:700px;">
			<div id="LargeText">
				Sign up for Social-Paradise for free.
			</div>
			<form action="" method="POST">
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td style="padding-right:75px;">
							<b>Username
						</td>
						<td>
							<input type="text" name="_Username" value="<?= isset($UsernameIs) ? $UsernameIs : '' ?>" />
						</td>
					</tr>
					<tr>
						<td style="padding-right:75px;">
							<b>Password
						</td>
						<td>
							<input type="password" name="_Password" />
						</td>
					</tr>
					<tr>
						<td style="padding-right:75px;">
							<b>Confirm Password
						</td>
						<td>
							<input type="password" name="_ConfirmPassword" />
						</td>
					</tr>
					<tr>
						<td style="padding-right:75px;">
							<b>Email
						</td>
						<td>
							<input type="text" name="_Email" />
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="_Submit" value="Register">
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>

<?php include_once "Footer.php"; ?>