
		<p style="text-align: center;">Авторизироваться</p>
		<form name="login-form" method="POST">
			<input class="menu-auth" type="text" maxlength="20" name="menu-login" placeholder="Логин..."><br>
			<input class="menu-auth" type="password" maxlength="20" name="menu-password" placeholder="Пароль..."><br>
			<center><input class="menu-login-button" type="submit" value="Вход" name="login"></center>
		</form>

<?php

// если наличествуют куки с ид и хэшем / и в базе хэш не равен нулю
function getRandomCode($length = 6) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
	$code = "";
	$clen = strlen($chars) - 1;
	while(strlen($code) < $length) {
		$code .= $chars[mt_rand(0, $clen)];
	}
	return $code;
}
include("plugins/db.php");
	if(isset($_POST['login'])) # если нажата кнопка отправки...
	{
		if(empty($_POST['menu-login']) && (empty($_POST['menu-password']))) { # если не заполнены поля
			echo "Заполните поля, не тыкайте в кнопку просто так!";
		}
		else if(empty($_POST['menu-login']) || (empty($_POST['menu-password']))) { # если хоть одно не заполнено
			echo "Заполнены не все поля!";
		}
		else if(!empty($_POST['menu-login']) && (!empty($_POST['menu-password']))) { # если же всё норм
			# выполнить запрос и сравнить пароли
			$res = $db->query("SELECT * FROM admins WHERE u_login = '".$_POST['menu-login']."' LIMIT 1");
			$rowdata = $res->fetch_assoc(); //echo "TRUE! <br />";
			if($rowdata['u_pass'] !== md5(md5($_POST['menu-password']))) { # сравнить пароли (введенный и в БД)
				echo "<b>Неверный(е) логин и/или пароль.</b>";
			} 
			else {
				
				if($rowdata['session'] == 1)
					echo "Данный пользователь уже авторизирован в системе!";
				else 
				{
					echo "process";
					if($rowdata['disable_hash_generation'] == 0)
						$hash = md5(getRandomCode(10)); # получаем хэш рандомного кода, ужс
					else
						$hash = md5(md5('disable_hash_generation')); # делаем хэш статичным, если это указано в настр. юзера			
					$inaddr = ", u_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')"; # перевод IP в строку
					$dbhash = $db->query("UPDATE admins SET u_hash='".$hash."' ".$inaddr." WHERE u_id='".$rowdata['u_id']."'"); # обновляем хэш
					setcookie("id", $rowdata['u_id'], time() + 60 * 60 * 24 * 30);
					setcookie("hash", $hash, time() + 60 * 60 * 24 * 30);
					$db->query("UPDATE profiles SET last_session = '".date("Y-m-d H:i:s")."' WHERE uid = '".$rowdata['u_id']."'");
					//if($rowdata['disable_hash_generation'] != 1) // если хэширование не отключено
						//$db->query("UPDATE admins SET session = 1 WHERE u_id = '".$rowdata['u_id']."'");
					header("Location: ?q=content"); exit();
				}
			}
		}
	}
?>




 