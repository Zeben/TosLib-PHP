<?php
echo "
					<html>
					<style>
						.sql-query {
							border: 1px solid #bababa;
							margin: 10px;
							background-color: #ececec;
							transition-property: background;
							transition-duration: 0.5s;
						}
						.sql-query:hover {
							background-color: #f5f5f5;
						}
					</style>
						<div class='sql-query'>
						<table class='post'>
							<tbody>
								<tr>
									<td style='width:700px;'>
										<b> ".$row2['title']." </b> <br>
										<div style='font-size: 13px;color: rgb(100, 100, 100);'>
											Дата добавления: ".date('d.m.Y', strtotime($row2['date']))." г. <br>
											Автор: ".$row2['autor']." <br>
											Издательство: ".$row2['izdatelstvo']." <br>
											Год издания: ".$row2['god']." <br>
											Страниц: ".$row2['str']." <br>
											Язык: ".$row2['lang']." <br>
											Краткое описание: ".$row2['description']." <br>";
											
											$islogined = $db->query("SELECT * FROM admins");
											if(isset($_COOKIE['id']) && isset($_COOKIE['hash']))
											{
												while($res = $islogined->fetch_assoc())
												{
													if(($_COOKIE['id'] == $res['u_id']) && (md5(md5('rooted')) == $res['key_root']))
													{
														echo "<a href='?q=editor&id=".$row2['id']."&action=edit&mode=material'>Редактировать</a> <a href='?q=editor&id=".$row2['id']."&action=remover&mode=material'>Удалить</a>";
													}
												}
											}
											
					echo "
										</div>
									</td>
									<td>
										<p><a href='".$row2['link']."' target='".$row2['blank']."'><img src='/upload/image/down.jpg' style='width: 80px; height: 80px;' /></a><p>
									</td>
								</tr>
							</tbody>
						</table>
						</div>
						<!--<br>-->
					</html>
					";
?>