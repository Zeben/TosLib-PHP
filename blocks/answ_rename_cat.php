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
								Переименовать категорию </br>
								<form action='?q=editor&id=".$_GET['id']."&action=acceptor&mode=category' method='post' name='form'>
									<input class='search_input' type='text' maxlength='100' name='cat_name_rename' placeholder='Название категории...' value='".$res_category['title']."'>
									<br /><input class='search_button' type='submit' value='Применить' name='editcat_accept'>
								</form>
							</div>
						</html>
						";

?> 
