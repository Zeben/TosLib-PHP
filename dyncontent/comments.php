<?php

require('plugins/db.php');

function protect($input) {
	return trim(stripslashes(htmlspecialchars($input)));
}

$material_id = $db->query("SELECT * FROM data WHERE id = '".protect($_GET['materialid'])."'");
$material_id_result = $material_id->fetch_assoc();
/*

Описание таблиц comments
uid = значение, к какому материалу относится коммент
comment = сам коммент
userid = идентификатор юзера, выборка из таблицы admins или через куки (небезопасно)
masterid = запасная таблица, указывает родительский коммент
deep - глубина вложения

*/

// попробовать отдать данные в JSON?

?>

<input id='comment-uid' type='hidden' value=<?=$_GET['materialid']?>>

<div class='sql-query'>
	<center> Комментарии к материалу <b>"<?=$material_id_result['title']?>"</b> </center>
</div>

<div id='comments-wrapper'>
</div>

