<script type="text/javascript" async="true">
// FIXME: на сервере версия JQuery исправлена - исправить и здесь
/*

TOSLib Сhat v2 © Максим Логвинов aka Zeben ^_^. Авторские трава посланы^W защищены
Изменения:
 - чат переведён на JQuery. PureJS == гуано;
 - переписана логика работы добавления сообщений: они теперь не пересохраняются, используется инъекция сообщений в
 	div-контейнер, в следствии чего получилось избавиться от нескольких неприятных багов;
 - добавлены свистелки и перделки: плавный скроллинг, плавное добавление сообщений.
 - в связи с меньшим количеством строк кода и его упрощением стало проще добавлять изменения.
*/

var window_lastid = <?php
include 'plugins/db.php';
$r = $db->query("SELECT * FROM chat ORDER BY id DESC LIMIT 1");
$rr = $r->fetch_assoc();
echo $rr['id'];?>;
var tmpArray = new Array();
var islocked = 'no';
function pasteNick(f) { // запихиваем в поле ввода ник с тегами, удобно обращаться
	var nick = f.innerHTML.replace(/<.*?>/g, '');
	$("#message").val($("#message").val() + '[b]' + nick + ', [/b]');
}

function playSound(filename){   // проигрываем чётки звучок
	if($("#addbuttonNotifications").val() == "Уведомления (вкл.)") {
		document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="sound/' + filename + '.mp3" type="audio/mpeg" /><source src="sound/' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="sound/' + filename +'.mp3" /></audio>';
	}
}

function insertBg() { // подставляем хайлайтинг, если к нам обратились
	var tmp = '; background-color: #A3FFAA; border-radius: 3px;';
	return tmp;
}

function meStyle(time, id, msg) { // FIXME: убрать эту ф-цию и переписать stdStyle()
	var tmp = "<div class='chatMsgContent' style='padding: 0.2em 0'><i><span class='smalltext' class>" + time + "  </span> * " + "<a onclick='msgQuote(this)'>" + msg.replace(/\/me/g, id) + "</a>" + " *</i></div>";
	return tmp;
}

function stdStyle(bg, time, id, msg) { // чтобы не говнокодить, запихиваем стили в отдельную ф-цию и передаем самое необходимое
	if(typeof bg == 'undefined') bg = '';
	var tmp = "<div class='chatMsgContent' style='padding: 0.2em 0"+bg+"'><b><span class='smalltext' class>" + time + "  </span><a href='#' onclick='pasteNick(this)'>" + id + "</a> : </b>" + "<a onclick='msgQuote(this)'>" + msg + "</a>" + "</div>";
	return tmp;
}

function membersState(state, name) {
	bin = '<b>'; bout = '</b>';
	if(state == 1) {
		bin = '<i>'; bout = '</i>';
	}
	var tmp = "<div class='members'>"+bin+"<a href='#' onclick='pasteNick(this)' title>" + name + "</a>"+bout+"</div>";
	return tmp;
}

function last() { // загружает последние 100 сообщений
	$.ajax({
		type: "POST",
		url: "dyncontent/chat_handler.php",
		data: "last=1&r=" + Math.random(), // предотвращаем кэширование сообщений
		success: function(response) {
			var json = $.parseJSON(response); // парсим JSON
			//console.log(json);
			for(var i = 0; i < json.length; i++) { // считаем длину массива
				var bg = ''; // обнуляем стили хайлайтинга
				if(json[i][0].message.indexOf($('#topicname').text()) != -1) { // если юзер упомянут
					bg = insertBg(); // меняем стили
				}
				if(json[i][0].message.indexOf('/me') == 0) // если упомянут тег в начале сообщения
					$('#messagebox').append(meStyle(json[i][0].timest, json[i][0].user_id, json[i][0].message));
				else
					$('#messagebox').append(stdStyle(bg, json[i][0].timest, json[i][0].user_id, json[i][0].message));
			}
			$('#messagebox').animate({ // анимация скроллинга вниз
				scrollTop:$('#messagebox').prop('scrollHeight')
			}, 'slow');
		}
	});
}

function chat() { // основная ф-ция чата - добавлять новые сообщения, если они есть
	if(islocked == 'no') { 
		islocked = 'yes'; // запрещаем параллельные AJAX-запросы в предотвращение проглатывания и дублирования сообщений
		$.ajax({
			type: "POST",
			url: "dyncontent/chat_handler.php",
			data: "update=1&lid=" + window_lastid + "&r=" + Math.random(),
			success: function(response) {
				var json = $.parseJSON(response);
				console.log(json);
				for(var i = 0; i < json.length; i++) {
					var bg = '';
					if(json[i][0].message.indexOf($('#topicname').text()) != -1) {
						playSound('KDE');
						bg = insertBg();
					}
					if(json[i][0].message.indexOf('/me') == 0)
						$('#messagebox').append(meStyle(json[i][0].timest, json[i][0].user_id, json[i][0].message));
					else {
						console.log('new'); // FIXME: сверить с серверной версией скрипта
						/* создаем новый контейнер, присоединяем к нему данные новых сообщений и стили */
						var n = $('<div>').append(stdStyle(bg, json[i][0].timest, json[i][0].user_id, json[i][0].message)).css({'opacity': '0', 'left': '100px'});
						$(n).appendTo('#messagebox').animate({opacity: 1}, 'slow'); // присоединяем контейнер к сообщениям с анимацией
					}
					window_lastid = json[i].ii; // обновляем данные о последнем сообщении во избежание дублирования
				}
				if(json.length >= 1) { // мега костыль в виде отдельного условия: если мы получили новые сообщения - скроллить сообщения вниз с анимацией
					$('#messagebox').animate({
						scrollTop:$('#messagebox').prop('scrollHeight')
					}, 'slow');
				}
				islocked = 'no'; // AJAX завершён - разлочка
				$('#message').attr('disabled', false); // разлочка полей ввода и кнопки
				$('#addbutton').val('Отправить');
				$('#addbutton').attr('disabled', false);
			}
		})
	}
	
}
setInterval("chat()", 2500); // ф-ция вызывается каждые 2.5с.
// FIXME: если пропал инет - чат перестаёт работать - добавить проверку

function add() { // отправка нового сообщения на сервер
	$('#message').attr('disabled', true); // залочка поля ввода и кнопки
	$('#addbutton').val('Ждите...');
	$('#addbutton').attr('disabled', true);
	$.ajax({
		type: "POST",
		url: "dyncontent/chat_handler.php",
		data: "add=" + encodeURIComponent($('#message').val()),
		success: function() {
			chat();
			$('#message').val('');
		}
	});
}

function members() { // работа с пользователями
	$('#membstyle').css('opacity', '0.2'); // FIXME: обновить на сервере
	$.ajax({
		type: "POST",
		url: "dyncontent/chat_handler.php",
		data: "members=1&d="+ new Date().getSeconds(),
		success: function(response) {
			$('#membstyle').empty();
			var json = $.parseJSON(response);
			for(var i = 0; i < json.length; i++) {
				$('#membstyle').append(membersState(json[i][0].isSleep, json[i][0].name));
			}
			$('#memb_counter').html(json.length);
			$('#membstyle').css('opacity', '1');
		}
	})
}
setInterval("members()", 30000); // FIXME: перепроверить со значением на сервере

function searchKeyPress(e) {
    if (typeof e == 'undefined' && window.event) { e = window.event; } // ловим событие
    if (e.keyCode == 13) {
        $('#addbutton').click();
    }
}

function msgQuote(f) {
	var quote = f.innerHTML.replace(/<.*?>/g, '');
	document.getElementById("message").value = $(".chatMsgContent, b, span").val() + '[qu]' + quote + '[/qu]';
}

function toggleNotifications() {
	if($('#addbuttonNotifications').val() == "Уведомления (вкл.)") {
		$('#addbuttonNotifications').val("Уведомления (выкл.)");
		$('#addbuttonNotifications').css({"background-image":"url(https://upload.wikimedia.org/wikipedia/commons/3/3f/Mute_Icon.svg)"});
	} else {
		$('#addbuttonNotifications').val("Уведомления (вкл.)");
		$('#addbuttonNotifications').css({"background-image":"url(http://s3-eu-west-1.amazonaws.com/olafureliasson.net/assets/ui/audio_unmute_bl.svg)"});

	}
}

//window.onload = document.getElementById("messagebox").scrollTop = 1000000;
window.onload = last(); // FIXME: проверять при готовности DOM-дерева - переписать и проверить
window.onload = members();

</script>
<script type="text/javascript" src="js/bbcodes.js"></script>
<script type="text/javascript" src="js/jquery.caret.js"></script>
<div id="sound"></div>
<div id="messagebox">
</div>
<div id="message-input">
<!-- 	<input style="width:664px;" type="text" id="message" onkeydown="if(event.code == 13) document.getElementById('addbutton').click()"/>  -->
	<input id="block" name="block" type="hidden" value="no" />
	<input id="lid_block" name="lid_block" type="hidden" value="" />
	<input style="margin-right: 3px; width: 100%;" id="message" style="width:660px;" type="text" maxlength="300" onkeypress="searchKeyPress(event);"/>
	<input type="button" id="addbutton" class="menu-login-button-small" value="Отправить" onclick="add()"/>
</div>
<div id="bb-codes">
	<input type="button" id="bold" class="menu-login-button-small" value="b" onclick="bb('bold')"/>
	<input type="button" id="strike" class="menu-login-button-small" value="s" onclick="bb('strike')"/>
	<input type="button" id="inn" class="menu-login-button-small" value="i" onclick="bb('in')"/>
	<input type="button" id="underline" class="menu-login-button-small" value="u" onclick="bb('underline')"/>
	<input type="button" id="addbutton" class="menu-login-button-small" value="/me" onclick="bb('me')"/>
	<input type="button" id="inn" class="menu-login-button-small" value="|quote" onclick="bb('quote')"/>
	<input type="button" id="addbuttonNotifications" class="menu-login-button-small" value="Уведомления (вкл.)" onclick="toggleNotifications()"/>
</div>