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
	<div id="pm_response_body">От: <?=$a['pm_from']?></div>
	<div id="pm_response_body"><?=$a['pm_message']?></div>
</div>

<form action="?q=pm&sendto=<?=$a['pm_uid_from']?>" method='post' name='form_profiles_createbytemplate'>
<div class="sql-query" style='padding: 5px;'>
<center>Ответить:</center>
<textarea style="width: 100%" name="pm_text"></textarea>
<center><button>Отправить</button></center>
</div>
</form>