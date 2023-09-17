<?php
include_once "../Header.php";

if ($User) {
	?>
	<form action="" method="POST">
		<table>
			<tr>
				<td>
					<b>Create New Wall Post:</b>
				</td>
			</tr>
			<tr>
				<td>
					<textarea rows="2" cols="40" name="WallPost"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="Submit">
				</td>
			</tr>
		</table>
	</form>
	<?php

	$WallPost = isset($_POST['WallPost']) ? SecureString($_POST['WallPost']) : '';
	$Submit = isset($_POST['Submit']) ? SecureString($_POST['Submit']) : '';

	if ($Submit) {
		if (empty($WallPost)) {
			properdie("<b>Wall post required.</b>");
		}

		$WallPost = filter($WallPost);
		$now = time();
		if ($now > $myU->WallFlood) {
			$stmt = mysqli_prepare($connection, "INSERT INTO Wall (PosterID, Body, time) VALUES (?, ?, ?)");
			mysqli_stmt_bind_param($stmt, "iss", $myU->ID, $WallPost, $now);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);

			header("Location: /user.php?ID=$myU->ID");

			$BlockFlood = 30;
			$BlockFlood = $now + $BlockFlood;

			$stmt = mysqli_prepare($connection, "UPDATE Users SET WallFlood=? WHERE Username=?");
			mysqli_stmt_bind_param($stmt, "is", $BlockFlood, $User);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		} else {
			$PostAgain = $myU->WallFlood - $now;
			echo "Please wait <b>$PostAgain</b> more seconds until you can post again.";
		}
	}
} else {
	header("Location: index.php");
}

$getWallPosts = mysqli_query($connection, "SELECT * FROM Wall WHERE PosterID='" . $myU->ID . "' ORDER BY ID DESC");

while ($gWP = mysqli_fetch_object($getWallPosts)) {
	?>
	<center>
		<table id="WallPost" style="width:900px;">
			<tr>
				<td>
					<a href="../user.php?ID=<?= $myU->ID ?>"><b><?= $myU->Username ?></b></a>:
					<?= $gWP->Body ?>
				</td>
			</tr>
		</table>
		<br />
	<?php
}

include_once "../Footer.php";
?>