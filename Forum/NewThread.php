<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/Header.php");

if ($User) {
	$ID = (int) ($_GET['ID'] ?? 0);
	$getTopicQuery = "SELECT COUNT(*) FROM Topics WHERE ID=?";
	$getTopicStmt = mysqli_prepare($connection, $getTopicQuery);
	mysqli_stmt_bind_param($getTopicStmt, "i", $ID);
	mysqli_stmt_execute($getTopicStmt);
	mysqli_stmt_bind_result($getTopicStmt, $gT);
	mysqli_stmt_fetch($getTopicStmt);
	mysqli_stmt_close($getTopicStmt);

	if (!$ID) {
		echo "<b>Error";
	} elseif ($gT == 0) {
		echo "<b>This topic does not exist!</b>";
	} else {
		echo "
		<div id='LargeText'>
			Create a thread
		</div>
		<center>
		
		<form action='' method='POST'>
			<table>
				<tr>
					<td style='padding:5px;'>
						<b>Title</b>
					</td>
					<td style='padding:5px;'>
						<input type='text' name='Title' style='width:450px;'>
					</td>
				</tr>
				<tr>
					<td valign='top' style='padding:5px;'>
						<b>Body</b>
					</td>
					<td style='padding:5px;'>
						<textarea name='Body' style='width:450px;height:200px;'></textarea>
					</td>
				</tr>";
		if ($myU->PowerAdmin == "true" || $myU->PowerForumModerator == "true" || $myU->PowerMegaModerator == "true") {
			echo "
			<tr>
				<td style='padding:5px;'>
					<b>Type</b>
				</td>
				<td style='padding:5px;'>
					<select name='Type'>
						<option value='regular'>Regular</option>
						<option value='sticky'>Sticky</option>
					</select>
				</td>
			</tr>";
		}
		echo "
			<tr>
				<td>
					<input type='submit' name='Submit' value='Post'>
				</td>
			</tr>
		</table>
		</form>";

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$Title = $_POST['Title'] ?? '';
			$Body = $_POST['Body'] ?? '';
			$Type = $_POST['Type'] ?? '';
			$Submit = $_POST['Submit'] ?? '';

			if ($Submit) {
				$Title = mysqli_real_escape_string($connection, strip_tags(stripslashes($Title)));
				$Body = mysqli_real_escape_string($connection, strip_tags(stripslashes($Body)));
				$Type = mysqli_real_escape_string($connection, strip_tags(stripslashes($Type)));
				$Submit = mysqli_real_escape_string($connection, strip_tags(stripslashes($Submit)));

				if (!$Title || !$Body) {
					echo "<b>Your title or body is empty!</b>";
				} elseif (strlen($Title) < 4) {
					echo "<b>Your title is too short!</b>";
				} elseif (strlen($Title) > 249) {
					echo "<b>Your title is too long!</b>";
				} elseif (strlen($Body) < 4) {
					echo "<b>Your body is too short!</b>";
				} elseif (strlen($Body) > 499) {
					echo "<b>Your body is over 500 characters!</b>";
				} else {
					$Title = filter($Title);
					$Body = filter($Body);

					if ($myU->PowerAdmin == "false" || $myU->PowerForumModerator == "false" || $myU->PowerAdmin == "false") {
						$Type = "regular";
					}

					if ($now < $myU->forumflood) {
						echo "You are posting too fast, please wait.";
					} else {
						$flood = $now + 10;
						mysqli_query($connection, "UPDATE Users SET forumflood='$flood' WHERE ID='$myU->ID'");
						$insertQuery = "INSERT INTO Threads (Title, Body, PosterID, Type, tid, bump) VALUES (?, ?, ?, ?, ?, ?)";
						$insertStmt = mysqli_prepare($connection, $insertQuery);
						$now = time();
						mysqli_stmt_bind_param($insertStmt, "ssisii", $Title, $Body, $myU->ID, $Type, $ID, $now);
						mysqli_stmt_execute($insertStmt);
						$lastInsertId = mysqli_insert_id($connection);
						echo('<meta http-equiv="refresh" content="0;URL=/ViewThread.php?ID=' . $lastInsertId . '">');
						die();
					}
				}
			}
		}
	}
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/Footer.php");
?>