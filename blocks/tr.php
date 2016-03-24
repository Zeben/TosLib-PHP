<?php

class TR {
	public $trPersonalMessages;
	public $trUsers;
	public $trChat;
	public $trExit;
	public $trMenu;
	public $trBugtracker;
	public $trLastAddedMaterials;
	public $trMainEdit;
	public $trMainDelete;
	public $trErrKeys;
	public $trBackToMain;

	public function setRU() {
		$this->trPersonalMessages = "Личные сообщения";
		$this->trUsers = "Пользователи";
		$this->trChat = "Чатик";
		$this->trExit = "Выход";
		$this->trMenu = "Меню";
		$this->trBugtracker = "Багтрекер";
		$this->trLastAddedMaterials = "Последние добавленные материалы";
		$this->trMainEdit = "Редактировать";
		$this->trMainDelete = "Удалить";
		$this->trBackToMain = "Вернуться на главную";
		$this->trErrKeys = "Комбинация параметров неверна";
	}

	public function setEN() {
		$this->trPersonalMessages = "Personal Messages";
		$this->trUsers = "Users";
		$this->trChat = "Chat";
		$this->trExit = "Exit";
		$this->trMenu = "Menu";
		$this->trBugtracker = "Bugtracker";
		$this->trLastAddedMaterials = "Recent files";
		$this->trMainEdit = "Edit";
		$this->trMainDelete = "Delete";
		$this->trErrKeys = "Wrong keys combination";
		$this->trBackToMain = "Return to main page";
	}
}

?>