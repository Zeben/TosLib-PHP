// function playSound(filename){   // проигрываем чётки звучок
// 	document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="sound/' + filename + '.mp3" type="audio/mpeg" /><source src="sound/' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="sound/' + filename +'.mp3" /></audio>';
// }

function pm_check() {
	$.ajax({
		type: "POST",
		url: "dyncontent/pm_notify.php",
		data: "check=1",
		success: function(response) {
			if(response != 0) {
				//playSound('INCOMING');
				$('#inbox').empty().append('<div style="color: #C50000">Новых сообщений: '+response+'</div>');
			} else {
				$('#inbox').empty().append('Личные сообщения');
			}
		}
	})
}
$(window).load(pm_check());
setInterval('pm_check()', 20*1000);