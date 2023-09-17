<?php
	include_once "Header.php";
	
		if ($User) {
		
			echo "
			<div id='LargeText'>
				Thank you, ".$User."
			</div>
			Thank you for your service, we appreciate it. Your bux should be added immediatly.
			<br />
			Did the bux not go through? Please PM Isaac with the PayPal email + First & Last name.
			";
		
		}
		
		else {
		
			header("Location: ../");
		
		}
	
	include_once "Footer.php";