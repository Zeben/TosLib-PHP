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
									Тут можно выбрать другую категорию: 
									<select name='material_in_cat'>";
									
									$material_in_cat_query = $db->query("SELECT * FROM categories");
									while($material_in_cat_res = $material_in_cat_query->fetch_assoc())
									{	
										if($res_material['cat'] == $material_in_cat_res['id']) 
										echo "<option selected value='".$material_in_cat_res['id']."'>".$material_in_cat_res['title']."</option>";
										else 
										echo "<option value='".$material_in_cat_res['id']."'>".$material_in_cat_res['title']."</option>";
									}
									
									echo "
									</select>
									<br />
									Название книги: <input class='search_input' type='text' maxlength='100' name='material_title' placeholder='Название книги...' value='".$res_material['title']."'>
									<br />Авторы книги: <input class='search_input' type='text' maxlength='100' name='material_authors' placeholder='Автор(ы) книги...' value='".$res_material['autor']."'>
									<br />Издательство: <input class='search_input' type='text' maxlength='100' name='material_release' placeholder='Издательство...' value='".$res_material['izdatelstvo']."'>
									<br />Год издания: <input class='search_input' type='text' maxlength='100' name='material_year_release' placeholder='Год издания...' value='".$res_material['god']."'>
									<br />Количество страниц: <input class='search_input' type='text' maxlength='5' name='material_num_pages' placeholder='Количество страниц...' value='".$res_material['str']."'>
									Язык книги: <select name='material_lang'>";
										switch($res_material['lang'])
										{
											case("Русский"): $arr_lang = array("Русский", "Английский", "Украинский"); break;
											case("Английский"): $arr_lang = array("Английский", "Русский", "Украинский"); break;
											case("Украинский"): $arr_lang = array("Украинский", "Английский", "Русский"); break;
											case(""): $arr_lang = array("Английский", "Русский", "Украинский"); break;
										}
										echo "
										<option selected value='".$arr_lang[0]."'>".$arr_lang[0]."</option>
										<option value='".$arr_lang[1]."'>".$arr_lang[1]."</option>
										<option value='".$arr_lang[2]."'>".$arr_lang[2]."</option>
									</select>
									<br />Дата добавления: <input class='search_input' name='material_year_addition' type='text' id='date' value='".$res_material['date']."'>
									<br />Краткое описание: <textarea id='material_description' name='material_description' cols='62' rows='5' wrap='soft'>".$res_material['description']."</textarea>
									<br />
									Ссылка: <table class='table_link' style='width: 100%;'>
											<tbody>
											<tr>
												<td width='10'>
														<img src='img/go-down.png'></td>
													<td>
														<input class='search_input' type='text' maxlength='255' name='link' placeholder='Ссылка...' value='".strip_tags($res_material['link'],"<a>")."'>
													</td>
														<td width='30' style='font-size: 12px; text-align: center;'>Открывать в <select name='open_type'><option value='_blank'>новом окне (_blank)</option><option value='_parent'>этом окне (_parent)</option>
													</select>
												</td>
											</tr>
											</tbody>
										</table>
									<br /><input class='search_button' type='submit' value='Применить' name='editmaterial_accept'>
								</form>
							</div>
						</html>
						"; 
?>