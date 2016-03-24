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
								Добавление нового раздела </br>
								<form action='?q=editor&action=acceptor&mode=title' method='post' name='form'>
									<input class='search_input' type='text' maxlength='100' name='title_name' placeholder='Название раздела...'>
									<br />
									<center>
									<table border='0'>
									<tr>
									<td>
										 <input class='search_button' type='submit' value='Применить' name='addtitle_accept'>
									</td>
									<td>
										 <input class='search_button' type='submit' value='Отменить' name='addtitle_reject'>
									</td>
									</tr>
									</table>
									</center>
								</form>
							</div>
						</html>
						";

?>