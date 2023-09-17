<?php
include_once "Global.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<title>Social-Paradise</title>
	<link rel="stylesheet" href="../../Base/Style/Main.css">
	<link rel="stylesheet" href="../../Base/Themes/Default/default.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../../Base/Themes/Pascal/pascal.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../../Base/Themes/Orman/orman.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../../Base/Style/Nivo.css" type="text/css" media="screen" />
	<?php
		$currentMonth = date('m');
		if ($currentMonth >= 12 || $currentMonth <= 2) {
			echo('<script type="text/javascript" src="../snowstorm-min.js"></script>');
		}
	?>
	<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="http://www.tumuski.com/library/Nibbler/Nibbler.js"></script>
	<script>
		$(document).ready(function(){
			$('.redirect').click(function(){
				window.location = $(this).attr('redirect');
			});
		});
	</script>
</head>
<body>
	<header>
	<div align='center'>
		<div id='Header'>
			<table cellspacing='0' cellpadding='0' width='1100'>
				<tr>
					<td width='200'>
						<img src='../../Images/SPNewLogo.png'  height='40'>
					</td>
					<td>
						<table cellspacing='0' cellpadding='0'>
							<tr>
								<td>
									<a href='../../'>Home</a>
								</td>
								<td>
									<a href='../../Users/'>Users</a>
								</td>
								<td>
									<a href='../../Shop/'>Shop</a>
								</td>
								<td>
									<a href='../../UserShop/'>User Shop</a>
								</td>
								<td>
									<a href='../../Groups/'>Groups</a>
								</td>
								<td>
									<a href='../../Forum/'>Forum</a>
								</td>
								<td>
									<a href='../../upgrades.php'>Upgrades</a>
								</td>
								<?php
									if (isset($myU->ID)) {
										// echo "
										// <td>
										// 	<a href='../TradeSystem'>Trade</a>
										// </td>
										// ";
									}
								?>
								<?php
									if (isset($myU->PowerAdmin)) {
										if ($myU->PowerAdmin == "true") {
											echo "
												<td>
													<a href='../../Admin/?tab=configuration'>Admin</a>
												</td>
											";
										}
									}
									if (isset($myU->ID)) {
										echo "
											<td style='padding-left:35px;'>
												<a href='../../user.php?ID=$myU->ID'>$User</a>
											</td>
											<td>
												<a href='#'><font color='#2E9412'>$Bux Bux</font></a>
											</td>
											<td>
												<a href='../../Logout.php'>Logout</a>
											</td>
										";
									} else {
										echo "
											<td style='padding-left:35px;'>
												<a href='../../Login.php'>Login</a>
											</td>
											<td>
												<a href='../../register.php'>Register</a>
											</td>
										";
									}
								?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>

		<?php
		if (isset($myU->ID)) {
			echo "<center>
			<div id='SubBar'>
				<table cellspacing='0' cellpadding='0'>
					<tr>
						<td>
							<a href='../../My/Account/'>Account</a>
						</td>
						<td>
							<a href='../../user.php?ID=$myU->ID'>Public Profile</a>
						</td>
						<td>
							<a href='../../My/Character/'>Character</a>
						</td>
						<td>
							<a href='../../Wall/'>Wall</a>
						</td>
						<td>
							<a href='../../My/FRs/'>Friend Requests ($FriendsPending)</a>
						</td>
						<td>
							<a href='../../Inbox/'>Inbox ($PMs)</a>
						</td>
						<td>
							<a href='../../ItemLogs.php?view=all'>Purchase History</a>
						</td>
					</tr>
				</table>
			</div>
			";
		}
		?>
		<center>

			<!--End Top Bar-->
			<!--Begin Announcement Bar-->
			<?php
			if ($Maintenance !== "None") {
				echo "
				<center>
					<div id='Alert' style='background:orange'>
						<center style='color:white'>We are currently undergoing maintenance. You may experience temporary glitches during this time.</center>
					</div>
				</center>
				";
			}
			
			if (!empty($gB->Text)) {
				if ($gB->Color == "White") {
					$AnnouncementTextColor = "Black";
				}else {
					$AnnouncementTextColor = "White";
				}
				
				echo "
					<center>
						<div id='Alert' style='background:". $gB->Color ."'>
							<center style='color: ". $AnnouncementTextColor ."'>".nl2br($gB->Text)."</center>
							";
							$kkk = 30*6;
							$extratime = 86400*$kkk;
							$premiumtime = time() + $extratime;
							// echo $premiumtime;
							echo "
						</div>
					</center>
				";
			}
			
			echo "</header>";

			$getAllGroups = mysqli_query($connection, "SELECT * FROM Groups");

			while ($gAG = mysqli_fetch_object($getAllGroups)) {

				$getAllMembers = mysqli_query($connection, "SELECT * FROM GroupMembers WHERE GroupID='$gAG->ID'");
				$gA = mysqli_num_rows($getAllMembers);

				mysqli_query($connection, "UPDATE Groups SET GroupMembers='$gA' WHERE ID='$gAG->ID'");

			}
			?>

			<?php
			if (isset($myU) && ($myU->PowerAdmin == "true" || $myU->PowerMegaModerator == "true" || $myU->PowerImageModerator == "true")) {
				$NumOnline = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM Users WHERE NOW() < expireTime"));
				$NumR = 0;
				$NumPending = 0;
				$NumWaiting = 0;
				$NumPending1 = 0;
				$NumPending2 = 0;
			
				if ($NumR > 0) {
					$SayP = "<font color='red'><b>Unmoderated Profanity Reports ($NumR)</b></font>";
				} else {
					$SayP = "Unmoderated Profanity Reports ($NumR)";
				}
			
				if ($NumPending > 0) {
					$SayNP = "<font color='red'><b>Unmoderated User Items ($NumPending)</b></font>";
				} else {
					$SayNP = "Unmoderated User Items ($NumPending)";
				}
			
				if ($myU->PowerAdmin == "true") {
					$NumWaiting = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM ItemDrafts"));
					if ($NumWaiting > 0) {
						$SayNW = "<font color='red'><b>Unmoderated Store Items ($NumWaiting)</b></font>";
					} else {
						$SayNW = "Unmoderated Store Items ($NumWaiting)";
					}
				}
			
				$getPending1 = mysqli_query($connection, "SELECT * FROM GroupsPending ORDER BY ID");
				$NumPending1 = mysqli_num_rows($getPending1);
				$getPending2 = mysqli_query($connection, "SELECT * FROM GroupsLogo");
				$NumPending2 = mysqli_num_rows($getPending2);
			
				if ($NumPending1 > 0) {
					$SayNP1 = "<font color='red'><b>Unmoderated Groups ($NumPending1)</b></font>";
				} else {
					$SayNP1 = "Unmoderated Groups ($NumPending1)";
				}
			
				if ($NumPending2 > 0) {
					$SayNP2 = "<font color='red'>Unmoderated Group Logos ($NumPending2)</b></font>";
				} else {
					$SayNP2 = "Unmoderated Group Logos ($NumPending2)";
				}
			
				$AllShow = $NumR + $NumPending + $NumWaiting + $NumPending1 + $NumPending2;
				$KShow = ($AllShow > 0) ? "<font color='red'><b>Show Quick Admin <b>&uarr; ($AllShow)</b></font>" : "Show Quick Admin <b>&uarr; ($AllShow)";
			
				echo "
					<script type='text/javascript'>
					$(document).ready(function(){
						$('#quickAdmin_hide').hide();
						$('#quick_admin').hide();
							$('#quickAdmin_show').click(function(){
							$('#quick_admin').delay(500).slideDown();
							$('#quickAdmin_hide').delay(1000).slideDown();
							$('#quickAdmin_show').slideUp();
							});
							$('#quickAdmin_hide').click(function(){
							$('#quick_admin').delay(500).slideUp();
							$('#quickAdmin_hide').slideUp();
							$('#quickAdmin_show').delay(1000).slideDown();
							});
					  });
					</script>
				";
				echo "
					<div id='quickAdmin_show' style='position:fixed;z-index:9999;bottom:0px;right:250px;background:#eee;padding:5px;border:1px solid #aaa; cursor:pointer;'>
					$KShow</b>
					</div>
					<div id='quickAdmin_hide' style='position:fixed;z-index: 9999;bottom:96px;right:250px;background:#eee;padding:5px;border:1px solid #aaa;cursor:pointer;'>
					Hide Quick Admin <b>&darr;</b>
					</div>
					<div id='quick_admin' style='position:fixed;z-index: 9999;bottom:0px;right:250px;background:#eee;padding:5px;border:1px solid #aaa;'>
					<div align='left'>
				";
			
				if ($myU->PowerAdmin == "true" || $myU->PowerMegaModerator == "true") {
					echo "<a href='../../Reports.php'>$SayP</a><br />";
				}
				if ($myU->PowerAdmin == "true" || $myU->PowerMegaModerator == "true" || $myU->PowerImageModerator == "true") {
					echo "<a href='../../ItemModeration.php'>$SayNP</a><br />";
				}
				if ($myU->PowerAdmin == "true") {
					echo "<a href='../../ItemRelease.php'>$SayNW</a><br />";
				}
				if ($myU->PowerAdmin == "true" || $myU->PowerMegaModerator == "true") {
					echo "<a href='../../ModerateGroups.php'>$SayNP1</a><br />";
					echo "<a href='../../ModerateLogos.php'>$SayNP2</a><br />";
					echo "<a href='../../online.php'><b>Online Users ($NumOnline)</b></a><br/> </div>";
				}
				echo "</div></div>";
			}
			?>
			<br>
			</br>
			<!--End Announcement Bar-->

			<!--Begin Main Container-->
			<main>
			<center>
				<div id="Container"><div align='center'>