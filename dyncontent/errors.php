<?php
include 'dyncontent/error_messenger.php';
switch($_GET['errnum'])
{
// сообщения об ошибках
	case('err_keys'): 
		echo err($tr->trErrKeys); break;
	case('err_403'): 
		echo err("Нет доступа к защищённому контенту! / Forbidden (403)"); break;
	case('err_few_chars_in_search'):
		echo err("Запрос не введён либо составляет менее 4-х символов."); break;
	case('err_db_search_selection'):
		echo err("Запрос на выборку из БД не прошёл. Сообщите об этом!"); break;
	case('err_none_in_search_results'):
		echo err("Запрос не дал результатов."); break;
	case('err_cat_empty'):
		echo err("Каталог пуст."); break;
	case('err_title_empty'):
		echo err("Раздел пуст."); break;
	case('err_doesnt_logedin'):
		echo err("Обращение к данным параметрам требует повышенных привилегий."); break;
	case('err_doesnt_filled'):
		echo err("Несколько или одно из полей не заполнено."); break;
	case('err_string_empty'):
		echo err("Строка пуста!"); break;
	case('err_protector'):
		echo err("Объект содержит уязвимости и отключён из соображений безопасности."); break;

// обычные уведомления
	case('msg_done'):
		echo err("Выполнено! Нажмите 'Вернуться' для обновления страницы."); break;
	case('msg_null'):
		echo err("Отменено пользователем."); break;
	
}
?>