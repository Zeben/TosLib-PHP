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
		Удалить эту запись?<br />
		<form action='?q=editor&id=".$_GET['id']."&action=remover&mode=null' method='post' name='form'>
			<br />
			<center>
				<table border='0'>
				<tr>
				<td>
					<input class='search_button' type='submit' value='Да' name='mat_rem_accept'>
				</td>
				<td>
					<input class='search_button' type='submit' value='Нет' name='mat_rem_reject'>
				</td>
				</table>
			</center>
		</form>
	</div>
</html>
"; 
?>