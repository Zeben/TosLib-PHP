<?php
function err_user($err_text)
{
	$temporary = 
	"
	<html>
		<div class='sql-query'>
		<center>
			<form action='?q=editor&action=addition&mode=user' method='post' name='form'>
			<br />
				".$err_text."<br />
				<input class='search_button' type='submit' value='Назад' name='return'>
			</form>
			</center>
		</div>
	</html>
	"; 
	return $temporary;
}
?>