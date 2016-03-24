<?php
// Скрипт проверки
# Соединямся с БД
include("plugins/db.php");
if (isset($_COOKIE['id']) && isset($_COOKIE['hash']))
{ 
	$r = $db->query("SELECT *,INET_NTOA(u_ip) FROM admins WHERE u_id = '".$_COOKIE['id']."' LIMIT 1");
    $userdata = $r->fetch_assoc();
	if(($userdata['u_hash'] != $_COOKIE['hash']) || ($userdata['u_id'] != $_COOKIE['id'])) 
	{
		setcookie("id", "", time() - 3600*24*30*12);		
		setcookie("hash", "", time() - 3600*24*30*12);
		setcookie("lang", "ru");		
		header("Location: ?");
	} else
	{
		// TODO : сюда можно будет добавить генерацию нового хэша при каждой загрузке страницы
		// может увеличить безопасность, но также может добавить и проблем.

		date_default_timezone_set('UTC');
		$db->query("UPDATE profiles SET last_session = '".date("Y-m-d H:i:s")."' WHERE uid = '".$_COOKIE['id']."'");
		include 'privileged.php';
	}
}
else
{
    print "Включите куки";
}
?>