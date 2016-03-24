<?php

$db = new mysqli("site", "login", "password", "dbtable");
if($db->connect_errno) echo "Не могу подключиться к БД! Ошибка: ".$db->connect_error;
$db->query("SET NAMES utf8");
?>
