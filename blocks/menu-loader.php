<?php 
include("plugins/db.php");
	if(isset($_COOKIE['hash']))
	{
		$r = $db->query("SELECT * FROM admins WHERE u_hash = '".$_COOKIE['hash']."'");
		$cook = $r->fetch_assoc();
	}
	$r2 = $db->query("SELECT * FROM razdeli");
	while($row1 = $r2->fetch_assoc())
	{
		echo "
		<li>
			<a href='?q=books&id=".$row1['id']."&action=explore'>".$row1['title']."</a>
		";
			echo "<ul>";
			if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
				if(($cook['key_root'] == md5(md5('rooted'))))
					echo "
					<li>
						<a><b>Настройки раздела и категорий...</b></a>
						<ul>
						<li>
							<a href='?q=editor&id=".$row1['id']."&action=addition&mode=category'>Добавить категорию...</a>
						</li>
						<li>
							<a href='?q=books&id=".$row1['id']."&action=removeraz'><b>Удалить раздел</b></a>
						</li>
						</ul>
					</li>";
					
				$r3 = $db->query("SELECT * FROM categories WHERE razdel = ".$row1['id']."");
				while($row3 = $r3->fetch_assoc())
				{
					echo 
					"
					<li>";
					if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
						if(($cook['key_root'] == md5(md5('rooted'))))
						{
							echo "<ul>
									<li>
										<a href='?q=editor&id=".$row3['id']."&action=remover&mode=category'><b>Удалить категорию...</b></a>
									</li>
									<li>
										<a href='?q=editor&id=".$row3['id']."&action=edit&mode=category'><b>Переименовать категорию...</b></a>
									</li>
								 </ul>";
							
						}
					echo "
						<a href='?q=books&id=".$row3['id']."&action=cats'>".$row3['title']."</a>
					</li>
					";
				}
			echo "</ul>";
		echo 
		"
		</li>
		";
	}
	if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
		if(($cook['key_root'] == md5(md5('rooted'))))
		{
			echo "
			<li>
				<a href='?q=xmlparser'>Импорт CSV-файла...</a><br>
			</li>
			<li>
				<a href='?q=editor&action=addition&mode=title'><b>Добавить раздел...</b></a><br>
			</li>"
			;
		}
?>

