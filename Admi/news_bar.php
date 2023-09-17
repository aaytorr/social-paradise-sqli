<?php include_once "connection.php"; ?>
<b>
Worldwide News Bar
</b>
<br />
<br />
<form action='' method='POST'>
	<table>
		<tr>
			<td>
				<textarea name='Banner' rows='5' cols='90'><?php $Current = mysql_fetch_object($Current = mysql_query("SELECT * FROM Banner")); echo $Current->Text; ?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input type='submit' name='Submit' value='Update'>
			</td>
		</tr>
	</table>
</form>
<?php
$Banner = mysql_real_escape_string(strip_tags(stripslashes($_POST['Banner'])));
$Submit = mysql_real_escape_string(strip_tags(stripslashes($_POST['Submit'])));
	if ($Submit) {
	
		mysql_query("UPDATE Banner SET Text='".$Banner."'");
		header("Location: /Admi/?page=news_bar");
	
	}
?>