<?php
echo
"
<html>
	<style>
		.sql-query {
			border: 1px solid #bababa;
			margin: 10px;
			background-color: #ececec;
			transition-property: background;
			transition-duration: 0.5s;
		}
		.sql-query:hover {
			background-color: #f5f5f5;
		}
	</style>
	<div class='sql-query'>
		<form action='?q=editor&id=".$_GET['id']."&action=addition&mode=material' method='post' name='form'>
			<br />
			<center><input class='search_button' type='submit' value='Добавить запись' name='mat_add_answ'></center>
		</form>
	</div>
</html>
"; 
?>