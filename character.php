<?php
include_once "Header.php";
if ($User) {
	$Setting = array(
		"PerPage" => 10
	);
	
	if (isset($_GET['Page'])) {
		$Page = $_GET['Page'];
		
		if ($Page < 1 || !is_numeric($Page)) {
			$Page = 1;
		}
	} else {
		$Page = 1;
	}
	
	$Minimum = ($Page - 1) * $Setting["PerPage"];
	
	// query
	$query = "SELECT * FROM Inventory WHERE UserID=? ORDER BY ID";
	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, "s", $myU->ID);
	mysqli_stmt_execute($stmt);
	$allusers = mysqli_stmt_get_result($stmt);
	$num = mysqli_num_rows($allusers);
	$i = 0;
	$Num = ($Page + 8);
	$a = 1;
	$Log = 0;
	if ($User) {
		echo "<center>
		<table width='80%'>
			<tr>
				<td style='width:250px;' valign='top'>
					<div id='ProfileText'> 
						My Character
					</div>
					<div id=''>
						<center>";
		echo "<img src='../../Avatar.php?ID=$myU->ID'>";
		echo "
					</div>
					<br />
					<div id='ProfileText'>
						What I'm Wearing
					</div>
					<div style='overflow-x:auto;width:348px;max-width:348px;'>
						<table><tr>";

		$counter = 0;
		if (!empty($myU->Background)) {
			$counter++;
			$query = "SELECT * FROM Items WHERE File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "s", $myU->Background);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$check1 = mysqli_num_rows($checkifitem);

			if ($check1 == "1") {
				$query = "SELECT * FROM Items WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Background);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);

				$Link = "/Store/Item.php";

			} else {
				$query = "SELECT * FROM UserStore WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Background);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);
				$Link = "/Store/UserItem.php";
			}

			$query = "SELECT * FROM Inventory WHERE UserID=? AND File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "ss", $myU->ID, $gN->File);
			mysqli_stmt_execute($stmt);
			$getID = mysqli_stmt_get_result($stmt);
			$gI = mysqli_fetch_object($getID);
			$echo = "</a><br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'><font color='red'>Remove</font></a>";
			$echo1 = "<br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'>";

			$TheItemName = $gN->Name;
			if (strlen($TheItemName) >= 10) {
				$TheItemName = substr($TheItemName, 0, 10);
				$TheItemName = $TheItemName . "...";
			}
			echo "
					<td valign='top' align='center'>

						$echo1<img src='/Store/Dir/" . $gI->File . "'>
						<br />
						<a href='$Link'>" . $TheItemName . "</a>
						$echo
						";
			$Wear = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear'])));
			$Remove = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove'])));
			$Sess = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess'])));
			$Var = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var'])));

			if ($gI->store == "regular") {
				$query = "SELECT * FROM Items WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			} else {
				$query = "SELECT * FROM UserStore WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			}

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);
					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}
			}
			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}
			}
			echo "
					</td>";

		}

		if (!empty($myU->Eyes)) {
			$counter++;
			$query = "SELECT * FROM Items WHERE File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "s", $myU->Eyes);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$check1 = mysqli_num_rows($checkifitem);

			if ($check1 == "1") {

				$query = "SELECT * FROM Items WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Eyes);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);

				$Link = "/Store/Item.php";

			} else {

				$query = "SELECT * FROM UserStore WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Eyes);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);
				$Link = "/Store/UserItem.php";

			}

			$query = "SELECT * FROM Inventory WHERE UserID=? AND File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "ss", $myU->ID, $gN->File);
			mysqli_stmt_execute($stmt);
			$getID = mysqli_stmt_get_result($stmt);
			$gI = mysqli_fetch_object($getID);
			$echo = "</a><br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'><font color='red'>Remove</font></a>";
			$echo1 = "<br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'>";

			$TheItemName = $gN->Name;
			if (strlen($TheItemName) >= 10) {
				$TheItemName = substr($TheItemName, 0, 10);
				$TheItemName = $TheItemName . "...";
			}
			echo "
					<td valign='top' align='center'>
						$echo1<img src='/Store/Dir/" . $gI->File . "'>
						<br />
						<a href='$Link'>" . $TheItemName . "</a>
						$echo
						";
			$Wear = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear'])));
			$Remove = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove'])));
			$Sess = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess'])));
			$Var = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var'])));

			if ($gI->store == "regular") {
				$query = "SELECT * FROM Items WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			} else {
				$query = "SELECT * FROM UserStore WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			}

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {



					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);

					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}

			}
			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}

			}
			echo "
					</td>";

		}

		if (!empty($myU->Mouth)) {
			$counter++;
			$query = "SELECT * FROM Items WHERE File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "s", $myU->Mouth);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$check1 = mysqli_num_rows($checkifitem);

			if ($check1 == "1") {

				$query = "SELECT * FROM Items WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Mouth);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);

				$Link = "/Store/Item.php";

			} else {

				$query = "SELECT * FROM UserStore WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Mouth);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);
				$Link = "/Store/UserItem.php";

			}

			$query = "SELECT * FROM Inventory WHERE UserID=? AND File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "ss", $myU->ID, $gN->File);
			mysqli_stmt_execute($stmt);
			$getID = mysqli_stmt_get_result($stmt);
			$gI = mysqli_fetch_object($getID);
			$echo = "</a><br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'><font color='red'>Remove</font></a>";
			$echo1 = "<br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'>";

			$TheItemName = $gN->Name;
			if (strlen($TheItemName) >= 10) {
				$TheItemName = substr($TheItemName, 0, 10);
				$TheItemName = $TheItemName . "...";
			}
			echo "
					<td valign='top' align='center'>
						$echo1<img src='/Store/Dir/" . $gI->File . "'>
						<br />
						<a href='$Link'>" . $TheItemName . "</a>
						$echo
						";
			$Wear = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear'])));
			$Remove = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove'])));
			$Sess = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess'])));
			$Var = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var'])));

			if ($gI->store == "regular") {
				$query = "SELECT * FROM Items WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			} else {
				$query = "SELECT * FROM UserStore WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			}

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);

					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}

			}
			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}

			}
			echo "
					</td>";

		}

		if (!empty($myU->Head)) {
			$counter++;
			$query = "SELECT * FROM Items WHERE File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "s", $myU->Head);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$check1 = mysqli_num_rows($checkifitem);

			if ($check1 == "1") {

				$query = "SELECT * FROM Items WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Head);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);

				$Link = "/Store/Item.php";

			} else {

				$query = "SELECT * FROM UserStore WHERE File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "s", $myU->Head);
				mysqli_stmt_execute($stmt);
				$getName = mysqli_stmt_get_result($stmt);
				$gN = mysqli_fetch_object($getName);
				$Link = "/Store/UserItem.php";

			}

			$query = "SELECT * FROM Inventory WHERE UserID=? AND File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "ss", $myU->ID, $gN->File);
			mysqli_stmt_execute($stmt);
			$getID = mysqli_stmt_get_result($stmt);
			$gI = mysqli_fetch_object($getID);
			$echo = "</a><br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'><font color='red'>Remove</font></a>";
			$echo1 = "<br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'>";

			$TheItemName = $gN->Name;
			if (strlen($TheItemName) >= 10) {
				$TheItemName = substr($TheItemName, 0, 10);
				$TheItemName = $TheItemName . "...";
			}
			echo "
					<td valign='top' align='center'>
						$echo1<img src='/Store/Dir/" . $gI->File . "'>
						<br />
						<a href='$Link'>" . $TheItemName . "</a>
						$echo
						";
			$Wear = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear'])));
			$Remove = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove'])));
			$Sess = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess'])));
			$Var = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var'])));

			if ($gI->store == "regular") {
				$query = "SELECT * FROM Items WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			} else {
				$query = "SELECT * FROM UserStore WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			}

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);

					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}

			}
			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}

			}
			echo "
					</td>";

		}

		if (!empty($myU->Neck)) {
			$counter++;
			$query = "SELECT * FROM Items WHERE File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "s", $myU->Neck);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$echo1 = "<br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'>";

			$TheItemName = $gN->Name;
			if (strlen($TheItemName) >= 10) {
				$TheItemName = substr($TheItemName, 0, 10);
				$TheItemName = $TheItemName . "...";
			}
			echo "
					<td valign='top' align='center'>
						$echo1<img src='/Store/Dir/" . $gI->File . "'>
						<br />
						<a href='$Link'>" . $TheItemName . "</a>
						$echo
						";
			$Wear = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear'])));
			$Remove = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove'])));
			$Sess = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess'])));
			$Var = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var'])));

			if ($gI->store == "regular") {
				$query = "SELECT * FROM Items WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			} else {
				$query = "SELECT * FROM UserStore WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			}

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);

					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}

			}
			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}

			}
			echo "
					</td>";

		}

		if (!empty($myU->Shoulder)) {
			$counter++;
			$query = "SELECT * FROM Items WHERE File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "s", $myU->Shoulder);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$echo1 = "<br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'>";

			$TheItemName = $gN->Name;
			if (strlen($TheItemName) >= 10) {
				$TheItemName = substr($TheItemName, 0, 10);
				$TheItemName = $TheItemName . "...";
			}
			echo "
					<td valign='top' align='center'>
						$echo1<img src='/Store/Dir/" . $gI->File . "'>
						<br />
						<a href='$Link'>" . $TheItemName . "</a>
						$echo
						";
			$Wear = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear'])));
			$Remove = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove'])));
			$Sess = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess'])));
			$Var = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var'])));

			if ($gI->store == "regular") {
				$query = "SELECT * FROM Items WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			} else {
				$query = "SELECT * FROM UserStore WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			}

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);

					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}

			}
			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}

			}
			echo "
					</td>";

		}

		if (!empty($myU->Chest)) {
			$counter++;
			$query = "SELECT * FROM Items WHERE File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "s", $myU->Chest);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$echo1 = "<br><a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'>";

			$TheItemName = $gN->Name;
			if (strlen($TheItemName) >= 10) {
				$TheItemName = substr($TheItemName, 0, 10);
				$TheItemName = $TheItemName . "...";
			}
			echo "
					<td valign='top' align='center'>
						$echo1<img src='/Store/Dir/" . $gI->File . "'>
						<br />
						<a href='$Link'>" . $TheItemName . "</a>
						$echo
						";
			$Wear = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear'])));
			$Remove = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove'])));
			$Sess = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess'])));
			$Var = mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var'])));

			if ($gI->store == "regular") {
				$query = "SELECT * FROM Items WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			} else {
				$query = "SELECT * FROM UserStore WHERE ID=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "i", $gI->ItemID);
				mysqli_stmt_execute($stmt);
				$getItemQ = mysqli_stmt_get_result($stmt);
				$gQ = mysqli_fetch_object($getItemQ);
			}

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);

					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}

			}
			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);
					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}

			}
			echo "
					</td>";

		}

		echo "
				</tr>
			</table>
		</div>";

		$Wear = isset($_GET['Wear']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Wear']))) : '';
		$Remove = isset($_GET['Remove']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Remove']))) : '';
		$Sess = isset($_GET['Sess']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Sess']))) : '';
		$Var = isset($_GET['Var']) ? mysqli_real_escape_string($connection, strip_tags(stripslashes($_GET['Var']))) : '';
		
			echo "<td>";
			echo "<a href='$Link'>";
			echo "<img src='/Store/Dir/" . $gI->File . "'>";
			echo "</a><br>";
			echo "<a href='$Link'>" . $gN->Name . "</a><br>";
			echo "<a href='?Page=$Page&Sess=" . $gI->code1 . "&Var=" . $gI->code2 . "&Remove=Yes' style='font-size:10pt;'><font color='red'>Remove</font></a>";
			echo "</td>";
			echo "</tr>";

			if ($Page && $Sess && $Var && $Wear) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);

				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$query = "SELECT * FROM Items WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$check1 = mysqli_num_rows($checkifitem);

					if ($check1 == "1") {

						$cI = mysqli_fetch_object($checkifitem);

					} else {

						$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
						mysqli_stmt_execute($stmt);
						$checkifitem = mysqli_stmt_get_result($stmt);
						$cI = mysqli_fetch_object($checkifitem);

					}

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);

					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {
						echo $code1;
						echo " " . $code2;
						echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
					}

				}

			}

			if ($Page && $Sess && $Var && $Remove) {

				$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
				mysqli_stmt_execute($stmt);
				$getitem = mysqli_stmt_get_result($stmt);
				$gi = mysqli_fetch_object($getitem);
				$gi1 = mysqli_num_rows($getitem);
				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}
				if ($gi1 == 0) {

					echo "Error";

				} else if ($gi1 >= 1) {

					$code1 = sha1($cI->File);
					$code2 = sha1($myU->ID);

					if ($gi->code1 == $code1 && $gi->code2 = $code2) {

						$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
						$stmt = mysqli_prepare($connection, $query);
						mysqli_stmt_bind_param($stmt, "s", $myU->ID);
						mysqli_stmt_execute($stmt);
						header("Location: ../../My/Character/?Page=$Page");

					} else {

						header("Location: ../../My/Character/?Page=$Page");

					}

				}

			}

		}

		echo "
			</table>
		</div>";

		if ($Page && $Sess && $Var && $Wear) {

			$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
			mysqli_stmt_execute($stmt);
			$getitem = mysqli_stmt_get_result($stmt);
			$gi = mysqli_fetch_object($getitem);
			$gi1 = mysqli_num_rows($getitem);

			if ($gi1 == 0) {

				echo "Error";

			} else if ($gi1 >= 1) {

				$query = "SELECT * FROM Items WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$check1 = mysqli_num_rows($checkifitem);

				if ($check1 == "1") {

					$cI = mysqli_fetch_object($checkifitem);

				} else {

					$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
					mysqli_stmt_execute($stmt);
					$checkifitem = mysqli_stmt_get_result($stmt);
					$cI = mysqli_fetch_object($checkifitem);

				}

				$code1 = sha1($cI->File);
				$code2 = sha1($myU->ID);

				if ($gi->code1 == $code1 && $gi->code2 = $code2) {

					$query = "UPDATE Users SET $gi->Type=? WHERE ID=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "ss", $gi->File, $myU->ID);
					mysqli_stmt_execute($stmt);
					header("Location: ../../My/Character/?Page=$Page");

				} else {
					echo $code1;
					echo " " . $code2;
					echo "<br />" . $gi->code1 . " then " . $gi->code2 . "";
				}

			}

		}

		if ($Page && $Sess && $Var && $Remove) {

			$query = "SELECT * FROM Inventory WHERE code1=? AND code2=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "ss", $Sess, $Var);
			mysqli_stmt_execute($stmt);
			$getitem = mysqli_stmt_get_result($stmt);
			$gi = mysqli_fetch_object($getitem);
			$gi1 = mysqli_num_rows($getitem);
			$query = "SELECT * FROM Items WHERE ID=? AND File=?";
			$stmt = mysqli_prepare($connection, $query);
			mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
			mysqli_stmt_execute($stmt);
			$checkifitem = mysqli_stmt_get_result($stmt);
			$check1 = mysqli_num_rows($checkifitem);

			if ($check1 == "1") {

				$cI = mysqli_fetch_object($checkifitem);

			} else {

				$query = "SELECT * FROM UserStore WHERE ID=? AND File=?";
				$stmt = mysqli_prepare($connection, $query);
				mysqli_stmt_bind_param($stmt, "is", $gI->ItemID, $gI->File);
				mysqli_stmt_execute($stmt);
				$checkifitem = mysqli_stmt_get_result($stmt);
				$cI = mysqli_fetch_object($checkifitem);

			}
			if ($gi1 == 0) {

				echo "Error";

			} else if ($gi1 >= 1) {

				$code1 = sha1($cI->File);
				$code2 = sha1($myU->ID);

				if ($gi->code1 == $code1 && $gi->code2 = $code2) {

					$query = "UPDATE Users SET $gi->Type='' WHERE ID=?";
					$stmt = mysqli_prepare($connection, $query);
					mysqli_stmt_bind_param($stmt, "s", $myU->ID);
					mysqli_stmt_execute($stmt);
					header("Location: ../../My/Character/?Page=$Page");

				} else {

					header("Location: ../../My/Character/?Page=$Page");

				}

			}

		}
		
		echo "</td>";
		
		if ($counter >= 5) {
			echo "</tr><tr>";
			$counter = 0;
		}
	}
		
	echo "</tr></table>";
		
		
$amount=ceil($num / $Setting["PerPage"]);

if ($Page > 1) {
	echo '<a href="../../My/Character/?Page='.($Page-1).'">Previous</a> - ';
}
echo '   Page '.$Page.' of '.(ceil($num / $Setting["PerPage"]));
if ($Page < ($amount)) {
	echo ' - <a href="../../My/Character/?Page='.($Page+1).'">Next</a>';
} else {
	echo "<a name='bottom'></a>";
	echo "</div></td></tr></table><br><br>";
}

include_once "Footer.php";
?>