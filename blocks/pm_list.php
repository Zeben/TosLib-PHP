
<style type="text/css">
	#pm_table {
		width: 100%;
		border-collapse: collapse;
		font-size: 13px;
	}

	.hideme {

	}

	#pm_table tbody tr td div {
		height: 1.2em;
		overflow: hidden;

	}

	#pm_table tbody tr td div a {
		text-decoration: none;
		color: #333;
	}
	.pm_r {
		display: inline-block;
		padding-left: 10px;

	}

	#live {
		border: 1px solid #ccc;
		border-radius: 5px;
		padding: 5px;
		margin: 5px;
		background-color: #fff;
	}

</style>
<script type="text/javascript">

	function hideIn() {
		if($('.hideme_out').css('display') == 'block') {
			$('.hideme_out').slideUp(500);
			$('.hideme_in').slideDown(500);
		}
	}

	function hideOut() {
		if($('.hideme_in').css('display') == 'block') {
			$('.hideme_in').slideUp(500);
			$('.hideme_out').slideDown(500);
		}
	}

	function hideQuick() {
		$('.hideme_quick').slideToggle(500);
	}

	function injectTable(id, name) {
		var tmp = '<div style="border: 1px solid #ccc; border-radius: 5px; margin: 5px; background-color: #eee"><button class="menu-login-button"  title="Профиль пользователя '+name+'" onclick="window.location.href=\'?q=profiles&id='+id+'\'">■</button><button class="menu-login-button" onclick="window.location.href=\'?q=pm&writeto='+id+'\'">N</button><div style="display: inline-block; padding-left: 5px;">'+name+'</div>';
		return tmp;
	}



	var user_counter = 0;
	function ajax_user_pagination(page) {
		$.ajax({
			type: "GET",
			url: "dyncontent/live_user.php",
			data: "all&page=" + page,
			success: function(response) {
				//console.log(response);
				var json = $.parseJSON(response);
				console.log(json);
				$('#live').empty();
				var n = '';
				for(var i = 0; i < json.length; i++) {
					n = $('<div>').append(injectTable(json[i][0].u_id, json[i][0].u_login)).css('opacity', '0');
					user_counter = json[i].ucount;
					$(n).appendTo('#live').animate({opacity: 1}, 'slow');
				}
				
				$('#pagination').empty();
				for(var ii = 0; ii < user_counter / 5; ii++)
				{
					$('#pagination').append('<button class="menu-login-button" onclick="ajax_user_pagination('+ii+')">'+(ii + 1)+'</button>');
				}
			}
		});
	}

	$(window).load(function() {
		$('#live_input').keyup(function(I){
			switch(I.keyCode) {
				case 13:
				case 27:
				case 38:
				case 40:
				break;
				default: {
					if($(this).val().length == 0) {
						ajax_user_pagination(1);
					}
					if($(this).val().length > 2) {
						$.ajax({
							type: "GET",
							url: "dyncontent/live_user.php",
							data: "query=" + $(this).val(),
							success: function(response) {
								//console.log(response);
								var json = $.parseJSON(response);
								$('#live').empty();
								$('#pagination').empty();
								$('#live').append('');
								for(var i = 0; i < json.length; i++) {
									n = $('<div>').append(injectTable(json[i].u_id, json[i].u_login)).css('opacity', '0');
									$(n).appendTo('#live').animate({opacity: 1}, 'slow');
								}
							}
						});
					}
				}
			}
		});
	})
$(window).load(ajax_user_pagination(0));
</script>

<div class="sql-query">
	<center>Управление личными сообщениями</center>
	<center>
		<button onclick="hideIn()">Входящие</button>
		<button onclick="hideOut()">Исходящие</button>
		<button onclick="hideQuick()">Быстрое сообщение</button>
	</center>
</div>

<div class="hideme_quick" style="display: none">
	<div class="sql-query" style='padding: 5px;'>
		Выберите пользователя из списка ниже либо начинайте вводить его никнейм.
		Для отправки пользователю письма, нажмите N; для просмотра его профиля, нажмите на квадрат Малевича. <br>
		<center><input type='text' name='live_user_search' id='live_input'></input></center>
		<div id='live'>Кликните на поле ввода и нажмите 'backspace' для постраничного вывода списка всех пользователей. </div>
		<div id='pagination' style="display: inline-block"></div>
	</div>
</div>

<div class='hideme_in'>
	<div class="sql-query" style='padding: 5px;'>
	<center>Входящие</center>
	<table id="pm_table" cellpadding="5">
		<tbody>
			<tr  style="border: 1px solid #ccc; background-color: #ccc">
				<td  nowrap="1">
					<div>От кого</div>
				</td>
				<td >
					<div>Текст</div>
				</td>
				<td >
					<div>Дата</div>
				</td>
			</tr>
		<?foreach ($arr as $a):?>
			<tr  style="border: 1px solid #ccc;">
				<td>
					<div><a href='?q=profiles&id=<?=$a['pm_uid_from']?>'><?=$a['pm_from']?></a></div> 
				</td>
				<td>
					<div>
						<a href='?q=pm&read=<?=$a['id']?>'>
							<?if($a['pm_unreaded'] != 0):?>
								<?="<b>".$a['pm_message']."</b>"?></b>
							<?else:?>
								<?=$a['pm_message']?>
							<?endif?>
						</a>
					</div>
				</td>
				<td nowrap="1">
					<div><?=$a['pm_time']?></div>
				</td>
			</tr>
		<?endforeach?>
		</tbody>
	</table>
	<div id='pm_page_inbox' style="display: inline-block">
		<?for($i = 0; $i < $count_in / 10; $i++):?>
			<?if(($count_in / 10) != 0):?>
				<button class="menu-login-button" onclick="window.location.href='?q=pm&inbox=<?=$i?>'"><?=$i + 1?></button>
			<?endif?>
		<?endfor?>
	</div>
	</div>
</div>


<div class='hideme_out' style="display: none;">
	<div class="sql-query" style='padding: 5px;'>
	<center>Исходящие</center>
		<table id="pm_table" cellpadding="5">
			<tbody>
				<tr  style="border: 1px solid #ccc; background-color: #ccc">
					<td  nowrap="1">
						<div>Кому</div>
					</td>
					<td >
						<div>Текст</div>
					</td>
					<td >
						<div>Дата</div>
					</td>
				</tr>
			<?foreach ($arr2 as $a):?>
				<tr  style="border: 1px solid #ccc;">
					<td>
						<div><?=$a['pm_to']?></div> 
					</td>
					<td>
						<div><a href='?q=pm&readout=<?=$a['id']?>'><?=$a['pm_message']?></a></div>
					</td>
					<td nowrap="1">
						<div><?=$a['pm_time']?></div>
					</td>
				</tr>
			<?endforeach?>
			</tbody>
		</table>
		<div id='pm_page_outbox' style="display: inline-block">
			<?for($i = 0; $i < $count_out / 10; $i++):?>
				<?if(($count_out / 10) != 0):?>
					<button class="menu-login-button" onclick="window.location.href='?q=pm&outbox=<?=$i?>'"><?=$i + 1?></button>
				<?endif?>
			<?endfor?>
		</div>
	</div>
</div>