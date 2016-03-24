<?php 
// Подгружает страницу указаную в $_POST['uri']
$ajax = true; 
if(!empty($_POST['uri'])){
  include $_POST['uri'];
}