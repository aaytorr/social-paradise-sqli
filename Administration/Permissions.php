<?php
	include_once "../Global.php";
	
	if (!$User) {
	
		include_once "../403.shtml";
		exit;
	
	}
	
	if ($myU->PowerAdmin != "true") {
	
		include_once "../403.shtml";
		exit;
	
	}