<?php
/* Session */
$User = $_SESSION['Username'];

if ($User) {
	$stmt = $connection->prepare("SELECT * FROM Users WHERE Username = ?");
	$stmt->bind_param("s", $User);
	$stmt->execute();
	$result = $stmt->get_result();
	$myU = $result->fetch_object();
	$stmt->close();
}

if (!$myU || $myU->PowerAdmin !== "true") {
	echo('<meta http-equiv="refresh" content="0;URL=/">');
	exit();
}

$stmt = $connection->prepare("SELECT * FROM Configuration");
$stmt->execute();
$result = $stmt->get_result();
$Configuration = $result->fetch_object();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$BannerText = isset($_POST['BannerText']) ? strip_tags($_POST['BannerText']) : "";
	$ColorBanner = isset($_POST['Color']) ? strip_tags($_POST['Color']) : "";
	$MaintenanceType = isset($_POST['MaintenanceType']) ? strip_tags($_POST['MaintenanceType']) : "";
	$RegisterEnabled = isset($_POST['_RegisterEnabled']) ? "true" : "false";
	$Submit = isset($_POST['Submit']);

	if ($Submit) {
		$BannerText = filter($BannerText);
		$ColorBanner = filter($ColorBanner);

		$stmt = $connection->prepare("UPDATE Banner SET Text = ?, Color = ?");
		$stmt->bind_param("ss", $BannerText, $ColorBanner);
		$stmt->execute();
		$stmt->close();

		$stmt = $connection->prepare("UPDATE Configuration SET Register = ?, MaintenanceType = ?");
		$stmt->bind_param("ss", $RegisterEnabled, $MaintenanceType);
		$stmt->execute();
		$stmt->close();

		echo('<meta http-equiv="refresh" content="0;URL=?tab=configuration">');
		exit();
	}
}
?>
<form action="" method="POST">
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table>
					<tr>
						<td>
							<label id="ProfileText">Banner Text:</label><br /><br />
							Text:
							<input type="text" value="<?= htmlentities($gB->Text ?? '') ?>" name="BannerText" id="BannerText" />
						</td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td>
							Color:
							<select name="Color" style="padding:2px;">
								<option value="Orange" <?php if ($gB->Color === 'Orange') echo 'selected'; ?>>Orange</option>
								<option value="Red" <?php if ($gB->Color === 'Red') echo 'selected'; ?>>Red</option>
								<option value="Black" <?php if ($gB->Color === 'Black') echo 'selected'; ?>>Black</option>
								<option value="White" <?php if ($gB->Color === 'White') echo 'selected'; ?>>White</option>
							</select>
						</td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<label id="ProfileText">Enabled Features:</label><br /><br />
				<table>
					<tr>
						<td>
							<div id="Text">Register</div>
						</td>
						<td style="padding-left:150px;">
							<input type="checkbox" name="_RegisterEnabled" value="true" <?php if ($Configuration->Register == "true") { echo 'checked'; } ?>/>
						</td>
					</tr>
					<tr>
						<td>
							<div id="Text">Maintenance</div>
						</td>
						<td style="padding-left:150px;">
							<select name="MaintenanceType" style="padding:2px;">
								<option value="None" <?php if ($Configuration->MaintenanceType === 'None') echo 'selected'; ?>>Off</option>
								<option value="Banner" <?php if ($Configuration->MaintenanceType === 'Banner') echo 'selected'; ?>>Banner</option>
								<option value="Lockdown" <?php if ($Configuration->MaintenanceType === 'Lockdown') echo 'selected'; ?>>Lockdown</option>
							</select>
						</td>
					</tr>
				</table>
				<br>
				<input type="submit" name="Submit" value="Update" />
			</td>
		</tr>
	</table>
</form>