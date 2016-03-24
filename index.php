<?php
	include("plugins/db.php");
	if(isset($_COOKIE['hash']) && isset($_COOKIE['id']))
	{
		include 'plugins/check.php';
	}
	else include 'plugins/log.php';
?>