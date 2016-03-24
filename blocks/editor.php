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
							$add = $db->query("INSERT INTO categories (title, razdel) VALUES ('".$_POST['cat_name']."', '".$_GET['id']."')");
							$_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
						}
						else if(!empty($_POST['editcat_accept']))
						{
							$edit = $db->query("UPDATE categories SET title='".$_POST['cat_name_rename']."' WHERE id = '".$_GET['id']."'");
							$_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
						}
						else { $_GET['errnum'] = "err_string_empty"; include 'dyncontent/errors.php'; }
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
								echo "<html><form action='index.php?q=editor&id=".$_GET['id']."&action=edit&mode=material' method='post'><input class='search_button' align='middle' type='submit' value='Вернуться назад' name='return_to_material'><form></html>";
							}
							else
							{
								
								$edit = $db->query("UPDATE data SET title = '".$_POST['material_title']."', autor = '".$_POST['material_authors']."', izdatelstvo = '".$_POST['material_release']."', god = '".$_POST['material_year_release']."', str = '".$_POST['material_num_pages']."', lang = '".$_POST['material_lang']."', date = '".$_POST['material_year_addition']."', link = '".$_POST['link']."', blank = '".$_POST['open_type']."', description = '".$_POST['material_description']."' WHERE id = '".$_GET['id']."'");
								$_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
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
								$_GET['errnum'] = "err_doesnt_filled"; include 'dyncontent/errors.php';
								echo $_POST['material_description'];
								echo "<html><form action='index.php?q=editor&id=".$_GET['id']."&action=addition&mode=material' method='post'><input class='search_butto