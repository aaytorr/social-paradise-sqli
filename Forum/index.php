<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/Header.php");

if (!$User) {
	header("Location: /index.php");
	exit;
}

$getTopics = mysqli_query($connection, "SELECT * FROM Topics ORDER BY ID");

echo "
<table style='padding:10px;width:100%;padding:10px;background:#ECECEC;border:1px solid rgba(50, 50, 50, 0.75);'>
	<tr>
		<td width='850'>
			<b>Topic Name</b>
		</td>
		<td>
			<b>Number of Posts</b>
		</td>
	</tr>
</table>
";

while ($gT = mysqli_fetch_object($getTopics)) {
	echo "
	<table style='width:100%;padding:10px;background:#F3F3F3;border:1px solid rgba(50, 50, 50, 0.75);border-top:0;'>
		<tr>
			<td width='850'>
				<a href='/Forum/ViewTopic.php?ID=$gT->ID' style='font-size:13px;'><b>".$gT->TopicName."</b></a>
				<br />
				<font style='font-size:8pt'>".$gT->TopicDescription."</font>
			</td>
			<td style='padding-left:40px;'>
		";

	$stmt = mysqli_prepare($connection, "SELECT * FROM Threads WHERE tid = ?");
	mysqli_stmt_bind_param($stmt, "i", $gT->ID);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$Posts = mysqli_num_rows($result);
	
	echo "$Posts
			</td>
		</tr>
		<tr height='20px;'></tr>
	</table>
	";
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/Footer.php");
?>