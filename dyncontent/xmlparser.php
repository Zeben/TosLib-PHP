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
<div class='sql-query'>
	Заметка: для правильного импорта CSV-файла необходимо сохранить любой прайс формата excel в csv с ";" в качестве разделителя поля и " в качестве разделителя текста (libreoffice). <br />
	<a target="_blank" href="http://s019.radikal.ru/i625/1401/22/4731f88692f8.png"><img src="http://s019.radikal.ru/i625/1401/22/4731f88692f8t.jpg" ></a>
	<br />
	<form action="?q=upload" method="post" enctype="multipart/form-data">
		<input class="search_input" type="text" size="40" name="table_name" placeholder="Название таблицы..."><br />
		<label for="file">Файл CSV для импорта в БД:</label>
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Отправить">
	</form>
</div>