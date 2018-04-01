<?php
	session_start();
	//echo !!!0;
	require_once '../tpl_php/functions.php';
	if(isset($_SESSION['data']) && (!isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0)) 
	    require_once '../tpl_php/autoload.php';
	else
	    require_once '../tpl_php/autoload_light.php';
	if (isset($_POST['send_file'])) {
		if(Cfile::isSecure($_FILES['word_file'])){
			//print("a");
			$name = Cfile::Load($_FILES['word_file']);
			if($name != false)
			{
				//print("b");
				$sql = "DELETE FROM os_sys_docs WHERE name_cat=1";
				$res = $mysqli->query($sql);
				//print("<br>$sql<br>");
				$sql = "INSERT INTO os_sys_docs(name_cat,name_doc) VALUES(1,'$name')";
				$res = $mysqli->query($sql);
				//print("<br>$sql<br>");
			}
		}
		header("location:".$_SERVER['REQUEST_URI']);
	}
	if (isset($_POST['hw_save'])) {
		
	}
	$sql = "SELECT * FROM os_homeworks";
	$res = $mysqli->query($sql);
	while($row = $res->fetch_assoc()) {
		if(isset($_POST["hw_save_".$row["id"]])) {
			//var_dump($_POST);
			if($_SESSION["data"]["level"] == 1) {
				if($_POST['attached_file'] != "") {
					$sql_insert = sprintf("INSERT INTO os_homework_docs(id_hw, `from`, file_name) VALUES('%s','%s','%s')",$row["id"],"student",$_POST["attached_file"]);
					//print("<br>$sql_insert<br>");
					$res_insert = $mysqli->query($sql_insert);
				}
				$sql_update = sprintf("UPDATE os_homeworks SET change_status=1, check_status=3 WHERE id=%s",$row["id"]);
				$res_update = $mysqli->query($sql_update);
			}
			if($_SESSION['data']['level'] > 1) {
				if($_POST['attached_file'] != "") {
					$sql_insert = sprintf("INSERT INTO os_homework_docs(id_hw, `from`, file_name) VALUES('%s','%s','%s')",$row["id"],"teacher",$_POST["attached_file"]);
					$res_insert = $mysqli->query($sql_insert);
				}
				if(isset($_POST['option5'])) {
					$rework = "4";
				} else {
					$rework = "2";
				}
				$sql_journal = sprintf("UPDATE os_journal SET mark_hw='%s' WHERE id_s=%s
										   AND id_l=(SELECT id_lesson FROM os_lesson_homework WHERE id=%s)",$_POST['hw_mark'],$_POST['id_u'],$_POST['id_hw']);
				$res_journal = $mysqli->query($sql_journal);
				$sql_update = sprintf("UPDATE os_homeworks SET change_status=1, comment='%s', status='$rework', check_status='$rework' WHERE id=%s",
									   $_POST['pholder'], $row['id']);
				$res_update = $mysqli->query($sql_update);
			}
			header("location:".$_SERVER["REQUEST_URI"]);
		}
	}
	//print(date("Y-m-d",define_week_start_and_end("start", strtotime("2016-10-12"))));
	/*if (isset($_SESSION["data"]) && $_SESSION["data"]["level"] == 1) {
	$sql = sprintf("SELECT * FROM os_lessons WHERE class=(SELECT class FROM os_users WHERE id='%s') AND subject IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",
			$_SESSION["data"]["id"],$_SESSION["data"]["id"]);
		$res = $mysqli->query($sql);
		//print("<br>$sql<br>");
		while ($row = $res->fetch_assoc()) {
			$sql_lhw = sprintf("SELECT * FROM os_lesson_homework WHERE id_lesson=%s",$row["id"]);
			$res_lhw = $mysqli->query($sql_lhw);
			if ($res_lhw->num_rows!=0) {
				$row_lhw = $res_lhw->fetch_assoc();
				$sql_homework = sprintf("SELECT * FROM os_homeworks WHERE `from`='%s' AND id_hw='%s'",$_SESSION["data"]["id"],$row_lhw["id"]);
				$res_homework = $mysqli->query($sql_homework);
				//print($sql_journal);
				if ($res_homework->num_rows == 0) {
					//print("<br>incorrect<br>");
					if ($row["is_control"] == 0) {
						$status = 1;
					}
					else{
						$status = 3;
					}
					$date_ru = explode(" ",$row["date_ru"]);
					$date_ua = explode(" ",$row["date_ua"]);
					$sql_create = sprintf("INSERT INTO os_homeworks(date_h,`from`,subj,class,id_hw,status,check_status) VALUES('%s',%s,%s,%s,%s,$status,2)",
						$date_ru[0],$_SESSION["data"]["id"],$row["subject"],$row["class"],$row_lhw["id"]);
					$res_create = $mysqli->query($sql_create);
					//print("<be>$sql_create<br>");
				}
			}
			
		}
	}*/
?>
<!DOCTYPE html> 
<html>
<head>  		
	<title>Домашнее задание - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/full_hw.1236.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
		<?php
			if(isset($_SESSION["data"]) && $_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=5";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=5",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(5, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div id="docs" style="">
			<div class="close" onclick="close_docs_modal()">X</div>
				<iframe name='first_frame' style="width:400px;height:400px;display:none;"><?php

					if(isset($_POST['upload_file'])){
						//var_dump($_FILES['file']);
						if(Cfile::isSecure($_FILES['file_upl'])){
							//print("a");
							$name = Cfile::Load($_FILES['file_upl']);
							$truth_name = $_FILES['file_upl']['name'];
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

			<input type="hidden" name="user_id" value=""></input>
			<input type="hidden" name="hw_id" value=""></input>
			<!--<span class='submit button'>Загрузить фото</span>
			<input type="submit" name="upload_file" value="Залить файл">--> 
		</form>
			<div id="doc_list"></div>
		</div>
		<div class="block0">
			<input type="hidden" name="level" value="<?=$_SESSION['data']['level']?>">
			<input type="hidden" name="language" value="<?=$_COOKIE['lang']?>">
			<input type="hidden" name="id" value="<?=$_SESSION['data']['id']?>">
			<input type="hidden" name="count" value="5">
			<input type="hidden" name="count_all">
			<input type="hidden" name="cur_page" value="1">
			<input type="hidden" name="cur_bot_lim" value="0">
			<input type="hidden" name="cur_top_lim" value="5">
			<input type="hidden" name="currentCourse" value="<?php print($_SESSION['data']['currentCourse']); ?>">
			<?php
				if(isset($_GET['id'])){
					print("<input type='hidden' name='get' value='yep'>");
				}
				else{
					print("<input type='hidden' name='get' value='nope'>");
				}
			?>

			<?php if(isset($_SESSION["data"]) && $_SESSION['data']['level'] == 4): ?>
			<form method="post" action="" enctype="multipart/form-data">
				<input type="file" name="word_file">
				<input type="submit" name="send_file" value="Загрузить шаблон">
			</form>
			<?php endif; ?>
			<?php
				$sql = "SELECT * FROM os_sys_docs WHERE name_cat=1";
				$res = $mysqli->query($sql);
				if ($res->num_rows!=0) {
					$row = $res->fetch_assoc();
					if ($row['name_doc']!="") {
						if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
							printf("<a href='../upload/hworks/%s' download>Загрузить бланк ДЗ</a>",$row['name_doc']);
						} else {
							printf("<a href='../upload/hworks/%s' download>Завантажити бланк ДЗ</a>",$row['name_doc']);
						}
					}
				}
			?>
			<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				<h1>Домашнее задание</h1>
			<? else: ?>
				<h1>Домашнє завдання</h1>
			<? endif; ?>
			
				<!-- ФИЛЬТР УЧЕНИКА -->
			<?php if(isset($_SESSION["data"]) && $_SESSION['data']['level'] == 1): ?>
			<div class="homework_filter"> 
			<form action="#" method="post"> 
				<?php
					if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru') {
						$all_subjects = "Все предметы";
						$show = "Показать";
						$status = array(
								0 => "Только несделанные",
								1 => "Проверенные",
								2 => "Контрольные работы",
								3 => "На доработке",
								4 => "На проверке",
								5 => "Все"
							);
						$toLesson = "К уроку";
					} else {
						$all_subjects = "Усi предмети";
						$show = "Показати";
						$status = array(
								0 => "Тільки незроблені",
								1 => "Перевірені",
								2 => "Контрольні роботи",
								3 => "На доопрацюванні",
								4 => "На перевірці",
								5 => "Усі"
							);
						$toLesson = "До уроку";
					}
				?>
				<input type="hidden" name="class" value="<?=$_SESSION['data']['class']?>">
			    <table>
					<tr> 						 
						<?php
							if(!isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0) {
								$sql = sprintf("SELECT * FROM os_subjects WHERE id 
									IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_SESSION['data']['id']);
							} else {
								$sql = sprintf("SELECT * FROM os_subjects WHERE id IN (
												SELECT id_s FROM os_class_subj WHERE class=%s AND course=%s)",
												$_SESSION['data']['class'],$_SESSION['data']['currentCourse']);
							}
							$res = $mysqli->query($sql);
						?>
							<td><span>Предмет</span><br>
								<select name="subject" >
									<option value="0" selected><?php echo $all_subjects; ?></option>
									<?php
									while ($row = $res->fetch_assoc()) {
										printf("<option value='%s'>%s</option>",$row['id'],$row['name_'.$_COOKIE['lang']]);
									}
									?>
								</select>
							</td>
							<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru'): ?>
						<td><span>Дата с</span><br>
							<?php else: ?>
						<td><span>Дата від</span><br>
							<?php endif; ?>
							<input type="date" name="date_s">
						</td>
						<td><span>Дата до</span><br>
							<input type="date" name="date_do">
						</td>
							<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru'): ?>
						<td><span>Показать</span><br>
							<?php else: ?>
						<td><span>Показати</span><br>
							<?php endif; ?>
							<select name="show">
								<option selected value="5">по 5</option>
								<option value="10">по 10</option>
								<option value="15">по 15</option>
								<option value="20">по 20</option>
								<option value="25">по 25</option>
								<option value="30">по 30</option>
							</select>
						</td>
						<td><span>Статус ДЗ</span><br>
							<select name="status">
								<option value="1"><?php echo $status[0]; ?></option>
								<option value="2"><?php echo $status[1]; ?></option>
								<option value="3"><?php echo $status[2]; ?></option>
								<option value="4"><?php echo $status[3]; ?></option>
								<option value="5"><?php echo $status[4]; ?></option>
								<option selected value="'1','2','3','4','5'"><?php echo $status[5]; ?></option>
							</select>
						</td>
										
					</tr>
				</table>
			</form>
			</div>  
				<?php endif; ?>
				<!-- ФИЛЬТР УЧЕНИКА -->	
				
				<!-- ФИЛЬТР АДМИНА -->
				<?php if(isset($_SESSION["data"]) && $_SESSION['data']['level'] > 1): ?>	
			<div class="homework_filter"> 
			<form action="#" method="post"> 
			    <table>
					<tr>
						<td><?php if($_SESSION['data']['level'] != 2): ?>
							<span>Класс</span><br>
							<?php 
								$sql_classes = "SELECT * FROM os_class_manager";
								$res_classes = $mysqli->query($sql_classes);
							?>
							<select name="class">
								<?php
									if ($res_classes->num_rows != 0) {
										while ($row_classes = $res_classes->fetch_assoc()) {
											printf("<option value='%s'>%s</option>",$row_classes['id'],$row_classes['class_name']);
										}
									}
								?>
							</select>
							<?php endif; ?>
							<?php if ( $_SESSION['data']['level'] == 2 ) : ?>
						<?php 
							$sql = sprintf("SELECT * FROM os_teacher_class WHERE id_teacher='%s'",$_SESSION['data']['id']);
							//print($sql);
							$res = $mysqli->query($sql);		

						?>
							<td><span>Класс</span><br>
								<select name="class" >
									<option value="0" selected>Все классы</option>
									<?php
									if($res->num_rows!=0) {
										while ($row = $res->fetch_assoc()) {
											printf("<option value='%s'>%s-й класс</option>",$row['id_c'],$row['id_c']);
										}
									}
									?>
								</select>
							</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО Учителям -->
						<?php endif;  ?>
						</td>			 
						<td><span>Предмет</span><br>
							<select name="subject">

							</select>
						</td>
						<td><span>Дата с</span><br>
							<input type="date" name="date_s">
						</td>
						<td><span>Дата до</span><br>
							<input type="date" name="date_do">
						</td>
						<td><span>Показать</span><br>
							<select name="show">
								<option selected value="5">по 5</option>
								<option value="10">по 10</option>
								<option value="15">по 15</option>
								<option value="20">по 20</option>
								<option value="25">по 25</option>
								<option value="30">по 30</option>
							</select>
						</td>
						<td><span>Статус</span><br>
							<select name="status">
								<option value="1">Только несделанные</option>
								<option value="2">Проверенные</option>
								<option value="3">Контрольные работы</option>
								<option value="4">На доработке</option>
								<option value="5">На проверке</option>
								<option selected value="'1','2','3','4','5'">Все</option>
							</select>
						</td>
										
					</tr>
					<tr>
					<td colspan="6"><input type="search" name="search" placeholder="ученик"> <input type="button" name="start_search" value="Поиск"> </td>
					</tr>
				</table>
			</form>
			</div>
				<?php endif; ?>
				<!-- ФИЛЬТР АДМИНА -->	
				
			<!--<div class="homework_table table_hw">-->
			<!-- КОНТЕНТ УЧЕНИКА -->


			<?php if(isset($_SESSION["data"]) && $_SESSION['data']['level'] == 1): ?>
				<div class="homework_table table_hw">
			<?php endif; ?>

			<?php if(isset($_SESSION["data"]) && $_SESSION['data']['level'] > 1): ?>
				<div class="homework_table hw_table_admin_d">
				<table class='hat_table'><tr><td>ФИО</td><td>Класс</td><td>Неделя</td><td>Тема</td><td>Дата загрузки</td><td>Оценка</td></tr></table>
			<?php endif; ?>
			<?php
				$lang = $_COOKIE['lang'];
				//print("<br>$lang<br>");
	if(isset($_GET['id'])){
		if($_SESSION['data']['level'] == 1){
			$options = new Options();
			$date_end = $options->get_option('semester_end_date');
			$semester = $options->get_option('semester_current_number');
			//print($date_end);
			$iter = 0;
			$end_timestamp	   = strtotime($date_end);
			$date_plus_two	   = $end_timestamp+60*60*24*2;
			$current_timestamp = time();
			$result_timestump  = $end_timestamp - $current_timestamp;

			if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru') {
				$attach   	 = "Прикрепить";
				$send    	 = "Отправить";
				$reworkT 	 = "Отправлено на доработку";
				$checkT  	 = "Отправлено на проверку учителю";
				$yourMark    = "Ваша оценка";
				$stopAct     = "Отменить";
				$sentAnsw    = "Отправленный ответ";
				$checkedAnsw = "Проверенное";
				$testHw		 = "Тестовое ДЗ";
				$createHw	 = "Творческое ДЗ";
				$translate_array = array( "template_timer_frame"  => "<span class='hw-timer-rest'>До окончания загрузки творческого ДЗ осталось <psan class='hw-timer'>%sд %sч</span></span>",
										  "incorrect_timer_frame" => "<span class='hw-timer-rest'>Истек срок выполнения творческого ДЗ по данному уроку</span>" );
			} else {
				$attach  	 = "Прикріпити";
				$send    	 = "Надіслати";
				$reworkT 	 = "Відправлено на допрацювання";
				$checkT  	 = "Відправлено на перевірку вчителю";
				$yourMark 	 = "Ваша оцінка";
				$stopAct 	 = "Відмінити";
				$sentAnsw    = "Відправлена відповідь";
				$checkedAnsw = "Перевірене";
				$testHw		 = "Тестове ДЗ";
				$createHw	 = "Творче ДЗ";
				$translate_array = array( "template_timer_frame"  => "<span class='hw-timer-rest'>До завершення завантаження творчого ДЗ залишилося <psan class='hw-timer'>%sд %sг</span></span>",
										  "incorrect_timer_frame" => "<span class='hw-timer-rest'>Термін виконання творчого ДЗ до цього уроку закінчився</span>" );
			}
			$sql = sprintf("SELECT * FROM os_journal WHERE id='%s'",$_GET['id']);
			$res = $mysqli->query($sql);

			
			if($res->num_rows != 0){
				$row = $res->fetch_assoc();


				$sql_user = sprintf("SELECT id ,CONCAT(surname,' ',name,' ',patronymic) AS fio, class FROM os_users WHERE id='%s'",$_SESSION['data']['id']);
				//print("<br>$sql_user<br>");
				$res_user = $mysqli->query($sql_user);
				$row_user = $res_user->fetch_assoc();
				$result = array(
					"fio" => $row_user['fio'],
					"id_u" => $row_user['id'],
					"class" => $row_user['class']
				);

				$sql_lname = sprintf("SELECT id, title_$lang, DATE(date_$lang) AS date_lesson, is_control, subject, course, date_ru, date_ua, theme FROM os_lessons 
					WHERE id=%s",$row['id_l']);
				//print("<br>$sql_lname<br>");
				$res_lname = $mysqli->query($sql_lname);
				$row_lname = $res_lname->fetch_assoc();
				$result['ltitle'] = $row_lname['title_'.$lang];
				$result['lid'] = $row_lname['id'];
				$result['ldate'] = $row_lname['date_lesson'];


				$sql_hw = sprintf("SELECT * FROM os_homeworks WHERE `from`=%s AND id_hw=(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",
					$_SESSION['data']['id'],$result['lid']);
				$res_hw = $mysqli->query($sql_hw);
				//print("<br>$sql_hw<br>");
				
				if($res_hw->num_rows != 0){
					$row_hw = $res_hw->fetch_assoc();
					//блокировка
					if($_SESSION['data']['class'] != 11 && $_SESSION['data']['edu_type'] == 1 && $_SESSION['data']['currentCourse'] == 0) {
						$lock_result = control_semester($row_lname['date_lesson']);
						$date_lesson_new = Date("Y-m-d h:i:s");
						$row_hw['last_hw_date'] .= " 00:00:01";
						if($row_hw['last_hw_date'] <= $date_lesson_new) {
							$result['lock_status'] = 'locked';
							$result['last_hw_message'] = $translate_array['incorrect_timer_frame'];
						}
						if($row_hw['last_hw_date'] >= $date_lesson_new) {
							$currentTimestamp = time();
							$goalTimestamp	  = strtotime($row_hw['last_hw_date']);
							$resultTimestamp  = $goalTimestamp - $currentTimestamp;
							$days = floor($resultTimestamp / (24 * 60 * 60));
							$hours = floor(($resultTimestamp - ($days * (24 * 60 * 60))) / (60*60));
							$result['last_hw_message'] = sprintf($translate_array['template_timer_frame'], $days, $hours);
						}
						if(!$lock_result/*|| $row_lname['date_lesson'] < $date_lesson_new*/)
							$result["lock_status"] = 'locked';
					}
					$result["id"] = $row_hw['id'];
					$result["id_hw"] = $row_hw['id_hw'];
					$result["date"] = $row_hw['date_h'];
					$result["comment"] = $row_hw['comment'];
					$result["status"] = $row_hw['status'];
					$result["check_status"] = $row_hw['check_status'];
					$result["change_status"] = $row_hw['change_status'];
				}
				

				$sql_hw1 = sprintf("SELECT id,hw_text_$lang FROM os_lesson_homework WHERE id='%s'",$row_hw['id_hw']);
				//print("<br>$sql_hw1<br>");
				$res_hw1 = $mysqli->query($sql_hw1);
				$row_hw1 = $res_hw1->fetch_assoc();
				$result['text_hw'] = $row_hw1['hw_text_'.$lang];




				$sql_test = sprintf("SELECT id_test FROM os_lesson_test WHERE lang='%s' AND type=5 AND id_lesson=%s",
					$_COOKIE['lang'],$row_lname['id']);
				//print("<br>$sql_test<br>");
				//print($sql_test);
				$res_test = $mysqli->query($sql_test);
				if($res_test->num_rows != 0){
					$row_test = $res_test->fetch_assoc();
					$result['c_test_id'] = $row_test['id_test'];
				}
				else{
					$result['c_test_id'] = 0;
				}
				//var_dump($result);
				$sql_mark = sprintf("SELECT mark_hw FROM os_journal WHERE id_s='%s' 
					AND id_l=(SELECT id_lesson FROM os_lesson_homework WHERE id='%s')",$_SESSION['data']['id'],$row_hw['id_hw']);
				//print("<br>$sql_mark<br>");
				$res_mark = $mysqli->query($sql_mark);
				if($res_mark->num_rows != 0) {
					$row_mark = $res_mark->fetch_assoc();
					$result["mark"] = $row_mark['mark_hw'];
				} else {
					if($row_lname['is_control'] == 1) {
						$control = 3;
					} else {
						$control = 1;
					}
					$sql_insert_journal = sprintf("INSERT INTO os_journal (id_s, id_l, date_ru, date_ua, status, id_subj, course, theme) 
													    VALUES (%s, %s, '%s', '%s', $control, %s, %s, %s)",
													    $_SESSION['data']['id'], $row_lname['id'], $row_lname['date_ru'], $row_lname['date_ua']
														, $row_lname['subject'], $row_lname['course'], $row_lname['theme']);
					$res_insert_journal = $mysqli->query($sql_insert_journal);
					$result["mark"] = 0;
				}
				$sql_docs1 = sprintf("SELECT * FROM os_homework_docs WHERE `from`='student' AND id_hw='%s'",$row_hw['id']);
				//print("<br>$sql_docs1<br>");
				$res_docs1 = $mysqli->query($sql_docs1);
				if($res_docs1->num_rows != 0){
					while ($row_docs1 = $res_docs1->fetch_assoc()) {
						$result["student"][] = $row_docs1['file_name'];
					}
				}
				$sql_docs2 = sprintf("SELECT * FROM os_homework_docs WHERE `from`='teacher' AND id_hw='%s'",$row_hw['id']);
				$res_docs2 = $mysqli->query($sql_docs2);
				if($res_docs2->num_rows != 0 && $res_docs2->num_rows != NULL){
					while ($row_docs2 = $res_docs2->fetch_assoc()) {
						$result["teacher"][] = $row_docs2['file_name'];
					}
				}
				$str = "";
				$links_s = "";
				$links_t = "";
				$hat = "";
				$change_status = "";
					

				/* uploaded file's block */
					$files_module = "";
				/* uploaded file's block */

				/* homework's block hat */
					$hw_hat = "";
					$at_link = "";
				/* homework's block hat */

				foreach( $result["student"] as $value){
					$links_s .= "<a download href='../upload/hworks/".$value."'>".substr($value,22,18)."</a>, ";
				}
				foreach( $result["teacher"] as $value){
					$links_t .= "<a download href='../upload/hworks/".$value."'>".substr($value,22,18)."</a>, ";
				}
				$links_s = rtrim($links_s,', ');
				$links_t = rtrim($links_t,', ');
				//var_dump($result);
				$upd = "";
				//var_dump($result);
				if(isset($result['id']) && ($_SESSION['data']['level'] == 1) || ($_SESSION['data']['level'] > 1 && $_SESSION['data']['level'] != 3)){
					if ($_SESSION['data']['level'] > 1 && $_SESSION['data']['level'] != 3) {
						$at_link ="<br><span class='hw_add_docs' onclick=\"open_docs_modal(".$result["id"].",'teacher',".$result['id_u'].")\">$attach</span>
					<br><span class='atachik' id='attached".$result["id"]."'></span>";
					//print("<br>Alalalal lalala lalal <br>");
					}
					if($result['lock_status'] != 'locked') {
						if ($_SESSION['data']['level'] == 1) {
							$at_link ="<br><span class='hw_add_docs' onclick=\"open_docs_modal(".$result["id"].",'student',".$result['id_u'].")\">$attach</span>
						<br><span class='atachik' id='attached".$result["id"]."'></span>";
						$upd = sprintf("<input type='submit' class='hw_save' name='hw_save_%s' value='$send'>",$result["id"]);
						}
					}
				}
				//print("<p>$links_s</p>");
				if($result['check_status'] == 4){
					$hat .= "<h2 class='rework'>$reworkT</h2>";
				}
				if($result['check_status'] == 3){
					$hat .= "<h2 class='rework'>$checkT</h2>";
				}
				$classes = "";

				if($result['mark'] != 0 && $result['check_status'] != 4 && $result['check_status'] != 3 && $result['status'] != 4){
					if($result['mark'] != "" && $result['mark'] != null && $result['mark'] != 0)
							if ($result["status"] == 3) {
								$classes = "hw_stat_3";
							}
							else if($result['status'] == 1){
								$classes = "hw_stat_1";
							}
							else if($result['status'] == 2){
								$classes = "hw_stat_2";
							}
							else if($result['status'] == 4){
								$classes = "hw_stat_1";
							}
							else{
								$classes = "hw_stat_1";
							}
						$hat .= "<div class='mark_s $classes'>$yourMark: ".$result['mark']."</div>";
				}
				//var_dump($result);
				$make_read = "";
				if($result['change_status'] == 1){
					if ($_SESSION['data']['level'] > 1)
						$change_status .= "<span class='circled'>1</span>";
					if ($_SESSION['data']['level'] == 1 && $result['check_status'] != 3){
						$make_read = " onclick=\"make_read(".$result['id'].")\"";
						$change_status .= "<span class='circled'>1</span>";
					}
				}

				$classes = "";
				$classes_new = "";

				if ($result["status"] == 3) {
							$classes = "hw_zadanie_kontr user_hw_page";
							$classes_new = "hw_zadanie_kontr hw_zadanie_block_in";
						
						/*	$classes = "hw_zadanie_kontr admin_hw_page";
							$classes_new = "hw_zadanie_kontr hw_zadanie_block_in";
						*/
					}
					else if($result['status'] == 1){
							$classes = "hw_zadanie_norm user_hw_page";
							$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						
						/*	$classes = "hw_zadanie_norm admin_hw_page";
							$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						*/
					}
					else if($result['status'] == 2){
							$classes = "hw_zadanie_prov user_hw_page";
							$classes_new = "hw_zadanie_prov hw_zadanie_block_in";
						
						/*	$classes = "hw_zadanie_prov admin_hw_page";
							$classes_new = "hw_zadanie_prov hw_zadanie_block_in";
						*/
					}
					else if($result['status'] == 4){
							$classes = "hw_zadanie_norm user_hw_page";
							$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						
						/*	$classes = "hw_zadanie_norm admin_hw_page";
							$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						*/
					}
					else{
						$classes = "hw_zadanie_norm user_hw_page";
						$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
					}

				//print($result['ldate']);
				$lmore_text = "<div class='comment'>".$result['comment']."</div>";
				$lmore_text .= "<input type='hidden' name='id_hw' value='".$result['id_hw']."'><table>
				<tr>
					<td>
						<input type='hidden' name='id_u' value='".$result['id_u']."'>
						<input type='hidden' name='attached_file' value='' id='file_attached".$row_hw['id']."'>
					</td>
					<td>
						<!--Оценка за ДЗ: <span class='mark'>".$result['mark']."</span><br>-->
						$upd
						<input type='button' class='hw_save hw_otmena' value='$stopAct'>
					</td>
				</tr>
				</table>
				";

				$files_module .= "<div class='by_stud'>
							<p>$sentAnsw: </p>$links_s
						</div>
						<div class='by_teach'>
							<p>$checkedAnsw: </p>$links_t
						</div>
						<div>$at_link</div>";
				$hw_hat .= "<div class='$classes'>$hat {$result['last_hw_message']}<h3>Тема: ".$result['ltitle']." </h3><div class='dates'>(".
				date("d.m",define_week_start_and_end("start", strtotime($result['ldate'])))."-".date("d.m",define_week_start_and_end("end", strtotime($result['ldate']))).
				")</div><br><a class='hw_uc_testdz' target='_blank' href='http://online-shkola.com.ua/tests/completing.php?id=".$result['c_test_id']."'>$testHw</a>".
				"<a class='hw_kurok' href=\"javascript:onoff2('div".$row_hw['id']."');\"$make_read>$createHw $change_status </a>".
				"<a class='hw_kurok idsd' href='../lessons/watch.php?id=".$result['lid']."'>$toLesson</a></div>";

				$str .= "<form method='post' action=''>".$hw_hat;
				$str .= "<div id='div".$row_hw['id']."' class='$classes_new' style='display:none;'>
					<!--<h4><a class='hw_uslovia' href='../lessons/lookhw.php?id=".$row_hw['id']."'>Условия ДЗ</a></h4>-->".$result['text_hw'].
						"<table>
					<tr>
						<td>
							$files_module
						</td>
					</tr>
						</table>
							$lmore_text
						</div>
					</form>";
				print($str);
			}
		}
			if ($_SESSION['data']['level'] > 1) {
				if(!isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0) {
					$sql = sprintf("SELECT * FROM os_users WHERE id IN(SELECT id_student FROM os_student_subjects 
						WHERE id_subject=(SELECT subject FROM os_lessons WHERE id=%s)) 
					AND class IN(SELECT id_class FROM os_lesson_classes WHERE id_lesson=%s)",$_GET['id'],$_GET['id']);
				} else {
					$max_id_where = sprintf("SELECT DISTINCT id_user FROM os_courses_students WHERE id_course=%s AND payment_end_date>='%s'",
						$_SESSION['data']['currentCourse'],Date("Y-m-d"));
					$sql = sprintf("SELECT * FROM os_users WHERE id IN (%s) 
									AND class IN(SELECT id_class FROM os_lesson_classes WHERE id_lesson=%s)",
									$max_id_where, $_GET['id']);
				}
				$res = $mysqli->query($sql);
				

			if ($res->num_rows!=0) {
			while ($row = $res->fetch_assoc()) {
				$result = array(
					"fio" => $row['surname'].' '.$row['name'].' '.$row['patronymic'].' ',
					"id_u" => $row['id'],
					"class" => $row['class']
				);
				$sql_hw = sprintf("SELECT * FROM os_homeworks WHERE `from`=%s AND id_hw=(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",
					$row['id'],$_GET['id']);
				//print($sql_hw);
				$res_hw = $mysqli->query($sql_hw);
				
				if($res_hw->num_rows != 0){
					$row_hw = $res_hw->fetch_assoc();
					$result["id"] = $row_hw['id'];
					$result["id_hw"] = $row_hw['id_hw'];
					$result["date"] = $row_hw['date_h'];
					$result["comment"] = $row_hw['comment'];
					$result["status"] = $row_hw['status'];
					$result["check_status"] = $row_hw['check_status'];
					$result["change_status"] = $row_hw['change_status'];
				}
				if (isset($result['id'])) {
					$sql_mark_max = sprintf("SELECT * FROM os_lesson_homework WHERE id='%s'",$result['id_hw']);
					$res_mark_max = $mysqli->query($sql_mark_max);
					$row_mark_max = $res_mark_max->fetch_assoc();
					$result["mark_max"] = $row_mark_max["mark"];
				}
				$sql_lname = sprintf("SELECT id, title_$lang, DATE(date_$lang) AS date_lesson FROM os_lessons 
					WHERE id=%s",$_GET['id']);
				//print("<br>$sql_lname<br>");
				$res_lname = $mysqli->query($sql_lname);
				$row_lname = $res_lname->fetch_assoc();
				$result['ltitle'] = $row_lname['title_'.$lang];
				$result['lid'] = $row_lname['id'];
				$result['ldate'] = $row_lname['date_lesson'];

				$sql_hw1 = sprintf("SELECT id,hw_text_$lang FROM os_lesson_homework WHERE id='%s'",$row_hw['id_hw']);
				//print("<br>$sql_hw<br>");
				$res_hw1 = $mysqli->query($sql_hw1);
				$row_hw1 = $res_hw1->fetch_assoc();
				$result['text_hw'] = $row_hw1['hw_text_'.$lang];

				$sql_test = sprintf("SELECT id_test FROM os_lesson_test WHERE lang='%s' AND type=5 AND id_lesson=%s",
					$_COOKIE['lang'],$row_lname['id']);
				//print("<br>$sql_test<br>");
				$res_test = $mysqli->query($sql_test);
				if($res_test->num_rows != 0){
					$row_test = $res_test->fetch_assoc();
					$result['c_test_id'] = $row_test['id_test'];
				}
				else{
					$result['c_test_id'] = 0;
				}
				$sql_mark = sprintf("SELECT * FROM os_journal WHERE id_s='%s' 
					AND id_l=(SELECT id_lesson FROM os_lesson_homework WHERE id='%s')",$result['id_u'],$row_hw['id_hw']);
				if($res_mark->num_rows != 0) {
					//print("<br>$sql_mark<br>");
					$res_mark = $mysqli->query($sql_mark);
					$row_mark = $res_mark->fetch_assoc();
					$result["mark"] = $row_mark['mark_hw'];
				} else {
					if($row_lname['is_control'] == 1) {
						$control = 3;
					} else {
						$control = 1;
					}
					$sql_insert_journal = sprintf("INSERT INTO os_journal (id_s, id_l, date_ru, date_ua, status, id_subj, course, theme) 
													    VALUES (%s, %s, '%s', '%s', $control, %s, %s, %s)",
													    $result['id_u'], $row_lname['id'], $row_lname['date_ru'], $row_lname['date_ua']
														, $row_lname['subject'], $row_lname['course'], $row_lname['theme']);
					$res_insert_journal = $mysqli->query($sql_insert_journal);
					$result["mark"] = 0;
				}
				
				$sql_docs1 = sprintf("SELECT * FROM os_homework_docs WHERE `from`='student' AND id_hw='%s'",$row_hw['id']);
				$res_docs1 = $mysqli->query($sql_docs1);
				if($res_docs1->num_rows != 0){
					while ($row_docs1 = $res_docs1->fetch_assoc()) {
						$result["student"][] = $row_docs1['file_name'];
					}
				}
				$sql_docs2 = sprintf("SELECT * FROM os_homework_docs WHERE `from`='teacher' AND id_hw='%s'",$row_hw['id']);
				$res_docs2 = $mysqli->query($sql_docs2);
				if($res_docs2->num_rows != 0 && $res_docs2->num_rows != NULL){
					while ($row_docs2 = $res_docs2->fetch_assoc()) {
						$result["teacher"][] = $row_docs2['file_name'];
					}
				}

				$str = "";
					$links_s = "";
					$links_t = "";
					$hat = "";
					$change_status = "";
					$classes = "";
					$classes_new = "";
					/* uploaded file's block */
					$files_module = "";
					/* uploaded file's block */

					/* homework's block hat */
					$hw_hat = "";
					$at_link = "";
					/* homework's block hat */

					if (isset($result['id'])) {
						if(isset($result["student"])) {
							foreach( $result["student"] as $value){
								$links_s .= substr($value,22,18)."<a href='../upload/hworks/$value'> (Скачать)</a>, ";
							}
						}
						if(isset($result["teacher"])) {
							foreach( $result["teacher"] as $value){
								$links_t .= substr($value,22,18)."<a download href='../upload/hworks/$value'> (Скачать)</a>, ";
							}
						}
					}
					/*echo "<pre>";
					print_r($result);
					echo "</pre>";*/
					$links_s = rtrim($links_s,', ');
					$links_t = rtrim($links_t,', ');
					/*if($_SESSION['data']['level'] != 3){
						$links_t.="<br><span onclick=\"open_docs_modal(".$row['id'].",'teacher',".$result['id_u'].")\">Прикрепить</span>
						<br><span class='atachik' id='attached".$row['id']."'></span>";
					}*/
					$eld_hat = "";
					if($result['check_status'] == 4){
						$eld_hat .= "<h2 class='rework'>Отправлено на доработку</h2>";
					}
					if($result['check_status'] == 3){
						$eld_hat .= "<h2 class='rework'>Отправлено на проверку учителю</h2>";
					}
					if($result['check_status'] != 3 && $result['check_status'] != 4){
						$eld_hat .= sprintf("<h2 class='rework'>Оценка: %s</h2>",$result['mark']);
					}
					if(isset($result['id']) && ($_SESSION['data']['level'] == 1) || ($_SESSION['data']['level'] > 1 && $_SESSION['data']['level'] != 3)){
						if ($_SESSION['data']['level'] > 1 && $_SESSION['data']['level'] != 3) {
							$at_link ="<br><span class='hw_add_docs' onclick=\"open_docs_modal(".$result["id"].",'teacher',".$result['id_u'].")\">Прикрепить</span>
						<br><span class='atachik' id='attached".$result["id"]."'></span>";
						}
						if ($_SESSION['data']['level'] == 1) {
							$at_link ="<br><span class='hw_add_docs' onclick=\"open_docs_modal(".$result["id"].",'student',".$result['id_u'].")\">Прикрепить</span>
						<br><span class='atachik' id='attached".$result["id"]."'></span>";
						}
					}
					if($result['mark'] != 0 && $result['check_status'] != 4 && $result['status'] != 4){
						if($result['mark'] != "" && $result['mark'] != null && $result['mark'] != 0){
							if ($result["status"] == 3) {
								$classes = "hw_stat_3";
							}
							else if($result['status'] == 1){
								$classes = "hw_stat_1";
							}
							else if($result['status'] == 2){
								$classes = "hw_stat_2";
							}
							else if($result['status'] == 4){
								$classes = "hw_stat_1";
							}
							else{
								$classes = "hw_stat_1";
							}
							$hat .= "<div class='$classes'>Оценка: ".$result['mark']."</div>";
						}
					}
					if($result['change_status'] == 1){
						$change_status .= "<span class='circled'>1</span>";
					}

					if ($result["status"] == 3) {
						$classes = "hw_zadanie_kontr admin_hw_page";
						$classes_new = "hw_zadanie_kontr hw_zadanie_block_in";
						
					}
					else if($result['status'] == 1){
						$classes = "hw_zadanie_norm admin_hw_page";
						$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						
					}
					else if($result['status'] == 2){
						$classes = "hw_zadanie_prov admin_hw_page";
						$classes_new = "hw_zadanie_prov hw_zadanie_block_in";
						
					}
					else if($result['status'] == 4){
						$classes = "hw_zadanie_norm admin_hw_page";
						$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
						
					}
					else{
						$classes = "hw_zadanie_norm admin_hw_page";
						$classes_new = "hw_zadanie_norm hw_zadanie_block_in";
					}

					$lmore_text = "";
					$upd = "";
					$mark = "";
					if (isset($result['id'])) {
						$upd = sprintf("<input type='submit' class='hw_save' value='Отправить' name='hw_save_%s'>", $result['id']);
						$mark = sprintf("Оценка за ДЗ <input type='text' name='hw_mark' id='mark_%s' value='%s' oninput=\"track_max(%s,%s)\">
							<div id='err_mark_%s'></div>",
							$result['id'],$result['mark'],$result['id'],$result['mark_max'],$result['id']);
					}

					if($_SESSION['data']['level'] != 3){
						$hat .= "<h2>" . $result['fio'] . "</h2>";
						$lmore_text = "<textarea placeholder='текст комментария' name='pholder'>"
						.$result["comment"]."</textarea>
							<input type='hidden' name='id_hw' value='".$result['id_hw']."'><table>
							<tr>
								<td>
							<div class='hw_dorad_otp'><input type='checkbox' name='option5' value='a5'> отправить на доработку</div>
							<input type='hidden' name='id_u' value='".$result['id_u']."' id='".$row['id']."'>
							<input type='hidden' name='attached_file' value='' id='file_attached".$row_hw['id']."'>
								<td>
							$mark
							$upd
							<input type='button' class='hw_save' value='Отменить'></td>
							</tr>
						</table>
						";
					}
					else{
						$hat .= "<h2>" . $result['fio'] . "</h2>";
						$lmore_text = "<div class='comment'>".$result['comment']."</div>"
						.$result["comment"]."</textarea>
							<input type='hidden' name='id_hw' value='".$result['id_hw']."'><table>
							<tr>
								<td>
								<td>
							Оценка за ДЗ: <span class='mark'>".$result['mark']."</span><br>
							<input type='button' class='hw_save' value='Отменить'></td>
							</tr>
						</table>
						";
					}
					if($_SESSION['data']['level'] > 1){
						$files_module .= "<div class='by_stud'>
							<p>Отправленный ответ: </p>$links_s
						</div>
						<div class='by_teach'>
							<p>Проверенное: </p>$links_t
						</div>
						<div>$at_link</div>";
						$hw_hat .= "<table class='hw_table_admin_s'>
							<tr><td>".$result['fio']."</td><td>".$result['class']."</td><td><div class='dates'> ("
							. date( "d.m", define_week_start_and_end( "start", strtotime($result['ldate'] ))) 
							. "-" . date( "d.m", define_week_start_and_end( "end", strtotime($result['ldate'] )))
							. ")</div></td><td><h3>Тема: "
							. $result['ltitle']." </h3></td><td>{$result["date"]}</td><td><a href=\"javascript:onoff('div".$row['id']."');\">Оценить</a> $eld_hat</td>"
							. "</tr></table>";
					}

				$str .= "<form method='post' action=''>$hw_hat<div id='div".$row['id']."'  class='$classes_new' style='display:none;'>
					<!--<h4><a class='hw_uslovia' href='../lessons/lookhw.php?id=".$row['id']."'>Условия ДЗ</a></h4>-->".$result['text_hw'].
					"<table>
				<tr>
					<td>
						$files_module
					</td>
				</tr>
					</table>
					$lmore_text
					</div></form>";
					print($str);
			}
		}
					}
				}
			?>
				<!--<h3><a href="#">Загрузить бланк ДЗ</a></h3>-->
				
				
					 
				</div>
			<!-- КОНТЕНТ УЧЕНИКА -->	
			
			 	<div class="paginate"></div> 						
			</div>
		</div> 
	</div> 
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
						str += "<div class='simple_doc' onclick=\"attach_document('" + $("input[name = hw_id]").val() +
							"','" + data["name"] + "','" + escapeHtml(data["real_name"], 2) + "')\">" + escapeHtml(data["real_name"]) + "</div>"
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