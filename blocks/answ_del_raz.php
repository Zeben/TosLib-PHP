<?php
echo
"
<html>
	<style>
		.sql-query {
			border: 1px solid #bababa;
			margin: 10px;
			padding: 10px;
			background-color: #ececec;
			transition-property: background;
			transition-duration: 0.5s;
		}
		.sql-query:hover {
			background-color: #f5f5f5;
		}
	</style>
	<div class='sql-query'>
		Вы действительно хотите произвести удаление раздела <b>".$remover['title']."</b>?<br />
		Операция рекурсивно удалит всё содержимое раздела; действие необратимо!<br />
		<form action='?q=books&id=".$_GET['id']."&action=accept' method='post' name='form'>
			<br />
			<center>
			<table border='0'>
			<tr>
			<td>
				<input class='search_button' type='submit' value='Да' name='razrem_accept'>
			</td>
			<td>
				<input class='search_button' type='submit' value='Нет' name='razrem_reject'>
			</td>
			</tr>
			</table>
			</center>
		</form>
	</div>
</html>
"; 
?>