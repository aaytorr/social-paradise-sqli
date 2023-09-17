<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/Header.php";

if ($myU->PowerAdmin !== "true") {
	header("Location: /index.php");
	exit;
}

$navigationLinks = [
	['label' => 'Main Configuration', 'url' => '?tab=configuration'],
	['label' => 'Grant Power to User', 'url' => '?tab=power'],
];

// Additional link for specific users
if (in_array($myU->Username, ["Isaac", "Ellernate", "Niko"])) {
	$navigationLinks[] = ['label' => 'Give Item to User', 'url' => '?tab=item'];
}

$navigationLinks[] = ['label' => 'Logs', 'url' => '?tab=logs'];

echo <<<HTML
<table cellspacing='0' cellpadding='0' width='100%'>
	<tr>
		<td width='250' valign='top'>
			<center>
				<div id='ProfileText'>
					<div align='left'>Panel Navigation</div>
				</div>
				<div align='left'>
					<div id='aP'>
HTML;

foreach ($navigationLinks as $link) {
	echo "<a href='{$link['url']}'>{$link['label']}</a>";
}

echo <<<HTML
					</div>
					<br />
					<div id='ProfileText'>
						Time
					</div>
HTML;

include_once "time.php";
include_once "timesource.php";

echo <<<HTML
					<br />
HTML;
$onlineUsersQuery = "SELECT * FROM Users WHERE ? < expireTime";
$stmt = mysqli_prepare($connection, $onlineUsersQuery);
mysqli_stmt_bind_param($stmt, "i", $date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$onlineUsersCount = mysqli_num_rows($result);

echo <<<HTML
					<div id='ProfileText'>Statistics</div>
					<div title='' style='color:black;'>{$onlineUsersCount} users are online</div>
					<br />
				</div>
		</td>
		<td style='padding-left:15px;' valign='top'>
			<div style='border-radius:5px;'>
HTML;

$tab = SecurePost($_GET['tab']);
if (!$tab) {
	echo "<b>Please navigate in the navigation bar.</b>";
} else {
	include_once "$tab.php";
}

echo <<<HTML
			</div>
		</td>
	</tr>
</table>
HTML;

include_once $_SERVER['DOCUMENT_ROOT'] . "/Footer.php";
