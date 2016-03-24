<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" async="true">
var window_lastid = <?php
include 'plugins/db.php';
$r = $db->query("SELECT * FROM chat ORDER BY id DESC LIMIT 1");
$rr = $r->fetch_assoc();
echo $rr['id'];?>;
var tmpArray = new Array();
var islocked = 'no';
function pasteNick(f) {
	var nick = f.innerHTML.replace(/<.*?>/g, '');
	document.getElementById("message").value = '[b]' + nick + ', [/b]';
}

function playSound(filename){   
	document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="sound/' + filename + '.mp3" type="audio/mpeg" /><source src="sound/' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="sound/' + filename +'.mp3" /></audio>';
}

function insertBg() {
	var tmp = '; background-color: #A3FFAA; border-radius: 3px;';
	return tmp;
}

function meStyle(time, id, msg) {
	var tmp = "<div style='padding: 0.2em 0'><i><span class='smalltext' class>[" + time + "]  </span> * " + msg.replace(/\/me/g, id) + " *</i></div>";
	return tmp;
}

function stdStyle(bg, time, id, msg) {
	if(typeof bg == 'undefined') bg = '';
	var tmp = "<div style='padding: 0.2em 0"+bg+"'><b><span class='smalltext' class>[" + time + "]  </span><a href='#' onclick='pasteNick(this)'>" + id + "</a> : </b>" + msg + "</div>";
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

function last() {
	$.ajax({
		type: "POST",
		url: "dyncontent/chat_handler.php",
		data: "last=1&r=" + Math.random(),
		success: function(response) {
			var json = $.parseJSON(response);
			//console.log(json);
			for(var i = 0; i < json.length; i++) {
				var bg = '';
				if(json[i][0].message.indexOf($('#topicname').text()) != -1) {
					bg = insertBg();
				}
				if(json[i][0].message.indexOf('/me') == 0)
					$('#messagebox').append(meStyle(json[i][0].timest, json[i][0].user_id, json[i][0].message));
				else
					$('#messagebox').append(stdStyle(bg, json[i][0].timest, json[i][0].user_id, json[i][0].message));
			}
			$('#messagebox').animate({
				scrollTop:$('#messagebox').prop('scrollHeight')
			}, 'slow');
		}
	});
}

function chat() {
	if(islocked == 'no') {
		islocked = 'yes';
		$.ajax({
			type: "POST",
			url: "dyncontent/chat_handler.php",
			data: "update=1&lid=" + window_lastid + "&r=" + Math.random(),
			success: function(response) {
				var json = $.parseJSON(response);
				//console.log(json);
				for(var i = 0; i < json.length; i++) {
					var bg = '';
					if(json[i][0].message.indexOf($('#topicname').text()) != -1) {
						playSound('KDE');
						bg = insertBg();
					}
					if(json[i][0].message.indexOf('/me') == 0)
						$('#messagebox').append(meStyle(json[i][0].timest, json[i][0].user_id, json[i][0].message));
					else {
						//console.log('new');
						var n = $('<div>').append(stdStyle(bg, json[i][0].timest, json[i][0].user_id, json[i][0].message)).css({'opacity': '0', 'left': '100px'});
						//$('#messagebox').append(stdStyle(bg, json[i][0].timest, json[i][0].user_id, json[i][0].message));
						$(n).appendTo('#messagebox').animate({opacity: 1}, 'slow');
					}

					window_lastid = json[i].ii;
				}
				if(json.length >= 1) {
					$('#messagebox').animate({
						scrollTop:$('#messagebox').prop('scrollHeight')
					}, 'slow');
				}
				islocked = 'no';
				$('#message').attr('disabled', false);
				$('#addbutton').val('Отправить');
				$('#addbutton').attr('disabled', false);
			}
		})
	}
	
}
setInterval("chat()", 2500);

function add() {
	$('#message').attr('disabled', true);
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

function members() {
	$('#membstyle').css('opacity', '0.4');
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
setInterval("members()", 30000);

function searchKeyPress(e) {
    if (typeof e == 'undefined' && window.event) { e = window.event; }
    if (e.keyCode == 13) {
        $('#addbutton').click();
    }
}

//window.onload = document.getElementById("messagebox").scrollTop = 1000000;
window.onload = last();
window.onload = members();

</script>
<div id="sound"></div>
<div id="messagebox" style="height: 432px; margin: 5px; overflow-x: none; overflow-y: auto;">
</div>
<div style="border-top: 1px solid #555; padding: 5px; display: flex;">
<!-- 	<input style="width:664px;" type="text" id="message" onkeydown="if(event.code == 13) document.getElementById('addbutton').click()"/>  -->
	<input id="block" name="block" type="hidden" value="no" />
	<input id="lid_block" name="lid_block" type="hidden" value="" />
	<input style="width:664px;" type="text" id="message" maxlength="300" onkeypress="searchKeyPress(event);"/>

	<input type="button" id="addbutton" value="Отправить" onclick="add()"/>
</div>