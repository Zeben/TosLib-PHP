<?php

	include("plugins/db.php");
	error_reporting(E_ALL);
	date_default_timezone_set("Europe/Kiev");
	$r = $db->query("SELECT * FROM admins WHERE u_hash = '".$_COOKIE['hash']."'");
	$row = $r->fetch_assoc();
	$q = empty($_GET['q']) ? 'content' : $_GET['q'];
	if(md5(md5('rooted')) == $row['key_root'])
	{
			switch($q)
			{
				case('content'):
					$view = "content";
				break;
				case('books'):
					$view = "books";
				break;
				case('editor'):
					$view = "editor";
				break;
				case('xmlparser'):
					$view = "xmlparser";
				break;
				case('upload'):
					$view = "upload";
				break;
				case('q'):
					{
						if($row['session'] == 1)
						{
							$db->query("UPDATE admins SET session = 0 WHERE u_hash = '".$_COOKIE['hash']."'"); // обнуляем переменную сессии
						}
						setcookie("id", "", time() - 3600*24*30*12);
						setcookie("hash", "", time() - 3600*24*30*12);
						header("Location: ?q=content");
					} break;			
				case('search'):
				{
					if(empty($_POST['search']) || (strlen($_POST['search']) < 4))
					{
						$_GET['errnum'] = "err_few_chars_in_search";  $view = "errors";
					}
					else if(isset($_POST['submit']))
					{
						$search = $_POST['search'];
						$search = trim($search);
						$search = stripslashes($search);
						$search = htmlspecialchars($search);
						$view = "search";
					}
				} break;

				case('tlchat'):
				{
					$view = "tlchat";
				} break;
				case('tlchat_alpha'):
				{
					$view = "tlchat_alpha";
				} break;
				case('changes'):
				{
					$view = "changes";
				} break;
				case('profiles'):
				{
					$view = "profiles";
				} break;
				case('pm'):
				{
					$view = "pm";
				} break;
				case('comments'):
				{
					$view = "comments";
				} break;
			}
			// закрываем доступ к модулям, в них есть недоработка и не нужны нам
			if(($q == "xmlparser") || ($q == "upload")) { $_GET['errnum'] = "err_protector";  $view = "errors"; }
	} else
	{
		switch($q)
		{
			case('content'):
				$view = "content";
			break;
			case('books'):
				$view = "books";
			break;
			case('editor'):
				$view = "editor";
			break;
			case('q'):
				{
					setcookie("id", "", time() - 3600*24*30*12);
					setcookie("hash", "", time() - 3600*24*30*12);
					header("Location: ?q=content");
				} break;			
			case('search'):
			{
				if(empty($_POST['search']) || (strlen($_POST['search']) < 4))
				{
					$_GET['errnum'] = "err_few_chars_in_search";  $view = "errors";
				}
				else if(isset($_POST['submit']))
				{
					$search = $_POST['search'];
					$search = trim($search);
					$search = stripslashes($search);
					$search = htmlspecialchars($search);
					$view = "search";
				}
			} break;
			case('tlchat'):
				{
					$view = "tlchat";
				} break;
			case('tlchat_alpha'):
				{
					$view = "tlchat_alpha";
				} break;
			case('changes'):
			{
				$view = "changes";
			} break;

			case('profiles'):
			{
				$view = "profiles";
			} break;
			case('pm'):
			{
				$view = "pm";
			} break;
			case('comments'):
			{
				$view = "comments";
			} break;


		}
		if(($q == "xmlparser") || ($q == "upload")) { $_GET['errnum'] = "err_protector";  $view = "errors"; }
	}
	$arr = array(	
					'content',
					'books',
					'editor',
					'xmlparser',
					'upload', 
					'search', 
					'q', 
					'tlchat', 
					'changes',
					'profiles',
					'tlchat_alpha',
					'pm',
					'comments'
				);
	if(!in_array($q, $arr)) { $_GET['errnum'] = "err_keys";  $view = "errors"; }

	include("blocks/tr.php");
	$tr = new TR;
	$tr->setRU();
	if(!isset($_COOKIE['lang'])) setcookie("lang", "ru");
	if(isset($_GET['switchlang']) && $_GET['switchlang'] == '1') {
		if($_COOKIE['lang'] == 'ru') {
			setcookie("lang", "en");
			unset($_GET['switchlang']);
		} else if($_COOKIE['lang'] == 'en') {
			setcookie("lang", "ru");
			unset($_GET['switchlang']);
		} 
		echo "<script>alert('Changes will be conmifmed on next load / Изменения будут применены при следующей загрузке')</script>";
		unset($_GET['switchlang']);
	} 
	if(isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'ru') {
		$tr->setRU();
	} else {
		$tr->setEN();
	}

	include("blocks/root.php");
?>