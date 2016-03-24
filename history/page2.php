<?php 
if($ajax) {
  echo "<span style='color:green'>Можете нажать кнопку \"Назад\" в браузере, чтобы убедиться в работоспособности History API</span>";
} else { 
  include 'index.php';
}