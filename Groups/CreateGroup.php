<?php

include_once "../Header.php";

if (!$User) {
	header("Location: ../index.php");
	exit();
}

if ($myU->Premium == 1) {
	$Price = 50;
} else {
	$Price = 100;
}

echo "
	<form action='' enctype='multipart/form-data' method='POST'>
		<b>Create Group <font color='green'>($Price BUX)</font></b>
		<table>
			<tr>
				<td>
					<b>Group Name:</b>
				</td>
				<td>
					<input type='text' name='GroupName'>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>
					<b>Group Description:</b>
					<br />
					<textarea style='width:400px;height:100px;' name='Description'></textarea>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>
					<b>Logo:</b>
				</td>
				<td>
					<input type='file' name='image' accept='image/*'>
				</td>
			</tr>
		</table>
		<table> 
			<tr>
				<td>
					<input type='submit' name='Submit' value='Create Group' id='buttonsmall'>
				</td>
			</tr>
		</table>
	</form>
";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$GroupName = mysqli_real_escape_string($connection, strip_tags(stripslashes($_POST['GroupName'])));
	$Description = mysqli_real_escape_string($connection, strip_tags(stripslashes($_POST['Description'])));
	$Submit = mysqli_real_escape_string($connection, strip_tags(stripslashes($_POST['Submit'])));

	if ($Submit) {
		if (!$GroupName || !$Description) {
			properdie("<b>Please fill in all fields.</b>");
		}
		
		$filee = $_FILES['image'];
		
		if (empty($filee['tmp_name'])) {
			properdie("<b>Please select an image to upload.</b>");
		}

		$GroupName = filter($GroupName);
		$Description = filter($Description);

		$checkGroupExist = mysqli_prepare($connection, "SELECT * FROM Groups WHERE Name=?");
		mysqli_stmt_bind_param($checkGroupExist, "s", $GroupName);
		mysqli_stmt_execute($checkGroupExist);
		mysqli_stmt_store_result($checkGroupExist);
		$GroupExist = mysqli_stmt_num_rows($checkGroupExist);

		$checkGroupExist1 = mysqli_prepare($connection, "SELECT * FROM GroupsPending WHERE Name=?");
		mysqli_stmt_bind_param($checkGroupExist1, "s", $GroupName);
		mysqli_stmt_execute($checkGroupExist1);
		mysqli_stmt_store_result($checkGroupExist1);
		$GroupExist1 = mysqli_stmt_num_rows($checkGroupExist1);

		if ($GroupExist > 0 || $GroupExist1 > 0) {
			// Group name already exists, kill it
			properdie("<b>That group name already exists, sorry!</b>");
		}

		if ($GroupExist == 0 || $GroupExist1 == 0) {
			if ($myU->Bux >= $Price) {
				$NumberInGroups = mysqli_prepare($connection, "SELECT * FROM GroupMembers WHERE UserID=?");
				mysqli_stmt_bind_param($NumberInGroups, "i", $myU->ID);
				mysqli_stmt_execute($NumberInGroups);
				mysqli_stmt_store_result($NumberInGroups);
				$NumberInGroups = mysqli_stmt_num_rows($NumberInGroups);

				if ($myU->Premium == "1") {
					$Groups = 100;
				} else {
					$Groups = 5;
				}

				if ($NumberInGroups < $Groups && isset($_FILES['image']['tmp_name'])) {
					$FileName = $_FILES['image']['name'];
					$_FILES['image']['name'] = sha1($FileName . time() . ".png");
					$target = "GL/";
					$target = $target . basename($_FILES['image']['name']);
					$ok = 1;
					
					$fileType = exif_imagetype($_FILES['image']['tmp_name']);
					if ($fileType !== false && $_FILES["image"]["type"] == "image/png" && $_FILES["image"]["size"] < 1000000000000000) {
						if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
							$insertGroup = mysqli_prepare($connection, "INSERT INTO GroupsPending (Name, Description, OwnerID, Logo) VALUES (?, ?, ?, ?)");
							mysqli_stmt_bind_param($insertGroup, "ssis", $GroupName, $Description, $myU->ID, $_FILES['image']['name']);

							if (mysqli_stmt_execute($insertGroup)) {
								$updateUserBux = mysqli_prepare($connection, "UPDATE Users SET Bux = Bux - ? WHERE ID = ?");
								mysqli_stmt_bind_param($updateUserBux, "ii", $Price, $myU->ID);
								mysqli_stmt_execute($updateUserBux);

								echo "<b>Group created successfully, your name, description, and logo are under review. This should take up to 10 minutes.</b>";
							} else {
								echo "<b>Fatal error. Your account has not been charged.</b>";
							}
						}
					}
				} else {
					echo("<b>Sorry, but you can only be in $Groups groups.</b>");
				}
			} else {
				echo("<b>Insufficient funds.</b>");
			}
		} else {
			echo("<b>That group name already exists.</b>");
		}
	}
}

include_once "../Footer.php";
?>