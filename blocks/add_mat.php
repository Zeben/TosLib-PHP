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
								<form action='?q=editor&id=".$_GET['id']."&action=acceptor&mode=material' method='post' name='form'>
								<br />
									Название книги: <input class='search_input' type='text' maxlength='100' name='material_title' placeholder='Название книги...'>
									<br />Авторы книги: <input class='search_input' type='text' maxlength='100' name='material_authors' placeholder='Автор(ы) книги...'>
									<br />Издательство: <input class='search_input' type='text' maxlength='100' name='material_release' placeholder='Издательство...'>
									<br />Год издания: <input class='search_input' type='text' maxlength='100' name='material_year_release' placeholder='Год издания...'>
									<br />Количество страниц: <input class='search_input' type='text' maxlength='5' name='material_num_pages' placeholder='Количество страниц...'>
									Язык книги: <select name='material_lang'>
										<option value='Украинский'>Украинский</option>
										<option value='Русский'>Русский</option>
										<option value='Английский'>Английский</option>
									</select>
									<br />Дата добавления: <input class='search_input' name='material_year_addition' type='text' id='date' value='".date("Y-m-d")."'>
									<br />Краткое описание: <textarea id='material_description' name='material_description' cols='62' rows='5' wrap='soft' placeholder='Напишите краткое описание, или не пишите...'></textarea>
									<br />
									Ссылка: <table class='table_link' style='width: 100%;'>
											<tbody>
											<tr>
												<td width='10'>
														<img src='img/go-down.png'></td>
													<td>
														<input class='search_input' type='text' maxlength='255' name='link' placeholder='Ссылка...'>
													</td>
														<td width='30' style='font-size: 12px; text-align: center;'>Открывать в <select name='open_type'><option value='_blank'>новом окне (_blank)</option><option value='_parent'>этом окне (_parent)</option>
													</select>
												</td>
											</tr>
											</tbody>
										</table>
									<br /><input class='search_button' type='submit' value='Применить' name='addmaterial_accept'>
								</form>
							</div>
						</html>
						"; 
?>