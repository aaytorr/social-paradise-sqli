<?php
include_once "Header.php";
$ID = mysqli_real_escape_string($connection, $_GET['ID']);
if (!$ID) {
	include_once("Error/404.php");
	die();
} else {
	$getUserStmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
	mysqli_stmt_bind_param($getUserStmt, "s", $ID);
	mysqli_stmt_execute($getUserStmt);
	$getUserResult = mysqli_stmt_get_result($getUserStmt);
	$gU = mysqli_fetch_object($getUserResult);
	$UserExistStmt = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID = ?");
	mysqli_stmt_bind_param($UserExistStmt, "s", $ID);
	mysqli_stmt_execute($UserExistStmt);
	$UserExistResult = mysqli_stmt_get_result($UserExistStmt);
	$UserExist = mysqli_num_rows($UserExistResult);
	if ($UserExist == 0) {
		include_once("Error/404.php");
		die();
	}
	$checkBanStmt = mysqli_prepare($connection, "SELECT * FROM IPBans WHERE IP = ?");
	mysqli_stmt_bind_param($checkBanStmt, "s", $gU->IP);
	mysqli_stmt_execute($checkBanStmt);
	$checkBanResult = mysqli_stmt_get_result($checkBanStmt);
	$Ban = mysqli_num_rows($checkBanResult);
	if ((isset($myU->PowerAdmin) && $myU->PowerAdmin == "true") || (isset($myU->PowerMegaModerator) && $myU->PowerMegaModerator == "true")) {
		if ($Ban > 0) {
			echo "<center>
					<div id='cA' style='width:90%;'>
						<b>This user is IP banned forever. ";
			if ($myU->PowerMegaModerator == "true" || $myU->PowerAdmin == "true") {
				echo "<a href='../user.php?ID=$ID&IP=Revert'>(revert)</a>";
			}
			echo "</b>
					</div>
					<br />";
			$IP = mysqli_real_escape_string($connection, $_GET['IP']);
			if ($IP == "Revert") {
				mysqli_query($connection, "DELETE FROM IPBans WHERE IP='$gU->IP'");
				header("Location: user.php?ID=$ID");
			}
		}
	}
	if ($gU->Ban == "1") {
		echo "<center>
				<div id='cA' style='width:90%;'>
					<b>This user is banned. ";
		if ($myU->PowerMegaModerator == "true" || $myU->PowerAdmin == "true") {
			echo "<a href='../BanUser.php?ID=$ID&Unban=True'>(revert)</a>";
		}
		echo "</b>
				</div>";
		if ($myU->PowerAdmin == "true" || $myU->PowerMegaModerator == "true") {
			echo "<br />
					<div id='aB' style='width:75%;'>
						<center>
							<table width='50%'>
								<tr>
									<td>
										<b style='font-size:20px;'>";
			if ($gU->BanLength == "0") {
				echo "Reminder/Warning";
			}
			if ($gU->BanLength == "1") {
				echo "Banned for 1 Day";
			}
			if ($gU->BanLength == "3") {
				echo "Banned for 3 Days";
			}
			if ($gU->BanLength == "7") {
				echo "Banned for 7 Days";
			}
			if ($gU->BanLength == "14") {
				echo "Banned for 14 Days";
			}
			if ($gU->BanLength == "forever") {
				echo "Account Deleted";
			}
			echo "</b>
									<center><table width='500'><tr><td align='left'>
											<div id='HeadText'>";
			if ($gU->BanLength != "forever") {
				$TimeUp = date("F j, Y g:iA", $gU->BanTime);
			}
			echo "</div>
										</td>
									</tr>
									<tr>
										<td align='left'>
											<font style='font-size:12px;'>
											Reason: <b>" .
				$gU->BanType .
				"</b>
											<br /><br />
											Our staff has found your account in violation of our terms. We have such rights to suspend your account if you do not abide by our rules.
											</font>
											<br />
								<br />
								<center>
								<div id='aB' style='border-radius:5px;width:600px;'>
									<center>
									";
			if ($gU->BanContent) {
				echo "
									<div style='background:white;padding:7px;border:1px solid #ccc;width:97%;text-align:left;'>
									 <b>Bad Content:</b> <i>" .
					$gU->BanContent .
					"</i>
									 <br />
									 <font style='font-size:9px;'>The following text in italics is offensive text you have typed that is not approved by our staff.</font>
									</div>
									";
			}
			echo "
									</center>
									<br />
									<b>Moderator Note:</b> $gU->BanDescription
								</div>
							</td></tr></table></div>
						</td>
				</tr>
			</table></div>
<br />
			";
		}
	} else {
		if ($User) {
			if ($myU->ID != $ID) {
				if ($gU->Premium == 1) {
					#echo "<style>body { background-image:url(../Images/PremiumStripeBG.png); }</style>";
				}
				mysqli_query($connection, "UPDATE Users SET pviews = pviews + 1 WHERE ID = '" . $ID . "'");
				$checkIfFriendStmtA = mysqli_prepare($connection, "SELECT * FROM FRs WHERE SenderID = ? AND ReceiveID = ?");
				mysqli_stmt_bind_param($checkIfFriendStmtA, "ii", $myU->ID, $ID);
				mysqli_stmt_execute($checkIfFriendStmtA);
				$checkAResult = mysqli_stmt_get_result($checkIfFriendStmtA);
				$checkA = mysqli_num_rows($checkAResult);
				$checkIfFriendStmtB = mysqli_prepare($connection, "SELECT * FROM FRs WHERE SenderID = ? AND ReceiveID = ?");
				mysqli_stmt_bind_param($checkIfFriendStmtB, "ii", $ID, $myU->ID);
				mysqli_stmt_execute($checkIfFriendStmtB);
				$checkBResult = mysqli_stmt_get_result($checkIfFriendStmtB);
				$checkB = mysqli_num_rows($checkBResult);
				if (isset($_GET['SendFR'])) {
					$sendFR = stripslashes($_GET['SendFR']);
				} else {
					$sendFR = "";
				}
				if (isset($_GET['Close'])) {
					$close = stripslashes($_GET['Close']);
				} else {
					$close = "";
				}
				if ($sendFR == "send") {
					if ($checkA >= 1 || $checkB >= 1) {
						echo "
					<div style='background-image:url(/Images/menuhover.png);width:100%;height:100%;top:0;left:0;position:fixed;'><center>
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<div id='aB' style='width:300px;border:2px solid #aaa;border-radius:5px;'>
							<font id='HeadText'>
								<b>Hang on there buckaroo</b>
							</font>
							<br />
							<form action='' method='POST'>
								It seems like you are already friends with this user, or there is an active friend request.
								<br /><br />
								<input type='submit' name='Close' value='Close' id='SpecialInput'>
							</form>
						</div>
					</div>
				";
					} else {
						$insertFRStmt = mysqli_prepare($connection, "INSERT INTO FRs (SenderID, ReceiveID, Active) VALUES (?, ?, '0')");
						mysqli_stmt_bind_param($insertFRStmt, "ii", $myU->ID, $ID);
						mysqli_stmt_execute($insertFRStmt);
						echo "
					<div style='background-image:url(/Images/menuhover.png);width:100%;height:100%;top:0;left:0;position:fixed;'><center>
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<div style='background:#ccc;border:1px solid gray;width:500px;padding:8px;text-align:left;'>
							<font id='HeadText'>
								Success
							</font>
							<br />
							<form action='' method='POST'>
								You have successfully sent a friend request to " .
							$gU->Username .
							"!
								<br />
								<input type='submit' name='Close' value='Close'>
							</form>
						</div>
					</div>
				";
					}
					if ($close) {
						header("Location: user.php?ID=$ID");
					}
				}
			}
		}
		echo "
<table width='65%'>
	<tr>
		<td width='50%' valign='center'>
			<div id='ProfileText'>
			" .
			$gU->Username .
			"
			</div>
			<div id='Gradient1'>
			";
		if ((isset($myU->PowerAdmin) && $myU->PowerAdmin == "true") || (isset($myU->PowerMegaModerator) && $myU->PowerMegaModerator == "true")) {
			if (!empty($gU->OriginalName)) {
				echo "<center><font color='blue' style='font-size:7pt;'><b>User's original name was " . $gU->OriginalName . "</b></font> <a href='?ID=$ID&moderation=revertname' style='font-size:7pt;'>[Revert]</a>";
			}
		}
		echo "
				<center>
				";
		echo "
				<div>
				<img src='../Avatar.php?ID=$gU->ID'>
				</div>
				";
		// Check if user is still in the main group
		if (!empty($gU->MainGroupID)) {
			$checkUser = mysqli_prepare($connection, "SELECT * FROM GroupMembers WHERE GroupID=? AND UserID=?");
			mysqli_stmt_bind_param($checkUser, "ss", $gU->MainGroupID, $ID);
			mysqli_stmt_execute($checkUser);
			$result = mysqli_stmt_get_result($checkUser);
			$numMember = mysqli_num_rows($result);
			if ($numMember == 0) {
				mysqli_query($connection, "UPDATE Users SET MainGroupID='' WHERE ID='$ID'");
				header("Location: user.php?ID=$ID");
			}
			$getMain = mysqli_prepare($connection, "SELECT * FROM Groups WHERE ID=?");
			mysqli_stmt_bind_param($getMain, "s", $gU->MainGroupID);
			mysqli_stmt_execute($getMain);
			$resultMain = mysqli_stmt_get_result($getMain);
			$gM = mysqli_fetch_object($resultMain);
			echo "
					<br />
					<b style='font-size:8pt;'>Main Group:</b>
					<br />
					<div style='height:50px;width:50px;'>
					<a href='../Groups/Group.php?ID=$gM->ID'><img src='../Groups/GL/" .
				$gM->Logo .
				"' width='50' height='50'>
					
					</div>
					<b><font style='font-size:11px;'>" .
				$gM->Name .
				"</font></a></b>
					";
		}
		echo "
					<br />
					";
		if ($gU->Description) {
			echo "
					<div style='width:90%;padding:5px;overflow-y:auto;max-height:100px;background:white;border-radius:5px;border:1px solid #CCC;'>
					" .
				nl2br($gU->Description) .
				"
					</div>
					";
		}
		echo "
					<div style='padding-top:5px;padding-bottom:5px;'>
					";
		if ($User) {
			echo "<a href='SendMessage.php?ID=$ID'><b>Send Message</b></a> &nbsp; &nbsp; &nbsp; <a href='Donate.php?ID=$ID'><b>Send Donation</b></a>";
			echo " &nbsp; &nbsp; &nbsp; <a href='?ID=$ID&SendFR=send'><b>Send Friend Request</b></a>";
		}
		echo "</div></div><br />";
		echo "<div id='ProfileText'><div align='left'>Statistics</div></div>";
		echo "<div id='Gradient1'>";
		echo "<table width='100%'>";
		echo "<tr><td width='40%'><b>Profile Views:</b></td>";
		echo "<td>" . number_format($gU->pviews) . "</td></tr>";
		$ThreadsStmt = mysqli_prepare($connection, "SELECT * FROM Threads WHERE PosterID = ?");
		mysqli_stmt_bind_param($ThreadsStmt, "i", $ID);
		mysqli_stmt_execute($ThreadsStmt);
		mysqli_stmt_store_result($ThreadsStmt);
		$Threads = mysqli_stmt_num_rows($ThreadsStmt);
		$RepliesStmt = mysqli_prepare($connection, "SELECT * FROM Replies WHERE PosterID = ?");
		mysqli_stmt_bind_param($RepliesStmt, "i", $ID);
		mysqli_stmt_execute($RepliesStmt);
		mysqli_stmt_store_result($RepliesStmt);
		$Replies = mysqli_stmt_num_rows($RepliesStmt);
		$Posts123 = $Threads + $Replies;
		echo "<tr><td width='40%'><b>Forum Posts:</b></td>";
		echo "<td>" . number_format($Posts123) . "</td></tr>";
		$NumFriendsStmt = mysqli_prepare($connection, "SELECT * FROM FRs WHERE ReceiveID = ? AND Active = '1'");
		mysqli_stmt_bind_param($NumFriendsStmt, "i", $ID);
		mysqli_stmt_execute($NumFriendsStmt);
		mysqli_stmt_store_result($NumFriendsStmt);
		$NumFriends = mysqli_stmt_num_rows($NumFriendsStmt);
		echo "<tr><td width='40%'><b>Friends:</b></td>";
		echo "<td>$NumFriends</td></tr>";
		$NumberWallPostsStmt = mysqli_prepare($connection, "SELECT * FROM Wall WHERE PosterID = ?");
		mysqli_stmt_bind_param($NumberWallPostsStmt, "i", $ID);
		mysqli_stmt_execute($NumberWallPostsStmt);
		mysqli_stmt_store_result($NumberWallPostsStmt);
		$NumberWallPosts = mysqli_stmt_num_rows($NumberWallPostsStmt);
		$expiryTime = $gU->expireTime;
		echo "<tr><td width='40%'><b>Wall Posts:</b></td>";
		echo "<td>" . number_format($NumberWallPosts) . "</td></tr>";
		echo "<tr><td width='40%'><b>Status/Last Seen:</b></td>";
		if (!$expiryTime) {
			$SS = "Error";
		} else {
			if (date("Y-m-d H:i:s") < $expiryTime) {
				$SS = "<font color='green'>Online</font>";
			} else {
				$SS = date("F j, Y g:iA", strtotime($expiryTime));
			}
		}
		echo "<td>$SS</td></tr>";
		echo "
</td>
</tr>
</table>
</div>
<br />
<div id='ProfileText'><div align='center'>
	Badges</div>
</div>
<div id='Gradient1'>
";
		$numBadges = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM Badges WHERE UserID='$ID'"));
		if ($numBadges > 0) {
			echo "<center><table><tr>";
			$badgesStmt = mysqli_prepare($connection, "SELECT * FROM Badges WHERE UserID=? ORDER BY ID");
			mysqli_stmt_bind_param($badgesStmt, "i", $ID);
			mysqli_stmt_execute($badgesStmt);
			$badgesResult = mysqli_stmt_get_result($badgesStmt);
			$badge = 0;
			while ($Row = mysqli_fetch_object($badgesResult)) {
				$badge++;
				echo "
		<td width='100'><center>
			<img src='/Images/Badges/" .
					$Row->Position .
					".png' height='64' width='64'>
			<br />
			<b>" .
					$Row->Position .
					"</b>
		</td>
		";
				if ($badge >= 4) {
					echo "</tr><tr>";
					$badge = 0;
				}
			}
			echo "</tr></table>";
		}
		echo "
</div>
</td>
<td width='50%' valign='top'>
<div id='ProfileText'>
	Wall (" . $NumberWallPosts . ")
</div>
<div style='overflow-y:auto;max-height:150px;' id='Gradient1'>
";
		$getWallStmt = mysqli_prepare($connection, "SELECT * FROM Wall WHERE PosterID=? ORDER BY ID DESC");
		mysqli_stmt_bind_param($getWallStmt, "i", $ID);
		mysqli_stmt_execute($getWallStmt);
		$getWallResult = mysqli_stmt_get_result($getWallStmt);
		while ($gW = mysqli_fetch_object($getWallResult)) {
			echo "
	<table width='99%' id='WallPost'>
		<tr>
			<td>
				<font style='font-size:10px;float:right;'>
				";
			$k = date("m-d-y g:i:A", strtotime($gW->time));
			echo "
	$k
	</font>
	<a href='../user.php?ID=" .
				$ID .
				"'><b>" .
				$gU->Username .
				"</b></a>: " .
				$gW->Body .
				"
	";
			if ($myU->PowerMegaModerator == "true" || $myU->PowerAdmin == "true") {
				echo "
		<br />
		<font style='font-size:8pt;'>
			<a href='user.php?ID=$ID&wall=scrub&wallid=" .
					$gW->ID .
					"'><font color='blue'><b>Scrub</b></font></a> |
			<a href='user.php?ID=$ID&wall=delete&wallid=" .
					$gW->ID .
					"'><font color='blue'><b>Delete</b></font></a>
		</font>
		";
			}
			echo "
			</td>
		</tr>
	</table>
	 <DIV STYLE='PADDING-TOP:5PX;'></DIV>
	";
		}
		if ((isset($myU->PowerAdmin) && $myU->PowerAdmin == "true") || (isset($myU->PowerMegaModerator) && $myU->PowerMegaModerator == "true")) {
			$wall = isset($_GET['wall']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['wall']))) : '';
			$wallid = isset($_GET['wallid']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['wallid']))) : '';
			if ($wall == "scrub") {
				mysqli_query($connection, "UPDATE Wall SET Body='[ Content Deleted ]' WHERE ID='" . $wallid . "'");
				header("Location: user.php?ID=$ID");
			} elseif ($wall == "delete") {
				mysqli_query($connection, "DELETE FROM Wall WHERE ID='$wallid'");
				header("Location: user.php?ID=$ID");
			}
		}
		echo "</div>";
		$Setting = ["PerPage" => 8];
		$Page1 = isset($_GET['FRPage']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['FRPage']))) : '';
		if ($Page1 < 1 || !is_numeric($Page1)) {
			$Page1 = 1;
		}
		$Minimum1 = ($Page1 - 1) * $Setting["PerPage"];
		$allusers1 = mysqli_query($connection, "SELECT * FROM FRs WHERE ReceiveID='$ID' AND Active='1'");
		$num1 = mysqli_num_rows($allusers1);
		$getFRsStmt = mysqli_prepare($connection, "SELECT * FROM FRs WHERE ReceiveID=? AND Active='1' LIMIT ?, ?");
		mysqli_stmt_bind_param($getFRsStmt, "iii", $ID, $Minimum1, $Setting["PerPage"]);
		mysqli_stmt_execute($getFRsStmt);
		$getFRsResult = mysqli_stmt_get_result($getFRsStmt);
		$RFriends = mysqli_query($connection, "SELECT * FROM FRs WHERE ReceiveID='$ID' AND Active='1'");
		$NumFriends = mysqli_num_rows($getFRsResult);
		$KFriends = mysqli_num_rows($RFriends);
		echo "<br />";
		echo "<div id='ProfileText'>Friends (" . $KFriends . ")</div>";
		echo "<div id='Gradient1'>";
		echo "<center>";
		echo "<table><tr>";
		if ($NumFriends == 0) {
			echo "<b>This user has added no friends yet.</b>";
		}
		$friends = 0;
		while ($gF = mysqli_fetch_object($getFRsResult)) {
			$friends++;
			$getFriend = mysqli_query($connection, "SELECT * FROM Users WHERE ID='" . $gF->SenderID . "'");
			$gFF = mysqli_fetch_object($getFriend);
			echo "<td align='center' width='100'>
					<div style='height:100px;width:100px;overflow-y:hidden;'>
					<a href='../user.php?ID=" .
				$gFF->ID .
				"' style='color:black;font-weight:bold;'>
					<img src='../Avatar.php?ID=" .
				$gFF->ID .
				"'>
					</div>
					" .
				$gFF->Username .
				"
					</a>
				</td>";
			if ($friends >= 4) {
				echo "</tr><tr>";
				$friends = 0;
			}
		}
		echo "</tr></table>";
		$amount = isset($Setting["PerPage1"]) && $Setting["PerPage1"] != 0 ? ceil($num1 / $Setting["PerPage1"]) : 0;
		if ($Page1 > 1) {
			echo '<a href="user.php?ID=' . $ID . '&FRPage=' . ($Page1 - 1) . '">Prev</a> - ';
		}
		echo $Page1 . '/' . $amount;
		if ($Page1 < $amount) {
			echo ' - <a href="user.php?ID=' . $ID . '&FRPage=' . ($Page1 + 1) . '">Next</a>';
		}
		$NumInGroups1 = mysqli_query($connection, "SELECT * FROM GroupMembers WHERE UserID='$ID'");
		$NumInGroups = mysqli_num_rows($NumInGroups1);
		echo "
</div>
</center>
<br />
<div id='ProfileText'>
Groups (" .
			$NumInGroups .
			")
</div>
<div id='Gradient1'>
<center>
<table><tr>
";
		$groups = 0;
		while ($gG = mysqli_fetch_object($NumInGroups1)) {
			$groups++;
			$getGroupK = mysqli_query($connection, "SELECT * FROM Groups WHERE ID='$gG->GroupID'");
			$gK = mysqli_fetch_object($getGroupK);
			echo "
	<td width='100' valign='top'>
	<center>
	<a href='../Groups/Group.php?ID=" .
				$gK->ID .
				"'>
	<div style='width:50px;height:50px;padding:5px;border:1px solid #ddd;'>
	<img src='../Groups/GL/" .
				$gK->Logo .
				"' height='50' width='50'>
	</div>
	<b>" .
				$gK->Name .
				"</b>
	</a>
	</td>
	";
			if ($groups >= 4) {
				echo "</tr><tr>";
				$groups = 0;
			}
		}
		echo "
</tr></table>
</center>
</div>
</td>
</tr>
</table>
";
		echo "
<div id='ProfileText'>
Inventory
</div>
<div id='Gradient1'><center><table><tr><a name='OwnedItems'></a>
";
		$counter = 0;
		$Setting = ["PerPage1" => 8];
		$Item = isset($_GET['Item']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Item']))) : '';
		if ($Item < 1) {
			$Item = 1;
		}
		if (!is_numeric($Item)) {
			$Item = 1;
		}
		$Minimum1 = ($Item - 1) * $Setting["PerPage1"];
		//query
		$allusers1 = mysqli_query($connection, "SELECT * FROM Inventory WHERE UserID='" . $ID . "'");
		$num1 = mysqli_num_rows($allusers1);
		$getOwnedItems = mysqli_query($connection, "SELECT * FROM Inventory WHERE UserID='" . $ID . "' ORDER BY ID DESC LIMIT {$Minimum1},  " . $Setting["PerPage1"]);
		while ($gO = mysqli_fetch_object($getOwnedItems)) {
			$getFromStore = mysqli_query($connection, "SELECT * FROM Items WHERE File='" . $gO->File . "'");
			$if_store = mysqli_num_rows($getFromStore);
			if ($if_store == "1") {
				$store = 1;
				$gF = mysqli_fetch_object($getFromStore);
			} else {
				$store = 0;
				$getFromStore = mysqli_query($connection, "SELECT * FROM UserStore WHERE File='" . $gO->File . "'");
				$gF = mysqli_fetch_object($getFromStore);
				$DeleteItem = mysqli_num_rows($getFromStore);
			}
			if ($DeleteItem == "0") {
				mysqli_query($connection, "DELETE FROM Inventory WHERE ID='" . $gO->ID . "'");
			}
			$getCreator = mysqli_query($connection, "SELECT * FROM Users WHERE ID='" . $gF->CreatorID . "'");
			$gC = mysqli_fetch_object($getCreator);
			if ($if_store == 1) {
				$Link = "/Store/Item.php?ID=" . $gF->ID . "";
			} else {
				$Link = "/Store/UserItem.php?ID=" . $gF->ID . "";
			}
			$counter++;
			echo "
	<td style='font-size:11px;' align='left' width='100' valign='top'><a href='" .
				$Link .
				"' border='0' style='color:black;'>
		<div style='border:1px solid #ddd;border-radius:5px;padding:5px;'>
			";
			if ($gF->saletype == "limited") {
				echo "
			<div style='width: 100px; height: 200px; z-index: 3;  background-image: url(../Store/Dir/" .
					$gF->File .
					");'>
				<div style='width: 100px; height: 200px; z-index: 3;  background-image: url(/Images/LimitedWatermark.png);'>";
				if ($gO->SerialNum != "0") {
					echo "<center><font style='color:#253138;font-weight:bold;'>#" . $gO->SerialNum . "/" . $gF->numbersales . "</font></font>";
				}
				echo "</div></div>";
			} else {
				echo "<img src='../Store/Dir/" . $gF->File . "' width='100' height='200'>";
			}
		}
		echo "
			</div>";
		if ($gF) {
			echo "<b>" . $gF->Name . "</b></a><br />";
			if ($gF->CreatorID && isset($gC)) {
				echo "<font style='font-size:10px;'>Creator: <a href='/user.php?ID=" . $gF->CreatorID . "'>" . $gC->Username . "</a><br />";
			} else {
				echo "<font style='font-size:10px;'>Creator: N/A<br />";
			}
		}
		if ($gF && $gF->sell == "yes") {
			if ($gF->saletype == "limited" && $gF->numberstock == "0") {
				echo "
		<font color='green'><b>Was Bux: " .
					$gF->Price .
					"</b></font>
		";
			} else {
				echo "
		<font color='green'><b>Bux: " .
					$gF->Price .
					"</b></font>
		";
			}
		}
		echo "
</td>
";
		if ($counter >= 8) {
			echo "</tr><tr>";
			$counter = 0;
		}
	}
	echo "</tr></table><center>";
	$amount = ceil($num1 / $Setting["PerPage1"]);
	if ($Item > 1) {
		echo '<a href="user.php?ID=' . $ID . '&Item=' . ($Item - 1) . '#OwnedItems">Prev</a> - ';
	}
	echo 'Page ' . $Item . '/' . ceil($num1 / $Setting["PerPage1"]);
	if ($Item < $amount) {
		echo ' - <a href="user.php?ID=' . $ID . '&Item=' . ($Item + 1) . '#OwnedItems">Next</a>';
	}
	echo "
</div>
";
	if ((isset($myU->PowerAdmin) && $myU->PowerAdmin == "true") || (isset($myU->PowerMegaModerator) && $myU->PowerMegaModerator == "true")) {
		echo "
	<br />
	<center><div  style='width:90%;'>
		<a href='user.php?ID=$ID&moderation=scrubname'><b>Scrub Name</b></a>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='user.php?ID=$ID&moderation=scrubdescription'><b>Scrub Description</b></a>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='BanUser.php?ID=$ID'><b>Ban User</b></a>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='ViewOtherAccounts.php?ID=$ID'><b>View Other Accounts</b></a>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='user.php?ID=$ID&moderation=ipban'><b>IP Ban</b></a>
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='iplogs.php?ID=$ID'><b>IP Logs</b></a>
	";
		if ($myU->Username == "Isaac" || $myU->Username == "Ellernate" || $myU->Username == "Niko") {
			echo "
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='user.php?ID=$ID&moderation=fireuser'><b>Fire User</b></a>
		";
		}
		echo "
	</div>
	</center>
	";
		$moderation = isset($_GET['moderation']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['moderation']))) : '';
		if ($moderation == "scrubdescription") {
			mysqli_query($connection, "UPDATE Users SET Description='[ Content Deleted ]' WHERE ID='$ID'");
			header("Location: user.php?ID=$ID");
		}
		if ($myU->Username == "Isaac" || $myU->Username == "Ellernate" || $myU->Username == "Niko") {
			if ($moderation == "fireuser") {
				mysqli_query($connection, "UPDATE Users SET PowerImageModerator='false' WHERE ID='$ID'");
				mysqli_query($connection, "UPDATE Users SET PowerForumModerator='false' WHERE ID='$ID'");
				mysqli_query($connection, "UPDATE Users SET PowerArtist='false' WHERE ID='$ID'");
				mysqli_query($connection, "UPDATE Users SET PowerMegaModerator='false' WHERE ID='$ID'");
				mysqli_query($connection, "UPDATE Users SET PowerAdmin='false' WHERE ID='$ID'");
				//badges
				mysqli_query($connection, "DELETE FROM Badges WHERE Position='Image Moderator' AND UserID='$ID'");
				mysqli_query($connection, "DELETE FROM Badges WHERE Position='Forum Moderator' AND UserID='$ID'");
				mysqli_query($connection, "DELETE FROM Badges WHERE Position='Artist' AND UserID='$ID'");
				mysqli_query($connection, "DELETE FROM Badges WHERE Position='Mega Moderator' AND UserID='$ID'");
				mysqli_query($connection, "DELETE FROM Badges WHERE Position='Administrator' AND UserID='$ID'");
				header("Location: user.php?ID=$ID");
			}
		}
		if ($moderation == "scrubname") {
			mysqli_query($connection, "UPDATE Users SET OriginalName='$gU->Username' WHERE ID='$ID'");
			mysqli_query($connection, "UPDATE Users SET Username='[ Content Deleted $ID ]' WHERE ID='$ID'");
			header("Location: user.php?ID=$ID");
		}
		if ($moderation == "revertname") {
			mysqli_query($connection, "UPDATE Users SET Username='$gU->OriginalName' WHERE ID='$ID'");
			mysqli_query($connection, "UPDATE Users SET OriginalName='' WHERE ID='$ID'");
			header("Location: user.php?ID=$ID");
		}
		if ($moderation == "ipban") {
			if ($Ban == 0) {
				mysqli_query($connection, "INSERT INTO IPBans (IP) VALUES('" . $gU->IP . "')");
				mysqli_query($connection, "INSERT INTO Logs (UserID, Message, Page) VALUES('" . $myU->ID . "','IP Banned $gU->Username','" . $_SERVER['PHP_SELF'] . "')");
				header("Location: user.php?ID=$ID");
			}
			header("Location: user.php?ID=$ID");
		}
	}
}
echo "</div>";
include_once "Footer.php";
?>