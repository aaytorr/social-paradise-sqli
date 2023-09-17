<?php

	include_once "../Header.php";
	
	if ($User) {
	
		echo "
		<form action='' method='POST'>
			<input type='submit' name='CreateGroup' value='Create Group' id='buttonsmall'>
		</form>
		";
		
		$CreateGroup = isset($_POST['CreateGroup']) ? mysqli_real_escape_string($connection, strip_tags($_POST['CreateGroup'])) : '';
		
		if ($CreateGroup) {
			echo('<meta http-equiv="refresh" content="0;URL=/Groups/CreateGroup.php">');
			die();
		}
		
	}
	
	$Setting = array(
		"PerPage" => 10
	);
	
	$Page = isset($_GET['Page']) ? $_GET['Page'] : 1;
	$Page = ($Page < 1 || !is_numeric($Page)) ? 1 : $Page;
	$Minimum = ($Page - 1) * $Setting["PerPage"];
	
	// Count total items
	$getall = mysqli_query($connection, "SELECT COUNT(*) AS count FROM Items");
	$row = mysqli_fetch_assoc($getall);
	$all = $row['count'];
	
	// Count total groups
	$allusers = mysqli_query($connection, "SELECT COUNT(*) AS count FROM Groups");
	$row = mysqli_fetch_assoc($allusers);
	$num = $row['count'];
	
	$i = 0;
	$Num = ($Page + 8);
	$a = 1;
	$Log = 0;
	
	echo "
		<div id='LargeText'>
			Browse Groups
		</div>
		<br />
	";
	
	$getGroups = mysqli_prepare($connection, "SELECT * FROM Groups ORDER BY GroupMembers DESC LIMIT ?, ?");
	mysqli_stmt_bind_param($getGroups, "ii", $Minimum, $Setting["PerPage"]);
	mysqli_stmt_execute($getGroups);
	$result = mysqli_stmt_get_result($getGroups);
	
	while ($gG = mysqli_fetch_object($result)) {
		$getGroupMembers = mysqli_prepare($connection, "SELECT * FROM GroupMembers WHERE GroupID=?");
		mysqli_stmt_bind_param($getGroupMembers, "i", $gG->ID);
		mysqli_stmt_execute($getGroupMembers);
		$groupMembers = mysqli_stmt_get_result($getGroupMembers);
		$gM = mysqli_num_rows($groupMembers);
		$gK = mysqli_fetch_object($groupMembers);
		
		$getGroup = mysqli_prepare($connection, "SELECT * FROM Groups WHERE ID=?");
		mysqli_stmt_bind_param($getGroup, "i", $gK->GroupID);
		mysqli_stmt_execute($getGroup);
		$groupResult = mysqli_stmt_get_result($getGroup);
		$getG = mysqli_fetch_object($groupResult);
		
		$groupOwner = mysqli_prepare($connection, "SELECT * FROM Users WHERE ID=?");
		mysqli_stmt_bind_param($groupOwner, "i", $getG->OwnerID);
		mysqli_stmt_execute($groupOwner);
		$ownerResult = mysqli_stmt_get_result($groupOwner);
		$gO = mysqli_fetch_object($ownerResult);
		
		echo "
		<style>
		#group:hover {
		background:#eee;
		}
		</style>
		<table id='group'>
			<tr>
				<td valign='top'>
					<a href='Group.php?ID=".$getG->ID."'>
					<div style='width:75px;height:75px;border:1px solid #ddd;'>
						<img src='../Groups/GL/".$getG->Logo."' height='75' width='75'>
					</div>
					</a>
				</td>
				<td valign='top' width='600'>
					<a href='Group.php?ID=".$getG->ID."' style='text-decoration:none;'>
					<font style='font-size:12px;'>".$getG->Name."</font>
					<br />
					<font style='color:#555;font-size:10px;'>
					".$getG->Description."
					</font>
					</a>
				</td>
				<td valign='top' style='font-size:12px;padding-left:100px;'>
					Group Owner: ".$gO->Username."
					<br />
					Members: ".$gM."
				</td>
			</tr>
		</table>
		<br />
		";
	}
	
	echo "<center>";
	$amount = ceil($num / $Setting["PerPage"]);
	
	if ($Page > 1) {
		echo '<a href="index.php?Page='.($Page - 1).'">Prev</a> - ';
	}
	
	echo ''.$Page.'/'.(ceil($num / $Setting["PerPage"]));
	
	if ($Page < $amount) {
		echo ' - <a href="index.php?Page='.($Page + 1).'">Next</a>';
	}
	
	include_once "../Footer.php";
?>