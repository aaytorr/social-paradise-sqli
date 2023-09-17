<?php

include_once "../Header.php";

$ID = isset($_GET['ID']) ? mysqli_real_escape_string($connection, $_GET['ID']) : null;

if (!$User) {
	header("Location: ../index.php");
	exit;
}

if (!$ID) {
	properdie("<b>This group does not exist.</b>");
}

$getGroup = mysqli_prepare($connection, "SELECT * FROM Groups WHERE ID = ?");
mysqli_stmt_bind_param($getGroup, "s", $ID);
mysqli_stmt_execute($getGroup);
$getGroupResult = mysqli_stmt_get_result($getGroup);

$gG = mysqli_fetch_object($getGroupResult);
$GroupExist = mysqli_num_rows($getGroupResult);

if ($GroupExist == 0) {
	properdie("<b>This group does not exist.</b>");
}

echo "
<form action='' method='POST'>
	<table width='98%'>
		<tr>
			<td>
				<div id='ProfileText'>
					<table cellspacing='0' cellpadding='0'>
						<tr>
							<td width='95%'>
								<b>".$gG->Name."</b>
							</td>
							<td width='10%' style='text-align:left;'>
								<div align='right'>";

$checkInGroup = mysqli_prepare($connection, "SELECT * FROM GroupMembers WHERE GroupID = ? AND UserID = ?");
mysqli_stmt_bind_param($checkInGroup, "ss", $ID, $myU->ID);
mysqli_stmt_execute($checkInGroup);
$InGroupResult = mysqli_stmt_get_result($checkInGroup);
$InGroup = mysqli_num_rows($InGroupResult);

if ($InGroup == 0) {
	echo "
		<input type='submit' id='buttonsmall' value='Join' name='Join'>";
	
	$Join = isset($_POST['Join']) ? $_POST['Join'] : null;
	
	if ($Join) {
		$getMe = mysqli_prepare($connection, "SELECT * FROM GroupMembers WHERE UserID = ?");
		mysqli_stmt_bind_param($getMe, "s", $myU->ID);
		mysqli_stmt_execute($getMe);
		$getMeResult = mysqli_stmt_get_result($getMe);
		$gM = mysqli_num_rows($getMeResult);
		
		if ($myU->Premium == "1") {
			$Groups = 100;
		} else {
			$Groups = 5;
		}
		
		if ($gM < $Groups) {
			if ($InGroup == 0) {
				mysqli_query($connection, "INSERT INTO GroupMembers (GroupID, UserID) VALUES ('$ID', '$myU->ID')");
				header("Location: Group.php?ID=$ID");
				exit;
			}
		} else {
			properdie("<b>Sorry, but you can only be in 5 groups at once.</b>");
		}
	}
} else {
	if ($myU->ID != $gG->OwnerID) {
		echo "
		<input type='submit' id='buttonsmall' value='Leave' name='Leave'>";
		$Leave = isset($_POST['Leave']) ? $_POST['Leave'] : null;
		
		if ($Leave) {
			if ($InGroup == 1) {
				mysqli_query($connection, "DELETE FROM GroupMembers WHERE GroupID = '$ID' AND UserID = '$myU->ID'");
				header("Location: Group.php?ID=$ID");
				exit;
			}
		}
	} else {
		echo "<input type='submit' id='buttonsmall' value='?' style='visibility:hidden;'>";
	}
}

echo "
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id=''>
					<table>
						<tr>
							<td valign='top' width='100'>
								<div style='border:1px solid #ddd;width:100px;height:100px;padding:5px;'>
									<img src='../Groups/GL/".$gG->Logo."' height='100' width='100'>
								</div>
								<br />
								<Center><b>Group Owner:</b>
								<br />
									<a href='../user.php?ID=".$gG->OwnerID."'>
									<div style='height:50px;width:100px;overflow-y:hidden;'>
										<img src='../Avatar.php?ID=".$gG->OwnerID."' height='100'>
									</div>";

$getOwner = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
mysqli_stmt_bind_param($getOwner, "s", $gG->OwnerID);
mysqli_stmt_execute($getOwner);
$getOwnerResult = mysqli_stmt_get_result($getOwner);
$gP = mysqli_fetch_object($getOwnerResult);
echo "
									<b>".$gP->Username."</b>
									</a>
							</td>
							<td valign='top' style=''>
								<div style='width:300px;height:100px;font-size:12px;color:#777;background:#f5f5f5;border:1px solid #ccc;padding:5px;max-height:100px;overflow-y:auto;'>
									".nl2br($gG->Description)."
									";
$Number = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM GroupMembers WHERE GroupID = '".$ID."' ORDER BY ID DESC"));
echo "
								</div>
								";
if ($myU->ID == $gG->OwnerID) {
	echo "<br /><input type='submit' name='RedirectToAdmin' value='Edit Group' id='buttonsmall'>";
	$RedirectToAdmin = isset($_POST['RedirectToAdmin']) ? $_POST['RedirectToAdmin'] : null;
	if ($RedirectToAdmin) {
		header("Location: EditGroup.php?ID=$ID");
		exit;
	}
	echo "<br />";
}
if ($InGroup == 1) {
	if ($myU->MainGroupID == $ID) {
		echo "
		<div style='padding-top:3px;'></div>
		<input type='submit' name='RemoveMain' value='Remove Main' id='buttonsmall'>";
		$RemoveMain = isset($_POST['RemoveMain']) ? $_POST['RemoveMain'] : null;
		if ($RemoveMain) {
			mysqli_query($connection, "UPDATE Users SET MainGroupID = '' WHERE ID = '$myU->ID'");
			header("Location: Group.php?ID=$ID");
			exit;
		}
	} else {
		echo "
		<div style='padding-top:3px;'></div>
		<input type='submit' name='MakeMain' value='Make Main' id='buttonsmall'>";
		$MakeMain = isset($_POST['MakeMain']) ? $_POST['MakeMain'] : null;
		if ($MakeMain) {
			mysqli_query($connection, "UPDATE Users SET MainGroupID = '$ID' WHERE ID = '$myU->ID'");
			header("Location: Group.php?ID=$ID");
			exit;
		}
	}
}
echo "
							</td>
							<td valign='top' style='padding-left:30px;width:400px;'>";

// Get members
$Setting = array(
	"PerPage" => 8
);
$Page = isset($_GET['Page']) ? mysqli_real_escape_string($connection, $_GET['Page']) : null;
$Page = ($Page < 1 || !is_numeric($Page)) ? 1 : $Page;
$Minimum = ($Page - 1) * $Setting["PerPage"];

$getMembers = mysqli_prepare($connection, "SELECT * FROM GroupMembers WHERE GroupID = ? ORDER BY ID DESC LIMIT ?, ?");
mysqli_stmt_bind_param($getMembers, "sss", $ID, $Minimum, $Setting["PerPage"]);
mysqli_stmt_execute($getMembers);
$getMembersResult = mysqli_stmt_get_result($getMembers);

echo "<table><tr>";

$counter = 0;
while ($gM = mysqli_fetch_object($getMembersResult)) {
	$counter++;
	$getUser = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
	mysqli_stmt_bind_param($getUser, "s", $gM->UserID);
	mysqli_stmt_execute($getUser);
	$getUserResult = mysqli_stmt_get_result($getUser);
	$gU = mysqli_fetch_object($getUserResult);

	echo "
	<td width='100'>
		<center>
		<a href='../user.php?ID=".$gM->UserID."'>
		<div style='height:50px;width:100px;overflow-y:hidden;'>
			<img src='../Avatar.php?ID=".$gM->UserID."' height='100'>
		</div>
		<b>".$gU->Username."</b>
		</a>
	</td>";

	if ($counter >= 4) {
		echo "</tr><tr>";
		$counter = 0;
	}
}

echo "</tr></table><center>";
$amount = ceil($Number / $Setting["PerPage"]);
if ($Page > 1) {
	echo '<a href="Group.php?ID='.$ID.'&Page='.($Page - 1).'">Prev</a> - ';
}
echo ''.$Page.'/'.(ceil($Number / $Setting["PerPage"]));
if ($Page < ($amount)) {
	echo ' - <a href="Group.php?ID='.$ID.'&Page='.($Page + 1).'">Next</a>';
}
echo "
							</td>
						</tr>
					</table>
				</div>";

if ($InGroup == 1) {
	echo "
	<br />
	<div id='ProfileText'>
		Comments<a name='comments'></a>
	</div>";

	$getComments = mysqli_prepare($connection, "SELECT * FROM GroupWall WHERE GroupID = ? ORDER BY ID DESC");
	mysqli_stmt_bind_param($getComments, "s", $ID);
	mysqli_stmt_execute($getComments);
	$getCommentsResult = mysqli_stmt_get_result($getComments);

	while ($gC = mysqli_fetch_object($getCommentsResult)) {
		echo "
		<table id=''>
			<tr>
				<td valign='top'>
					<center>
					<div style='height:50px;width:100px;overflow-y:hidden;'>
						<img src='../Avatar.php?ID=".$gC->UserID."' height='100'>
					</div>";
		$getPoster = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
		mysqli_stmt_bind_param($getPoster, "s", $gC->UserID);
		mysqli_stmt_execute($getPoster);
		$getPosterResult = mysqli_stmt_get_result($getPoster);
		$gP = mysqli_fetch_object($getPosterResult);
		echo "
					".$gP->Username."
				</td>
				<td valign='top' style='width:900px;'>
					".$gC->Message."
					";
		if ($myU->ID == $gG->OwnerID) {
			echo "
			<br /><br />
			<a href='?ID=$ID&WallAction=Delete&WallID=$gC->ID'><font color='red'><b>Delete</b></font></a>";
		}
		echo "
				</td>
			</tr>
		</table>
		<br />";
	}

	if ($myU->ID == $gG->OwnerID) {
		$WallAction = isset($_GET['WallAction']) ? mysqli_real_escape_string($connection, $_GET['WallAction']) : null;
		$WallID = isset($_GET['WallID']) ? mysqli_real_escape_string($connection, $_GET['WallID']) : null;

		if ($WallAction == "Delete") {
			mysqli_query($connection, "DELETE FROM GroupWall WHERE ID = '$WallID'");
			header("Location: Group.php?ID=".$ID."#comments");
			exit;
		}
	}

	echo "
	<table id=''>
		<tr>
			<td valign='top' width='100'>
				<center>
				<div style='height:50px;width:100px;overflow-y:hidden;'>
					<img src='../Avatar.php?ID=".$myU->ID."' height='100'>
				</div>
				<b>".$myU->Username."</b>
			</td>
			<td valign='top' style='padding-left:10px;width:500px;'>
				<textarea name='Message' style='width:100%;height:50px;resize:none;font-size:10px;' maxlength='500'></textarea>
				<br />
				<div align='right'>
					<input type='submit' id='buttonsmall' value='Post' name='Submit'>
				</div>
			</td>
		</tr>
	</table>
</div>
</td>
</tr>
</table>
</form>
";

$Message = isset($_POST['Message']) ? mysqli_real_escape_string($connection, $_POST['Message']) : null;
$Submit = isset($_POST['Submit']) ? mysqli_real_escape_string($connection, $_POST['Submit']) : null;

if ($Submit) {
	if ($Message) {
		mysqli_query($connection, "INSERT INTO GroupWall (GroupID, UserID, Username, Message) VALUES ('$ID', '$myU->ID', '$myU->Username', '$Message')");
		header("Location: Group.php?ID=$ID#comments");
		exit;
	}
}}

include_once "../Footer.php";
?>