<?php

function err_generic($err_text, $redir_link, $redir_button_message)
{
	$temporary = 
	"
	<html>
		<div class='sql-query'>
		<center>
			<form action='".$redir_link."' method='post' name='form'>
			<br />
				".$err_text."<br />
				<input class='search_button' type='submit' value='".$redir_button_message."' name='return'>
			</form>
			</center>
		</div>
	</html>
	"; 
	return $temporary;
}

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
function err($err_text)
{
	$temporary = 
	"
	<html>
		<div class='sql-query'>
		<center>
			<form action='?q=content' method='post' name='form'>
			<br />
				".$err_text."<br />
				<input class='search_button' type='submit' value='Вернуться на главную' name='return'>
			</form>
			</center>
		</div>
	</html>
	"; 
	return $temporary;
}

?>