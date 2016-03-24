<?php

if(!isset($_GET['q'])) $_GET['q'] = 'content';
switch ($_GET['q']) 
{
	case 'search':
	{
		$material_id_query = $db->query("SELECT * FROM categories WHERE id = '".$_POST['material_id']."'");
		$material_id_query_res = $material_id_query->fetch_assoc();
		$category_id_query = $db->query("SELECT * FROM razdeli WHERE id = '".$material_id_query_res['razdel']."'");
		$category_id_query_res = $category_id_query->fetch_assoc();
		echo "
			<input class='menu-login-button-small' style='font-size: 12px;' type='button' name='subtitle_title' value='".$category_id_query_res['title']."' onclick=\"window.location.href='?q=books&id=".$category_id_query_res['id']."&action=explore'\">
		<b>></b>
			<input class='menu-login-button-small' style='font-size: 12px;' type='button' name='subtitle_title' value='".$material_id_query_res['title']."' onclick=\"window.location.href='?q=books&id=".$material_id_query_res['id']."&action=cats'\">"
		;
	} break;
	case 'books':
	{
		if(isset($_GET['action']) && $_GET['action'] == 'explore')
		{
			$catalog_id_query = $db->query("SELECT * FROM razdeli WHERE id = '".$_GET['id']."' ");
			$catalog_id_query_res = $catalog_id_query->fetch_assoc();
			echo "
			<input style='margin: 2px;' class='menu-login-button-small' type='button' name='subtitle_title' value='".$catalog_id_query_res['title']."'>
			";
		} else
		{
			$subtitler_categories = $db->query("SELECT * FROM categories WHERE id = '".$_GET['id']."'");
			$subtitler_categories_res = $subtitler_categories->fetch_assoc(); 

			$subtitler_titles = $db->query("SELECT * FROM razdeli WHERE id = '".$subtitler_categories_res['razdel']."'");
			$subtitler_titles_res = $subtitler_titles->fetch_assoc(); 

			echo 
			"
			<input style='margin: 2px;' class='menu-login-button-small' type='button' name='subtitle_title' value='".$subtitler_titles_res['title']."' onclick=\"window.location.href='?q=books&id=".$subtitler_categories_res['razdel']."&action=explore'\">
			<b>></b>
			<input style='margin: 2px;' class='menu-login-button-small' type='button' name='subtitle_title' value='".$subtitler_categories_res['title']."'>
			";
		}
	} break;
	case 'content':
	{
		$category_id_query = $db->query("SELECT * FROM categories WHERE id = '".$_POST['material_id']."'");
		$category_id_query_res = $category_id_query->fetch_assoc();
		$section_id_query = $db->query("SELECT * FROM razdeli WHERE id = '".$category_id_query_res['razdel']."'");
		$section_id_query_res = $section_id_query->fetch_assoc();

		echo "
		<div style='display: inline-block;'>
			<input class='menu-login-button-small' 
				style='margin: 2px; font-size: 12px; 
				border-top-left-radius: 3px;
				border-bottom-left-radius: 3px;' 
			type='button' name='subtitle_title' value='".$section_id_query_res['title']."' onclick=\"window.location.href='?q=books&id=".$section_id_query_res['id']."&action=explore'\">
		>
			<input class='menu-login-button-small' 
				style='margin: 2px; font-size: 12px;
				border-top-right-radius: 3px;
				border-bottom-right-radius: 3px;
				' type='button' name='subtitle_title' value='".$category_id_query_res['title']."' onclick=\"window.location.href='?q=books&id=".$category_id_query_res['id']."&action=cats'\">
		</div>

			<button id='comment-button' class='menu-login-button-small' onclick=\"location.href='?q=comments&materialid=".$row['id']."'\"'>Комментарии</button>
				"
		;
	} break;
}

?>