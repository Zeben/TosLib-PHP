var tmpArray = new Array();
var islocked = 'no';
function pasteNick(f) {
	var nick = f.innerHTML.replace(/<.*?>/g, '');
	document.getElementById("message").value = '[b]' + nick + ', [/b]';
}


function getXmlHttp() {
	var XmlHttp;
	try {
		XmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch(e) {
		try {
			XmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch(E) {
			XmlHttp = false;
		}
	}

	if(!XmlHttp && typeof XMLHttpRequest != 'undefined') {
		XmlHttp = new XMLHttpRequest();
	}

	return XmlHttp;
}
//загружает последние 10 сообщений только при заходе на чат, потом отключается
function last() {
	var httpObj = getXmlHttp();
	httpObj.open("POST", "dyncontent/chat_handler.php", true);
	httpObj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpObj.send("last=1&r="+Math.random());
	httpObj.onreadystatechange = function() {
		if(httpObj.readyState == 4) {
			if(httpObj.status == 200) {
				var response = httpObj.responseText;
				//console.log(response);
				response = JSON.parse(response);
				//alert(response);
				//console.log(response);
				if(tmpArray.length == response.length) return;
				var startInt = tmpArray.length;
				tmpArray = response;
				var messages = document.getElementById("messagebox").innerHTML;
				for(var i = startInt; i < response.length; i++) {
					var bg = '';
					if(response[i][0].message.indexOf(document.getElementById("topicname").innerHTML.replace(/<.*?>/g, '')) != -1) {
							bg = "; background-color: #A3FFAA; border-radius: 3px;";
						}
					if(i == (response.length - 1)) document.getElementById("lid_block").value = response[i][0].id;
					if(response[i][0].message.indexOf('/me') == 0) {
						messages = messages + "<div style='padding: 0.2em 0'><i><span class='smalltext' class>[" + response[i][0].timest + "]  </span> * " + response[i][0].message.replace(/\/me/g, response[i][0].user_id) + " *</i></div>";
					} else {
						messages = messages + "<div style='padding: 0.2em 0"+bg+"'><b><span class='smalltext' class>[" + response[i][0].timest + "]  </span><a href='#' onclick='pasteNick(this)'>" + response[i][0].user_id + "</a> : </b>" + response[i][0].message + "</div>";
					}
				}
				document.getElementById("messagebox").innerHTML = messages;
				document.getElementById("messagebox").scrollTop = 1000000;

			}
		}
	}
}

// обновляет состояние окна лишь тогда, когда в чат поступает новое сообщение
function chat() {
	//if(document.getElementById("block").value == 'no') {
	if(islocked == 'no') {
		//document.getElementById("block").value = 'yes';
		islocked = 'yes';
		var httpObj = getXmlHttp();
		httpObj.open("POST", "dyncontent/chat_handler.php", true);
		httpObj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpObj.send("update=1&lid="+document.getElementById("lid_block").value+"&r="+Math.random());
		httpObj.onreadystatechange = function() {
			if(httpObj.readyState == 4) {
				if(httpObj.status == 200) {
					var response = httpObj.responseText;
					//console.log(response);
					response = JSON.parse(response);
					console.log(response);
					if(response.ii == 'undefined') alert();
					var startInt = tmpArray.length;
					var messages = document.getElementById("messagebox").innerHTML;

					for(var i = 0; i < response.length; i++) {
						var bg = '';
						//console.log(response[i][0].user_id);
						//console.log(response[i][0].message.indexOf(response[i][0].user_id));
						if(response[i][0].message.indexOf(document.getElementById("topicname").innerHTML.replace(/<.*?>/g, '')) != -1) {
							bg = "; background-color: #A3FFAA; border-radius: 3px;";
						}
						if(response[i][0].message.indexOf("/me") == 0) {
							messages = messages + "<div style='padding: 0.2em 0'><i><span class='smalltext' class>[" + response[i][0].timest + "]  </span> * " + response[i][0].message.replace(/\/me/g, response[i][0].user_id) + " *</i></div>";
						} else {
							messages = messages + "<div style='padding: 0.2em 0"+bg+"'><b><span class='smalltext' class>[" + response[i][0].timest + "]  </span><a href='#' onclick='pasteNick(this)'>" + response[i][0].user_id + "</a> : </b>" + response[i][0].message + "</div>";
						}
						
						document.getElementById("lid_block").value = response[i].ii;	
					}

					document.getElementById("messagebox").innerHTML = messages;
					document.getElementById("message").disabled = false;
					document.getElementById("addbutton").value = 'Отправить';
					document.getElementById("addbutton").disabled = false;
					
					//document.getElementById("block").value = 'no';
					islocked = 'no';
					if(response.length >= 1) {
						document.getElementById("messagebox").scrollTop = document.getElementById("messagebox").scrollTop + 1000;
					}

				}
			}
		}
	}
	
}
setInterval("chat()", 2500);

// сервер от каждого юзера получает разные данные.
// Но ответ все получают один и тот же автоматически. Будем выводить данные.
// 
function members() {
	var httpObj = getXmlHttp();
	document.getElementById("membstyle").setAttribute("style","opacity: 0.3;");
	httpObj.open("POST", "dyncontent/chat_handler.php", true);
	httpObj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpObj.send("members=1&d="+new Date().getSeconds());
	httpObj.onreadystatechange = function() {
		if(httpObj.readyState == 4) {
			if(httpObj.status == 200) {
				var response = httpObj.responseText;
				document.getElementById("membstyle").setAttribute("style","opacity: 1.0;");
				//console.log(response);
				response = JSON.parse(response);
				//console.log(response);
				var allMmembers = new Array();
				for(var i = 0; i < response.length; i++) {
					if(response[i][0].isSleep == 1) {
						allMmembers = allMmembers + "<div class='members'><i><a href='#' onclick='pasteNick(this)' title>" + response[i][0].name + "</a></i></div>";
					} else {
						allMmembers = allMmembers + "<div class='members'><b><a href='#' onclick='pasteNick(this)' title>" + response[i][0].name + "</a></b></div>";
					}
				}
				document.getElementById("memb_counter").innerHTML = response.length;
				document.getElementById("membstyle").innerHTML = allMmembers;
			}
		}
	}
	
}
setInterval("members()", 30000);

// вызывается по кнопке и ентеру для занесения сообщения в БД
function add() {
	document.getElementById("message").disabled = true;
	document.getElementById("addbutton").value = 'Ждите...';
	document.getElementById("addbutton").disabled = true;
	msg = document.getElementById("message").value;
	var httpObj = getXmlHttp();
	httpObj.open("POST", "dyncontent/chat_handler.php", true);
	httpObj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpObj.send("add=" + encodeURIComponent(msg));
	httpObj.onreadystatechange = function() {
		if(httpObj.readyState == 4) {
			if(httpObj.status == 200) {
				chat();
				document.getElementById('message').value = '';
			}
		}
	}
	
}

 function searchKeyPress(e) {
    if (typeof e == 'undefined' && window.event) { e = window.event; }
    if (e.keyCode == 13) {
        document.getElementById('addbutton').click();
    }
}


function msgQuote(f) {
	var quote = f.innerHTML;

}
//window.onload = document.getElementById("messagebox").scrollTop = 1000000;
window.onload = last();
window.onload = members();
