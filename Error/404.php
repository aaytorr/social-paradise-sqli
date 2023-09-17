<?php
http_response_code(404);
include_once($_SERVER['DOCUMENT_ROOT']."/Header.php");
?>

<style>
	.ErrorPage {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 100%;
	}
</style>

<center>
	<div id="ErrorPage">    
		<img src="../Images/alert.png" alt="Alert" class="ErrorAlert">
		
		<h1><span id="ErrorTitle">Requested page not found</span></h1>
		<h3><span id="ErrorMessage">You may have clicked an expired link or mistyped the address.</span></h3>
	
		<div class="CenterNavigationButtonsForFloat">
			<a class="btn-small btn-neutral" title="Go to Previous Page Button" onclick="history.back();return false;" href="#"><span class="btn-text"> Go to Previous Page</span></a>
			
			<a class="btn-neutral btn-small" title="Return Home" href="/"><span class="btn-text">Return Home</span></a>
			<div style="clear:both"></div>
		</div>
	</div>
</center>

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Footer.php");
?>