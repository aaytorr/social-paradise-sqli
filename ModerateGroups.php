<?php
	include_once "Header.php";
	
	if (!$User) {
		header("Location: index.php");
	}
	
	if ($myU->PowerAdmin == "true" || $myU->PowerMegaModerator == "true") {
		$getGroups = mysqli_query($connection, "SELECT * FROM GroupsPending ORDER BY ID DESC");
		
		echo "<table><tr>";
		
		while ($gG = mysqli_fetch_object($getGroups)) {
			$getCreator = mysqli_query($connection, "SELECT * FROM Users WHERE ID='".$gG->OwnerID."'");
			$gC = mysqli_fetch_object($getCreator);
			
			echo "
				<td>
					<center>
					<div style='border:1px solid #ddd;padding:5px;height:100px;width:100px;'>
						<img src='../Groups/GL/".$gG->Logo."' width='100' height='100'>
					</div>
					<b>".$gG->Name."</b>
					<br />
					<div align='left'>
						<b>Creator:</b> ".$gC->Username."
					</div>
					<br />
					<a href='?GroupID=".$gG->ID."&Action=Accept'><font color='green'><b>Accept</b></font></a> / <a href='?GroupID=".$gG->ID."&Action=Deny'><font color='red'><b>Deny</b></font></a>
				</td>
			";
		}
		
		$GroupID = isset($_GET['GroupID']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['GroupID']))) : "";
		$Action = isset($_GET['Action']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Action']))) : "";
		
		if ($GroupID && $Action) {
			// Check if group id is real
			$checkGroup = mysqli_query($connection, "SELECT * FROM GroupsPending WHERE ID='$GroupID'");
			$GroupActive = mysqli_num_rows($checkGroup);
			
			if ($GroupActive > 0) {
				$getGroup = mysqli_query($connection, "SELECT * FROM GroupsPending WHERE ID='$GroupID'");
				$gG = mysqli_fetch_object($getGroup);
				
				if ($Action == "Accept") {
					$Name = isset($gG->Name) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($gG->Name))) : "";
					
					// Insert into main group database and insert member into the group
					
					// Prepare the statement
					$insertGroupStmt = mysqli_prepare($connection, "INSERT INTO Groups (Name, Description, OwnerID, Logo) VALUES (?, ?, ?, ?)");
					
					// Bind the parameters
					mysqli_stmt_bind_param($insertGroupStmt, "ssss", $Name, $gG->Description, $gG->OwnerID, $gG->Logo);
					
					// Execute the statement
					mysqli_stmt_execute($insertGroupStmt);
					
					// Delete from temp
					mysqli_query($connection, "DELETE FROM GroupsPending WHERE ID='$gG->ID'");
					
					$getNewGroup = mysqli_query($connection, "SELECT * FROM Groups WHERE OwnerID='$gG->OwnerID' AND Name='".mysqli_real_escape_string($connection, stripslashes(strip_tags($Name)))."' AND Description='$gG->Description'");
					$gN = mysqli_fetch_object($getNewGroup);
					
					// Make the first user
					mysqli_query($connection, "INSERT INTO GroupMembers (GroupID, UserID) VALUES ('".$gN->ID."','".$gG->OwnerID."')");
					
					header("Location: ModerateGroups.php");
				}
				elseif ($Action == "Deny") {
					// Delete the group and send a private message saying the group was declined
					
					mysqli_query($connection, "DELETE FROM GroupsPending WHERE Name='".$gG->Name."' AND Description='".$gG->Description."' AND OwnerID='".$gG->OwnerID."'");
					header("Location: ModerateGroups.php");
				}
			}
		}
	}
	else {
		header("Location: ../index.php");
	}
	
	echo "</tr></table>";
	
	include_once "Footer.php";
?>