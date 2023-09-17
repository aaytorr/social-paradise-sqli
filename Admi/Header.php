<?php
include_once "connection.php";
#include_once "../filter.php";
?>
<html>
	<head>
		<title>Social-Paradise - Administration</title>
		<link rel='stylesheet' href='style.css'>
	</head>
	<body>
		<table cellspacing='0' cellpadding='0' width='100%' height='100%'>
			<tr>
				<td valign='top' style='width:320px;'>
					<div style='height:100%;width:300px;background:#eee;border-right:1px solid #aaa;'>
						<center><img src='../Images/SPNewLogo.png' width='120'></center>
						<div style='padding-top:15px;padding-left:15px;'>
							<b>Site Control</b>
							<br />
							 &nbsp; <a href='?page=news_bar'>Worldwide News Bar</a>
							 <br />
							 <br />
							 <b>Users</b>
							 <br />
							 &nbsp; <a href='?page=user_search'>Search Users</a>
						</div>
					</div>
				</td>
				<td valign='top' style='padding-top:30px;'>
					<?php
					$page = mysql_real_escape_string(strip_tags(stripslashes($_GET['page'])));
					if ($page) {
					if ($page != "index") {
					if ($page != "Header") {
					if (file_exists($page.".php")) {
					include_once($page.".php");
					}
					else {
					echo "<b>Page not found.</b>";
					}
					}
					else {
					echo "<b>Error.</b>";
					}
					}
					else {
					echo "<b>Error.</b>";
					}
					}
					?>
				</td>
			</tr>
		</table>
