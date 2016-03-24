
var classicArray = '[{"id":"1", "m":"Первый коммент", "pid":"0", "deep":"0"},{"id":"2", "m":"О, не по-англицки", "pid":"1", "deep":"1"},{"id":"3", "m":"Новый коммент... Должен быть ПОСЛЕДНИЙ", "pid":"0", "deep":"0"},{"id":"4", "m":"Третий по счёту", "pid":"2", "deep":"2"},{"id":"5", "m":"Ответ на второй коммент, должен бить ТРЕТИМ", "pid":"4", "deep":"3"}]';
var json = null;

console.log(json);

function setToStorage() {
	localStorage.setItem("bar", classicArray);
	updateComments();
}

function clearStorage() {
	localStorage.removeItem("bar");
	updateComments();
}

function updateComments() {
	if(localStorage.getItem("bar") == null) {
		$("#wrapper").empty().append('Local storage is empty');
	} else {
		json = $.parseJSON(localStorage.getItem("bar"));
		classicAttach();
	}
}

function classicAttach() {
	$("#wrapper").empty();
	for(var i = 0; i < json.length; i++) {
		var cl = $('<div class="comment" id='+json[i].id+'>').append(json[i].m).css({"margin-left": json[i].deep * 10});
		cl.append($('<button onclick="toggleSubmit('+json[i].deep+', '+json[i].pid+')">Submit to...</button>').css({"float":"right"}));
		cl.append($('<input id="input'+json[i].id+' type="text">').css({"float":"right", "visibility":"hidden"}));
		$(cl).appendTo('#wrapper');
	}
}

function toggleSubmit(deep, parentid) {
	console.log(deep + ", " + parentid);
}

function newComment() {

	updateComments();
}

$(window).ready(function() {
	updateComments();
});