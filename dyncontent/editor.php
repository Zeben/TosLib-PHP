<?php
include("plugins/db.php");
$r = $db->query("SELECT * FROM admins WHERE u_hash = '".$_COOKIE['hash']."'");
$row = $r->fetch_assoc();
if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
{
	if(($_COOKIE['id'] == $row['u_id']) && (md5(md5('rooted')) == $row['key_root'])) {
		if(!isset($_GET['action'])) {
			echo "Комбинация параметров неверна";
		} 
		else {
			if($_GET['action'] == "addition")
			{
				switch($_GET['mode'])
				{
					case('category'): {
						include 'blocks/answ_add_cat.php';
					} break;
					case('material'): {
						include 'blocks/add_mat.php';
					} break;
					case('title'): {
						include 'blocks/add_title.php';
					} break;
					case('user'): {
						include 'blocks/add_user.php';
					} break;
				}
			}
			if($_GET['action'] == "remover") {
				switch($_GET['mode']) {
					case('category'): {
						include 'blocks/answ_del_cat.php';
					} break;
					case('material'): {
						include 'blocks/answ_del_material.php';
					}
				}
				
				if(isset($_POST['catrem_accept']) && ($_POST['catrem_accept'] == "Да")) {
					$datarem = $db->query("DELETE FROM data WHERE cat = ".$_GET['id']."");
					$catrem = $db->query("DELETE FROM categories WHERE id = ".$_GET['id']."");
					$_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
				}
				
				if(isset($_POST['mat_rem_accept'])) {
					$db->query("DELETE FROM data WHERE id = ".$_GET['id']."");
					$_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
				}
				
				if(isset($_POST['mat_rem_reject']) && ($_POST['mat_rem_reject'] == "Нет")) {
					$_GET['errnum'] = "msg_null"; include 'dyncontent/errors.php';
				}
				
				if(isset($_POST['catrem_reject']) && ($_POST['catrem_reject'] == "Нет")) {
					$_GET['errnum'] = "msg_null"; include 'dyncontent/errors.php';
				}
				
			}
			if($_GET['action'] == "edit")
			{
				switch($_GET['mode']) 
				{
					case('material'):
					{
						$db_material = $db->query("SELECT * FROM data WHERE id = ".$_GET['id']."");
						$res_material = $db_material->fetch_assoc();
						$cat_temp_id = $res_material['cat'];
						include 'blocks/editor_mat.php';
					} break;
					case('category'):
					{
						$db_category = $db->query("SELECT * FROM categories WHERE id = ".$_GET['id']."");
						$res_category = $db_category->fetch_assoc();
						include 'blocks/answ_rename_cat.php';
					} break;
				}
			}
			if($_GET['action'] == "acceptor")
			{
				switch($_GET['mode'])
				{
					case('category'):
					{
						if(!empty($_POST['cat_name'])) {
							if(!$db->query("INSERT INTO categories (title, razdel) VALUES ('".$_POST['cat_name']."', '".$_GET['id']."')"))
								echo "Ошибка выполнения запроса:".$db->error;
							else 
							{
								include 'dyncontent/error_messenger.php'; 
								$redirectStr = "?q=books&id=".$db->insert_id."&action=cats";
								echo err_generic("Выполнено!", $redirectStr, "Перейти в созданную категорию");
							}
						}
						else if(!empty($_POST['editcat_accept'])) {
							if(!$db->query("UPDATE categories SET title='".$_POST['cat_name_rename']."' WHERE id = '".$_GET['id']."'")) 
								echo "Ошибка выполнения запроса:".$db->error;
							else $_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
						}
						else { 
							$redirectStr = "?q=editor&id=".$_GET['id']."&action=addition&mode=category";
							include 'dyncontent/error_messenger.php'; 
							echo err_generic("Строка пуста!", $redirectStr, "Назад");
						}
					}
					break;
					case('material'):
					{
						if(isset($_POST['editmaterial_accept']))
						{
							if((empty($_POST['material_title'])) || /*название*/
							(empty($_POST['material_authors'])) || /*авторы*/
							(empty($_POST['material_release'])) || /*издательство*/
							(empty($_POST['material_year_release'])) || /*дата издания*/
							(empty($_POST['material_num_pages'])) || /*кол-во страниц*/
							(empty($_POST['material_lang'])) || /*язык материала*/
							(empty($_POST['material_year_addition'])) || /*дата добавления*/
							(empty($_POST['link'])) || /*ссылка*/
							(empty($_POST['open_type'])) /*тип открытия*/
							)
							{
								$_GET['errnum'] = "err_doesnt_filled"; include 'dyncontent/errors.php';
								echo "<form action='index.php?q=editor&id=".$_GET['id']."&action=edit&mode=material' method='post'><input class='search_button' align='middle' type='submit' value='Вернуться назад' name='return_to_material'><form>";
							}
							else
							{
								
								if(!$db->query("UPDATE data SET title = '".$_POST['material_title']."', autor = '".$_POST['material_authors']."', izdatelstvo = '".$_POST['material_release']."', god = '".$_POST['material_year_release']."', str = '".$_POST['material_num_pages']."', lang = '".$_POST['material_lang']."', date = '".date('Y-m-d', strtotime(str_replace('.', '-', $_POST['material_year_addition'])))."', link = '".$_POST['link']."', blank = '".$_POST['open_type']."', description = '".$_POST['material_description']."', cat = '".$_POST['material_in_cat']."' WHERE id = '".$_GET['id']."'")) echo "Error: ". $db->error;
								include 'dyncontent/error_messenger.php'; 
								echo err_generic("Выполнено!", "?q=content", "Вернуться на главную");
								
							}
						}
						if(isset($_POST['addmaterial_accept']))
						{
							if((empty($_POST['material_title'])) || /*название*/
							(empty($_POST['material_authors'])) || /*авторы*/
							(empty($_POST['material_release'])) || /*издательство*/
							(empty($_POST['material_year_release'])) || /*дата издания*/
							(empty($_POST['material_num_pages'])) || /*кол-во страниц*/
							(empty($_POST['material_lang'])) || /*язык материала*/
							(empty($_POST['material_year_addition'])) || /*дата добавления*/
							(empty($_POST['link'])) || /*ссылка*/
							(empty($_POST['open_type'])) /*тип открытия*/
							)
							{
								$redirectStr = "?q=editor&id=".$_GET['id']."&action=addition&mode=material";
								include 'dyncontent/error_messenger.php'; 
								echo err_generic("Одно или несколько полей не заполнено.", $redirectStr, "Назад");
							}
							else
							{
								$mat_add = $db->query("INSERT INTO data (title, link, blank, date, autor, izdatelstvo, lang, description, god, str, cat) VALUES ('".$_POST['material_title']."','".trim(strip_tags($_POST['link']))."','".$_POST['open_type']."','".$_POST['material_year_addition']."','".$_POST['material_authors']."','".$_POST['material_release']."','".$_POST['material_lang']."','".$_POST['material_description']."','".$_POST['material_year_release']."','".$_POST['material_num_pages']."','".$_GET['id']."')");
								include 'dyncontent/error_messenger.php'; 
								$var_ins_id = "?q=books&id=".$_GET['id']."&action=cats";
								echo err_generic("Выполнено!", $var_ins_id, "Просмотреть категорию");
								echo err_generic("", "?q=content", "Вернуться на главную");
							}
						}
					}
					break;
					case('title'):
					{
						if(isset($_POST['addtitle_accept']))
						{
							if(!empty($_POST['title_name'])) 
							{
								$db->query("INSERT INTO razdeli (title) VALUES ('".strip_tags($_POST['title_name'])."')");
								$var_ins_id = $db->insert_id;
								include 'dyncontent/error_messenger.php'; 
								$redirectStr = "?q=books&id=".$var_ins_id."&action=explore";
								echo err_generic("Выполнено!", $redirectStr, "Перейти в созданный раздел");
							}
							else
							{
								include 'dyncontent/error_messenger.php'; 
								echo err_generic("Строка пуста!", "?q=editor&action=addition&mode=title", "Назад");
							}
						}
						
					} break;
					case('user'):
					{
						if(isset($_POST['adduser_accept']))
						{
							if((!empty($_POST['add_user_name'])) && (!empty($_POST['add_user_password'])))
							{
							
								if((isset($_POST['isadmin'])) && ($_POST['isadmin'] == 'confirm')) // если отмечена галочка админов
									$varRooted = md5(md5('rooted')); // добавляем мега-слово
								else
									$varRooted = ""; // в противном случае - ничего не отправляем
									
								if((isset($_POST['disable_hash_generation'])) && ($_POST['disable_hash_generation'] == 'confirm')) // если отмечено...
									$disableHashGeneration = "1"; // добавляем юзера в категорию со статичным хэшем, брешь в безопасности!
								else
									$disableHashGeneration = "0"; // или не добавляем...
									
								if(!$db->query("INSERT INTO admins (u_login, u_pass, key_root, disable_hash_generation) VALUES ('".strip_tags($_POST['add_user_name'])."', '".md5(md5($_POST['add_user_password']))."', '".$varRooted."', '".$disableHashGeneration."')"))
									echo "Ошибка выполнения запроса: ".$db->error;
								else
								{
									$_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
								}
									
							}
						} 
						if(isset($_POST['adduser_reject']))
						{
							$_GET['errnum'] = "msg_null"; include 'dyncontent/errors.php';
						}
					} break;
					case('useraction'):
					{
						if(isset($_POST['user_functions_accept'])) // если нажата кнопка подтвержения
						{
							include 'dyncontent/error_messenger.php'; 
							///////////////
							if(isset($_POST['checkbox'])) // если хоть один чекбокс указан - выполняем тело цикла
							{
								$checkbox_array = $_POST['checkbox'];	// массив чекбоксов
								if(isset($_POST['checkbox_user_delete'])) // если отмечены чекбоксы удаления
								{
									$checkbox_user_array = $_POST['checkbox_user_delete']; // массив чекбоксов удаления юзеров
									if((count($checkbox_array) == count($checkbox_user_array)) && $checkbox_array === $checkbox_user_array)
									{
										//print_r($_POST['checkbox_user_delete']);
										foreach ($_POST['checkbox_user_delete'] as $key => $value) 
										{
											//echo $value. "<br />";
											$user_report_query = $db->query("SELECT u_login FROM admins WHERE u_id = '".$value."'");
											$user_report_query_res = $user_report_query->fetch_assoc();
											echo "<div class='sql-query'><b>".$user_report_query_res['u_login']."</b>: Удаление пользователя...</div>";
											if(!$db->query("DELETE FROM admins WHERE u_id = '".$value."'")) echo "Ошибка выполнения запроса: ".$db->error;
										}
										echo err_user("Выбранный(е) пользователь(и) удален(ы). Нажмите 'Назад' для проверки таблицы пользователей.");
									} else echo err("Чекбоксы отмеченных юзеров и удаляемых юзеров не совпадают.");
								}

								/* создаем счётчики и массивы */
								$change_password_array = $_POST['change_password'];
								$change_username_array = $_POST['change_username'];
								$change_password_counter = 0;
								$change_username_counter = 0;

								/* считаем количество заполненных полей для массива паролей*/
								foreach ($change_password_array as $key => $value) // перебираем массив паролей
								{
									if(!empty($value)) // если в итеррации поле не пусто
									{
										$change_password_counter += 1; // то добавляем к счётчику кол-во полей
									}
								}

								/* считаем количество заполненных полей для массива новых никнеймов*/
								foreach ($change_username_array as $key => $value) {
									if(!empty($value)) // если в итеррации поле не пусто
									{
										$change_username_counter += 1; // то добавляем к счётчику кол-во полей
									}
								}

								/* производим множество проверок на совпадение с чекбоксами и заполненность вообще */
								if(($change_password_counter != 0) || ($change_username_counter != 0))
								{
									//echo "d";
									if(($change_password_counter > count($checkbox_array)) || ($change_username_counter > count($checkbox_array)))
									{
										echo err_user("Чекбоксы не совпадают со столбцами заполненных полей.");
									}
									else
									if((count($checkbox_array) == $change_password_counter) || 
									   (count($checkbox_array) == $change_username_counter) ||
									   (count($checkbox_array) >= ($change_password_counter + $change_username_counter)))
									{
										$num_rows_query_res_temp = array();
										$num_rows_query = $db->query("SELECT * FROM admins");
											$temp_count = 0;
											while($num_rows_query_res = $num_rows_query->fetch_array())
											{
													$num_rows_query_res_temp[] = $num_rows_query_res['u_id'];
													$temp_count++;
											}
										$arr_temp_num_rows = array();
										if($change_password_counter != 0)
										{
											foreach ($change_password_array as $key => $value) 
											{
												if(!empty($value))
												{
													if(!$db->query("UPDATE admins SET u_pass = '".md5(md5($value))."' WHERE u_id = '".$num_rows_query_res_temp[$key]."'")) echo "Ошибка выполнения запроса: ".$db->error;
													$user_report_query = $db->query("SELECT u_login FROM admins WHERE u_id = '".$num_rows_query_res_temp[$key]."'");
													$user_report_query_res = $user_report_query->fetch_assoc();
													echo "<div class='sql-query'><b>".$user_report_query_res['u_login']."</b>: Пароль изменён.</div>";
													$arr_temp_num_rows[] = $num_rows_query_res_temp[$key];
												}
											}
										}

										if($change_username_counter != 0)
										{
											foreach ($change_username_array as $key => $value) 
											{
												if(!empty($value))
												{
													if(!$db->query("UPDATE admins SET u_login = '".$value."' WHERE u_id = '".$num_rows_query_res_temp[$key]."'")) echo "Ошибка выполнения запроса: ".$db->error;
													$user_report_query = $db->query("SELECT u_login FROM admins WHERE u_id = '".$num_rows_query_res_temp[$key]."'");
													$user_report_query_res = $user_report_query->fetch_assoc();
													echo "<div class='sql-query'><b>".$user_report_query_res['u_login']."</b>: Никнейм изменён.</div>";
													$arr_temp_num_rows[] = $num_rows_query_res_temp[$key];
												}
											}
										}
										//print_r($checkbox_array);
										//print_r($arr_temp_num_rows);
										
										foreach (array_diff($checkbox_array, $arr_temp_num_rows) as $key => $value) 
										{
											$user_report_query = $db->query("SELECT u_login FROM admins WHERE u_id = '".$value."'");
											$user_report_query_res = $user_report_query->fetch_assoc();
											echo "<div class='sql-query'><b>".$user_report_query_res['u_login']."</b>: Действие пропущено: параметры не заданы.</div>";
										}
									}
								}
								if(($change_password_counter == 0) && ($change_username_counter == 0) && (!isset($_POST['checkbox_user_delete']))) // не выполнять никаких действий, если всё пусто
								{
									echo err_user("Операция не выполнена: Вы не отметили действия.");
								}
							} else echo err_user("Операция не выполнена: Пользователи не отмечены."); // иначе - выводим предупреждение
							///////////////

						}
						
					}
				}
			}
		}
	} else { $_GET['errnum'] = "err_keys"; include 'dyncontent/errors.php'; };
}
else { $_GET['errnum'] = "err_doesnt_logedin"; include 'dyncontent/errors.php'; };
?>