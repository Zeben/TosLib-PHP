function dynamic(value) {
	jQuery.ajax({
		type: 'GET',
		url: 'dyncontent/test.php',
		data: value,
		success: function(responce) {
			$("#content").empty().append(responce);
		}
	});
	console.log(value);
}