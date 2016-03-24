

function connectToId(comment, deep, timestamp, userid) {
	var c = $('<div class="sql-query-comments">').append("<b>" + userid + "</b> написал(а): <br />" + comment + "<br />" + "<a style='font-size: 11px; color: blue;'>" + timestamp + "</a>");
	$(c).appendTo('#comments-wrapper').css({"margin-left": (deep * 10 + 10) + "px"});
}

function processTree(tree) {
	console.log(tree);
}

function getComments() {
	$.ajax({
		type: "GET",
		url: "dyncontent/comments-json.php",
		data: "uid=" + $("#comment-uid").val(),
		success: function(response) {
			//console.log(response);
			var json = $.parseJSON(response);
			//processTree(json);
			console.log(json);
		}
	});
} 

function submit() {
	$.ajax({
		type: "POST",
		url: "dyncontent/comments-json.php",
		data: "uid=" + $("#comment-uid").val() + "submit=1&msg=",
		success: function(response) {
			console.log("DONE. CHECK DB.");
		}
	});
}

$(document).ready(function() {
	if($("#comment-uid").length) getComments();
});
