<?php
// Configuration
$perPage = 12;
$currentPage = (int)($_GET['Page'] ?? 1);
$currentPage = max(1, $currentPage);

// Pagination calculation
$totalLogsQuery = "SELECT COUNT(*) as total FROM Logs";
$totalLogsResult = mysqli_query($connection, $totalLogsQuery);
$totalLogs = mysqli_fetch_assoc($totalLogsResult)['total'];
$totalPages = ceil($totalLogs / $perPage);
$currentPage = min($currentPage, $totalPages);

// Limit the logs query
$offset = ($currentPage - 1) * $perPage;
$getLogsQuery = "SELECT * FROM Logs ORDER BY ID DESC LIMIT ?, ?";
$stmt = mysqli_prepare($connection, $getLogsQuery);
mysqli_stmt_bind_param($stmt, "ii", $offset, $perPage);
mysqli_stmt_execute($stmt);
$getLogs = mysqli_stmt_get_result($stmt);

// Output the logs
?>
<form action='' method='POST'>
	<table>
		<tr>
			<td>
				<?php if ($currentPage > 1): ?>
					<a href="/Admin/?tab=logs&Page=<?php echo ($currentPage - 1); ?>">Prev</a> -
				<?php endif; ?>
				<?php echo $currentPage . '/' . $totalPages; ?>
				<?php if ($currentPage < $totalPages): ?>
					- <a href="/Admin/?tab=logs&Page=<?php echo ($currentPage + 1); ?>">Next</a>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</form>
<?php
while ($gL = mysqli_fetch_object($getLogs)) {
	$getActionQuery = "SELECT * FROM Users WHERE ID=?";
	$stmt = mysqli_prepare($connection, $getActionQuery);
	mysqli_stmt_bind_param($stmt, "i", $gL->UserID);
	mysqli_stmt_execute($stmt);
	$getActionResult = mysqli_stmt_get_result($stmt);
	$gA = mysqli_fetch_object($getActionResult);
	?>
	<table width='100%'>
		<tr>
			<td width='75'>
				<center>
					<a href='/user.php?ID=<?php echo $gA->ID; ?>'>
						<img src='../Avatar.php?ID=<?php echo $gA->ID; ?>' height='100'>
						<br />
						<?php echo $gA->Username; ?>
					</a>
				</center>
			</td>
			<td valign='top'>
				<?php echo $gL->Message; ?>
				<br />
				<br />
				<?php echo $gA->IP; ?>
			</td>
		</tr>
	</table>
	<br />
<?php
}
?>
<form action='' method='POST'>
	<table>
		<tr>
			<td>
				<?php if ($currentPage > 1): ?>
					<a href="/Admin/?tab=logs&Page=<?php echo ($currentPage - 1); ?>">Prev</a> -
				<?php endif; ?>
				<?php echo $currentPage . '/' . $totalPages; ?>
				<?php if ($currentPage < $totalPages): ?>
					- <a href="/Admin/?tab=logs&Page=<?php echo ($currentPage + 1); ?>">Next</a>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</form>