<?php
	/*$mta = explode("/",$_SERVER['DOCUMENT_ROOT']);
    $mtel = $mta[count($mta) - 1]."";*/
	//print("<br>".$_SERVER['HTTP_HOST']."<br>");
	$mtel = $_SERVER['HTTP_HOST'];
	if(isset($_SESSION['data']) && $_SESSION['data']['level'] == 1){
		$sql_num_events = sprintf("SELECT COUNT(id) FROM os_events WHERE id_user='%s' AND read_status=0",$_SESSION['data']['id']);
		$res_num_events = $mysqli->query($sql_num_events);
		if ($res_num_events->num_rows != 0) {
			$row_num_events = $res_num_events->fetch_assoc();
			$events_count = $row_num_events['COUNT(id)'];
		}
		else{
			$events_count = 0;
		}
	}
	if(isset($_SESSION['data'])){
		
		if ($_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3) {
			$sql_num_hw = "SELECT COUNT(*) FROM os_homeworks 
							WHERE change_status=1 
							  AND check_status=3";

			$sql_num_mess = sprintf("SELECT COUNT(id) FROM os_chat_messages 
									  WHERE read_status=1 
									    AND id_user 
									 NOT IN ('%s') 
										AND id_chat 
										 IN (SELECT DISTINCT id_chat FROM os_chat_users 
										 			   WHERE id_user='%s' OR id_user='admin')",
					$_SESSION['data']['id'],$_SESSION['data']['id']);

	  $sql_num_mess_course = sprintf("SELECT COUNT(id) FROM os_chat_messages 
									  WHERE read_status=1 
									    AND id_user 
									 NOT IN ('%s') 
										AND id_chat 
										 IN (SELECT DISTINCT id_chat FROM os_chat_users 
										 			   WHERE id_user='admin')",
					$_SESSION['data']['id'],$_SESSION['data']['id']);
$sql_num_mess_in_course = $sql_num_mess;//sprintf($sql_num_mess_course, $_SESSION['data']['currentCourse']);

			$sql_num_hw_course = sprintf("SELECT COUNT(*) FROM os_homeworks 
										   WHERE change_status=1
										   	 AND check_status=3
										     AND id_hw
										      IN (SELECT id FROM os_lesson_homework
												   WHERE id_lesson 
													  IN (SELECT id FROM os_lessons
													       WHERE course = %%s ))");
$sql_num_hw_in_course = sprintf($sql_num_hw_course, $_SESSION['data']['currentCourse']);
		}
		if ($_SESSION['data']['level'] == 2) {
			$sql_num_hw = sprintf("SELECT COUNT(*) FROM os_homeworks 
									WHERE change_status=1 
									  AND check_status=3 
									  AND class 
									   IN (SELECT id_c FROM os_teacher_class 
									   		WHERE id_teacher='%s') 
									  AND subj 
									   IN (SELECT id_s FROM os_teacher_subj 
									   		WHERE id_teacher='%s')",
					$_SESSION['data']['id'],$_SESSION['data']['id']);

			$sql_num_mess = sprintf("SELECT COUNT(id) FROM os_chat_messages 
									  WHERE read_status=1 
									    AND id_user 
									 NOT IN ('%s') 
										AND id_chat 
										 IN (SELECT DISTINCT id_chat FROM os_chat_users 
										 			   WHERE id_user='%s')",
					$_SESSION['data']['id'],$_SESSION['data']['id']);
$sql_num_mess_course = sprintf("SELECT COUNT(id) FROM os_chat_messages 
								 WHERE read_status=1 
								   AND id_user 
								NOT IN ('%s') 
								   AND id_chat 
									IN (SELECT DISTINCT id_chat FROM os_chat_users 
									 			  WHERE id_user='%s'
									 			  	AND id_user 
									 			  	 IN (SELECT id_user FROM os_courses_students
									 			  	 	  WHERE id_course = %%s))",
				$_SESSION['data']['id'],$_SESSION['data']['id']);
$sql_num_mess_in_course = sprintf($sql_num_mess_course, $_SESSION['data']['currentCourse']);
			$sql_num_hw_course = sprintf("SELECT COUNT(*) FROM os_homeworks 
										   WHERE change_status=1 
										     AND check_status=3 
										     AND class 
										      IN (SELECT id_c FROM os_teacher_class 
										      	   WHERE id_teacher='%s') 
										     AND subj 
										      IN (SELECT id_s FROM os_teacher_subj 
										      	   WHERE id_teacher='%s')
										     AND id_hw
										      IN (SELECT id FROM os_lesson_homework
												   WHERE id_lesson 
													  IN (SELECT id FROM os_lessons
													       WHERE course = %%s ))",
					$_SESSION['data']['id'],$_SESSION['data']['id']);
$sql_num_hw_in_course = sprintf($sql_num_hw_course, $_SESSION['data']['currentCourse']);
		}
		if ($_SESSION['data']['level'] == 1) {
			$sql_num_hw = sprintf("SELECT COUNT(*) FROM os_homeworks 
												  WHERE change_status=1 
												    AND (check_status=4 
												     OR check_status=2) 
												    AND `from`='%s'
												    AND id_hw 
														    IN (SELECT id FROM os_lesson_homework
														    	 WHERE id_lesson 
														    	    IN (SELECT id FROM os_lessons
														    	    	 WHERE course = 0 ))",
				$_SESSION['data']['id']);

$sql_num_mess = sprintf("SELECT DISTINCT COUNT(id) FROM os_chat_messages 
								   WHERE read_status=1 
									 AND id_user NOT 
									  IN ('%s') 
									 AND id_chat 
									  IN (SELECT DISTINCT id_chat FROM os_chat_users 
													WHERE id_user='%s') 
													  AND id_chat 
													   IN (SELECT DISTINCT a.id_chat FROM os_chat_users 
													   					AS a 
													   				  JOIN os_chat_users 
													   				    AS b 
													   				    ON a.id_chat=b.id_chat 
													   				 WHERE a.id_user='%s' 
													   				   AND b.id_user='admin' 
													   				    OR b.id_user 
													   				    IN (SELECT id FROM os_users 
													   				    	 WHERE id 
													   				    	 	IN (SELECT id_manager FROM os_class_manager 
													   				    	 		 WHERE id='%s') 
													   				    	 		 	OR b.id_user 
													   				    	 		 	IN (SELECT DISTINCT id FROM os_users 
													   				    	 		 				  WHERE level=2
													   				    	 		 					AND id
													   				    	 		 					 IN (SELECT id_teacher FROM os_teacher_class
													   				    	 		 						  WHERE id_c = %s)
													   				    	 		 					AND id 
													   				    	 		 					 IN (SELECT id_teacher FROM os_teacher_subj
													   				    	 		 						  WHERE course = 0 
													   				    	 		 							AND id_s
													   				    	 		 							 IN (SELECT id_subject FROM os_student_subjects
													   				    	 		 								  WHERE id_student = %s)))))",
					$_SESSION['data']['id'],$_SESSION['data']['id'],$_SESSION['data']['id'],$_SESSION['data']['class'],
					$_SESSION['data']['class'],$_SESSION['data']['id']);

			$sql_num_hw_course = sprintf("SELECT COUNT(*) FROM os_homeworks 
														 WHERE change_status=1 
														   AND (check_status=4 
														    OR check_status=2) 
														   AND `from`='%s'
														   AND id_hw 
														    IN (SELECT id FROM os_lesson_homework
														    	 WHERE id_lesson 
														    	    IN (SELECT id FROM os_lessons
														    	    	 WHERE course = %%s ))",
					$_SESSION['data']['id']);
$sql_num_hw_in_course = sprintf($sql_num_hw_course, $_SESSION['data']['currentCourse']);

$sql_num_mess_course = sprintf("SELECT DISTINCT COUNT(id) FROM os_chat_messages 
									  	  WHERE read_status=1 
									 		AND id_user NOT 
									  		 IN ('%s') 
									 		AND id_chat 
									  		 IN (SELECT DISTINCT id_chat FROM os_chat_users 
														   WHERE id_user='%s') 
													  		 AND id_chat 
													   		  IN (SELECT DISTINCT a.id_chat FROM os_chat_users 
													   						   AS a 
													   				  		 JOIN os_chat_users 
													   				    	   AS b 
													   				    	   ON a.id_chat=b.id_chat 
													   				 		WHERE a.id_user='%s' 
													   				   		  AND b.id_user='admin' 
													   				    	   OR b.id_user 
													   				    	   IN (SELECT id FROM os_users 
													   				    	 		WHERE id 
													   				    	 		   IN (SELECT id_manager FROM os_class_manager 
													   				    	 		 		WHERE id='%s') 
													   				    	 		 		   OR b.id_user 
													   				    	 		 		   IN (SELECT DISTINCT id FROM os_users 
													   				    	 		 				  		 WHERE level=2
													   				    	 		 				  		   AND id 
													   				    	 		 				  		    IN (SELECT id_teacher FROM os_courses_teachers
													   				    	 		 				  				 WHERE id_course = %%s))))",
					$_SESSION['data']['id'],$_SESSION['data']['id'],$_SESSION['data']['id'],$_SESSION['data']['class']);
$sql_num_mess_in_course = sprintf($sql_num_mess_course, $_SESSION['data']['currentCourse']);

		}
		//print("<br>$sql_num_hw<br>");
		//print("<br>$sql_num_mess<br>");
		//print("<br>$sql_num_hw_in_course<br>");
		//print("<br>$sql_num_mess_in_course<br>");
		$res_num_hw = $mysqli->query($sql_num_hw);
		$row_num_hw = $res_num_hw->fetch_assoc();
		$res_num_mess = $mysqli->query($sql_num_mess);
		$row_num_mess = $res_num_mess->fetch_assoc();
		$res_num_hw_course = $mysqli->query($sql_num_hw_in_course);
		if($res_num_hw_course->num_rows != 0) {
			$row_num_hw_course = $res_num_hw_course->fetch_assoc();
		} else {
			$row_num_hw_course = array("COUNT(*)" => 0);
		}

		$res_num_mess_course = $mysqli->query($sql_num_mess_in_course);
		if($res_num_mess_course->num_rows != 0) {
			$row_num_mess_course = $res_num_mess_course->fetch_assoc();
		} else {
			$row_num_mess_course = array("COUNT(*)" => 0);
		}
	}
	$truth_token = false;
?>
<?php
	if($_COOKIE['lang'] == "ru" || !isset($_COOKIE['lang'])) {
		$getInSchool = "Поступить в школу";
		$aboutSchool = "О школе";
		$prices		 = "Цены на обучение";
		$howTeaching = "Как мы учим";
	} else {
		$getInSchool = "Вступити до школи";
		$aboutSchool = "Про школу";
		$prices		 = "Ціни на навчання";
		$howTeaching = "Як ми навчаємо";
	}
?>

		<?php if(!isset($_SESSION['data']['level']) || $_SESSION['data']['level'] == 0):?>
			
			
		  <div id="float-block-sticky-wrapper" class="sticky-wrapper" style="height: 90px;border-bottom: 1px solid;">
			<div class="block0"><div class="index_body_2 scrollflow -pop -opacity" id="float-block">

			<div class="index_body_2_fix">

				<a class="min_log" href="/index.php#index_top"><img src="../tpl_img/min_log.png"></a>

				<a class="postup1" href="/index.php#1">
					<?php echo $getInSchool; ?>
					</a>			

				<a class="menuse1" href="/index.php#2">
					<?php echo $aboutSchool; ?>
									</a>

				<a class="menuse1" href="/index.php#3">
					<?php echo $prices; ?>
									</a>

				<a class="menuse1" href="/index.php#4">
					<?php echo $howTeaching; ?>
									</a>

				 

			</div>

		</div>
		<!-- смена языка -->
		<div class="language" style="position: absolute;
    width: 80px;
    margin-top: -44px;
    margin-left: 925px;
    text-align: center;">
			<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
				<input type="submit" name="ru" value="">
				<input type="submit" name="ua" value="">
			</form>
		</div>
		<!-- смена языка --></div></div>
<?php endif;?>
            <!-- *МЕНЮ БЕЗ РЕГИСТРАЦИИ -->
            <?php
			if($_COOKIE['lang'] == "ru" || !isset($_COOKIE['lang'])) {
				$timetable = "Расписание";
				$diary = "Дневник";
				$calendar = "Календарь";
				$journal = "Журнал";
				$subjectJournal = "Предметный журнал";
				$tabel = "Табель";
				$homework = "Домашнее задание";
				$messages = "Сообщения";
				$profile = "Профиль";
				$personalInfo = "Личная информация";
				$sendersControl = "Управление рассылками";
				$schoolPayment = "Оплата школы";
				$userlist = "Списки пользователей";
				$bills = "Квитанции";
				$exit = "Выход";
				$createOnlineLesson = "Создать онлайн-урок";
				$addNew = "Добавить новость";
				$addFooter = "Добавить футер";
				$redactLanding = "Редактор лендинга";
				$redactClasses = "Редактор классов";
				$makeBackup = "Сделать бекап оценок";
				$makeBackuphw = "Сделать бекап ДЗ";
				$switchCourse = "Курс";
				$events = "События";
				$coursePayment = "Оплата курсов";
				$thematic = "Тематическое";
				/*$marking_will_stop = '<div class="grey-alert-student">
										Обратите внимание, что выполнить все ДЗ за первый семестр необходимо
										 не позднее 28.12.2017 до 23:59. <br>
										 После этого возможность выполнения заданий будет
										  ограничена. <br>
										  До окончания этого периода осталось %s д %s ч
									  </div>';*/
				$marking_will_stop = '<div class="grey-alert-student">
										30.12.2017 в разделе Табель
										 выведены семестровые оценки для учеников 1-10 классов, которые
										  расчитываются как среднее из тематических оценок по каждому предмету. Оценки в 1
										   классе не учитываются и несут стимулирующий характер.
									  </div>';
			} else {
				$timetable = "Розклад";
				$diary = "Щоденник";
				$calendar = "Календар";
				$journal = "Журнал";
				$subjectJournal = "Предметний журнал";
				$tabel = "Табель";
				$homework = "Домашнє завдання";
				$messages = "Повiдомлення";
				$profile = "Профіль";
				$personalInfo = "Персональна інформація";
				$sendersControl = "Керування розсилками";
				$schoolPayment = "Оплата школи";
				$userlist = "Списки користувачів";
				$bills = "Квитанції";
				$exit = "Вихід";
				$createOnlineLesson = "Створити онлайн-урок";
				$addNew = "Додати новину";
				$addFooter = "Додати футер";
				$redactLanding = "Редагувати лендінг";
				$redactClasses = "Редагувати класи";
				$makeBackup = "Зробити бекап оцiнок";
				$makeBackuphw = "Зробити бекап ДЗ";
				$switchCourse = "Курс";
				$events = "Події";
				$coursePayment = "Оплата курсiв";
				$thematic = "Тематичний";
				/*$marking_will_stop = '<div class="grey-alert-student">
										Зверніть увагу, що виконати всі ДЗ за перший семестр необхідно
										 не пізніше 28.12.2017 до 23:59. <br>
										 Після цього можливість виконання завдань буде
										  обмежена. <br> 
										  До закінчення цього періоду залишилося %s д %s г
									  </div>';*/
				$marking_will_stop = '<div class="grey-alert-student">
										30.12.2017 в розділі
										  Табель виведені семестрові оцінки для учнів 1-10 класів, які
										   розраховуються як середнє з тематичних оцінок з кожного предмета. Оцінки в 
										   1 класі не враховуються і мають стимулюючий характер.
									  </div>';
			}
		?>
<?php if(isset($_SESSION['data']) && (!isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0)): ?>

			<!-- *МЕНЮ АДМИНА -->
			<?php if( isset($_SESSION['data']) && $_SESSION['data']['level'] == 4):?>
			<div class="header">
		<div class="block0">
			<ul id="nav7">
			  <li class="no_bg"><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/index.php"><img src="/tpl_img/logo.png"></a></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#"><?php echo $timetable; ?></a> 
				  <ul>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/schedule/diary.php"><?php echo $diary; ?></a></li>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/schedule/calendar.php"><?php echo $calendar; ?></a></li>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/schedule/archieve.php"><?php echo $thematic; ?></a></li> 
				  </ul></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#"><?php echo $journal; ?></a>
				  <ul>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/jurnal/jurnal.php"><?php echo $subjectJournal; ?></a></li>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/jurnal/tabel.php"><?php echo $tabel; ?></a></li>
				</ul></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/homework"><?php echo $homework; ?> 
				<?php 
					if ($row_num_hw["COUNT(*)"]!=0) {
					 	printf("<span>%s</span>",$row_num_hw["COUNT(*)"]);
					}
				?>
				</a> </li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/chats"><?php echo $messages; ?> <?php
					//var_dump($row_num_mess["COUNT('id')"]);
					if($row_num_mess["COUNT(id)"] != 0){
						printf("<span id='chat_cnt'>%s</span>",$row_num_mess["COUNT(id)"]);
					}
				?></a> </li>
				<li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#"><?php echo $profile; ?></a> 
				<ul>
					<li><a href="/cabinet/index.php#tab_1"><?php echo $personalInfo; ?></a></li>
					<li><a href="/cabinet/index.php#tab_2"><?php echo $sendersControl; ?></a></li>
					<li><a href="/cabinet/index.php#tab_3"><?php echo $schoolPayment; ?> </a></li>
					<li><a href="/cabinet/index.php#tab_4"><?php echo $userlist; ?> </a></li>
					<li><a href="/cabinet/bills.php"><?php echo $bills; ?> </a></li>
					<li><a href="http://<?=$mtel;?>/cabinet/goout.php"><?php echo $exit; ?> </a></li>
				</ul></li>
				<li class="course-list-main">
					<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png">
					<a href="#"><?php echo $switchCourse; ?></a> 
				<ul class='osha-cources-switch'>
					<li class="selected">
						<a href="">Онлайн-школа</a>
					</li>
					<?php
						$sql_courses = "SELECT * FROM os_courses_meta WHERE is_active=1";
						$res_courses = $mysqli->query($sql_courses);
						if($res_courses->num_rows!=0) {
							while($row_courses = $res_courses->fetch_assoc()) {
								$counter = 0;
								$sql_course_messages = sprintf($sql_num_mess_course, $row_courses['id']);
								$res_course_messages = $mysqli->query($sql_course_messages);
								if($res_course_messages->num_rows != 0) {
									$row_course_messages = $res_course_messages->fetch_assoc();
									$counter += (int)$row_course_messages['COUNT(id)'];
									if($row_course_messages['COUNT(id)'] != 0)
										$truth_token = true;
								}
								$sql_course_hw = sprintf($sql_num_hw_course, $row_courses['id']);
								$res_course_hw = $mysqli->query($sql_course_hw);
								if($res_course_hw->num_rows != 0) {
									$row_course_hw = $res_course_hw->fetch_assoc();
									$counter += (int)$row_course_hw['COUNT(*)'];
									if($row_course_hw['COUNT(*)'] != 0)
										$truth_token = true;
								}
								$add_element = '';
								if($counter != 0) {
									$add_element = sprintf('<span>%s</span>', $counter);
								}

								printf("<li class='unselected'><a href='http://%s/switcher.php?change_course=1&course=%s'>%s %s</a></li>",
									$_SERVER['HTTP_HOST'],$row_courses['id'],$row_courses['course_name_' . $_COOKIE['lang']], $add_element);
							}
						}
					?>

				</ul>
				<?php
					if($truth_token == true) {
						print('<script type="text/javascript">
								$(".course-list-main>a").empty().append("<span>!</span>Курс");
							</script>');
					}
				?>
			</li>
			  <li><a class="pluss" href="#"> </a>
				<ul>
					<li><a href="http://<?=$mtel;?>/lessons/index.php"><?php echo $createOnlineLesson; ?> </a></li> 
					<li><a href="http://<?=$mtel;?>/news"><?php echo $addNew; ?> </a></li>
					<li><a href="http://<?=$mtel;?>/redactors/index.php?categ=8"><?php echo $addFooter; ?> </a></li>
					<li><a href="http://<?=$mtel;?>/redactors/index.php?categ=4"><?php echo $redactLanding; ?> </a></li>
					<li><a href="http://<?=$mtel;?>/redactors/classred.php"><?php echo $redactClasses; ?> </a></li>
					<li><a href="http://<?=$mtel;?>/excel"><?php echo $makeBackup; ?> </a></li>
					<li><a href="http://<?=$mtel;?>/tpl_php/jobs/zip_all_hw.php"><?php echo $makeBackuphw; ?></a></li>
					<li><a href="http://<?=$mtel;?>/courses">Courses</a></li>
					<li><a href="http://<?=$mtel;?>/themes">Themes</a></li>
					<li><a href="http://<?=$mtel;?>/cabinet/options.php">Опции</a></li>
					<li class="hoverer"> <a href="#"> Скрипты </a>
						<ul class="subhoverer-left">
							<li><a href="http://<?=$mtel;?>/tpl_php/jobs/re_create_hworks.php">Перегенерировать ДЗ/журнал</a></li>
							<!--<li><a href="http://<?=$mtel;?>/tpl_php/jobs/regenerate_journal.php">Перегенерировать Журнал</a></li>-->
							<li><a href="http://<?=$mtel;?>/tpl_php/jobs/rebind_subjects.php">Перепривязать Предметы</a></li>
							<li><a href="http://<?=$mtel;?>/tpl_php/jobs/regenerate_test_in_two_langs.php">Исправить тесты - язык</a></li>
							<li><a href="http://<?=$mtel;?>/tpl_php/jobs/select_issue.php" 
								title="без учителя/больше 12/без даты/без темы">Они вышли ночью в поле с конем</a></li>
							<li><a href="http://<?=$mtel;?>/cabinet/auth_twin.php" 
								title="Ай загуляю, ай загуля-я-яю">Авторизация по id</a></li>
							<li><a href="http://<?=$mtel;?>/tpl_php/jobs/create_hworks_courses.php" 
								title="Каждой твари по паре, а каждому ученику по домашке">Сгенерировать дз в курсы</a></li>
							<li><a href="http://<?=$mtel;?>/tpl_php/jobs/tabel_common_script.php" 
								title="А не сделать мне табель, а не сделать ли?">Табель/нули и 2ки</a></li>
						</ul>
					</li>
				</ul></li>
			</ul>
			<!-- смена языка -->
		<div class="language">
			<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
				<input type="submit" name="ru" value="">
				<input type="submit" name="ua" value="">
			</form>
		</div>
		<!-- смена языка -->
				
			<?php endif; ?>
			<!-- МЕНЮ АДМИНА* -->
			
			<!-- МЕНЮ ПОЛЬЗОВАТЕЛЯ -->
		        <?php if( isset($_SESSION['data']) &&  ($_SESSION['data']['level'] == 1 || 
		        									    $_SESSION['data']['level'] == 2 || 
		        									    $_SESSION['data']['level'] == 3)):?>
	<div class="header">
		<div class="block0">
			<ul id="nav7">
			  <li class="no_bg"><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/index.php"><img src="/tpl_img/logo.png"></a></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#"><?php echo $timetable ; ?></a> 
				  <ul>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/schedule/diary.php"><?php echo $diary ; ?></a></li>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/schedule/calendar.php"><?php echo $calendar ; ?></a></li>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/schedule/archieve.php"><?php echo $thematic ; ?></a></li> 
				  </ul></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#"><?php echo $journal ; ?></a>
				  <ul>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/jurnal/jurnal.php"><?php echo $subjectJournal ; ?></a></li>
					<?php //if($_SESSION['data']['level'] > 1): ?>
						<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/jurnal/tabel.php"><?php echo $tabel ; ?></a></li>
					<?php //endif; ?>
				  </ul></li>
			  <li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/homework"><?php echo $homework ; ?> 
				<?php 
					if ($row_num_hw["COUNT(*)"]!=0) {
					 	printf("<span>%s</span>",$row_num_hw["COUNT(*)"]);
					}
				?>
				</a> </li>
				<?php if($_SESSION['data']['level'] == 1): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/events"><?php echo $events ; ?>
					<?php if($events_count !=0): ?>
						<span><?php print($events_count); ?></span>
					<?php endif; ?> </a></li>
				<?php endif; ?>
			  <li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/chats/index.php?id=<? echo $_SESSION['data']['chat_id'];?>"><?php echo $messages ; ?> <?php
					//var_dump($row_num_mess["COUNT('id')"]);
					if($row_num_mess["COUNT(id)"] != 0){
						printf("<span id='chat_cnt'>%s</span>",$row_num_mess["COUNT(id)"]);
					}
				?></a> </li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#"><?php echo $profile ; ?></a> 
				<ul>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_1"><?php echo $personalInfo ; ?></a></li>
					<?php if($_SESSION['data']['level'] == 3): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_4"><?php echo $userlist ; ?></a></li>
					<?php endif; ?>
					<?php if($_SESSION['data']['level'] == 1): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_2"><?php echo $sendersControl ; ?></a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_3"><?php echo $schoolPayment ; ?></a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_4"><?php echo $coursePayment ; ?></a></li>
					<?php endif; ?>
					<li><a href="http://<?=$mtel;?>/cabinet/goout.php"><?php echo $exit ; ?></a></li>
				</ul></li> 
				<li class="course-list-main">
					<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png">
					<a href="#"><?php echo $switchCourse; ?></a> 
				<div>
				<ul>
					<li class="selected">
						<a href="">
						Онлайн-школа
						</a>

					</li>
					<?php
						$sql_courses = "SELECT * FROM os_courses_meta WHERE is_active=1";
						$res_courses = $mysqli->query($sql_courses);
						if($res_courses->num_rows!=0) {
							while($row_courses = $res_courses->fetch_assoc()) {
								$counter = 0;
								$sql_course_messages = sprintf($sql_num_mess_course, $row_courses['id']);

								$res_course_messages = $mysqli->query($sql_course_messages);
								if($res_course_messages->num_rows != 0) {
									$row_course_messages = $res_course_messages->fetch_assoc();
									$counter += (int)$row_course_messages['COUNT(id)'];
									if($row_course_messages['COUNT(id)'] != 0)
										$truth_token = true;
								}
								$sql_course_hw = sprintf($sql_num_hw_course, $row_courses['id']);
								$res_course_hw = $mysqli->query($sql_course_hw);
								if($res_course_hw->num_rows != 0) {
									$row_course_hw = $res_course_hw->fetch_assoc();
									$counter += (int)$row_course_hw['COUNT(*)'];
									if($row_course_hw['COUNT(*)'] != 0)
										$truth_token = true;
								}
								$add_element = '';
								if($counter != 0) {
									$add_element = sprintf('<span>%s</span>', $counter);
								}

								printf("<li class='unselected'>
											<a href='http://%s/switcher.php?change_course=1&course=%s'>%s %s</a>
										</li>",
									$_SERVER['HTTP_HOST'],$row_courses['id'],$row_courses['course_name_' . $_COOKIE['lang']], $add_element);
							}
						}
					?>
				</ul></div></li>
				<?php
					if($truth_token == true) {
						print('<script type="text/javascript">
								$(".course-list-main>a").empty().append("<span>!</span>Курс");
							</script>');
					}
				?>
			</ul>
			<!-- смена языка -->
		<div class="language">
			<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
				<input type="submit" name="ru" value="">
				<input type="submit" name="ua" value="">
			</form>
		</div>
		<!-- смена языка -->
		        <?php endif; ?>
			<!-- МЕНЮ ПОЛЬЗОВАТЕЛЯ -->
			
		
		</div>
	</div> 
	<?php
		/*if($_SESSION['data']['level'] == 1 && $_SESSION['data']['edu_type'] == 1) {
			$currentTimestamp = time();
			$goalTimestamp	  = strtotime("23:59 28.12.2017");
			$resultTimestamp  = $goalTimestamp - $currentTimestamp;
			$days = floor($resultTimestamp / (24 * 60 * 60));
			$hours = floor(($resultTimestamp - ($days * (24 * 60 * 60))) / (60*60));
			printf($marking_will_stop, $days, $hours);
		}*/
	?>
	<?php else: ?>
		<?php if(isset($_SESSION['data'])): ?>
	<div class="header_course-container">
		<ul>
			<li class="header_course-logo"><img src="/tpl_img/course_logo.png"></li>
			<li class="header_course-menu_ico">
				<a>
					<img style="margin-right:5px;" src="/tpl_img/course_calendar.png" onclick="location.href = 'http://<?php print($_SERVER['HTTP_HOST']) ?>/schedule/courseDiary.php'">
				</a>

			</li>
			<li class="header_course-menu_ico">
				<a>
					<img src="/tpl_img/course_dz.png" 
					onclick="location.href = 'http://<?php print($_SERVER['HTTP_HOST']) ?>/homework'"><!--<span class="header_course-notification">7</span>-->
				</a>
				<?php 
					if ($row_num_hw_course["COUNT(*)"]!=0) {
					 	printf("<div class='course-sub-counter'>%s</div>",$row_num_hw_course["COUNT(*)"]);
					}
				?>
			</li>
			<?php if($_SESSION['data']['level'] == 1): ?>
			<li class="header_course-menu_ico">
					<img src="/tpl_img/course_events.png" 
					onclick="location.href = 'http://<?php print($_SERVER['HTTP_HOST']) ?>/events'">
					<?php if($events_count !=0): ?>
						<div class='course-sub-counter'>
							<?php print($events_count); ?>
						</div>
					<?php endif; ?>
			</li>
			<?php endif; ?>
			<li class="header_course-menu_ico">
				<img src="/tpl_img/course_messages.png"  
				onclick="location.href = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/chats/index.php?id=<? echo $_SESSION['data']['chat_id'];?>'">
				<?php
					if($row_num_mess_course["COUNT(id)"] != 0){
						printf("<div class='course-sub-counter'>%s</span>",$row_num_mess_course["COUNT(id)"]);
					}
				?>
			</li>
			<li id="header_course-selector"><span>Курс</span>
			<div id="header_toggle_menu-show" class="header_toggle_menu">
				<ul>
					<li class='course_menu_passive'>
						<a href='http://online-shkola.com.ua/switcher.php?change_course=1&course=0'>Онлайн-школа</a> 
						<?php 
							if($row_num_mess['COUNT(id)'] != 0 || $row_num_hw['COUNT(*)'] != 0) {
								printf('<div class="course-sub-counter">%s</div>', 
									((int)$row_num_mess['COUNT(id)'] + (int)$row_num_hw['COUNT(*)']));
								$truth_token = true;
							}
						?>
					</li>
				<?php
					$sql_courses = "SELECT * FROM os_courses_meta WHERE is_active=1";
					$res_courses = $mysqli->query($sql_courses);
					if($res_courses->num_rows!=0) {
						while($row_courses = $res_courses->fetch_assoc()) {
							if($row_courses['id'] == $_SESSION['data']['currentCourse']) $class = "course_menu_active";
							else $class = "course_menu_passive";
							$counter = 0;
							if($_SESSION['data']['currentCourse'] != $row_courses['id']) {
								$sql_course_messages = sprintf($sql_num_mess_course, $row_courses['id']);
								$res_course_messages = $mysqli->query($sql_course_messages);
								if($res_course_messages->num_rows != 0) {
									$row_course_messages = $res_course_messages->fetch_assoc();
									$counter += (int)$row_course_messages['COUNT(id)'];
									if($row_course_messages['COUNT(id)'] != 0)
										$truth_token = true;
								}

								$sql_course_hw = sprintf($sql_num_hw_course, $row_courses['id']);
								$res_course_hw = $mysqli->query($sql_course_hw);
								if($res_course_hw->num_rows != 0) {
									$row_course_hw = $res_course_hw->fetch_assoc();
									$counter += (int)$row_course_hw['COUNT(*)'];
									if($row_course_hw['COUNT(*)'] != 0)
										$truth_token = true;
								}
							}
							$add_element = '';
							if($counter != 0) {
								$add_element = sprintf('<div class="course-sub-counter">%s</div>', $counter);
							}

							printf("<li class='$class'>
										<a href='http://%s/switcher.php?change_course=1&course=%s'>%s</a>
										%s
									</li>",
								$_SERVER['HTTP_HOST'],$row_courses['id'],$row_courses['course_name_' . $_COOKIE['lang']], $add_element);
						}
					}
				?>
				</ul>
			</div>
			<?php
				if($truth_token == true) {
					print('<script type="text/javascript">
							$("#header_course-selector").append("<div class=\"course-sub-counter\">!</div>");
						</script>');
				}
			?>
			</li>
			<li id="header_course-profile">
				<span onclick="location.href = 'http://<?php print($_SERVER['HTTP_HOST']); ?>/cabinet/index.php#tab_1'"><?php echo $profile ; ?></span>
				<?php
					$avatar = 'http://www.freeiconspng.com/uploads/user-icon-png-person-user-profile-icon-20.png';
					if(isset($_SESSION['data']) && $_SESSION['data']['avatar'] != '') {
						$avatar = sprintf("http://%s/upload/avatars/%s", $_SERVER['HTTP_HOST'], $_SESSION['data']['avatar']);
					}
				?>
				<img id="header_course-profile-first_pic" src="<?php echo $avatar; ?>">
				<img id="header_course-profile-second_pic" src="/tpl_img/sort-down.png">
				<div class="header_toggle_menu">
					<ul>
							<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_1"><?php echo $personalInfo ; ?></a></li>
						<?php if($_SESSION['data']['level'] == 1 || $_SESSION['data']['level'] == 4): ?>
							<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_2"><?php echo $sendersControl ; ?></a></li>
							<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_3"><?php echo $schoolPayment ; ?></a></li>
						<?php endif; ?>
						<?php if($_SESSION['data']['level'] == 1): ?>
							<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_4"><?php echo $coursePayment ; ?></a></li>
						<?php elseif($_SESSION['data']['level'] == 4): ?>
							<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_4"><?php echo $userlist ; ?></a></li>
							<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/bills.php"><?php echo $bills ; ?></a></li>
							<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_5"><?php echo $coursePayment ; ?></a></li>
						<?php endif; ?>
							<li><a href="http://<?=$mtel;?>/cabinet/goout.php"><?php echo $exit ; ?></a></li>
					</ul>
				</div>
				</li>
			<li id="language-selector">
			<form method="post" action="">
				<input type="submit" name="ru" value="">
				<input type="submit" name="ua" value="">
			</form>
		</li>
		</ul>
	</div>
	<script>
		document.getElementById("header_course-selector").onclick = function(){
console.log(document.getElementsByClassName("header_course-selector")[0]);
}
	</script>
	
<?php endif; ?>
	<?php endif; ?>

<input type="hidden" name="level_chat" id="level_chat" value="<?= isset($_SESSION['data']) ? $_SESSION['data']['level'] : '';?>">
<?php if(!isset($_SESSION['data'])): ?>	
<div>
<a class="block" onClick="open_block()">
<div <? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		style="background: url(../tpl_img/tp_btn.png);"
		<? else: ?>
		style="background: url(../tpl_img/tp_btn2.png);"
		<? endif; ?> class="tp_btn"></div>
</a>
	<div id="contents_tp" style="display:none" onClick="close_block()">
		<form id="form-3" class="tp_forma" method="post" action="<?=$_SERVER['REQUEST_URI']?>" name="tp_forma">
			<h3><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Техподдержка
					<? else: ?>
					Техпідтримка
					<? endif; ?></h3>
			<p><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Ваше обращение будет обработано<br>
			в течение 24 часов
			<?php
				$yName = "Ваше имя";
				$yMail = "Электронный адрес";
			?>
					<? else: ?>
					Ваше звернення буде опрацьовано<br>
			протягом 24 годин
			<?php
				$yName = "Ваше ім'я";
				$yMail = "Електронна адреса";
			?>
					<? endif; ?></p> 
			<input  type="text" name="name" placeholder="<?php echo $yName; ?>"><br>
			<input  type="text" name="email" placeholder="<?php echo $yMail; ?>"><br>
			<textarea title=""  name="text_message" 
				placeholder="<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>Введите текст вашего сообщения
				<? else: ?>Введіть текст вашого повідомлення<? endif; ?>"></textarea>
			<br>
			<?php
				if(isset($_SESSION['capcha-error']) && !empty($_SESSION['capcha-error'])) {
					printf("<p style='color:red'>%s</p>", $_SESSION['capcha-error']);
					unset($_SESSION['capcha-error']);
				}
			?>
			<input type="hidden" name="g-recaptcha-response" value="">
			<input type="hidden" value="1" name="mail">
			<input class="button_tp" type="button" value="Отправить" onclick="captcha_trigger('form-3')">
		</form>
	</div>
</div> 
<?php endif; ?>

<?php if(isset($_SESSION['data']) && $_SESSION['data']['level'] != 4 && !preg_match('~chats~', $_SERVER['REQUEST_URI'])): ?>
<div>
<a class="block" onClick="open_block()">
<div <? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		style="background: url(../tpl_img/tp_btn.png);"
		<? else: ?>
		style="background: url(../tpl_img/tp_btn2.png);"
		<? endif; ?> class="tp_btn"></div>
</a>
<div id="contents_tp" style="display:none" onClick="close_block()">
<form class="tp_forma" method="post" action="<?=$_SERVER['REQUEST_URI']?>" name="tp_forma" >
<h3>
	<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Техподдержка
		<? else: ?>
		Техпідтримка
		<? endif; ?>
		</h3>
<p>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Ваше обращение будет обработано<br>
в течение 24 часов
		<? else: ?>
		Ваше звернення буде опрацьовано<br>
протягом 24 годин
		<? endif; ?>
		</p> 
<ul id="ac_field_chat" style="overflow-y:auto; height:180px;">
</ul>
<input type='hidden' name="ac_from" value="<?=$_SESSION['data']['id']?>">
<input type='hidden' name="ac_to" value="admin">
<input type='hidden' name="ac_chat_id" id="chat_id" value="<?=$_SESSION['data']['chat_id']?>">

<textarea title="" name="ac_text_chat" class="message" placeholder="<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>Введите текст вашего сообщения<? else: ?>Введіть текст вашого повідомлення<? endif; ?>"></textarea>
<input class="button_tp" type="button" onclick="send()" name="mail_to_admin" value="Отправить">
</form>
</div>
</div>
<script type="text/javascript">  
	//request(d);
	getMessages($("input[name = ac_chat_id]").val());
	var pre_interv = setInterval(getMessages,120000,$("input[name = ac_chat_id]").val());
	$("textarea[name = ac_text_chat]").on('focus',function(){
		//alert(a);
	getMessages($("input[name = ac_chat_id]").val());
	var intervall = setInterval(getMessages,8000,$("input[name = ac_chat_id]").val());
		$(this).on('blur',function(){
		clearInterval(intervall);
					
		})
	})
</script>
<?php endif; ?>
<?php 
	if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
		$dontShow = "Больше<br>не показывать";
	} else {
		$dontShow = "Більше<br>не показувати";
	}
?>
<?php
	printf("<input type='hidden' name='language-common' value='%s'>", $_COOKIE['lang']);
?>
<div id="blue-alert" class="alert-window no-display">
	<div class="close" onclick="close_alertation('blue-alert')">✖</div>
	<div class="alert-text"></div>
</div>
<div id="grey-alert" class="alert-window no-display">
	<div class="close" onclick="close_alertation('grey-alert')">✖</div>
	<div class="alert-text"></div>
</div>
<div id="green-alert" class="alert-window no-display">
	<div class="close" onclick="close_alertation('green-alert')">✖</div>
	<div class="alert-text"></div>
</div>
<div id="red-alert" class="alert-window no-display"></div>