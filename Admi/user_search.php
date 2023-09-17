<?php include_once "connection.php"; ?>
<form action='' method='POST'>
	<b>Search User:</b>
	<table>
		<tr>
			<td>
				Username:
			</td>
			<td>
				<input type='text' name='SearchByUsername'>
			</td>
			<Td>
				<input type='submit' name='SubmitByUsername' value='Search'>
			</td>
		</tr>
	</table>
</form>
<?php

	//Written by Isaac
	//Performs a while of all usernames alike with the value of "SearchByUsername"
	//KK DONT STEAL
	//KK
	
	$Test = SecureString("<b>ok</b>");
	
	echo $Test;