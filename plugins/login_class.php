 <?php
 
if(!isset($_COOKIE['id']) or !isset($_COOKIE['hash']))
	include("login.php");
else
	include("check.php");
 
 ?>