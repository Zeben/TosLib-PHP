<?php 
Header("Cache-Control: no-cache, must-revalidate"); // говорим браузеру что-бы он не кешировал эту страницу
Header("Pragma: no-cache");
if(isset($_POST['last'])) {

	include '../plugins/db.php';
	$hashgencheck = $db->query("SELECT disable_hash_generation FROM admins WHERE u_hash = '".$_COOKIE['hash']."' LIMIT 1");
	$r_hashgencheck = $hashgencheck->fetch_assoc();

	$res = $db->query("SELECT * FROM (SELECT * FROM chat ORDER BY id DESC LIMIT 100) AS tbl ORDER BY id ASC");
	$test_array = array();
	$test_counter = 0;
	if($r_hashgencheck['disable_hash_generation'] == 0)	
		while($res_arr = $res->fetch_assoc()) {
			$test_array[] = array($test_counter => $res_arr);
		}	
	echo json_encode($test_array);
	$db->close();
}

function safe_var($in_message) 
{
	echo $in_message;
	$in_message = htmlspecialchars($in_message);
	$in_message = replacebb($in_message);
	return $in_message;
}

function replacebb($msg) {
	$str_search = array(
      "#\\\n#is",									//  1
      "#&lt;(.+?)\/?&gt;#is",						//  2
      "#\[b\](.+?)\[\/b\]#is",						//  3
      "#\[s\](.+?)\[\/s\]#is",						//  4
      "#\[i\](.+?)\[\/i\]#is",						//  5
      "#\[u\](.+?)\[\/u\]#is",						//  6
      "#\[code\](.+?)\[\/code\]#is",				//  7
      "#\[qu\](.+?)\[\/qu\]#is",				//  8
      "#\[url=(.+?)\](.+?)\[\/url\]#is",			//  9
      "#\[url\](.+?)\[\/url\]#is",					// 10
      "#(https?\:\/\/\S+)#is",						// 11
      "#\[size=(.+?)\](.+?)\[\/size\]#is",			// 12
      "#\[color=(.+?)\](.+?)\[\/color\]#is",		// 13
      "#\[list\](.+?)\[\/list\]#is",				// 14
      "#\[listn](.+?)\[\/listn\]#is",				// 15
      "#\[\*\](.+?)\[\/\*\]#",						// 16
    );

    $str_replace = array(
      "<br />",																						//  1
      "<i style='color: #FF7000;'>теги вырезаны системой</i>",										//  2
      "<b>\\1</b>",																					//  3
      "<strike>\\1</strike>",																		//  4
      "<i>\\1</i>",																					//  5
      "<span style='text-decoration:underline'>\\1</span>",											//  6
      "<code class='code'>\\1</code>",																//  7
      "<p><div style='border-left: 3px solid #333; margin-left: 5px; padding-left: 3px;'>Цитата:<br><i>\\1</i></div></p>",	//  8
      "<a href='\\1'>\\2</a>",																		//  9
      "<a href='\\1'>\\1</a>",																		// 10
      "<a href='\\1' target='blank'>&#8599</a>",													// 11
      "<span style='font-size:\\1%'>\\2</span>",													// 12
      "<span style='color:\\1'>\\2</span>",															// 13
      "<ul>\\1</ul>",																				// 14
      "<ol>\\1</ol>",																				// 15
      "<li>\\1</li>"																				// 16
    );

    return preg_replace($str_search, $str_replace, $msg);
}

if(isset($_POST['add'])) {
	if(empty($_POST['add'])) echo "msg_empty";
	else {
		date_default_timezone_set("Europe/Kiev");
		$timestamp = time();
		include '../plugins/db.php';
		//echo $_COOKIE['id'];
		if(!$res = $db->query("SELECT u_login, u_ip, disable_hash_generation FROM admins WHERE u_hash = '".$_COOKIE['hash']."'")) echo "can't query rows: ".$db->error;
		$res_row = $res->fetch_assoc();

		$msg_data = 0;

		if((ip2long($_SERVER['REMOTE_ADDR']) == $res_row['u_ip']) && $res_row['disable_hash_generation'] == 0) { // FIXME : если будут жаловаться - пересмотреть это условие
			$db->query("INSERT INTO chat (user_id, timest, message) VALUES ('".$res_row['u_login']."', '".date('H:i:s', $timestamp)."', '".$db->real_escape_string(safe_var($_POST['add']))."')");
		} else return;
		$lastid = $db->insert_id;
		$db->query("UPDATE chat_members SET lastwrite = '".$timestamp."' WHERE uid = '".$_COOKIE['id']."'");
		$db->close();
	}
}



if(isset($_POST['update'])) {

	include '../plugins/db.php';
		
	$lid = $_POST['lid'];
	//$res = $db->query("SELECT * FROM chat WHERE id > '".$lid."' LIMIT 20 ");
	$res = $db->query("SELECT * FROM (SELECT * FROM chat WHERE id > '".$lid."' ORDER BY id DESC LIMIT 20) AS tbl ORDER BY id ASC");
	
	//$res = $db->query("SELECT * FROM chat ORDER BY timest ASC LIMIT 10");
	//$res = $db->query("SELECT * FROM (SELECT * FROM chat ORDER BY id DESC LIMIT 10) AS tbl ORDER BY id ASC");
	//$res_arr = $res->fetch_assoc();
	
	if(isset($_COOKIE['id']) && isset($_COOKIE['hash'])) // если установлены все куки
	{
		//$rrr = $db->query("SELECT u_ip FROM admins WHERE u_hash = '".$_COOKIE['hash']."'"); // извлекаем инфу о юзере по куки
		//if($r = $rrr->fetch_assoc()) {
			//if($r['u_ip'] == ip2long($_SERVER['REMOTE_ADDR'])) {
				$test_array = array();
				$test_counter = 0;
				while($res_arr = $res->fetch_assoc()) {
					$test_array[] = array($test_counter => $res_arr, 'ii' => $res_arr['id']);
				}
			//}
			
			echo json_encode($test_array);
			
		//} else return;
		
	} else echo 'msg_empty';
	
	$db->close();
}


if(isset($_POST['members'])) {
	include '../plugins/db.php';
	date_default_timezone_set("Europe/Kiev"); // киевское время
	$timestamp = time(); // юникс таймштамп

	$ip = $_SERVER['REMOTE_ADDR']; // узнаем удаленный ip посылающего
	$ip = ip2long($ip); // превращаем его в Long-строку

	$curTime = date('H:i:s', $timestamp); // узнаем текущее время в человеческом виде

	$id = $_COOKIE['id']; // узнаем куки посылающего
	$chash = $_COOKIE['hash'];

	$online_time = 30; // выставляем секунды, когда можно считать, что юзер вышел
	$sleep_time = 300; // если юзер 5 минут не пишет, то переменная становится true

	$delete_time = $timestamp - $online_time;
	$sleep_member = $timestamp - $sleep_time;
	$sleep_value = 0;
	

	$res = $db->query("SELECT uid, lastwrite FROM chat_members WHERE uid = '".$id."'"); // проверяем по айдишнику с куки, есть ли у нас такой юзер
	if($res->num_rows != 0) { // если есть...
		if($res_sleep = $res->fetch_assoc()) {
			$res_sleep['lastwrite'] < $sleep_member ? $sleep_value = 1 : $sleep_value = 0;
		}
		$for_update_check = $db->query("SELECT u_id FROM admins WHERE u_hash = '".$chash."'");
		if($for_update_check->num_rows == 0)
			return;
		else
			$db->query("UPDATE chat_members SET lastses = '".$timestamp."', isSleep = '".$sleep_value."' WHERE uid = '".$id."'"); // то обновляем время юзера
	} else  
	{
		$name = $db->query("SELECT u_login, u_id, disable_hash_generation FROM admins WHERE u_hash = '".$chash."'"); // извлекаем имя по айдишнику из куки// айпишнику
		$res_name = $name->fetch_assoc();
		if((($res_name['u_id'] == $_COOKIE['id']) && ($res_name['u_id'] != 0)) && $res_name['disable_hash_generation'] == 0)
			$db->query("INSERT INTO chat_members (uid, name, ip, lastses, lastwrite) VALUES ('".$id."', '".$res_name['u_login']."', '".$ip."', '".$timestamp."', '".$timestamp."')"); // иначе вставляем новую инфу о юзере
		else return;
	}
	$db->query("DELETE FROM chat_members WHERE lastses < '".$delete_time."'");
	//$count_members_query = $db->query("SELECT COUNT('*') FROM chat_members");
	$count_members_query = $db->query("SELECT name, uid, isSleep FROM chat_members ORDER BY isSleep, name ");

	$test_array = array();
	$test_counter = 0;
	while($row = $count_members_query->fetch_assoc()) {
		$test_array[] = array($row);
		$test_counter++;
	}
	echo json_encode($test_array);
	//print_r($test_array);
	$db->close();
}

?> 