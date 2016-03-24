<style type="text/css">
	#pm_response_body {
		border: 1px solid #ccc;
		border-radius: 5px;
		padding: 5px;
		margin: 5px;
		background-color: #fff;
	}
</style>

<form action="?q=pm&sendto=<?=$writeto?>" method='post' name='form_profiles_createbytemplate'>
<div class="sql-query" style='padding: 5px;'>

<center>Написать пользователю <?=$name?>:</center>
<textarea style="width: 100%" name="pm_text"></textarea>
<center><button>Отправить</button></center>
</div>
</form>