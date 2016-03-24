<?php

function create_table($table_name)
{
	$return_query = "
	CREATE TABLE `".$table_name."` 	(
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`t1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		`t2` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		`t3` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		`t4` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		`t5` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		`t6` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		`t7` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		PRIMARY KEY(`id`)
	) ENGINE = InnoDB
";
	return $return_query;
}

function csv_insert($file, $table)
{
	include 'plugins/db.php'; // коннектимся к БД
	include 'plugins/csv2.php'; // подключаем парсер csv
	$ra = $db->query("SHOW TABLES"); // запрос: какие таблицы есть в системе
	$data_array = csv_parser("upload/".$file); // преобразуем данные файла в 2мерный массив
	$isTableExist = FALSE; // по умолчанию таблицы не существует
	while($row = $ra->fetch_row()) // извлекаем данные о таблицах
	{
		if($row[0] == $table) { $isTableExist = TRUE;  } // если есть таблица с названием...
	}
	if($isTableExist) // выносим существующую таблицу
	{
		echo "<br />Уже есть таблица c таким названием, удаляем... <br />";
		$db->query("DROP TABLE ".$table."");
		$isTableExist = FALSE;
	}
	if(!$isTableExist) // создаем заново
	{
		echo "<br />Таблицы теперь нет, создаем... <br />";
		if(!$db->query(create_table($table))) echo "Ошибка выполнения запроса! : ".$db->error; else $isTableExist = TRUE; // теперь норм
	}
	if($isTableExist) // иначе сразу выполняем запросы
	{	
		$t_count = 0; // костыль для пропуска заголовочной строки
		$t_strip = 0; // сколько строк вырезано
		echo "Таблица создана, выполняем запросы... <br />";
		foreach($data_array as $key0 => $value) 
		{ 	
			$t_count++;
			if(($value[1] == '') || ($t_count == 1)) { $t_strip++; continue; }
			else 
			{ 
				// прошерстить в цикле строку t3 на наличие апострофа
				$t3_coma_remover = $value[2];
				// считаем количество символов в стоке
				$t3_count = strlen($t3_coma_remover);
				// выполняем цикл посимвольного чтения массива
				for($j = 0; $j < $t3_count; $j++)
				{
					if($t3_coma_remover[$j] == "'") // если в строке встечается апостроф
					{
						$value[2] = $db->real_escape_string($value[2]); // экранируем его
					}
				}
				if(!$db->query("INSERT INTO ".$table." (t1, t2, t3, t4, t5, t6, t7) VALUES('".$value[0]."', '".$value[1]."', '".$value[2]."', '".$value[3]."', '".$value[4]."', '".$value[5]."', '".$value[6]."')")) echo "<b>Ошибка парсинга строки (".$t_count." БД)! SQLError ".$db->errno.": </b>".$db->error."<br />";
			}
						
		}
		echo "<br />Обработано строк: ".$t_count;
		echo "<br />Пропущено строк: ".$t_strip;
		$result = $t_count - $t_strip;
		echo "<br />Запросов в БД: ".$result;
		echo "<br />Готово. Проверьте БД.";
	}
			// на основании кол-ва столбцов должны сгенерировать запрос
			// название таблицы - второй аргумент
}

?>