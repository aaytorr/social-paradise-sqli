<?php
	include_once "Header.php";
	
	if (!$User) {
	
		$Username = SecurePost($_POST['Username']);
		$Password = SecurePost($_POST['Password']);
		$Submit =   SecurePost($_POST['Submit']);
		
		if ($Submit) {
		
			if (!$Username || !$Password) {
			
				echo "
				<div id='Error'>
					Sorry, you have missing fields.
				</div>
				";
			
			}
			
			else {
			
				$_HASH = hash('sha512',$Password);
				
				$checkUser = mysql_query("SELECT * FROM Users WHERE Username='$Username'");
				
				$cU = mysql_num_rows($checkUser);
				
				if ($cU == 0) {
				
				echo "
				<div id='Error'>
					Sorry, that account does not exist.
				</div>
				";
				
				}
				
				else {
				
					$getPassword = mysql_query("SELECT * FROM Users WHERE Username='$Username' AND Password='$_HASH'");
					
					$gP = mysql_num_rows($getPassword);
					
					if ($gP == 0) {
					
						echo "
						<div id='Error'>
							Sorry, but your password is incorrect.
						</div>
						";
					
					}
					
					else {
					
						$_SESSION['Username']=$Username;
						$_SESSION['Password']=$_HASH;
						
						header("Location: index.php");
					
					}
				
				}
			
			}
		
		}
	
		echo "
		<form action='' method='POST'>
			<table width=''>
				<tr>
					<td>
						<div id='LargeText'>Login to your account</div>
					</td>
				</tr>
			</table>
			<table width=''>
				<tr>
					<td>
						<b>Username</b>
					</td>
					<td>
						<input type='text' name='Username'>
					</td>
				</tr>
				<tr>
					<td>
						<b>Password</b>
					</td>
					<td>
						<input type='password' name='Password'>
					</td>
				</tr>
				<tr>
					<td>
						<input type='submit' name='Submit' value='Log in'>
					</td>
				</tr>
			</table>
		</form>
		";
	
	}
	
	else {
	
		header("Location: ../");
	
	}
	
	include_once "Footer.php";