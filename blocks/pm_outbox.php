<style type="text/css">
	#pm_response_body {
		border: 1px solid #ccc;
		border-radius: 5px;
		padding: 5px;
		margin: 5px;
		background-color: #fff;
	}
</style>

<div class="sql-query" style='padding: 5px;'>
	<button onclick='history.back()'>Назад</button><center>Cообщение</center>
	<div id="pm_response_body">Автор: <?=$a['pm_from']?></div>
	<div id="pm_response_body"><?=$a['pm_message']?></div>
</div>