
<?php
	if(isset($_POST['profiles_create'])) header("Location: ?q=profiles&id='".$_GET['id']."'&action=create");
?>

<?php 


function protect($data) {
	return trim(stripslashes(htmlspecialchars($data)));
}

function isProfileExist($id) {
	include("plugins/db.php");
	$r = $db->query("SELECT * FROM profiles WHERE uid = '".protect($id)."' LIMIT 1");
	if($r->num_rows != 0) return true; else return false;
}

function isUserExist($id) { // вернуть хэш юзера и данные о доступности профиля
							// в случае успеха запроса, иначе вернуть false
	include("plugins/db.php");
	$r = $db->query("SELECT * FROM admins WHERE u_id = '".protect($id)."' LIMIT 1");
	if($r->num_rows != 0) {
		$rr = $r->fetch_assoc();
		return array($rr['u_hash'], $rr['allow_create_profile']);
	} else
		return false;
}

function allowCreateOrViewProfile($id) { // можно ли позволять создавать профиль
	if(isUserExist(protect($id)) != false)
	{
		$arr = isUserExist(protect($id));
		if($arr[1] == 0) return false; else return true;
	}
}

function readOnlyProfile($id) // отображать чужие профили ридонли, если это не свой профиль
{
	if(isUserExist(protect($id)) != false)
	{
		$arr = isUserExist(protect($id));
		if($arr[0] !== $_COOKIE['hash']) return true; else return false;
	}
}


function reversebb($msg) {
	$str_search = array(
     	"/\<b\>(.*?)\<\/b\>/is",
        "/\<i\>(.*?)\<\/i\>/is",
        "/\<u\>(.*?)\<\/u\>/is",
        "/<br \/>/is",
        "/\<a href=\'(.*?)\' target=\'blank\'\>&#8599\<\/a\>/is"
    );

    $str_replace = array(
    	'[b]$1[/b]',
        '[i]$1[/i]',
        '[u]$1[/u]',
        '',
        '$1'
    );

    return preg_replace($str_search, $str_replace, $msg);
}

function replacebb($msg) {
	$str_search = array(
      "#\\\n#is",
      "#&lt;(.+?)\/?&gt;#is",
      "#\[b\](.+?)\[\/b\]#is",
      "#\[s\](.+?)\[\/s\]#is",
      "#\[i\](.+?)\[\/i\]#is",
      "#\[u\](.+?)\[\/u\]#is",
      "#\[code\](.+?)\[\/code\]#is",
      "#\[quote\](.+?)\[\/quote\]#is",
      "#\[url=(.+?)\](.+?)\[\/url\]#is",
      "#\[url\](.+?)\[\/url\]#is",
      "#(https?\:\/\/\S+)#is",
      "#\[size=(.+?)\](.+?)\[\/size\]#is",
      "#\[color=(.+?)\](.+?)\[\/color\]#is",
      "#\[list\](.+?)\[\/list\]#is",
      "#\[listn](.+?)\[\/listn\]#is",
      "#\[\*\](.+?)\[\/\*\]#",
      "#\[img\](.+?)\[\/img\]#is"
    );

    $str_replace = array(
      "<br />",
      "<i style='color: #FF7000;'>теги вырезаны системой</i>",
      "<b>\\1</b>",
      "<strike>\\1</strike>",
      "<i>\\1</i>",
      "<span style='text-decoration:underline'>\\1</span>",
      "<code class='code'>\\1</code>",
      "<table width = '50%'><tr><td>Цитата</td></tr><tr><td class='quote'>\\1</td></tr></table>",
      "<a href='\\1'>\\2</a>",
      "<a href='\\1'>\\1</a>",
      "<a href='\\1' target='blank'>&#8599</a>",
      "<span style='font-size:\\1%'>\\2</span>",
      "<span style='color:\\1'>\\2</span>",
      "<ul>\\1</ul>",
      "<ol>\\1</ol>",
      "<li>\\1</li>",
      "<img src='\\1' alt = 'Изображение' />",
    );

    return preg_replace($str_search, $str_replace, $msg);
}


function safe_var($in_message) 
{
	$in_message = htmlspecialchars($in_message);
	$in_message = replacebb($in_message);
	return $in_message;
}

function reverse_var($in_message) 
{
	$in_message = htmlspecialchars($in_message);
	$in_message = reversebb($in_message);
	return $in_message;
}


// функции для вноса данных в базу

// function isUserAdmin($id)
// {
// 	include("plugins/db.php");
// 	$r = $db->query("SELECT key_root FROM admins WHERE u_id = '".protect($id)."' LIMIT 1");
// 	$rr = $r->fetch_assoc();
// 	if($rr)
// }

if(isset($_POST['createbytemplate'])) {
	if($_COOKIE['id'] === $_GET['id']) {
		include("plugins/db.php");
		$r = $db->query("SELECT u_login FROM admins WHERE u_id = '".protect($_COOKIE['id'])."'");
		$rr = $r->fetch_assoc();
		$db->query("INSERT INTO profiles (uid, name, last_session, description) VALUES ('".protect($_COOKIE['id'])."','".$rr['u_login']."','".date("Y-m-d H:i:s")."','[отсутствует]')");
		echo 
		"
		<div class='sql-query' style='padding: 5px; '>
			<center><b>Профиль создан. Теперь вы можете редактировать его по усмотрению.</b></center>
		</div>
		";
	}
}

if(isset($_POST['editprofile_accept'])) {
	if($_COOKIE['id'] === $_GET['id']) {
		$pr_username = '';
		$pr_userbirth = '';
		$pr_useraddress = '';
		$pr_userreflink = '';
		if(!readOnlyProfile(protect($_GET['id']))) {
			include("plugins/db.php");
			if(!empty($_POST['profile_user_name'])) {
				$pr_username = ", name = '".protect($_POST['profile_user_name'])."'"; 
			}

			if(!empty($_POST['profile_user_address'])) {
				$pr_useraddress = ", address = '".protect($_POST['profile_user_address'])."'"; 
			}

			if(!empty($_POST['profile_user_ref_link'])) {
				$pr_userreflink = ", ref_link = '".preg_replace("#(https?\:\/\/\S+)#is", "<a href=\\1>\\1</a>", htmlspecialchars($_POST['profile_user_ref_link']))."'"; 
				//$pr_userreflink = ", ref_link = 'lol'"; 
				//echo $pr_userreflink;
			}

			if(!empty($_POST['profile_user_birth'])) {
				$pr_userbirth = ", birth = '".protect($_POST['profile_user_birth'])."'"; 
			}
			//echo safe_var($_POST['profile_user_description']);
			$pr_desc = $db->real_escape_string(safe_var($_POST['profile_user_description']));
			$q = "UPDATE profiles SET description = '" . $pr_desc. "' " . $pr_username . " " . $pr_useraddress . " " . $pr_userreflink . " " . $pr_userbirth . " where uid = ".protect($_GET['id'])."";
			//echo $q;
			if(!$db->query($q)) echo "<center>Ошибка во время изменения записей: " . $db->error ."</center>";
			//echo "<textarea>".$pr_desc."</textarea>";
		}
	}
}


if(!isset($_GET['id'])) // если не указан ID
{
	include 'error_messenger.php';
	echo err("ID пользователя не указан.");
}
elseif(isUserExist($_GET['id']) == false)  // если юзера с таким ID нет
{
	include 'error_messenger.php';
	echo err("Нет такого пользователя.");
}
elseif(!isProfileExist($_GET['id']))
{
	$r0 = $db->query("SELECT u_login FROM admins WHERE u_id = '".protect($_GET['id'])."' LIMIT 1");
	$rr0 = $r0->fetch_assoc();
	echo 
	"
	<div class='sql-query' style='padding: 5px; '>
		<center>Профиль пользователя <b>".$rr0['u_login']."</b></center>
	</div>
	<div class='sql-query' style='padding: 5px; '>
		<center><b>Информация о пользователе отсутствует</b></center>
	</div>
	";
	if(!allowCreateOrViewProfile(protect($_GET['id']))) {
		if(!readOnlyProfile(protect($_GET['id']))) {
			echo 
			"
			<div class='sql-query'>
				<form action='?q=profiles&id=".protect($_COOKIE['id'])."&action=createbytemplate' method='post' name='form_profiles_createbytemplate'>
					<center><input type='submit' class='search_button' value='Исправим?' name='createbytemplate'></input></center>
				</form>
			</div>
			";
		}
	}

} 
elseif(allowCreateOrViewProfile($_GET['id']))
{
	include 'error_messenger.php';
	echo err("Профиль заблокирован.");
}
else 
{
	$rid = $_GET['id'];
	$r1 = $db->query("SELECT u_login, key_root FROM admins WHERE u_id = '".protect($rid)."' LIMIT 1");
	$rr1 = $r1->fetch_assoc();
	$r2 = $db->query("SELECT * FROM profiles WHERE uid = '".protect($rid)."' LIMIT 1");
	$rr2 = $r2->fetch_assoc();
	$date = date_create($rr2['last_session']); // вытаскиваем штамп времени авторизации
	if(!readOnlyProfile(protect($rid))) {
		echo 
		"

		<div class='sql-query'>
			<center>
				<button class='search_button' id='line_handler'>Редактировать</button>
			</center>
			<p>
			Нажимайте 'Редактировать' для того, чтобы показать/скрыть поля редактирования профиля. Костыльно, но удобно. При нажатии кнопки также появится кнопка применения изменений.
			<p>
			Поддерживаются bb-коды: [i],[b],[u]. Список кодов пополню. 
		</div>
		<script>
			btn = document.getElementById('line_handler');
			classes = document.getElementsByClassName('profile_lines');
			console.log(classes);

			btn.addEventListener('click', function(e){
				for(var i = 0, l = classes.length; i < l; i++) {
					classes[i].style.display = (classes[i].style.display == 'block' ? 'none' : 'block');
				}
				e.preventDefault();
			})
		</script>
		<form action='?q=profiles&id=".protect($rid)."&action=editor_process' method='post' name='profile_editor_form'>";
	}
	echo 
	"
	<div class='sql-query' style='padding: 5px; '>
		<center>Профиль пользователя <b>".$rr1['u_login']."</b></center>
	</div>
	<div class='sql-query' style='padding: 5px; '>";
		echo "<p>Настоящее имя: <b>".$rr2['name']."</b> "; if(!readOnlyProfile(protect($rid))) {
			echo 
			"
			<input style='display:none; width: 100%;' id='profile_editor' class='profile_lines' type='text' maxlength='30' name='profile_user_name' placeholder='Можно оставить пустым. Или выпендриться'>
			";
		}
		echo "<p>Дата рождения: <b>".date('d.m.Y г', strtotime($rr2['birth']))."</b> "; if(!readOnlyProfile(protect($rid))) {
			echo 
			"
			<input style='display:none; width: 100%;' id='profile_editor' class='profile_lines' type='date' maxlength='30' name='profile_user_birth'>
			";
		}
		echo "<p>Место жительства: <b>".$rr2['address']."</b> "; if(!readOnlyProfile(protect($rid))) {
			echo 
			"
			<input style='display:none; width: 100%;' id='profile_editor' class='profile_lines' type='text' maxlength='30' name='profile_user_address' placeholder='Можно оставить пустым.'>
			";
		}
		echo "<p>Ссылка на свой сайт: <b>".$rr2['ref_link']."</b> "; if(!readOnlyProfile(protect($rid))) {
			echo 
			"
			<input style='display:none; width: 100%;' id='profile_editor' class='profile_lines' type='text' maxlength='100' name='profile_user_ref_link' placeholder='Можно оставить пустым.'>
			";
		}

	echo "
	</div>
	<div class='sql-query' style='padding: 5px; '>
		Категория: <b>"; if($rr1['key_root'] == md5(md5('rooted'))) echo 'Администраторы'; else echo 'Пользователи'; echo "</b>
	</div>
	<div class='sql-query' style='padding: 5px; '>
		Заходил на сайт: <b>".date_format($date, 'd.m.Y г. H:i:s')."</b>
	</div>
	<div class='sql-query' style='padding: 5px; '>
		Описание: <br>".$rr2['description']." <br>"; if(!readOnlyProfile(protect($rid))) {
			echo 
			"
			<textarea style='display:none;' id='profile_editor' class='profile_lines' type='textarea' cols='100' rows='10' name='profile_user_description'>".reversebb($rr2['description'])."</textarea>
			";
		}

		echo "
	</div>
	<div class='sql-query profile_lines' style='padding: 5px; display: none;'>
		<input id='profile_editor' type='submit' value='Применить изменения' name='editprofile_accept'>
	</div>
	";
}

if(isset($_POST['editprofile_accept'])) {
	echo 
	"

	<div class='sql-query' style='padding: 5px; '>
		<center><b>Данные профиля обновлены.</b></center>
	</div>

	";
}

?>