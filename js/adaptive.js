function adaptiveTrigger() {
	if($("#search-panel-msgs").css("display") == "none") {
		$("#search-panel-msgs").slideDown(300);
		$("#search-panel-msgs").css({"display": "block"});
		
	}
	else
	if($("#search-panel-msgs").css("display") == "block") {
		$("#search-panel-msgs").css({"display": ""});
	}
}

function adaptiveMenu() {
	if($("#menu").css("left") == "-400px") {
		$("#menu").css({"left": "0px"});
	}
	else {
		$("#menu").css({"left": "-400px"});
	}
}

window.onscroll = function() {
	if($(window).width() > 768) {
		var barHeight = $("#header").height();
		var scrollEvent = $("body").scrollTop();
		console.log(barHeight);
		console.log(scrollEvent);
		if(scrollEvent > barHeight) {
			$("#menu").css({"position": "fixed","width":"20%","top":"33px"});
		} else {
			$("#menu").css({"position": "","width":"25%","top":""});
		}
	}
}


