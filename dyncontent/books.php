<?php

include("plugins/db.php");
if(!isset($_GET['action']))
	{
		$_GET['errnum'] = "err_keys";  include 'dyncontent/errors.php';
	} else
	{
		if($_GET['action'] == "explore")
		{
			$r = $db->query("SELECT * FROM categories WHERE razdel = ".$_GET['id']."");
			if($r->num_rows == 0)
			{ 
				$_GET['errnum'] = "err_title_empty"; 
				echo "<div class='sql-query'>";
					include 'blocks/subtitle.php'; /* подключаем навигацию в каталогах */  
				echo "</div>";
				include 'dyncontent/errors.php';
				if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
				{
					if(($_COOKIE['id'] == $cook['u_id']) && (md5(md5('rooted')) == $cook['key_root']))
					{
						include 'blocks/add_catalog_answ.php';
					}
				} 
			}
			else
			{
				echo 
				"
				<style>
					.cats {
						list-style: none;
						border: 1px solid #bababa;
						margin: 10px;
						padding: 5px;
						background-color: #ececec;
						color: #333;
					}
					.cats li a {
						text-decoration: none;
						color: #333;
					}
				</style>
				";
				echo "<div class='sql-query'>";
				include 'blocks/subtitle.php'; // подключаем навигацию в каталогах
				echo "</div>";
				echo "<ul class='m_mod'>";
				while($row = $r->fetch_assoc())
				{
					echo "
					<li>
						<a href='?q=books&id=".$row['id']."&action=cats'>".$row['title']."</a>
					</li>	
					
					";
				}
				echo "</ul>";
				if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
				{
					if(($_COOKIE['id'] == $cook['u_id']) && (md5(md5('rooted')) == $cook['key_root']))
					{
						include 'blocks/add_catalog_answ.php';
					}
				} 
			}
		}
		if($_GET['action'] == "cats")
		{
			$_POST['temp_catalog_id'] = $_GET['id'];
			$r2 = $db->query("SELECT * FROM data WHERE cat = ".$_GET['id']."");
			if($r2->num_rows == 0) 
			{
				{ 
					echo "<div class='sql-query'>";
					include 'blocks/subtitle.php'; /* подключаем навигацию в каталогах */  
					echo "</div>";
					$_GET['errnum'] = "err_cat_empty"; include 'dyncontent/errors.php'; 
				}; // сообщение о том, что раздел пуст
				// проверка на логин
				if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
				{
					if(($_COOKIE['id'] == $cook['u_id']) && (md5(md5('rooted')) == $cook['key_root']))
					{
						include 'blocks/add_mat_answ.php';
					}
				}
			} 
			else
			{
				echo "<div class='sql-query'>";
				include 'blocks/subtitle.php'; // подключаем навигацию в каталогах
				echo "</div>";
				while($row2 = $r2->fetch_assoc()) 
				{
					include 'blocks/explorer_material.php';
				}
				// проверка на логин
				if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
				{
					if(($_COOKIE['id'] == $cook['u_id']) && (md5(md5('rooted')) == $cook['key_root']))
					{
						include 'blocks/add_mat_answ.php';
					}
				}
			}
		}
		if($_GET['action'] == "removeraz")
		{
			if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
			{
				if(($_COOKIE['id'] == $cook['u_id']) && (md5(md5('rooted')) == $cook['key_root']))
				{
					$r3 = $db->query("SELECT * FROM razdeli WHERE id = ".$_GET['id']."");
					$remover = $r3->fetch_assoc();
					include 'blocks/answ_del_raz.php';
				}
			} else { $_GET['errnum'] = "err_keys"; include 'dyncontent/errors.php'; };
		}
		if($_GET['action'] == "accept")
		{
		}

	if(isset($_POST['razrem_accept']))
	{
		// УЗНАЕМ ИНДЕКСЫ ПО ВСЕМ КАТЕГОРИЯМ РАЗДЕЛА
		$recursor2 = $db->query("SELECT id FROM categories WHERE razdel = ".$_GET['id'].""); 
		while($recursor2_assoc = $recursor2->fetch_assoc())
		{
			// УДАЛЯЕМ ВСЕ ДАННЫЕ С DATA, ОТНОСЯЩИЕСЯ КО ВСЕМ КАТЕГОРИЯМ РАЗДЕЛА
			$recursor1 = $db->query("DELETE FROM data WHERE cat = ".$recursor2_assoc['id']."");

		}
		$recursor2 = $db->query("SELECT id FROM categories WHERE razdel = ".$_GET['id'].""); 
		if($recursor2->fetch_assoc() != NULL)
		{
			// УДАЛЯЕМ ВСЕ КАТЕГОРИИ, ОТНОСЯЩИЕСЯ К РАЗДЕЛУ
			$recursor3 = $db->query("DELETE FROM categories WHERE razdel = ".$_GET['id']."");
		}
		// И УДАЛЯЕМ РАЗДЕЛ
		$recursor4 = $db->query("DELETE FROM razdeli WHERE id = ".$_GET['id']."");
		$_GET['errnum'] = "msg_done"; include 'dyncontent/errors.php';
	}
	if(isset($_POST['razrem_reject']) && $_POST['razrem_reject'] == "Нет")
	{
		$_GET['errnum'] = "msg_null"; include 'dyncontent/errors.php';
	}
}
?> 
