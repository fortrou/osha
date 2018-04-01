<div class="chats_uchenik">				
		<?php
			if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru') {
				$withTeachers = "Чаты с учителями";
				$classTeacher = "Классный руководитель";
				$otherChats   = "Другие чаты";
				$techHelp  	  = "Техподдержка";
				$send 		  = "Отправить";
				$attach 	  = "Прикрепить";
				$toLoad 	  = "Чтобы загрузить любой чат, кликните по нему в поле слева";
				$writeMessage = "Написать сообщение";
			} else {
				$withTeachers = "Чати з вчителями";
				$classTeacher = "Класний керівник";
				$otherChats   = "Інші чати";
				$techHelp  	  = "Техпідтримка";
				$send 		  = "Надіслати";
				$attach 	  = "Прикріпити";
				$toLoad 	  = "Щоб завантажити будь-який чат, натисніть на нього в списку зліва";
				$writeMessage = "Написати повідомлення";
			}
		?>
				<div class="left">

					<h3><?php echo $withTeachers; ?></h3>
					<div class="common_chats_with_teachers">
					</div>
					<?php if( !isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0 ): ?>
					<!--<h3><?php echo $classTeacher; ?></h3>
					<div class="chat_with_manager">				
					</div>-->

					<h3><?php echo $otherChats; ?></h3>
					<div class="chats_with_me_stud">
					</div>
					<?php endif; ?>
					<h3><?php echo $techHelp; ?></h3>
					<div class="our_chats">
					<?php if($_SESSION['data']['chat_id'] != 0): ?>
					<p><a href="index.php?id=<?=$_SESSION['data']['chat_id']?>"><?php echo $techHelp; ?></a> <div class="oc_sobs" id='support'>1</div></p>
					<?php else: ?>
					<?php
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

					?>
					<p><a href="index.php?id=<?=$_SESSION['data']['chat_id']?>"><?php echo $techHelp; ?></a> 
						<?php 
						$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$_SESSION['data']['chat_id']."'";
						$res_new = $mysqli->query($sql_new);
						if ($res_new->num_rows != 0) {
							$row_new = $res_new->fetch_assoc();
								printf("<div class='oc_sobs' id='support'>%s</div>",$row_new['COUNT(*)']);
						}
						print("</p>");
						?>
					<?php endif; ?>
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
			<textarea placeholder="<?php echo $writeMessage; ?>..." name="com_text_message"></textarea>
			<input type="hidden" name="file_attached" value="">
			<div class="chats_btn"><input type="button" name="com_send" onclick="common_send()" value="<?php echo $send; ?>">
			<span onclick="open_docs_modal(<?=$_SESSION['data']['id']?>)"><?php echo $attach; ?></span></div>
			<div id="attached"></div>
		</div>
	
		<!--<h2>Руководство пользователя</h2>-->
		<p><?php echo $toLoad; ?></p>
	</div>
	<div class="clear"></div>
</div>