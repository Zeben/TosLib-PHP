<?php

include("plugins/db.php");
$_GET['keyword'] = $search;
$res = $db->query("SELECT * FROM data WHERE  title like '%$search%' OR autor like '%$search%'");
if (!$res)
{
	{ $_GET['errnum'] = "err_db_search_selection";  include 'dyncontent/errors.php'; }
	exit(mysql_error());
}
if($res->num_rows == 0)
{ 
	$_GET['errnum'] = "err_none_in_search_results"; 
	include 'dyncontent/errors.php';  
}
else
{
printf("
					<div class='sql-query' style='padding: 2px;'>
						Результаты поиска:
					</div>
		");
	while($row = $res->fetch_assoc())
	{
		$_POST['material_id'] = $row['cat'];
		echo "<div class='sql-query'>";
		printf("
				<table class='post'>
					<tbody>
						<tr>
							<td style='width:700px;'>
								<b> %s </b> <br>
								<div style='font-size: 13px;color: rgb(100, 100, 100);'>
									Дата добавления: %s г. <br>
									Автор: %s <br>
									Издательство: %s <br>
									Год издания: %s <br>
									Страниц: %s <br>
									Язык: %s <br>
									Краткое описание: %s <br>
								</div>
							</td>
							<td>
								<p><a href='%s' target='%s'><img src='/upload/image/down.jpg' style='width: 80px; height: 80px;' /></a><p>
							</td>
						</tr>
					</tbody>
				</table>
				<!--<br>-->
		",
		$row['title'], $row['date'], $row['autor'], $row['izdatelstvo'], $row['god'], $row['str'], $row['lang'], $row['description'], $row['link'], $row['blank']);
		include("blocks/subtitle.php");
		echo "</div>";
	}

}
//$db->close();

?> 
