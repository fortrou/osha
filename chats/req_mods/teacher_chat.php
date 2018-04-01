<div class="chat_filter">

				<table>

					<tr>

						<td>

							<form action="#" method="post"> 

								<input type="text" value="ru" hidden="" name="language">

								<input type="hidden" name="id" value="2">

								<input type="hidden" name="level" value="4">

								<input type="hidden" name="del_id" id="del_id" value="0">

								<table>

									<tbody><tr> 

										<td><span>Класс</span><br>

											<select name="com_filter_class">
												<?php
													$sql = "SELECT * FROM os_class_manager WHERE is_opened=0";
													$res = $mysqli->query($sql);
													while($row = $res->fetch_assoc()){
														printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
													}
												?>
											</select>

										</td>									

										<td><span>Предмет</span><br>

											<select name="com_filter_subject">
												<option value="" disabled>--</option>
											</select>

										</td>								

										<td><span>Уровень доступа</span><br>

											<select name="uroven_d">

												<option value="1,2,3,4" selected>Все</option>

												<option value="1">Ученики</option>

												<option value="2">Учителя</option>

												<option value="3">Менеджеры</option>

												<option value="4">Администраторы</option>

											</select>

										</td>

										

															</tr>

								</tbody></table>

							</form>

							<form action="#" method="post"> 

								<input type="search" name="search" placeholder="ученик"> <input type="button" value="Поиск"> 

							</form>

						

						</td>

						<td>

							<a class="tabel_link" >Создать ЧАТ</a> 

							<div id="tabel_link_content" style="display: none;margin-left: 100px;">

								<form>

									<input type="text" name="com_create_name" id="mark" placeholder="Введите название ЧАТа">
									
									<select id="com_create_type" name="com_create_type">

										<option value="1">Ученики</option>

										<option value="2">Учителя</option>

										<option value="3">Менеджеры</option> 

										<option value="4">Администраторы</option> 

									</select><br>
									
									<span>Класс</span><br>

											<select name="com_create_class">

												<?php
													$sql = sprintf("SELECT * FROM os_class_manager WHERE is_opened=0 AND id IN(SELECT id_c FROM os_teacher_class WHERE id_teacher='%s')",
														$_SESSION['data']['id']);
													$res = $mysqli->query($sql);
													while($row = $res->fetch_assoc()){
														printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
													}
												?>

											</select> 

									<select id="com_create_list" name="com_create_list" multiple>
									</select> 
									<div class="selected_list">

									</div>
									<input type="button" name="com_create_btn" value="Создать"> 

									<div class="clear"></div>

									

								</form>

							</div> 

						</td>

					</tr>

				</table>

			</div>

<div class="chats_uchitel">				

	<div class="left">
		<h3>Чаты с учениками</h3>
		<div class="chats_with_my_pupils">
		</div>
	
		<h3>Другие чаты</h3>
		<div class="chats_with_me_teacher">
		</div>
	
		<h3>Техподдержка</h3>
		<div class="our_chats">
		<?php
			if($_SESSION['data']['chat_id'] == 0){
				$sql_n = "INSERT INTO os_chat(id) SELECT MAX(id)+1 FROM os_chat";
				$res_n = $mysqli->query($sql_n);
				$sql_ch = "SELECT MAX(id) FROM os_chat";
				$res_ch = $mysqli->query($sql_ch);
				$row_ch = $res_ch->fetch_assoc();
				$sql_n = "UPDATE os_users SET chat_id='".$row_ch['MAX(id)']."' WHERE id='".$_SESSION['data']['id']."'";
				$res_n = $mysqli->query($sql_n);
				$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), 'admin' FROM os_chat";
				$res_n = $mysqli->query($sql_n);
				$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), '".$_SESSION['data']['id']."' FROM os_chat";
				$res_n = $mysqli->query($sql_n);
				$sql_n = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE id='".$_SESSION['data']['id']."'";
				$res_n = $mysqli->query($sql_n);
				$row_n = $res_n->fetch_assoc();
				$_SESSION['data']['chat_id'] = $row_n['chat_id'];
			}
			printf("<p><a href='index.php?id=%s'>Техподдержка</a>",$_SESSION['data']['chat_id']);
			$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row_chat_id['id_chat']."'";
					//print($sql_new);
			$res_new = $mysqli->query($sql_new);
			if ($res_new->num_rows != 0) {
				$row_new = $res_new->fetch_assoc();
				printf("<div class='oc_sobs'>%s</div>",$row_new['COUNT(*)']);
			}
			print("</p>");
		?>

					
		</div>
	</div>
				

<div class="right">
		<h3 id="chat_name"></h3>
		<div class="chats_okno" id="com_chat_field">
		</div>
		<div class="chats_okno_bottom">
			<?php if($_SESSION['data']['avatar'] != ""): ?>
				<div class="oc_icons">
					<img src="../upload/avatars/<?php print($_SESSION['data']['avatar']); ?>" width="50px" height="50px">
					<img src="../tpl_img/system_users.png" width="50px" height="50px" id="second_ava">
				</div>
			<?php else: ?>
				<div class="oc_icons">
					<img src="../upload/avatars/default.jpg" width="50px" height="50px">
					<img src="../tpl_img/system_users.png" width="50px" height="50px" id="second_ava">
				</div>
			<?php endif; ?>

			<span id="com_to_chat"></span><span id="com_from_chat"></span>
			<textarea placeholder="Написать сообщение..." name="com_text_message"></textarea>
			<input type="hidden" name="file_attached" value="">
			<div class="chats_btn"><input type="button" name="com_send" onclick="common_send()" value="Отправить">
			<span onclick="open_docs_modal(<?=$_SESSION['data']['id']?>)">Прикрепить</span></div>
			<div id="attached"></div>
		</div>
		<!--<h2>Руководство пользователя</h2>-->
		<p>Чтобы загрузить любой чат, кликните по нему в поле слева</p>
	</div>
	<div class="clear"></div>
</div>
