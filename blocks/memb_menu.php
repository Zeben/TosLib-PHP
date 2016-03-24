<style>
#membmenu {
	margin: 5px;
	background-color: #ececec;
	transition-property: background;
	transition-duration: 0.5s;
	border-radius: 3px;
}

#membstyle {
	font-size: 12px;
	padding: 5px;
}
</style>



<div id="menu">
	<ul class="m">
		<li><a href="?q=content">Главная</a></li>
	</ul>

	<div id="membmenu">
		<span style='padding: 7px;'>Онлайн:</span><span id="memb_counter"> </span>
		<div id="membstyle"></div>
		<center><i><a href="#" style='text-decoration: none; font-size: 11px; color: #000' onclick="members()">Обновить вручную</i></a></center>
	</div>
</div>
