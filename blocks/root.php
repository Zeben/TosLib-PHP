
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="css/style.css">
		<script type="text/javascript" src='js/jquery.min.js'></script>
		<script type="text/javascript" src='js/pm_notify.js'></script>
		<script type="text/javascript" src='js/adaptive.js'></script>
		<script type="text/javascript" src='js/comments.js'></script>
		<title>Менеджер облачного хранилища TosLib</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<div id="root-wrap">
			<?php include 'blocks/root_panel.php' ?>
		</div>
		<div id="wrapper">
			<div id="header" onclick='location.href="/"'></div>
			<?php 
			//if(isset($_GET['membmenu']) && $_GET['membmenu'] == 1) 
			// здесь будет вкрапление логики переключения меню на чатовское и форумовское
			if(isset($_GET['q']) && $_GET['q'] == 'tlchat')
				include('blocks/memb_menu.php'); 
			else {
				include('blocks/menu.php');
			}
			?>
				<div id="content">
					<!--Контент, епть!-->
	 				<?php include('dyncontent/'.$view.'.php');  ?>
				</div>
		</div>
		<div id="footer_root">
				<span ><a class="menu-login-button-small" href="http://promodj.com/devliner">&copy; Максим Логвинов (2013-2015 гг.) Авторские трава защищены</a>  <a class="menu-login-button-small" href="?q=changes"> Toslib 4.8 (список изменений)</a></span>
		</div>
	</body>
</html>