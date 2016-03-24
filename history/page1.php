<?php 
if($ajax) {
  echo "<span style='color:blue'>Динамически подгружаемый контент, в рамках урока по History API от lifeexample.ru</span>";
} else{
  include 'index.php';
}