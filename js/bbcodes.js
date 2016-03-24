function bb(value) {
	switch(value) {
		case 'bold': {
			$('#message').val($('#message').val() + '[b][/b]');
			$('#message').caret($('#message').val().length - 4);
			break;
		}
		case 'me': {
			$('#message').val('/me ');
			$('#message').caret($('#message').val().length);
			break;
		}
		case 'strike': {
			$('#message').val($('#message').val() + '[s][/s]');
			$('#message').caret($('#message').val().length - 4);
			break;
		}
		case 'quote': {
			$('#message').val($('#message').val() + '[qu][/qu]');
			$('#message').caret($('#message').val().length - 5);
			break;
		}
		case 'in': {
			$('#message').val($('#message').val() + '[i][/i]');
			$('#message').caret($('#message').val().length - 4);
			break;
		}
		case 'underline': {
			$('#message').val($('#message').val() + '[u][/u]');
			$('#message').caret($('#message').val().length - 4);
			break;
		}
	}
	
}