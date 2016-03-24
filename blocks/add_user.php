<?php
$userlist = $db->query("SELECT * FROM admins");
echo 
"
<html>
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
	#ut {
		border-style: solid;
		font-size: 14px;
		
	}
	#user_passwords {
		display: none;
	}
</style>
	<div class='sql-query'>
		</form>
		<b>Добавление нового пользователя</b> </br>
		Примечание: категорически не рекомендуется добавлять администратора в исключения генератора хэшей (вторая галочка)! <br />
		<form action='?q=editor&action=acceptor&mode=user' method='post' name='form'>
			<input class='search_input' type='text' maxlength='100' name='add_user_name' placeholder='Имя пользователя...'>
			<input class='search_input' type='text' maxlength='100' name='add_user_password' placeholder='Пароль пользователя...'>
			<input type='checkbox' name='isadmin' value='confirm'>Добавить пользователя в категорию администраторов<br>
			<input type='checkbox' name='disable_hash_generation' value='confirm'>Разрешить этому пользователю заходить с разных компьютеров одновременно (небезопасно!)<br>
			<center>
			<table border='0'>
			<tr>
				<td>
					<input class='search_button' type='submit' value='Применить' name='adduser_accept'>
				</td>
				<td>
					<input class='search_button' type='submit' value='Отменить' name='adduser_reject'>
				</td>
			</tr>
			</table>
			</center>
		</form>
		<b>Управление пользователями</b> (в разработке) <br />
		<i>Манипуляция данными пользователя возможна лишь тогда, когда они явно отмечены - это сделано для предотвращения случайных действий. После отметки пользователя (<b>#</b>) примените необходимые действия к нему.<br />Внимание: возможна групповая работа с пользователями. Для этого отметьте несколько пользователей для дальнейших манипуляций. Если пользователи были отмечены, но действия - нет, система предупредит об этом и предложит вернуться в меню пользователей.<br /></i>
		<script>
		function toggleTable() {
			var lTable = document.getElementById('ut');
			lTable.style.display = (lTable.style.display == 'none') ? 'table' : 'none';
		}
		</script>
		<input class='search_button' type='submit' value='Показать/скрыть таблицу управления пользователями' onclick='toggleTable()'>
		<form action='?q=editor&action=acceptor&mode=useraction' method='post' name='form'>

			<table id='ut' border='1' cellspacing='0' cellpadding='4'>
			<tbody>
				<tr>
					<td id='ut'>#</td>
					<td id='ut'>Никнейм</td>
					<td id='ut' width='50'>Привилегии</td>
					<td id='ut'>Удалить пользователя</td>
					<td id='ut'>Новый пароль?</td>
					<td id='ut'>Новый никнейм?</td>
				</tr>
			";
			while($userlist_res = $userlist->fetch_assoc())
			{
				if($userlist_res['key_root'] == md5(md5('rooted')))
				{	
					$varPrivileges = "В пользователи";
					$varStyle = "menu-login-button-into-users";
					$varAdminHighlight = "bgcolor='#D8FFE3'";
				}
				else
				{
					$varPrivileges = "В администраторы";
					$varStyle = "menu-login-button-into-admins";
					$varAdminHighlight = '';
				}
				echo "<tr ".$varAdminHighlight.">
					<td id='ut'><input name='checkbox[]' type='checkbox' id='checkbox[]' value='".$userlist_res['u_id']."'></td>
					<td id='ut'>".$userlist_res['u_login']."</td>
					<td id='ut'><input class='menu-login-button' name='checkbox_admins[]' type='submit' id='checkbox_admins[]' name='".$userlist_res['u_id']."' value='".$varPrivileges."'></td>
					<td id='ut'><input name='checkbox_user_delete[]' type='checkbox' id='checkbox_user_delete[]' value='".$userlist_res['u_id']."'></td>
					<td id='ut'><input class='search_input' name='change_password[]' id='change_password[]'></td>
					<td id='ut'><input class='search_input' name='change_username[]' id='change_username[]'></td>
				</tr>";
			}
	echo "		</tbody>
			</table>
			<center><input class='search_button' type='submit' value='Применить изменения...' name='user_functions_accept'></center>
			</form>
		
	</div>
</html>
";

?>