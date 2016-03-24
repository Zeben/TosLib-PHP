<?php

include("plugins/db.php");
$res = $db->query("SELECT * FROM data ORDER BY date DESC , id LIMIT 5");
//$res = $db->query("SELECT * FROM data ORDER BY MONTH(date) DESC, YEAR(date) DESC, id LIMIT 5");
//$res = $db->query("SELECT * FROM data ORDER BY STR_TO_DATE(date, '%d.%m.%Y') DESC, id LIMIT 5");
echo "<div class='sql-query'><center><a style='padding: 2px;'>"; echo $tr->trLastAddedMaterials; echo "</a></center></div>";
while( $row = $res->fetch_assoc())
{
	$_POST['material_id'] = $row['cat'];
	echo "
 			<div class='sql-query'>
 			<table class='post'>
				<tbody>
					<tr>
						<td>
							<b> ".$row['title']." </b> <br>
							<div style='font-size: 13px;color: rgb(100, 100, 100);'>
								Дата добавления: ".date('d.m.Y', strtotime($row['date']))." г. <br>
								Автор: ".$row['autor']." <br>
								Издательство: ".$row['izdatelstvo']." <br>
								Год издания: ".$row['god']." <br>
								Страниц: ".$row['str']." <br>
								Язык: ".$row['lang']." <br>
								Краткое описание: ".$row['description']." <br>";
								$isrooted = $db->query("SELECT * FROM admins WHERE u_id = ".$_COOKIE['id']."");
								if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
								{
									while($resroot = $isrooted->fetch_assoc())
									{
										if($resroot['key_root'] == md5(md5('rooted')))
										{
											echo "<a href='?q=editor&id=".$row['id']."&action=edit&mode=material'>";echo $tr->trMainEdit; echo "</a> <a href='?q=editor&id=".$row['id']."&action=remover&mode=material'>"; echo $tr->trMainDelete; echo"</a>";
										}
									}
								}
								echo "
							</div>
						</td>
						<td>
							<p><a href='".$row['link']."' target='".$row['blank']."'><img src='/upload/image/down.jpg' style='width: 80px; height: 80px;' /></a><p>
						</td>
					</tr>
				</tbody>
			</table>

				<div style='margin: 1px; font-size: 12px;'>";
					include("blocks/subtitle.php");
			echo "
				</div>
			</div>
			<!--<br>-->
	";
}
//$db->close();

?> 
