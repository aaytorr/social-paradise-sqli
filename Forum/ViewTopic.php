<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/Header.php");
echo "</div>";

$ID = mysqli_real_escape_string($connection, strip_tags($_GET['ID'] ?? ''));
$getTopic = mysqli_query($connection, "SELECT * FROM Topics WHERE ID='$ID'");
$TopicExist = mysqli_num_rows($getTopic);

$Setting = array(
	"PerPage" => 20
);

$Page = isset($_GET['Page']) ? mysqli_real_escape_string($connection, strip_tags($_GET['Page'])) : 1;
$Page = max(1, intval($Page));

$Minimum = ($Page - 1) * $Setting["PerPage"];

// query
$allusers = mysqli_query($connection, "SELECT * FROM Threads WHERE tid='$ID'");
$num = mysqli_num_rows($allusers);
$Num = ($Page + 8);

if ($TopicExist == 0) {
	echo "<b>This topic doesn't exist!</b>";
	exit;
}

if ($User) {
	echo "<div style='text-align:right;'><a href='NewThread.php?ID=$ID'>New Thread</a></div>";
}

echo "
	<table style='padding:10px;width:100%;padding:10px;background:#ECECEC;border:1px solid rgba(50, 50, 50, 0.75);'>
		<tr>
			<td width='500'>
				<b>Title</b>
			</td>
			<td width='150'>
				<b>Creator</b>
			</td>
			<td width='150'>
				<b>Last Reply</b>
			</td>
			<td width='75'>
				<b>Replies</b>
			</td>
		</tr>
	</table>
";

echo "<div style='border:1px solid rgba(50, 50, 50, 0.75);border-top:0;'>";

$getStickies = mysqli_query($connection, "SELECT * FROM Threads WHERE tid='$ID' AND Type='sticky'");
$s_counter = 0;
while ($gS = mysqli_fetch_object($getStickies)) {
	$s_counter++;
	$scss = ($s_counter % 2 == 0) ? 'background:#F0F0F0;' : 'background:#F3F3F3;';

	echo "
		<table width='100%' cellspacing='0' cellpadding='0' style='border-top:0;padding:10px; padding-right:0px; border-collapse:collapse;border-spacing:0;'>
			<tr style='" . $scss . "height:50px; border:0; padding:0;'>
				<td width='500'>
					<a href='ViewThread.php?ID=" . $gS->ID . "'><div style='margin-left:10px;color:blue;font-weight:bold;'>" . $gS->Title . "</div></a>
				</td>
				<td width='150'>
					";
	$getPoster = mysqli_query($connection, "SELECT * FROM Users WHERE ID='$gS->PosterID'");
	$gP = mysqli_fetch_object($getPoster);
	echo "<a href='/User.php?ID=$gP->ID'>$gP->Username</a>";
	echo "
				</td>
				<td width='150'>
					";
	$lastReply = mysqli_query($connection, "SELECT * FROM Replies WHERE tid='$gS->ID' ORDER BY ID DESC LIMIT 1");
	$lR = mysqli_num_rows($lastReply);
	if ($lR == 0) {
		$LastReply = "No One";
	} else {
		$lR = mysqli_fetch_object($lastReply);
		$getPoster = mysqli_query($connection, "SELECT * FROM Users WHERE ID='$lR->PosterID'");
		$gP = mysqli_fetch_object($getPoster);
		$LastReply = "<a href='../user.php?ID=$gP->ID'>$gP->Username</a>";
	}
	echo "
					$LastReply
					";
	$lR = mysqli_num_rows($lastReply);
	echo "
				</td>
				<td width='75'>
					$lR
				</td>
			</tr>
		</table>
	";
}
echo "</div>";
echo "<br />";
echo "<div style='border:1px solid rgba(50, 50, 50, 0.75);'>";

$getThreads = mysqli_query($connection, "SELECT * FROM Threads WHERE tid='$ID' AND Type='regular' ORDER BY bump DESC LIMIT {$Minimum},  " . $Setting["PerPage"]);
$t_counter = 0;
while ($gT = mysqli_fetch_object($getThreads)) {
	$t_counter++;
	$extracss = ($t_counter % 2 == 0) ? 'background:#E6E6E6;' : 'background:#E0E0E0;';

	$if_statement = ($t_counter == 20) ? "" : "border-bottom:1px solid rgba(50, 50, 50, 0.75);";

	echo "
		<table id='aBForum' width='100%' cellspacing='0' cellpadding='0' style='border-top:0;padding:10px; padding-right:0px; border-collapse:collapse;" . $if_statement . "'>
			<tr style='" . $extracss . "height:50px; border:0; padding:0;'>
				<td width='500' style='padding-left:10px;'>
					<a href='ViewThread.php?ID=" . $gT->ID . "'>$gT->Title</a>
				</td>
				<td width='150'>
					";
	$getPoster = mysqli_query($connection, "SELECT * FROM Users WHERE ID='$gT->PosterID'");
	$gP = mysqli_fetch_object($getPoster);
	echo "<a href='/User.php?ID=$gP->ID'>$gP->Username</a>";
	echo "
				</td>
				<td width='150'>
					";
	$lastReply = mysqli_query($connection, "SELECT * FROM Replies WHERE tid='$gT->ID' ORDER BY ID DESC LIMIT 1");
	$lR = mysqli_num_rows($lastReply);
	if ($lR == 0) {
		$LastReply = "No One";
	} else {
		$lR = mysqli_fetch_object($lastReply);
		$getPoster = mysqli_query($connection, "SELECT * FROM Users WHERE ID='$lR->PosterID'");
		$gP = mysqli_fetch_object($getPoster);
		$LastReply = "<a href='../user.php?ID=$gP->ID'>$gP->Username</a>";
	}
	echo "
					$LastReply
					";
	$lastReply1 = mysqli_query($connection, "SELECT * FROM Replies WHERE tid='$gT->ID' ORDER BY ID DESC");
	$lR = mysqli_num_rows($lastReply1);
	if ($lR > 0) {
		$R = mysqli_query($connection, "SELECT * FROM Replies WHERE tid='$gT->ID' ORDER BY ID DESC LIMIT 1");
		$R = mysqli_fetch_object($R);
	}
	echo "
				</td>
				<td width='75'>
					";
	if ($lR == 0) {
		echo "$lR";
	} else {
		echo "<a href='../Forum/ViewThread.php?ID=$gT->ID#$R->ID'>$lR</a>";
	}
	echo "
				</td>
			</tr>
		</table>
	";
}

echo "</tr></table></div><center>";
$amount = ceil($num / $Setting["PerPage"]);
if ($Page > 1) {
	echo '<a href="ViewTopic.php?ID=' . $ID . '&Page=' . ($Page - 1) . '">Prev</a> - ';
}
echo '' . $Page . '/' . (ceil($num / $Setting["PerPage"]));
if ($Page < ($amount)) {
	echo ' - <a href="ViewTopic.php?ID=' . $ID . '&Page=' . ($Page + 1) . '">Next</a>';
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/Footer.php");
?>