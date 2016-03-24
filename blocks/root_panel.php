<?php

	$qu = $db->query("SELECT * FROM admins WHERE u_id = ".$_COOKIE['id']."");
	$row = $qu->fetch_assoc();
	if(isset($_POST['login']) && ($_POST['login'] == "Выход")) header("Location: ?q=q");
	if(isset($_POST['users']) && ($_POST['users'] == "Пользователи")) header("Location: ?q=editor&action=addition&mode=user");
	///////////

?>

<div class="menu-container">
	<span class="search-panel-wrap">
			<button id="lined-button-phone" onclick="adaptiveMenu()">
				<div></div>
				<div></div>
				<div></div>
			</button>
			<button id="adaptive-button" onclick="adaptiveTrigger()"><?php echo $tr->trMenu; ?></button>
		</center>
		<span id="search-panel-msgs">
			
			<div id="sound"></div>
			<div id="floater">
				<button id="lined-button" onclick="adaptiveMenu()">
					<div></div>
					<div></div>
					<div></div>
			</button>
				<div class="search-button-form">
					<button class='menu-login-button-adaptive' style='padding: 5px; display: inline-block;' onclick='location.href="?q=pm"'>
						<div id='inbox'>
							<?php echo $tr->trPersonalMessages; ?>
						</div>
					</button>
				</div>

				<div class="search-button-form">
					<button class='menu-login-button-adaptive' style='padding: 5px; display: inline-block;' onclick='location.href="?q=profiles&id=<?php echo $_COOKIE['id']?>"'>
						<div id='topicname'><?php echo $row['u_login']?></div>
					</button>
				</div>

				<div class="search-button-form">
					<button class='menu-login-button-adaptive' style='padding: 5px; display: inline-block;' onclick='location.href="?q=content&switchlang=1"'>
						<?php 
						if(!isset($_COOKIE['lang'])) {
							setcookie("lang", "ru");
						} else {
							echo $_COOKIE['lang'];
						}
						?>
					</button>
				</div>

			</div>

			<div class="search-button-form">
				<button class='menu-login-button-adaptive' onclick='location.href="?q=q"'><?php echo $tr->trExit; ?></button>
			</div>

			<div class='search-button-form'>
				<button class='menu-login-button-adaptive' onclick='location.href="?q=tlchat"' name='chat'><?php echo $tr->trChat; ?></button>
			</div>

			<?php
			if(isset($_COOKIE['hash']))
			{
				$r = $db->query("SELECT * FROM admins WHERE u_hash = '".$_COOKIE['hash']."'");
				$cook = $r->fetch_assoc();
			}
			if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
				if(($cook['key_root'] == md5(md5('rooted'))))
				{
				echo "
					<div class='search-button-form'>
						<form id='menu-form' name='root_menu' method='POST'>
							<button class='menu-login-button-adaptive' type='submit' value='Пользователи' name='users'>"; echo $tr->trUsers; echo "</button>
						</form>
					</div>
					";
				}
			?>
				
		</span>

	</span>

</div>
