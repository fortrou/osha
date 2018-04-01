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
													$classes = "";
													while($row = $res->fetch_assoc()){
														$classes .= $row['id'].',';
													}
													$classes = rtrim($classes,',');
													print("<option value='$classes' selected>Все классы</option>");
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
								<input type="search" name="search" placeholder="ученик"> <input type="button" value="Поиск" name="start_search"> 
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
													$sql = "SELECT * FROM os_class_manager WHERE is_opened=0";
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
					
					
					<h3>Чаты других пользователей</h3>
					<div class="other_users_chats">
					</div>
					
					<h3>Другие чаты со мной</h3>
					<div class="other_my_chats">
					</div>
					<h3>Другие чаты без меня</h3>
					<div class="chats_without_me_manager">
					</div>
					<h3>Техподдержка</h3>
					<div class="our_chats">
					<?php
						if($_SESSION['data']['level'] == 4){
							$sql = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE level=3";
							//print($sql);
							$res = $mysqli->query($sql);
							printf("<p class='cat_hat'>Техподдержка с менеджерами</p>");
							while($row = $res->fetch_assoc()){
								if($row['chat_id'] != 0){
									printf("<p> <a href='index.php?id=%s'>%s</a>",
										$row['chat_id'],$row['fi']);
									$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row_chat_id['id_chat']."'";
									//print($sql_new);
									$res_new = $mysqli->query($sql_new);
									
									if ($res_new->num_rows != 0) {
										$row_new = $res_new->fetch_assoc();
										if($row_new['COUNT(*)'] != 0)
											printf("<div class='oc_sobs'>%s</div>",$row_new['COUNT(*)']);
									}
								}
								else{
									$sql_n = "INSERT INTO os_chat(id) SELECT MAX(id)+1 FROM os_chat";
									$res_n = $mysqli->query($sql_n);
									$sql_ch = "SELECT MAX(id) FROM os_chat";
									$res_ch = $mysqli->query($sql_ch);
									$row_ch = $res_ch->fetch_assoc();
									$sql_n = "UPDATE os_users SET chat_id='".$row_ch['MAX(id)']."' WHERE id='".$row['id']."'";
									$res_n = $mysqli->query($sql_n);
									$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), 'admin' FROM os_chat";
									$res_n = $mysqli->query($sql_n);
									$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), '".$row['id']."' FROM os_chat";
									$res_n = $mysqli->query($sql_n);
									$sql_n = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE id='".$row['id']."'";
									$res_n = $mysqli->query($sql_n);
									$row_n = $res_n->fetch_assoc();
									printf("<p> <a href='index.php?id=%s'>%s</a>",
										$row_n['chat_id'],$row_n['fi']);
									$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row_chat_id['id_chat']."'";
									//print($sql_new);
									$res_new = $mysqli->query($sql_new);
									
									if ($res_new->num_rows != 0) {
										$row_new = $res_new->fetch_assoc();
										if($row_new['COUNT(*)'] != 0)
											printf("<div class='oc_sobs'>%s</div>",$row_new['COUNT(*)']);
									}
									print("</p>");
								}
							}
							$sql = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE level=2";
							//print($sql);
							$res = $mysqli->query($sql);
							printf("<p class='cat_hat'>Техподдержка с учителями</p>");
							while($row = $res->fetch_assoc()){
								if($row['chat_id'] != 0){
									printf("<p> <a href='index.php?id=%s'>%s</a>",
										$row['chat_id'],$row['fi']);
									$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row_chat_id['id_chat']."'";
									//print($sql_new);
									$res_new = $mysqli->query($sql_new);
									
									if ($res_new->num_rows != 0) {
										$row_new = $res_new->fetch_assoc();
										if($row_new['COUNT(*)'] != 0)
											printf("<div class='oc_sobs'>%s</div>",$row_new['COUNT(*)']);
									}
								}
								else{
									$sql_n = "INSERT INTO os_chat(id) SELECT MAX(id)+1 FROM os_chat";
									$res_n = $mysqli->query($sql_n);
									$sql_ch = "SELECT MAX(id) FROM os_chat";
									$res_ch = $mysqli->query($sql_ch);
									$row_ch = $res_ch->fetch_assoc();
									$sql_n = "UPDATE os_users SET chat_id='".$row_ch['MAX(id)']."' WHERE id='".$row['id']."'";
									$res_n = $mysqli->query($sql_n);
									$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), 'admin' FROM os_chat";
									$res_n = $mysqli->query($sql_n);
									$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), '".$row['id']."' FROM os_chat";
									$res_n = $mysqli->query($sql_n);
									$sql_n = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE id='".$row['id']."'";
									$res_n = $mysqli->query($sql_n);
									$row_n = $res_n->fetch_assoc();
									printf("<p> <a href='index.php?id=%s'>%s</a>",
										$row_n['chat_id'],$row_n['fi']);
									$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row_chat_id['id_chat']."'";
									//print($sql_new);
									$res_new = $mysqli->query($sql_new);
									
									if ($res_new->num_rows != 0) {
										$row_new = $res_new->fetch_assoc();
										if($row_new['COUNT(*)'] != 0)
											printf("<div class='oc_sobs'>%s</div>",$row_new['COUNT(*)']);
									}
									print("</p>");
								}
							}
							$sql_classes = "SELECT * FROM os_class_manager";
							$res_classes = $mysqli->query($sql_classes);
							printf("<p class='cat_hat'>Техподдержка с учениками</p>");
							if ($res_classes->num_rows!=0) {
								while ($row_classes = $res_classes->fetch_assoc()) {
									
									$sql = sprintf("SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE level=1 AND class='%s'",
										$row_classes['id']);
									//print($sql);
									$res = $mysqli->query($sql);
									if($res->num_rows!=0)
										printf("<p class='cat_small_hat'>Класс %s</p>",$row_classes['class_name']);
									while($row = $res->fetch_assoc()){
										if($row['chat_id'] != 0){
											printf("<p> <a href='index.php?id=%s'>%s</a>",
												$row['chat_id'],$row['fi']);
											$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row_chat_id['id_chat']."'";
											//print($sql_new);
											$res_new = $mysqli->query($sql_new);
											
											if ($res_new->num_rows != 0) {
												$row_new = $res_new->fetch_assoc();
												if($row_new['COUNT(*)'] != 0)
													printf("<div class='oc_sobs'>%s</div>",$row_new['COUNT(*)']);
											}
										}
										else{
											$sql_n = "INSERT INTO os_chat(id) SELECT MAX(id)+1 FROM os_chat";
											$res_n = $mysqli->query($sql_n);
											$sql_ch = "SELECT MAX(id) FROM os_chat";
											$res_ch = $mysqli->query($sql_ch);
											$row_ch = $res_ch->fetch_assoc();
											$sql_n = "UPDATE os_users SET chat_id='".$row_ch['MAX(id)']."' WHERE id='".$row['id']."'";
											$res_n = $mysqli->query($sql_n);
											$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), 'admin' FROM os_chat";
											$res_n = $mysqli->query($sql_n);
											$sql_n = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), '".$row['id']."' FROM os_chat";
											$res_n = $mysqli->query($sql_n);
											$sql_n = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE id='".$row['id']."'";
											$res_n = $mysqli->query($sql_n);
											$row_n = $res_n->fetch_assoc();
											printf("<p> <a href='index.php?id=%s'>%s</a>",
												$row_n['chat_id'],$row_n['fi']);
											$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row_chat_id['id_chat']."'";
											//print($sql_new);
											$res_new = $mysqli->query($sql_new);
											
											if ($res_new->num_rows != 0) {
												$row_new = $res_new->fetch_assoc();
												if($row_new['COUNT(*)'] != 0)
													printf("<div class='oc_sobs'>%s</div>",$row_new['COUNT(*)']);
											}
											print("</p>");
										}
									}
								}
								
							}
							
						}
						
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