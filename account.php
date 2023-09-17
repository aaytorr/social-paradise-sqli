<?php
include_once "Header.php";
echo "<title>Account | Social-Paradise</title>";

if ($User) {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// Function to sanitize user input
		function sanitizeInput($input)
		{
			return htmlspecialchars(trim($input));
		}

		// Update Description
		if (isset($_POST['Submit'])) {
			$Description = sanitizeInput($_POST['Description']);
			$stmt = $connection->prepare("UPDATE Users SET Description=? WHERE Username=?");
			$stmt->bind_param("ss", $Description, $User);
			$stmt->execute();
			$stmt->close();
			header("Location: ../My/Account/");
			exit;
		}

		// Update Password
		if (isset($_POST['Submit2'])) {
			$CurrentPassword = $_POST['CurrentPassword'];
			$NewPassword = $_POST['NewPassword'];
			$ConfirmNewPassword = $_POST['ConfirmNewPassword'];

			// Verify the current password
			if (password_verify($CurrentPassword, $myU->Password)) {
				// Check if new passwords match
				if ($NewPassword === $ConfirmNewPassword) {
					$NewPasswordHash = password_hash($NewPassword, PASSWORD_DEFAULT);
					$stmt = $connection->prepare("UPDATE Users SET Password=? WHERE Username=?");
					$stmt->bind_param("ss", $NewPasswordHash, $User);
					$stmt->execute();
					$stmt->close();
					session_destroy();
					header("Location: index.php");
					exit;
				} else {
					echo "Your new password and new confirm password do not match!";
				}
			} else {
				echo "Your current password is not correct!";
			}
		}

		// Update Signature
		if (isset($_POST['Submit3'])) {
			$Siggy = sanitizeInput($_POST['Signature']);
			if ($Siggy) {
				$stmt = $connection->prepare("UPDATE Users SET Signature=? WHERE ID=?");
				$stmt->bind_param("si", $Siggy, $myU->ID);
				$stmt->execute();
				$stmt->close();
				header("Location: ../My/Account/");
				exit;
			}
		}
	}

	// Output the account form
	echo "
		<table width='95%'>
			<tr>
				<td>
					<div id='LargeText'>
						My Account
					</div>
					<div id=''>
						<div align='left'>
							<form action='' method='POST'>
								<table>
									<tr>
										<td>
											<div id='ProfileText'>My Description</div>
											<font id=''>Update your personal description here.</font>
											<br />
											<textarea name='Description' style='width:700px;height:100px;'>".$myU->Description."</textarea>
										</td>
									</tr>
									<tr>
										<td>
											<input type='submit' value='Update' name='Submit'>
										</td>
									</tr>
								</table>
							</form>
							<form action='' method='POST'>
								<br />
								<table>
									<tr>
										<td>
											<div id='ProfileText'>My Password</div>
											<font id='Small'>Update your password here.</font>
											<br />
											<table>
												<tr>
													<td>
														<b>Current Password:</b>
													</td>
													<td>
														<input type='password' name='CurrentPassword'>
													</td>
												</tr>
												<tr>
													<td>
														<b>New Password:</b>
													</td>
													<td>
														<input type='password' name='NewPassword'>
													</td>
												</tr>
												<tr>
													<td>
														<b>Confirm New Password:</b>
													</td>
													<td>
														<input type='password' name='ConfirmNewPassword'>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<input type='submit' value='Change' name='Submit2'>
										</td>
									</tr>
								</table>
							</form>
						</div>
					</td>
					<td>
					</td>
				</tr>
			</table>
		";
}

include_once "Footer.php";
?>