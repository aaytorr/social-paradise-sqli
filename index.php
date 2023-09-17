<?php
include_once('Header.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$Username = SecurePost($_POST['Username'] ?? '');
	$Password = SecurePost($_POST['Password'] ?? '');
	$Submit = SecurePost($_POST['Submit'] ?? '');
}

if (!empty($Submit)) {
	if (empty($Username) || empty($Password)) {
		echo "<div id='Error'>Sorry, you have missing fields.</div>";
	} else {
		$checkUserQuery = "SELECT * FROM Users WHERE Username = ?";
		$checkUserStmt = mysqli_prepare($connection, $checkUserQuery);
		mysqli_stmt_bind_param($checkUserStmt, 's', $Username);
		mysqli_stmt_execute($checkUserStmt);
		$checkUserResult = mysqli_stmt_get_result($checkUserStmt);
		$cU = mysqli_num_rows($checkUserResult);

		if ($cU == 0) {
			echo "<div id='Error'>Sorry, that account does not exist.</div>";
		} else {
			$userData = mysqli_fetch_assoc($checkUserResult);
			$hashedPassword = $userData['Password'];

			if (password_verify($Password, $hashedPassword)) {
				$_SESSION['Username'] = $Username;
				$_SESSION['Password'] = $hashedPassword;
				header("Location: index.php");
				exit;
			} else {
				echo "<div id='Error'>Sorry, but your password is incorrect.</div>";
			}
		}
	}
}
?>
<form action="" method="POST">
	<div align='left'>
		<table>
			<tr>
				<td valign='top'>
					<?php if (!isset($User)) : ?>
						<div id='Login'>
							<div align='center'>
								<table>
									<tr>
										<td id='Msg'>Login</td>
									</tr>
								</table>
							</div>
							<table>
								<tr>
									<td id='smalltext'>Username</td>
									<td>
										<input type='text' name='Username' required='required'>
									</td>
								</tr>
								<tr>
									<td id='smalltext'>Password</td>
									<td>
										<input type='password' name='Password' required='required'>
									</td>
								</tr>
								<tr>
									<td>
										<input type='submit' name='Submit' value='Login'>
									</td>
								</tr>
							</table>
							<a href='ForgotPassword.php'>Forgot your password?</a>
						</div>
						<br />
						<br />
					</form>
					<form action='register.php' method='POST'>
						<div id='Login'>
							<div align='center'>
								<table>
									<tr>
										<td>
											<div id='Msg'>
												Register
											</div>
											<div id='Msgsmall'>
												Sign up for free.
											</div>
										</td>
									</tr>
								</table>
							</div>
							<table>
								<tr>
									<td id='smalltext'>
										Username
									</td>
									<td>
										<input type='text' name='_Username'>
									</td>
								</tr>
								<tr>
									<td id='smalltext'>
										Password
									</td>
									<td>
										<input type='password' name='_Password'>
									</td>
								</tr>
								<tr>
									<td id='smalltext'>
										Confirm Password
									</td>
									<td>
										<input type='password' name='_ConfirmPassword'>
									</td>
								</tr>
								<tr>
									<td id='smalltext'>
										Email
									</td>
									<td>
										<input type='text' name='_Email'>
									</td>
								<tr>
									<td>
										<input type='submit' name='_Submit' value='Register'>
									</td>
								</tr>
							</table>
					<?php else : ?>
						<div id='Login'>
							<center>
								<b>Welcome, <?php echo $User; ?></b>
								<br />
								<br />
								<img src='../Avatar.php?ID=<?php echo $myU->ID; ?>'>
							</center>
						</div>
					<?php endif; ?>
				</td>
				<td valign='top' style='padding-left:70px;'>
					<div id='LargeText'>
						Creativity, intelligence, ideas.
					</div>
					<div id='smalltext'>
						A huge collaboration. Join today.
					</div>
					<br />
					<br />
					<table>
						<tr>
							<td align='left'>
								<div id='wrapper'>
									<div class='slider-wrapper theme-default'>
										<div id='slider' class='nivoSlider'>
											<img src='../Base/Slides/Slide1.png' alt='Welcome to Social-Paradise' />
											<img src='../Base/Slides/Slide2.png' alt='Avatars' />
											<img src='../Base/Slides/Slide3.png' alt='Security' />
											<img src='../Base/Slides/Slide4.png' alt='Security' />
										</div>
										<div id='htmlcaption' class='nivo-html-caption'>
											<strong>This</strong> is an example of a <em>HTML</em> caption with <a href='#'>a link</a>.
										</div>
									</div>
									<script type='text/javascript' src='../Base/Scripts/jquery-1.7.1.min.js'></script>
									<script type='text/javascript' src='../Base/Scripts/jquery.nivo.slider.pack.js'></script>
									<script type='text/javascript'>
										$(window).load(function() {
											$('#slider').nivoSlider();
										});
									</script>
									<br /><br /><br />
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php
include_once('Footer.php');
?>