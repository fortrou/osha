<?php 
	session_start();

	//if ( !isset($_GET['id'])) header("Location: ../index.php");
	
	require '../tpl_php/autoload.php';
	$db = Database::getInstance();
		$mysqli = $db->getConnection();
	if(isset($_POST['relocate'])) {
		$sql = sprintf("SELECT * FROM os_lessons WHERE id=%s", $_GET['id']);
		$res = $mysqli->query($sql);
		if($res->num_rows == 1) {
			$row = $res->fetch_assoc();
			$sql_copy = sprintf("INSERT INTO os_lessons (`subject`,`class`,`date_ua`,`date_ru`,`title_ua`,`title_ru`,`teacher_ua`,`teacher_ru`,`video_ua`,`video_ru`,`links_ua`,`links_ru`,`summary_ua`,`summary_ru`,`same_lang`,`lock_status`,`is_control`,`course`,`theme`,`lesson_year`) SELECT 
				`subject`,`class`,'%s','%s',`title_ua`,`title_ru`,%s,%s,`video_ua`,`video_ru`,`links_ua`,`links_ru`,`summary_ua`,`summary_ru`,`same_lang`,`lock_status`,`is_control`,`course`,`theme`,%s FROM os_lessons WHERE id = %s", 
					$_POST['date_ua'], $_POST['date_ru'], $_POST['teacher_ua'], $_POST['teacher_ru'], get_currentYearNum(), $row['id']);
			print("<br>$sql_copy<br>");
			$res_copy = $mysqli->query($sql_copy);
			if($mysqli->affected_rows > 0) {
				$sql_lastId = "SELECT last_insert_id() AS id FROM os_lessons";
				$res_lastId = $mysqli->query($sql_lastId);
				if($res_lastId->num_rows != 0) {
					$row_lastId = $res_lastId->fetch_assoc();
					$new_date = Date("Y-m-d", strtotime($_POST['date_ua']) + 3600 * 24 * 7);
					$sql_dublicate_classes = sprintf("INSERT INTO os_lesson_classes (id_lesson,id_class) 
														   SELECT %s, id_class 
														     FROM os_lesson_classes 
														    WHERE id_lesson = %s", $row_lastId['id'], $row['id']);
					$res_dublicate_classes = $mysqli->query($sql_dublicate_classes);
					print("<br>$res_dublicate_classes<br>");
					
					// TEST dublication block
					
					$sql_dublicate_test_issue = sprintf("SELECT * FROM os_tests WHERE less_id = %s", $row['id']);
					$res_dublicate_test_issue = $mysqli->query($sql_dublicate_test_issue);
					if($res_dublicate_test_issue->num_rows != 0) {
						while($row_dublicate_test_issue = $res_dublicate_test_issue->fetch_assoc()) {
							$sql_dublicate = sprintf("INSERT INTO os_tests(name, type, lang, less_id) 
														   SELECT name, type, lang, %s 
														     FROM os_tests 
														    WHERE id = %s",
														    $row_lastId['id'], $row_dublicate_test_issue['id']);
							$res_dublicate = $mysqli->query($sql_dublicate);
							if($mysqli->affected_rows != 0) {
								$sql_lastTest = "SELECT last_insert_id() AS id FROM os_tests";
								$res_lastTest = $mysqli->query($sql_lastTest);
								if($res_lastTest->num_rows != 0) {
									$row_lastTest = $res_lastTest->fetch_assoc();
									// Dublicate lesson-test issue
									$sql_dublicate = sprintf("INSERT INTO os_lesson_test (id_lesson,id_test,type,lang) 
																   SELECT %s, %s,type,lang 
															   		 FROM os_lesson_test 
															  		WHERE id_lesson = %s AND id_test = %s", 
															  		$row_lastId['id'], $row_lastTest['id'], $row['id'], $row_dublicate_test_issue['id']);
									$res_dublicate = $mysqli->query($sql_dublicate);
									
									// Dublicate lesson-test issue
									$sql_dublicate_quest_issues = sprintf("SELECT * FROM os_test_quest 
																				   WHERE id_test = %s", 
																				   $row_dublicate_test_issue['id']);
									$res_dublicate_quest_issues = $mysqli->query($sql_dublicate_quest_issues);
									if($res_dublicate_quest_issues->num_rows != 0) {
										while($row_dublicate_quest_issues = $res_dublicate_quest_issues->fetch_assoc()) {
											$sql_dublicate = sprintf("INSERT INTO os_test_quest(name, type, cost, doc, full_desc, id_test)
																		   SELECT name, type, cost, doc, full_desc, %s
																		     FROM os_test_quest 
																		    WHERE id_q = %s", $row_lastTest['id'], $row_dublicate_quest_issues['id_q']);
											$res_dublicate = $mysqli->query($sql_dublicate);
											if($mysqli->affected_rows != 0) {
												$sql_lastQuest = "SELECT last_insert_id() AS id FROM os_test_quest";
												$res_lastQuest = $mysqli->query($sql_lastQuest);
												if($res_lastQuest->num_rows != 0){
													$row_lastQuest = $res_lastQuest->fetch_assoc();
													
													// Выборка для типов вопросов 1-3
													if(in_array($row_dublicate_quest_issues['type'], array(1,2,3))) {
														print("<br>ВОПРОС 1 - 3 ТИПА<br>");
														$sql_dublicate_answers = sprintf("SELECT * FROM os_test_answs
																								  WHERE id_quest = %s", $row_dublicate_quest_issues['id_q']);
														$res_dublicate_answers = $mysqli->query($sql_dublicate_answers);
														if($res_dublicate_answers->num_rows != 0) {
															while($row_dublicate_answers = $res_dublicate_answers->fetch_assoc()) {
																$sql_dublicate = sprintf("INSERT INTO os_test_answs(answer, correct, id_quest)
																							   SELECT answer, correct, %s 
																							     FROM os_test_answs
																							    WHERE id_a = %s", 
																								$row_lastQuest['id'], $row_dublicate_answers['id_a']);
																print("<br> ОТВЕТ - ------ - $sql_dublicate<br>");
																$res_dublicate = $mysqli->query($sql_dublicate);
																var_dump($mysqli->affected_rows);
															}
														}
													}

													// Выборка для типа вопроса 4
													if($row_dublicate_quest_issues['type'] == 4) {
														print("<br>ВОПРОС НА СООТВЕТСТВИЯ<br>");
														
														// ответы
														$sql_dublicate_answers = sprintf("SELECT * FROM os_test_answs
																								  WHERE id_quest = %s", $row_dublicate_quest_issues['id_q']);
														$res_dublicate_answers = $mysqli->query($sql_dublicate_answers);
														if($res_dublicate_answers->num_rows != 0) {
															while($row_dublicate_answers = $res_dublicate_answers->fetch_assoc()) {
																$sql_dublicate = sprintf("INSERT INTO os_test_answs(answer, correct, id_quest)
																							   SELECT answer, correct, %s 
																							     FROM os_test_answs
																							    WHERE id_a = %s", 
																								$row_lastQuest['id'], $row_dublicate_answers['id_a']);
																print("<br> ОТВЕТ - ------ - $sql_dublicate<br>");
																$res_dublicate = $mysqli->query($sql_dublicate);
																var_dump($mysqli->affected_rows);
															}
														}

														// соответствия
														$sql_dublicate_answers = sprintf("SELECT * FROM os_test_matches
																								  WHERE id_quest = %s", $row_dublicate_quest_issues['id_q']);
														$res_dublicate_answers = $mysqli->query($sql_dublicate_answers);
														if($res_dublicate_answers->num_rows != 0) {
															while($row_dublicate_answers = $res_dublicate_answers->fetch_assoc()) {
																$sql_dublicate = sprintf("INSERT INTO os_test_matches(match_text, num, id_quest)
																							   SELECT match_text, num, %s 
																							     FROM os_test_matches
																							    WHERE id_ma = %s", 
																								$row_lastQuest['id'], $row_dublicate_answers['id_ma']);
																print("<br> СООТВЕТСТВИЕ - ------ - $sql_dublicate<br>");
																$res_dublicate = $mysqli->query($sql_dublicate);
																var_dump($mysqli->affected_rows);
															}
														}
													}

													if($row_dublicate_quest_issues['type'] == 5) {
														print("<br>ВОПРОС С КОРОТКИМ ОТВЕТОМ<br>");
														$sql_dublicate_answers = sprintf("SELECT * FROM os_test_short_answ
																								  WHERE id_quest = %s", $row_dublicate_quest_issues['id_q']);
														$res_dublicate_answers = $mysqli->query($sql_dublicate_answers);
														if($res_dublicate_answers->num_rows != 0) {
															while($row_dublicate_answers = $res_dublicate_answers->fetch_assoc()) {
																$sql_dublicate = sprintf("INSERT INTO os_test_short_answ(pre, answer, post, id_quest)
																							   SELECT pre, answer, post, %s 
																							     FROM os_test_short_answ
																							    WHERE id = %s", 
																								$row_lastQuest['id'], $row_dublicate_answers['id']);
																print("<br> - ------ - $sql_dublicate<br>");
																$res_dublicate = $mysqli->query($sql_dublicate);
																var_dump($mysqli->affected_rows);
															}
														}	
													}
												}
											}
										}
									}
								}
							}
						}
					}

					// TEST dublication block

					/*$sql_dublicate_tests = sprintf("INSERT INTO os_lesson_test (id_lesson,id_test,type,lang) 
														 SELECT %s, id_test,type,lang 
														   FROM os_lesson_test 
														  WHERE id_lesson = %s", $row_lastId['id'], $row['id']);
					$res_dublicate_tests = $mysqli->query($sql_dublicate_tests);*/
					print("<br>$res_dublicate_tests<br>");
					$sql_dublicate_hworks = sprintf("INSERT INTO os_lesson_homework (id_lesson,hw_text_ru,hw_text_ua,mark) 
														  SELECT %s,hw_text_ru,hw_text_ua,mark 
														    FROM os_lesson_homework 
														   WHERE id_lesson = %s", $row_lastId['id'], $row['id']);
					$res_dublicate_hworks = $mysqli->query($sql_dublicate_hworks);
					print("<br>$sql_dublicate_hworks<br>");

					$sql_users = sprintf("SELECT * FROM os_users WHERE level = 1 AND class IN (SELECT id_class FROM os_lesson_classes WHERE id_lesson='%s')",
										$row_lastId['id']);
					$res_users = $mysqli->query($sql_users);
					while($row_users = $res_users->fetch_assoc()){
						$date = explode('T',$_POST['date_ua']);
						//var_dump($date);
						$sql_id = sprintf("SELECT id FROM os_lesson_homework WHERE id_lesson='%s'",$row_lastId['id']);
						$res_id = $mysqli->query($sql_id);
						$row_id = $res_id->fetch_assoc();
						if ($row_lesson["is_control"] == 1) {
							$status = "'3'";
						}
						else{
							$status = "'1'";
						}
						$sql_new = sprintf("INSERT INTO os_homeworks(date_h, `from`, subj, class, id_hw, status, last_hw_date) 
												 VALUES ('%s', %s, %s, %s, %s, $status, '$new_date')",
							$date[0],$row_users['id'],$row['subject'],$row_users['class'],$row_id['id']);
						print("<br>$sql_new<br>");
						$res_new = $mysqli->query($sql_new);
						if($row['control'] == 0){
							$sql_j = sprintf("INSERT INTO os_journal(id_s,id_l,date_ru,date_ua,status,id_subj, course, theme) VALUES(%s,%s,'%s','%s',1,%s,%s,%s)",
								$row_users['id'],$row_lastId['id'],$_POST['date_ru'],$_POST['date_ua'],$row['subject'],$row['course'],$row['theme']);
						}
						else{
							$sql_j = sprintf("INSERT INTO os_journal(id_s,id_l,date_ru,date_ua,status,id_subj, course, theme) VALUES(%s,%s,'%s','%s',3,%s,%s,%s)",
								$row_users['id'],$row_lastId['id'],$_POST['date_ru'],$_POST['date_ua'],$row['subject'],$row['course'],$row['theme']);
						}
						print("<br>$sql_j<br>");
					}
					$res_j = $mysqli->query($sql_j);
					//header("Location: ". $_SERVER['REQUEST_URI']);
				} else {
					die();
				}
			}

		}
	}
	if ( $_POST['send'] ) 
	{

		
		try 
		{
		$sql = "SELECT * FROM os_users WHERE id IN(SELECT id_user FROM os_user_mails WHERE id_mail='1' AND yep='1')";
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		$sql_l = "SELECT * FROM os_lessons WHERE id='".$_GET['id']."'";
		//print("<br>$sql_l<br>");
		$res_l = $mysqli->query($sql_l);
		$row_l = $res_l->fetch_assoc();
		$sql_n = "SELECT * FROM os_mail_types WHERE id='1'";
		//print("<br>$sql_n<br>");
		$res_n = $mysqli->query($sql_n);
		$row_n = $res_n->fetch_assoc(); 
		while ($row = $res->fetch_assoc()) {
			$mail_text = sprintf($row_n['template'],$row_l['title_ru'],$_POST['date_ua'],$_POST['date_ru']);
			/*var_dump($mail);
			print("<br>");
			var_dump($row['email']);*/
			$headers= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=utf-8\r\n";
					$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n".
			    'X-Mailer: PHP/' . phpversion();
			mail($row['email'],"Изменение даты онлайн-урока",$mail_text,$headers);
			if (trim($row['p_email'])!="") {
				mail($row['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$mail_text,$headers);
			}
			// print_r(error_get_last());
		}
			Lesson::Update($_GET['id'] , $_POST);
			//header("Location:".$_SERVER['REQUEST_URI']);
		} 
		catch (Exception $e) 
		{
			print($e->getMessage());
		}
		header("Location: /schedule/calendar.php");
	}

	try 
	{
		$lesson = Lesson::load($_GET['id']);
	} 
	catch (Exception $e) 
	{
		print($e->getMessage());
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Перенос времени онлайн-урока</title>

	<?php include '../tpl_blocks/head.php'; ?>

</head>
<body>
	
	<?php include '../tpl_blocks/header.php'; ?>
<div class="content">
	<div class="block0">
		<form action="" method="post">
			<p>Дата Української версії</p>
			<input type="datetime-local" name="date_ua" value="<?=strftime("%Y-%m-%dT%H:%M" , strtotime($lesson->getDate('ua')) )?>">
			<p>Дата Русской версии</p>
			<input type="datetime-local" name="date_ru" value="<?=strftime("%Y-%m-%dT%H:%M" , strtotime($lesson->getDate('ru')) )?>">
			<input type="submit" value="Перенести" name="send">
		</form>
		<hr>
		<h1>Раздел переноса урока</h1>
		<hr />
		<h3>Раздел тестов</h3>
			<p> -- Тренировочный тест</p>
			<?php
			$sql = sprintf("SELECT * FROM os_lesson_test WHERE id_lesson = %s AND type = 4", $_GET['id']);
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				while($row = $res->fetch_assoc()){
					$sql_questions = sprintf("SELECT COUNT(*) AS cnt FROM os_test_quest WHERE id_test = %s", $row['id_test']);
					$res_questions = $mysqli->query($sql_questions);
					if($res_questions->num_rows != 0) {
						$row_questions = $res_questions->fetch_assoc();
						printf("<p style='color:red; font-weight:700; font-size:20px;'> 
									Вопросов в тренировочном тесте( язык %s ) - - - %s 
								</p>", $row['lang'], $row_questions['cnt']);
					}
				}
			} else {
				print("<p style='color:red'> Тренировочных тестов в данном уроке нет</p>");
			}
			?>
			<p> -- Контрольный тест</p>
			<?php
			$sql = sprintf("SELECT * FROM os_lesson_test WHERE id_lesson = %s AND type = 5", $_GET['id']);
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				while($row = $res->fetch_assoc()){
					$sql_questions = sprintf("SELECT COUNT(*) AS cnt FROM os_test_quest WHERE id_test = %s", $row['id_test']);
					$res_questions = $mysqli->query($sql_questions);
					if($res_questions->num_rows != 0) {
						$row_questions = $res_questions->fetch_assoc();
						printf("<p style='color:red; font-weight:700; font-size:20px;'> 
									Вопросов в контрольном тесте( язык %s ) - - - %s 
								</p>", $row['lang'], $row_questions['cnt']);
					}
				}
			} else {
				print("<p style='color:red'> Контрольных тестов в данном уроке нет</p>");
			}
			?>
		<hr />
		<form method="post" action="">
			<?php
				$sql = sprintf("SELECT id, name, surname 
								  FROM os_users 
								 WHERE id 
								    IN ( 
								       SELECT id_teacher 
								         FROM os_teacher_class 
								        WHERE id_c 
								           IN (%s))
								    AND id
								     IN (
								       SELECT id_teacher 
								         FROM os_teacher_subj 
								        WHERE id_s = ( 
								        	  SELECT subject 
								        	    FROM os_lessons 
								        	   WHERE id = %s))", 
					$lesson->getClass(), $_GET['id'] );
				$res = $mysqli->query($sql);
				$teachers_list = "";
				if($res->num_rows != 0) {
					while($row = $res->fetch_assoc()) {
						$teachers_list .= sprintf("<option value='%s'>%s %s</option>", $row['id'], $row['surname'], $row['name']);
					}
				}
			?>
	 		<p>Учитель украинской версии</p>
	 		<select name="teacher_ua">
	 			<option value="0">NOT SELECTED</option>
		 		<?php print($teachers_list); ?>
	 		</select>
			<p>Учитель русской версии</p>
			<select name="teacher_ru">
				<option value="0">NOT SELECTED</option>
	 			<?php print($teachers_list); ?>
	 		</select>
	 		<p>Дата Української версії</p>
			<input type="datetime-local" name="date_ua" value="<?=strftime("%Y-%m-%dT%H:%M" , strtotime($lesson->getDate('ua')) )?>">
			<p>Дата Русской версии</p>
			<input type="datetime-local" name="date_ru" value="<?=strftime("%Y-%m-%dT%H:%M" , strtotime($lesson->getDate('ru')) )?>">
	 		<input type="submit" name="relocate" value="Продублировать в текущий уч. год">
	 	</form> 	
	</div>
</div>
	<?php include '../tpl_blocks/footer.php'; ?>

</body>
</html>