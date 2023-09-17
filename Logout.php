<?php
include_once "Global.php";
if (isset($User)) {
	$expires = date('Y-m-d H:i:s');
	$stmt = mysqli_prepare($connection, "UPDATE Users SET expireTime=? WHERE Username=?");
	mysqli_stmt_bind_param($stmt, "ss", $expires, $User);
	mysqli_stmt_execute($stmt);
}
session_destroy();
header("Location: index.php");
?>