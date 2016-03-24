
<?php

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

function protect($data) { 
	return trim(stripslashes(htmlspecialchars($data)));
}

function isPmBlocked($uhash) { // если личка полностью заблокирована для юзера
	include("plugins/db.php");
	$q = "SELECT pm_blocked FROM admins WHERE u_hash = '".protect($uhash)."'";
	$r = $db->query($q);
	if($rr = $r->fetch_assoc()) {
		if($rr['pm_blocked'] == 1) return true; 
	} else return false;
}



function getInPmListForId($id, $page) { // списки входящих
	$rrr;
	include("plugins/db.php");
	$q = "SELECT * FROM pm WHERE pm_uid_to = '".protect($id)."' ORDER BY pm_time DESC LIMIT ".($page * 10).", 10";
	if(!$db->query($q)) return false;
	$r = $db->query($q);
	while($rr = $r->fetch_assoc()) {
		$rrr[] = $rr;
	}
	if(!isset($rrr)) return false;
	return $rrr;
}

function countPmIn($id) { // пересчет количества писем для пагинатора
	include("plugins/db.php");
	$q = "SELECT * FROM pm WHERE pm_uid_to = '".protect($id)."'";
	$r = $db->query($q);
	return $r->num_rows;
}

function countPmOut($id) { // пересчет количества писем для пагинатора
	include("plugins/db.php");
	$q = "SELECT * FROM pm WHERE pm_uid_from = '".protect($id)."'";
	$r = $db->query($q);
	return $r->num_rows;
}

function getOutPmListForId($id, $page) { // списки исходящих
	$rrr;
	include("plugins/db.php");
	$q = "SELECT * FROM pm WHERE pm_uid_from = '".protect($id)."' ORDER BY pm_time DESC LIMIT ".($page * 10).", 10";
	if(!$db->query($q)) return false;
	$r = $db->query($q);
	while($rr = $r->fetch_assoc()) {
		$rrr[] = $rr;
	}
	if(!isset($rrr)) return false;
	return $rrr;
}

function getPmById($id) { // получить конкретное письмо по айдишнику для прочтения
	include("plugins/db.php");
	$q = "SELECT * FROM pm WHERE id = '".protect($id)."' LIMIT 1";
	$r = $db->query($q);
	if($rr = $r->fetch_assoc()) {
		return $rr; 
	} else return false;
}

function isOwnerBannedBySender($to) { // узнать, забанен ли владелец, который шлёт письмо другому юзеру (to)
	$ban_status = false;
	include("plugins/db.php");
	$q = "SELECT * FROM pm_ban WHERE uid = '".protect($to)."'";
	$r = $db->query($q);
	while($rr = $r->fetch_assoc()) {
		if($rr['bans'] === protect($_COOKIE['id'])) {
			$ban_status = true;
		}
	}

	return $ban_status;
}

function getOwnerName() { // узнать имя владельца
	include("plugins/db.php");
	$q = "SELECT u_login FROM admins WHERE u_hash = '".protect($_COOKIE['hash'])."'";
	$r = $db->query($q);
	$rr = $r->fetch_assoc();
	return $rr['u_login'];
}

function getNameById($id) {
	include("plugins/db.php");
	$q = "SELECT u_login FROM admins WHERE u_id = '".protect($id)."'";
	$r = $db->query($q);
	if($rr = $r->fetch_assoc()) return $rr['u_login']; else return false;
}

function safe_var($in_message) 
{
	$in_message = htmlspecialchars($in_message);
	$in_message = replacebb($in_message);
	return $in_message;
}

///////////////////////////////////////////////////////////////
if(isset($_GET['readout'])) $readout = protect($_GET['readout']);
$id = protect($_COOKIE['id']);
if(isset($readout) && !empty($readout)) {
	$a = getPmById($readout);
	if(!getPmById($readout)) {
		$_POST['pm_error'] = 'Сообщения не существует.';
		include 'blocks/pm_errors.php';
	} else {
		if($id === $a['pm_uid_to'] || $id === $a['pm_uid_from']) {
			include 'blocks/pm_outbox.php';
		} else {
			$_POST['pm_error'] = 'Сообщения не существует.';
			include 'blocks/pm_errors.php';
		} 
	}
	
} else
if(isset($_GET['read'])) {
	$read = protect($_GET['read']);
	$id = protect($_COOKIE['id']);
	if(isset($read) && !empty($read)) {
		$a = getPmById($read);
		if(!getPmById($read)) {
			$_POST['pm_error'] = 'Сообщения не существует.';
			include 'blocks/pm_errors.php';
		} else {
			if($id === $a['pm_uid_to'] || $id === $a['pm_uid_from']) {
				include 'plugins/db.php';
				$db->query("UPDATE pm SET pm_unreaded = '0' WHERE id = '".$a['id']."'");
				include 'blocks/pm_response.php';
			} else {
				$_POST['pm_error'] = 'Сообщения не существует.';
				include 'blocks/pm_errors.php';
			} 
		}
	}
}
else
if(isset($_GET['writeto'])) {
	$writeto = protect($_GET['writeto']);
	if(isset($writeto) && !empty($writeto)) {
		$name = getNameById($writeto);
		include 'blocks/pm_new.php';
	}
} 
else {
	if(!isset($_GET['inbox'])) $pin = 0; else $pin = protect($_GET['inbox']);
	if(!isset($_GET['outbox'])) $pout = 0; else $pout = protect($_GET['outbox']);
	$cid = protect($_COOKIE['id']);

	$count_in = countPmIn($cid);
	if($pin > $count_in / 10) $pin = floor($count_in / 10);
	$arr = getInPmListForId($cid, $pin);
	

	$count_out = countPmOut($cid);
	if($pout > $count_out / 10) $pout = floor($count_out / 10);
	$arr2 = getOutPmListForId($cid, $pout);

	include 'blocks/pm_list.php';
	if(isset($_GET['outbox'])) echo "<script>hideOut();</script>"; else echo "<script>hideIn();</script>";
	//echo $pin."<br>".$pout;
}

if(isset($_GET['sendto'])) {
	$sendto = protect($_GET['sendto']);
	if(!empty($sendto)) {
		if(!empty($_POST['pm_text'])) {
			if(isOwnerBannedBySender($sendto)) {
				$_POST['pm_error'] = 'Пользователь заблокировал получение писем от Вас.';
				include 'blocks/pm_errors.php';
			}
			else {
				include 'plugins/db.php';
				$pm_text = $db->real_escape_string(safe_var($_POST['pm_text']));
				$q = "INSERT INTO pm (pm_uid_from, pm_uid_to, pm_from, pm_to, pm_message, pm_time, pm_unreaded) VALUES ('".protect($_COOKIE['id'])."', '".$sendto."', '".getOwnerName()."', '".getNameById($sendto)."', '".$pm_text."', '".date("Y-m-d H:i:s")."', '1')";
				if(!$db->query($q)) echo "Ошибка выполнения запроса: ".$db->error;
				echo "<script>window.location.href='?q=pm';</script>";
				//$_POST['pm_error'] = 'Сообщение отправлено.';
				//include 'blocks/pm_errors.php';

			}
		}
	}
}




?>