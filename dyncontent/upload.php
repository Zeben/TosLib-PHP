<div class="sql-query">
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
<?php
	if(empty($_POST['table_name'])) echo "Введите название таблицы в БД!";
	else if(empty($_FILES["file"]["name"])) echo "Файл не выбран.";
	else
	{
		echo "Upload: " . $_FILES["file"]["name"] . "<br>"; 
		$allowedExts = array("csv"); 
		$extension = end(explode(".", $_FILES["file"]["name"])); 
		if(($_FILES['file']['size'] < 20000000) && in_array($extension, $allowedExts)) 
		{
			if($_FILES['file']['error'] == 0) 
			{
				echo "   Upload: " . $_FILES["file"]["name"] . "<br>"; 
				echo "   Type: " . $_FILES["file"]["type"] . "<br>"; 
				echo "   Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "   Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
				if (file_exists("upload/" . $_FILES["file"]["name"])) 
				{ 
					echo $_FILES["file"]["name"] . "  <br /> файл уже на сервере, но заменяем... <br />";
					unlink("upload/".$_FILES["file"]["name"]);
				}
				move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]); 
				echo "   Сохранено в: " . "upload/" . $_FILES["file"]["name"];
				
			}
			else echo "   Return Code: " . $_FILES["file"]["error"] . "<br>";  
			include 'plugins/csv_inserter.php'; // поключаем API для загрузки данных с csv в БД (описание см. в файле)
			csv_insert($_FILES["file"]["name"], $_POST['table_name']); // вот тут начинается жопа
		}
		else echo "Скорее всего, файл офигенно большой, либо Вы попытались залить не CSV.";
	}
	
?> 
</div>