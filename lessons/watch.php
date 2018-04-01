<?php 
	session_start();
	if(isset($_SESSION['data']) && (!isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0)) 
	    require_once '../tpl_php/autoload.php';
	else
	    require_once '../tpl_php/autoload_light.php';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if ( !isset($_GET['id']) )
		header("Location: ../index.php");
	$id = (int)$_GET['id'];
	$_SESSION['lesson']['id'] = (int)$_GET['id'];
	try 
	{
		$lesson = Lesson::Load($_GET['id']);
		$lesson->check_users_access($lesson);
	} 
	catch (Exception $e) 
	{
		print($e->getMessage());
	}
	if($_SESSION['data']['level'] == 1) {
		$sql = sprintf("UPDATE os_journal SET visit_status=1 WHERE id_s=%s AND id_l=%s",$_SESSION['data']['id'],$_SESSION['lesson']['id']);
		$res = $mysqli->query($sql);
	}
	if(isset($_POST['up_hworks'])) {
		$new_date = Date("Y-m-d", time() + (int)$_POST['days_to_up'] * 24 * 3600);
		$sql = sprintf("SELECT * FROM os_lesson_homework WHERE id_lesson='%s'",$_GET['id']);
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			$row = $res->fetch_assoc();
			$sql_upd = sprintf("UPDATE os_homeworks SET last_hw_date = '%s' WHERE id_hw = %s", $new_date, $row['id']);
			//print($sql_upd);
			$res_upd = $mysqli->query($sql_upd);
		}
	}
	if(isset($_POST['delete'])){
		Lesson::delete_lesson($_GET['id']);
		$sql = sprintf("DELETE FROM os_homeworks WHERE id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$_GET['id']);
		$res = $mysqli->query($sql);
		$sql = sprintf("DELETE FROM os_homework_docs WHERE id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$_GET['id']);
		$res = $mysqli->query($sql);
		$sql = sprintf("DELETE FROM os_lesson_homework WHERE id_lesson='%s'",$_GET['id']);
		$res = $mysqli->query($sql);
		$sql = sprintf("DELETE FROM os_journal WHERE id_l='%s'",$_GET['id']);
		$res = $mysqli->query($sql);
		$sql = sprintf("DELETE FROM os_lesson_classes WHERE id_lesson='%s'",$_GET['id']);
		$res = $mysqli->query($sql);
		$sql_tests = sprintf("SELECT * FROM os_lesson_test WHERE id_lesson=%s",$_GET["id"]);
		$res_tests = $mysqli->query($sql_tests);
		while ($row_tests = $res_tests->fetch_assoc()) {
			$sql_t = sprintf("SELECT * FROM os_test_quest WHERE id_test='%s'",$row_tests["id_test"]);
	        $res_t = $mysqli->query($sql_t);
	        //print($sql_t);
	        while($row = $res_t->fetch_assoc()){
	            if($row['type']!=5){
	                $sql_d = sprintf("DELETE FROM os_test_answs WHERE id_quest='%s'",$row['id_q']);
	                $res_d = $mysqli->query($sql_d);
	                $sql_d = sprintf("DELETE FROM os_test_matches WHERE id_quest='%s'",$row['id_q']);
	                $res_d = $mysqli->query($sql_d);
	            }
	            else{
	                $sql_d = sprintf("DELETE FROM os_test_short_answ WHERE id_quest='%s'",$row['id_q']);
	                $res_d = $mysqli->query($sql_d);
	            }
	            $sql_d = sprintf("DELETE FROM os_test_quest WHERE id_q='%s'",$row['id_q']);
	            $res_d = $mysqli->query($sql_d);
	        }
	        $sql_del = sprintf("DELETE FROM os_lesson_test WHERE id='%s'",$row_tests["id"]);
	        $res_del = $mysqli->query($sql_del);
		}
		header("Location:../schedule/calendar.php");
	}
	$new_sql = sprintf("SELECT teacher_%s AS teacher FROM os_lessons WHERE id='%s'",$_COOKIE['lang'],$_GET['id']);
	$new_res = $mysqli->query($new_sql);
	$new_row = $new_res->fetch_assoc();
 ?>
<!DOCTYPE html> 
<head>  		
	<title>Онлайн урок - Расписание - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/common_chat.js"></script>
</head>
<body>
	<?php
		if(isset($_SESSION['data'])){
			include ("../tpl_blocks/header.php");
        }
        else{
            include ("../test_access/head2.php");
        }
	?>
	
	<div class="content">
		<div id="docs" >
			<div class="close"onclick="close_docs_modal()">X</div>
				<iframe name='first_frame' style="width:400px;height:400px;display:none;"><?php
					$db = Database::getInstance();
					$mysqli = $db->getConnection();

					if(isset($_POST['upload_file'])){
						//var_dump($_FILES['file']);
						if(Cfile::isSecure($_FILES['file'])){
							//print("a");
							$name = Cfile::Load($_FILES['file']);
							$truth_name = $_FILES['file']['name'];
							if($name != false)
							{
								$sql = sprintf("INSERT INTO os_user_docs(id_user,doc_addr,doc_name) VALUES('%s','%s','%s')",
									$_POST['user_id'],$name,$truth_name);
								$res = $mysqli->query($sql);
								
								
							}
						}
					}
					?>
				</iframe>
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>	
			<h2>Ваши документы</h2>
			<?php else: ?>
			<h2>Ваші документи</h2>
			<?php endif; ?>
			<form method="post" action="" enctype='multipart/form-data'>
			<script>
				$(function (){
					if($('#chose_file').length)
					{
						$('#chose_file').click(function(){
							$('#chose_file_input').click();
							return(false);
						});
						$('#chose_file_input').change(function(){
							$('#chose_file_text').html($(this).val());
						}).change(); // .change() в конце для того чтобы событие сработало при обновлении страницы
					}
				});
			</script>
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				<a id="chose_file" href="">Прикрепить файл</a>
				<span id="chose_file_text"></span> 
				<input id="chose_file_input" type="file" name="file_upl"><br>
				<p>Чтобы добавить файл на сайт,<br />нажмите на него в списке файлов</p>
			<?php else: ?>
				<a id="chose_file" href="">Прикріпити файл</a>
				<span id="chose_file_text"></span> 
				<input id="chose_file_input" type="file" name="file_upl"><br>
				<p>Щоб додати файл на сайт,<br />натисніть на нього в списку файлів</p>
			<?php endif; ?>
			<input type="hidden" name="lang" value="<?php print($_COOKIE['lang']); ?>">

			<input type="hidden" name="user_id" value=""></input>

			<!--<input type="submit" name="upload_file" value="Залить файл">-->
		</form>
			<div id="doc_list"></div>
		</div>
			<?php if(!isset($_SESSION['data'])): ?>
		<div class="alt_title_test">
		<div class="block0">
			<?php if(!isset($_SESSION['data'])): ?>
<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
			<h1>Все материалы, которые вы видите, являются демонстрационными. Функции обучения в демонстрационном доступе ограничены.
			 Для получения полного доступа к нашей онлайн-школе зарегистрируйтесь на сайте и оплатите обучение<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Оплатить обучение</a></h1>
			<?php else: ?>
			<h1>Усі матеріали, які ви бачите, є демонстраційними. Функції навчання в демонстраційному доступі
			 обмежені. Для одержання повного доступу до нашої онлайн-школи зареєструйтесь на сайті і оплатіть навчання<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Сплатити за навчання</a></h1>
			<?php endif; ?>
		<?php endif; ?> 
		</div>
		</div>
		<?php endif; ?>
		
		<div class="block0">
			<div class="online_less">
				<h1>Онлайн-урок</h1>	
					<div class="left">
						<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'): ?>
						<table>
							<tr>
								<td><h4>Класс <?php
									if(isset($_SESSION['data']) && $_SESSION['data']['level'] == 1) {
										$sql_cl = sprintf("SELECT * FROM os_class_manager WHERE id='%s'", $_SESSION['data']['class']);
										$res_cl = $mysqli->query($sql_cl);
										$row_cl = $res_cl->fetch_assoc();
										print($row_cl['class_name']);
									} else if(isset($_SESSION['data']) && $_SESSION['data']['level'] > 1) {
										print($lesson->getClass());
									}
									?></h4>
									<h4><?=$lesson->getSubject($_COOKIE['lang'])?></h4>
									<h4><?=htmlspecialchars_decode($lesson->getTitle($_COOKIE['lang']))?></h4></td>
								<td><h5>Дата и время проведения<br><span><?=$lesson->getDate($_COOKIE['lang'])?></span></h5></td>
							</tr>
						</table>
						<?php else: ?>
						<table>
							<tr>
								<td><h4>Клас <?php
									$sql_cl = sprintf("SELECT * FROM os_class_manager WHERE id='%s'",$lesson->getClass($_COOKIE['lang']));
									$res_cl = $mysqli->query($sql_cl);
									$row_cl = $res_cl->fetch_assoc();
									print($row_cl['class_name']);
									?></h4>
									<h4><?=$lesson->getSubject($_COOKIE['lang'])?></h4>
									<h4><?=htmlspecialchars_decode($lesson->getTitle($_COOKIE['lang']))?></h4></td>
								<td><h5>Дата та час проведення<br><span><?=$lesson->getDate($_COOKIE['lang'])?></span></h5></td>
							</tr>
						</table>
						<?php endif; ?>
						<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'): ?>
						<div class="player player_ru">
							<iframe width="100%" height="100%" src="<?=htmlspecialchars_decode($lesson->getVideoLink($_COOKIE['lang']))?>" frameborder="0" allowfullscreen></iframe>
						</div>						 
						<div class="main_c" id='ohide2' style='display:show'>
							<a id='nound' href="javascript:ShowHide('chide2','ohide2');">Главное в уроке</a>
						</div>	                                                                                                                                              
						<div class="main_c" id='chide2' width='100%' border='0' style='display:none'> 
							<a id='nound' href="javascript:ShowHide('chide2','ohide2');">Главное в уроке</a>  
							<div>
									<p><?=htmlspecialchars_decode($lesson->getSummary($_COOKIE['lang']))?></p>
							</div> 
						</div>
						 
						<div class="additivies" id='ohide21' style='display:show'>
							<a id='nound' href="javascript:ShowHide('chide21','ohide21');">Дополнительные материалы</a>
						</div>	                                                                                                                                              
					   <div class="additivies" id='chide21' width='100%' border='0' style='display:none'> 
							<a id='nound' href="javascript:ShowHide('chide21','ohide21');">Дополнительные материалы</a>  
							<div>
								<p><?=htmlspecialchars_decode($lesson->getLinks($_COOKIE['lang']))?></p>
							</div> 
					   </div>
					   <?php else: ?>
					   <div class="player player_ua">
							<iframe width="100%" height="100%" src="<?=htmlspecialchars_decode($lesson->getVideoLink($_COOKIE['lang']))?>" frameborder="0" allowfullscreen></iframe>
						</div>						 
						<div class="main_c" id='ohide2' style='display:show'>
							<a id='nound' href="javascript:ShowHide('chide2','ohide2');">Головне в уроці</a>
						</div>	                                                                                                                                              
						<div class="main_c" id='chide2' width='100%' border='0' style='display:none'> 
							<a id='nound' href="javascript:ShowHide('chide2','ohide2');">Головне в уроці</a>  
							<div>
									<p><?=htmlspecialchars_decode($lesson->getSummary($_COOKIE['lang']))?></p>
							</div> 
						</div>
						 
						<div class="additivies" id='ohide21' style='display:show'>
							<a id='nound' href="javascript:ShowHide('chide21','ohide21');">Додаткові матеріали</a>
						</div>	                                                                                                                                              
					   <div class="additivies" id='chide21' width='100%' border='0' style='display:none'> 
							<a id='nound' href="javascript:ShowHide('chide21','ohide21');">Додаткові матеріали</a>  
							<div>
								<p><?=htmlspecialchars_decode($lesson->getLinks($_COOKIE['lang']))?></p>
							</div> 
					   </div>
					   <?php endif; ?>
					</div>
					<div class="right"> 


				 


								
						
			<?php
				if ($lesson->getTrTest($_COOKIE['lang'])!=false) {
					if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'){
						$test = "Тренировочный тест";
					} else {
						$test = "Тренувальний тест";
					}
					printf("<div id='hover_minis_1'><button class='cover'><a href='../tests/completing.php?id=%s' target='_blank'>$test</a></button>",$lesson->getTrTest($_COOKIE['lang']));
						if ($_SESSION['data']['level'] == 4) {
							printf("<span><button class='cover'>
								<a href='../tests/testred.php?tid=%s'>Редактировать тест</a>
								</button></span>",$lesson->getTrTest($_COOKIE['lang']));
						} 
						print("</div>");
				}
				if(isset($_SESSION['data']) && $_SESSION['data']['level']==1){
					if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'){
						$hw = "Домашнее задание";
					} else {
						$hw = "Домашнє завдання";
					}
					printf("<div id='hover_minis_2'><button class='cover'><a href='../homework/index.php?id=%s' target='_blank'>$hw</a></button>",
						$lesson->getJournal($_SESSION['data']['id']));
					print("</div>");
				}
				else if(isset($_SESSION['data']) && $_SESSION['data']['level']!=1){
					printf("<div id='hover_minis_2'><button class='cover'><a href='../homework/index.php?id=%s' target='_blank'>Домашнее задание</a></button>",
						$_GET['id']);
					if ($_SESSION['data']['level'] == 4) {
						printf("<span style='z-index:9999;'><button class='cover'>
							<a href='../tests/testred.php?tid=%s'>Редактировать контрольный тест</a>
							</button><br>
							<button class='cover'>
							<a href='redacthw.php?id=%s'>Редактировать домашнее задание</a>
							</button></span>",$lesson->getContrTest($_COOKIE['lang']),$lesson->getHW());
					}
					print("</div>");
				}
				else{
					if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'){
						printf("<div id='hover_minis_2'><button class='cover'><a href='../test_access/homeworks.php?id=%s' target='_blank'>Домашнее задание</a></button>",
							$lesson->getHW());
					} else {
						printf("<div id='hover_minis_2'><button class='cover'><a href='../test_access/homeworks.php?id=%s' target='_blank'>Домашнє завдання</a></button>",
							$lesson->getHW());
					}
					print("</div>");
				}
			?>
						
					
						<!--<button><a href="#">Тренировочный тест</a></button> 
						<div id="hover_minis_2">
						<button class="cover"><a href="#">Домащнее задание</a></button> 
						<span><button class="cover"><a href="#">Выпаадающий пункт</a></button></span>
						</div> 	-->	
						 <div class="clear"></div> 
						<input type="text" hidden name="com_id_from" value="<?=$_SESSION['data']['id']?>">
						<input type='hidden' name='com_id_chat' id='com_id_chat' value="0">
						<input type='text' hidden name='com_type' id='com_type' value="com_lesson">
						<input type='text' hidden name='com_lang' id='com_lang' value="<?=$_COOKIE['lang']?>">
						<input type='text' hidden name='com_lesson' id='com_lesson' value="<?=$_GET['id']?>">
						<input type='text' hidden name='com_level' id='com_level' value="<?=$_SESSION['data']['level']?>">
						
						

						<!-- БЛОК ЧАТА ДЛЯ АДМИНА/УЧИТЕЛЯ -->	 
						<?php if($_SESSION['data']['level'] > 1): ?>
							<div class="online_chat_onclick_uchen">
							<div class="oc_zagolov">	
								<h4 id="chat_name">Список учеников</h4>
							</div>					
						 
							<div class="com_chat_usr_list">
							</div>							
						 	<?php if($_SESSION['data']['avatar'] != ""): ?>
							<div class="oc_icons">
								<img src="../upload/avatars/<?php print($_SESSION['data']['avatar']); ?>" width="50px" height="50px">
								<img src="../tpl_img/system_users.png" width="50px" height="50px">
							</div>
							<?php else: ?>
							<div class="oc_icons">
								<img src="../upload/avatars/default.jpg" width="50px" height="50px">
								<img src="../tpl_img/system_users.png" width="50px" height="50px">
							</div>
							<?php endif; ?>
							<textarea placeholder="Написать сообщение всем" name="com_text_message_all"></textarea>
																					<table>
								<tbody><tr>
									<td>
										<input type="button" name="com_send_all" onclick="common_send_all()" value="Отправить всем">
										<input type="hidden" name="file_attached" value="">
										<input type="hidden" name="id_name_all" value="0">
									</td>

									<td><span onclick="open_docs_modal(<?php print($_SESSION['data']['id']); ?>)">Прикрепить</span></td>
								</tr>
								<tr>
									<td>
										<div id="attached"></div>
									</td>
								</tr>
							</tbody></table>
							</div>
										
										
										
							<div id="content1" style="display: none;  margin-bottom: -543px;   position: relative;  top: -552px;" class="online_chat_onclick_uchen">
							<div class="oc_zagolov">	
								<h4 id="chat_name"> <span class="solo_name"></span><span><span id="close_chat">x</span></span></h4>
							</div>

							<div id="com_chat_field">
								
							</div>
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
							<textarea placeholder="Написать сообщение..." name="com_text_message"></textarea>
																					<table>
								<tbody><tr>
									<td>
										<input type="button" name="com_send" onclick="common_send()" value="Отправить">
										<input type="hidden" name="file_attached" value="">
									</td>

									<td><span onclick="open_docs_modal(<?php print($_SESSION['data']['id']); ?>)">Прикрепить</span></td>
								</tr>
								<tr>
									<td>
										<div id="attached"></div>
									</td>
								</tr>
							</tbody></table>
							</div>
 
									
						
						<?php endif; ?>
					<!-- БЛОК ЧАТА ДЛЯ АДМИНА/УЧИТЕЛЯ -->	

					<!-- БЛОК ЧАТА ДЛЯ УЧЕНИКА -->	
					<?php
						if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'){
							$attach = "Прикрепить";
							$writeM = "Написать сообщение...";
							$sendM  = "Отправить";
						} else {
							$attach = "Прикріпити";
							$writeM = "Написати повідомлення...";
							$sendM  = "Відправити";
						}
					?>
					<?php if($_SESSION['data']['level'] == 1): ?>
					<div class="online_chat_onclick_uchen">
							<div class="oc_zagolov">	
								<h4 id="chat_name"></h4>
							</div>

							<?php if(isset($_SESSION['data']['level'])): ?>
							<div id="com_chat_field"></div>

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
							<textarea placeholder="<?php echo $writeM; ?>" name="com_text_message"></textarea>
							<?php endif; ?>
							<?php if(isset($_SESSION['data'])): ?>
							<table>
								<tr>
									<td>
										<input type="button" name="com_send" onclick="common_send()" value="<?php echo $sendM; ?>">
										<input type="hidden" name="file_attached" value="">
									</td>
									<td><span onclick="open_docs_modal(<?=$_SESSION['data']['id']?>)"><?php echo $attach; ?></span></td>
								</tr>
								<tr>
									<td colspan="2" style="padding-top:15px;">
										<div id="attached"></div>
									</td>
								</tr>
							</table>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if(!isset($_SESSION['data'])): ?>
					<div class="online_chat_onclick_uchen">
							<div class="oc_zagolov">	
								<h4 id="chat_name"></h4>
							</div>

							<div id="com_chat_field"></div>


							<div class="oc_icons">
								<img src="../tpl_img/icon1.png" width="50px" height="50px">
								<img src="../tpl_img/icon2.png" width="50px" height="50px" id="second_ava">
							</div>

							<textarea placeholder="<?php echo $writeM; ?>" name="com_text_message" id="text_all_stud"></textarea>
							<table>
								<tr>
									<td>
										<input type="button" value="<?php echo $sendM; ?>">
										<input type="hidden" name="file_attached" value="">
									</td>

									<td><!--<span onclick="open_docs_modal(<?=$_SESSION['data']['id']?>)">Прикрепить</span>--></td>
								</tr>
								<tr>
									<td colspan="2" style="padding-top:15px;">
										<div id="attached"></div>
									</td>
								</tr>
							</table>
						</div>
					<?php endif; ?>
					<!-- БЛОК ЧАТА ДЛЯ УЧЕНИКА -->
					</div>
					<div class="clear"></div>
					<?php if (isset($_SESSION['data']['level']) && $_SESSION['data']['level'] > 1 && $_SESSION['data']['level'] <= 4):?>
						<div class='redactme'><a href="redactme.php?id=<?=$id?>">Редактировать урок</a></div>
					<?php endif; ?>
					<?php if ($_SESSION['data']['level'] == 4):?>
					<form action="" method="post">
						<input type="text" name="days_to_up"><input type="submit" name="up_hworks" value="Продлить проект всем">
					</form>
					<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" onsubmit="return confirm('Выдействительно желаете удалить этот урок?')">
						<input type="submit" name="delete" value="удалить"></input>
					</form>
					<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
						<?php if($lesson->getLockStatus() == 0): ?>
							<input type="submit" name="lock" value="заблокировать"></input>
						<?php else: ?>
							<input type="submit" name="unlock" value="разблокировать"></input>
						<?php endif; ?>
					</form>
					<?php endif; ?>
			</div> 
		</div> 
	</div> 
<script type="text/javascript">
	window.onload=function(){
		if($("input[name = com_level]").val() == 1){
			lesson_getChatWithLessonTeacher_student($("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
			var pre_interv = setInterval(lesson_getChatWithLessonTeacher_student,75000,$("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
			$("#text_all_stud").on('focus',function(){
				lesson_getChatWithLessonTeacher_student($("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
				var interv = setInterval(lesson_getChatWithLessonTeacher_student,4000,$("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
				$(this).on('blur',function(){
					clearInterval(interv);
					
				})
			})
			
			
		}
		if($("input[name = com_level]").val() == 2 || $("input[name = com_id_from]").val() == <?php print($new_row['teacher']); ?>){
			lesson_getChatsWithLessonStudents_teacher($("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
			var pre_interv = setInterval(lesson_getChatsWithLessonStudents_teacher,75000,$("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
			$("textarea[name = com_text_message_all]").on('focus',function(){
				//lesson_getChatsWithLessonStudents_teacher($("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
				var interv = setInterval(lesson_getChatsWithLessonStudents_teacher,4000,$("input[name = com_id_from]").val(),$("input[name = com_lesson]").val(),$("input[name = com_lang]").val());
				$(this).on('blur',function(){
					clearInterval(interv);
					
				})
			})
			//common_getMessages($("input[name = com_id_chat]").val());
		}
		$("#close_chat").click(function(){
			$("input[name = com_id_chat]").val('0')
			$(".comonline_chat_onclick_uchen").css("display","block");
			$("#content1").css("display","none");
		});
		/*$('.link1').click(function (e) {
			alert("clicked");
			$(this).toggleClass('active');
			$('#content1').toggle();
			$("input[name = id_name]").val($(this).attr("name"));
			e.stopPropagation();
		});

		$('body').click(function () {
			var link = $('a#link1');
			if (link.hasClass('active')) {
				link.click();
			}
		});*/
	}
</script>
<script type="text/javascript">
		/* ajax files */
	var files;
	$('input[name = file_upl]').change(function(){
	    files = this.files;
	    var dataf = new FormData();
    $.each( files, function( key, value ){
        dataf.append( key, value );
    });
 
    // Отправляем запрос
 
    $.ajax({
	        url : '../tpl_php/ajax/homeworks.php?uploadfiles' ,
			type : 'POST' , 
			dataType : 'json' ,
	        data: dataf,
	        processData: false, // Не обрабатываем файлы (Don't process the files)
	        contentType: false, // Так jQuery скажет серверу что это строковой запрос
	        success: function( data){
	 
	            // Если все ОК
	 
	            
	                // Файлы успешно загружены, делаем что нибудь здесь
	 
	                // выведем пути к загруженным файлам в блок '.ajax-respond'
	 
	                var files_path = data;
		                
						var str = "";
						str += "<div class='simple_doc' onclick=\"attach_document('"+data["name"]+"','"+data["real_name"]+"')\">"+data["real_name"]+"</div>"
						//alert(data);
						$("#doc_list").empty();
						$("#doc_list").append(str);
						var html = '';
	                /*$.each( files_path, function( key, val ){ html += val +'<br>'; } )
	                $('.ajax-respond').html( html );*/
	        },
	        error: function(){
	            console.log('ОШИБКИ AJAX запроса ');
	        }
	    });
	    // Отправляем запрос
	    
	});
$('.submit .button').click(function( event ){

    event.stopPropagation(); // Остановка происходящего
    event.preventDefault();  // Полная остановка происходящего
 
    // Создадим данные формы и добавим в них данные файлов из files
 
    
 
});
/* ajax files */
	</script>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 