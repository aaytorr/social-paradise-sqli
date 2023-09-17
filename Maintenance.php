<?php
include_once('Header.php');
http_response_code(503);
header("Retry-After: 3600"); 

$stmt = mysqli_prepare($connection, "SELECT * FROM Configuration");
mysqli_stmt_execute($stmt);
$Configuration = mysqli_stmt_get_result($stmt);
$Configuration = mysqli_fetch_object($Configuration);
$Maintenance = $Configuration->MaintenanceType;
	
if ($Maintenance !== "Lockdown") {
	header("Location: index.php");
	exit();
}
?>

<center>
<div style='padding-bottom:90px;'></div>
<table width='800'>
	<tr>
		<td width='410' valign='top'>
			<h2>Site Offline</h2>
			We sincerely apologize for the inconvenience caused. We are currently undergoing maintenance to ensure a smoother and better experience. We understand how important our site is to you, and we are working diligently to complete the necessary updates as quickly as possible.
			<br />
			<br />
			Thank you for your understanding, and we apologize again for any inconvenience caused. If you have any urgent concerns or questions, please don't hesitate to reach out to our customer support team, who will be more than happy to assist you.
		</td>
	</tr>
	<tr>
	<td valign='top'>
		<div id='Opacity'>
			<h2>Administration Access</h2>
			<form action='' method='POST'>
				<table>
					<tr>
						<td>
							<input type='password' name='Password' placeholder='Passcode...'>
						</td>
					</tr>
					<tr>
						<td>
							<input type='submit' name='submit' value='Login'>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</td>
	</tr>
</table>

<?php
if (isset($_POST['submit'])) {
	$password = $_POST['Password'];

	if ($password === "speaknow13") {
		$_SESSION['MaintenanceBypass'] = true;
		header("Location: index.php");
		exit();
	}
}

require_once 'Footer.php';
?>