<?php
	require_once("../autoload.php");
	require_once("../functions.php");
session_start();
session_write_close();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$current_year = get_currentYearNum();
	if($_POST){
		if($_POST['flag'] == '1'){
			$sql = "SELECT * FROM os_lessons WHERE 1=1 AND lesson_year = " . $current_year;
			$current_data = $_SESSION['data'];
			$where_clause = array();
			switch ($current_data['level']) {
				// ученик
				case '1':
					$where_clause[] = sprintf("id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=%s )",
						$current_data['class']);
					$where_clause[] = sprintf("course = %s", 
						$current_data['currentCourse']);
					// если это онлайн-школа
					if($current_data['currentCourse'] == 0) {
						if($_POST['subj_id'] != 0) {
							$subject_add = "= " . $_POST['subj_id'];
						} else {
							$subject_add = sprintf("IN (SELECT id_subject FROM os_student_subjects WHERE id_student=%s)",
								$current_data['id']);
						}
						$where_clause[] = sprintf("subject $subject_add");
					} else {
						// если другой курс 
						if($_POST['subj_id'] != 0) {
							$subject_add = "= " . $_POST['subj_id'];
						} else {
							$subject_add = sprintf("IN (SELECT id_s FROM os_class_subj WHERE class=%s AND course=%s)",
								$current_data['class'],$current_data['currentCourse']);
						}
						$where_clause[] = sprintf("subject $subject_add");
					}
					break;
				
				default:
					# code...
					break;
			} 
			$where_string = "";
			if(count($where_clause) > 0) {
				foreach ($where_clause as $value) {
					$where_string .= " AND " . $value;
				}
			}
			$sql .= $where_string;
			$res = $mysqli->query($sql);
			//print("<br>$sql<br>");
			/*while ($row = $res->fetch_assoc()) {
				$sql_lhw = sprintf("SELECT * FROM os_lesson_homework WHERE id_lesson=%s",$row["id"]);
				$res_lhw = $mysqli->query($sql_lhw);
				if ($res_lhw->num_rows!=0) {
					$row_lhw = $res_lhw->fetch_assoc();
					$sql_homework = sprintf("SELECT * FROM os_homeworks WHERE `from`='%s' AND id_hw='%s'",$_POST["id"],$row_lhw["id"]);
					$res_homework = $mysqli->query($sql_homework);
					//print($sql_homework . "<br>");
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
						$sql_user = sprintf("SELECT * FROM os_users WHERE id = %s", $_POST["id"]);
						$res_user = $mysqli->query($sql_user);
						if($res_user->num_rows == 0) continue;
						$row_user = $res_user->fetch_assoc();
						if($row_user['level'] > 1) continue;
						$sql_create = sprintf("INSERT INTO os_homeworks(date_h,`from`,subj,class,id_hw,status,check_status) VALUES('%s',%s,%s,%s,%s,$status,2)",
							$date_ru[0],$_POST["id"],$row["subject"],$current_data["class"],$row_lhw["id"]);
						$res_create = $mysqli->query($sql_create);
						//print("<be>$sql_create<br>");
					}
				}
				
			}*/
			$lang = $_POST['lang'];
			if(empty($lang) || $lang == 'ru') {
				$translate_array = array( "template_timer_frame"  => "<span class='hw-timer-rest'>До окончания загрузки творческого ДЗ осталось <psan class='hw-timer'>%sд %sч</span></span>",
										  "incorrect_timer_frame" => "<span class='hw-timer-rest'>Истек срок выполнения творческого ДЗ по данному уроку</span>" );
			} else {
				$translate_array = array( "template_timer_frame"  => "<span class='hw-timer-rest'>До завершення завантаження творчого ДЗ залишилося <psan class='hw-timer'>%sд %sг</span></span>",
										  "incorrect_timer_frame" => "<span class='hw-timer-rest'>Термін виконання творчого ДЗ до цього уроку закінчився</span>" );
			}
			$pre_month_date = Date("Y-m-d", (strtotime(Date("Y-m-d"))-60*60*24*30));
			$sql = "SELECT * FROM os_homeworks WHERE 1=1";
			
			$where_clause = array();
			if($current_data['currentCourse'] == 0) {
				$where_clause[] = sprintf("id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson IN(SELECT id FROM os_lessons WHERE course=%s))",
					$current_data['currentCourse']);
			} else {
				// выборка по курсу, если не онлайн школа
				$where_clause[] = sprintf("id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson IN(SELECT id FROM os_lessons WHERE course=%s))",
					$current_data['currentCourse']);
			}
			if($current_data['level'] == 1) {
				// добавляем from текущего юзера
				$where_clause[] = sprintf("`from`=%s",$current_data['id']);
			} else {
				// добавляем from строки поиска 
				$where_clause[] = sprintf("`from` IN(SELECT id FROM os_users WHERE CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%')",$_POST['name']);
			}
			if($_POST['subj_id'] != 0) {
				$where_clause[] = sprintf("subj = %s",
					$_POST['subj_id']);
			} else {
				if($current_data['currentCourse'] == 0) {
					if($current_data['level'] == 1) {
						$where_clause[] = sprintf("subj IN (SELECT id_subject FROM os_student_subjects WHERE id_student = %s)",
							$current_data['id']);
					}
					if($current_data['level'] == 2) {
						$where_clause[] = sprintf("subj IN (SELECT id_s FROM os_teacher_subj WHERE id_teacher = %s)",
							$current_data['id']);
					}
				} else {
					if($current_data['level'] == 1) {
						$where_clause[] = sprintf("subj IN (SELECT id_s FROM os_class_subj WHERE course = %s)",
							$current_data['currentCourse']);
					}
					if($current_data['level'] == 2) {
						$where_clause[] = sprintf("subj = (SELECT DISTINCT id_s FROM os_class_subj WHERE course = %s 
							AND class IN (SELECT id_c FROM os_teacher_class WHERE id_teacher = %s)
							AND id_s IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher = %s))",
							$current_data['currentCourse'], $current_data['id'], $current_data['id']);
					}
				}
			}
			if($_POST['class_id'] != 0) {
				$where_clause[] = sprintf("`from` IN (SELECT id FROM os_users WHERE class=%s)", 
					$_POST['class_id']);
			} else {
				if($_SESSION['data']['level'] == 2) {
					$where_clause[] = sprintf("`from` IN (SELECT id FROM os_users WHERE class IN (SELECT id_c FROM os_teacher_class WHERE id_teacher = %s))", 
						$_SESSION['data']['id']);
				}
			}
			if($_POST['status'] != 5) {
				$where_clause[] = sprintf("status IN (%s)",
					$_POST['status']);
			} else {
				$where_clause[] = "check_status = 3";
			}
			$where_string = "";
			if(count($where_clause) > 0) {
				foreach ($where_clause as $value) {
					$where_string .= " AND " . $value;
				}
			}
			$sql .= $where_string;
			if($current_data['currentCourse'] == 0) {
				if($_POST['from_date'] != "" && $_POST['from_date'] != "0000-00-00"){
					$sql .= sprintf(" AND id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson IN(SELECT id FROM os_lessons WHERE date_$lang>='%s'))",$_POST['from_date']);
					$sql .= sprintf(" AND date_h>='%s'",$_POST['from_date']);
				}
				else{
					$sql .= sprintf(" AND date_h>='%s'",$pre_month_date);
				}
				if($_POST['to_date'] != "" && $_POST['to_date'] != "0000-00-00"){
					$sql .= sprintf(" AND id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson IN(SELECT id FROM os_lessons WHERE date_$lang<='%s'))",$_POST['to_date']);
					$sql .= sprintf(" AND date_h<='%s'",$_POST['to_date']);
				}
				else{
					//$sql .= sprintf(" AND date_h<='%s'",Date("Y-m-d"));
				}
			}
			if($_POST['level'] == 1)
				$sql .= " AND `from`=".$_POST['id'];
			$sql .= " ORDER BY date_h DESC LIMIT ".$_POST['bot_lim'].",".$_POST['per_page'];
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);

			$result = array();
			if($res->num_rows == 0){
				exit();
			}
			$options = new Options();
			$date_end = $options->get_option('semester_end_date');
			$semester = $options->get_option('semester_current_number');
			//print($date_end);
			$iter = 0;
			$end_timestamp	   = strtotime($date_end);
			$date_plus_two	   = $end_timestamp+60*60*24*2;
			
			while ($row = $res->fetch_assoc()) {
				$current_timestamp = time();
				$result_timestump  = $end_timestamp - $current_timestamp;
			
				$sql_user = "SELECT id ,CONCAT(surname,' ',name,' ',patronymic) AS fio, class FROM os_users WHERE id='".$row['from']."'";
				//print("<br>$sql_user<br>");
				$res_user = $mysqli->query($sql_user);
				$row_user = $res_user->fetch_assoc();

				$result[$iter] = array(
					"id" => $row['id'],
					"id_hw" => $row['id_hw'],
					"fio" => $row_user['fio'],
					"id_u" => $row_user['id'],
					"date" => $row['date_h'],
					"comment" => $row['comment'],
					"status" => $row['status'],
					"lock_status" => "",
					"last_hw_message" => "",
					"check_status" => $row['check_status'],
					"change_status" => $row['change_status'],
					"class" => $row_user['class']
				);
				$sql_lname = "SELECT id, title_$lang, DATE(date_$lang) AS date_lesson, is_control, subject, course, date_ru, date_ua, theme FROM os_lessons 
				WHERE id = (SELECT id_lesson FROM os_lesson_homework WHERE id='".$row['id_hw']."')";

				//print("<br>$sql_lname<br>");
				$res_lname = $mysqli->query($sql_lname);
				$row_lname = $res_lname->fetch_assoc();
				$result[$iter]['ltitle'] = $row_lname['title_'.$lang];
				$result[$iter]['lid'] = $row_lname['id'];
				$result[$iter]['ldate'] = $row_lname['date_lesson'];

				//статус блокировки
				if((($_SESSION['data']['class'] != 11 && $_SESSION['data']['edu_type'] == 1) || $_SESSION['data']['level'] > 1) 
					&& $_SESSION['data']['currentCourse'] == 0) {
					$date_lesson_new = Date("Y-m-d h:i:s");
					$row['last_hw_date'] .= " 00:00:01";
					if($row['last_hw_date'] > $date_lesson_new) {
						$currentTimestamp = time();
						$goalTimestamp	  = strtotime($row['last_hw_date']);
						$resultTimestamp  = $goalTimestamp - $currentTimestamp;
						$days = floor($resultTimestamp / (24 * 60 * 60));
						$hours = floor(($resultTimestamp - ($days * (24 * 60 * 60))) / (60*60));
						$result[$iter]['last_hw_message'] = sprintf($translate_array['template_timer_frame'], $days, $hours);
					}
					if($row['last_hw_date'] < $date_lesson_new) {
						//print($row['last_hw_date'] . " - " . $date_lesson_new . " - 2 " . "<br>");
						$result[$iter]['lock_status'] = 'locked';
						$result[$iter]['last_hw_message'] = $translate_array['incorrect_timer_frame'];
					}
					$lock_result = control_semester($row_lname['date_lesson']);
					//printf("\n $date_lesson_new - %s \n", $row['last_hw_date']);
					if(!$lock_result)
						$result[$iter]['lock_status'] = 'locked';
				}

				$sql_mark_max = sprintf("SELECT * FROM os_lesson_homework WHERE id='%s'",$result[$iter]['id_hw']);
					//print("<br>$sql_mark_max<br>");
					$res_mark_max = $mysqli->query($sql_mark_max);
					$row_mark_max = $res_mark_max->fetch_assoc();
					$result[$iter]["mark_max"] = $row_mark_max["mark"];
				$result[$iter]['text_hw'] = $row_mark_max['hw_text_'.$lang];

				$sql_test = sprintf("SELECT id_test FROM os_lesson_test WHERE lang='%s' AND type=5 AND id_lesson=%s",
					$_POST['lang'],$row_lname['id']);
				//print("<br>$sql_test<br>");
				//print($sql_test);
				$res_test = $mysqli->query($sql_test);
				$row_test = $res_test->fetch_assoc();
				$result[$iter]['c_test_id'] = $row_test['id_test'];


				$sql_mark = sprintf("SELECT * FROM os_journal WHERE id_s='%s' 
					AND id_l=(SELECT id_lesson FROM os_lesson_homework WHERE id='%s')",$row['from'],$row['id_hw']);
				//print("<br>$sql_mark<br>");
				$res_mark = $mysqli->query($sql_mark);
				if($res_mark->num_rows != 0) {
					$row_mark = $res_mark->fetch_assoc();
					$result[$iter]["mark"] = $row_mark['mark_hw'];
				} else {
					if($row_lname['is_control'] == 1) {
						$control = 3;
					} else {
						$control = 1;
					}
					$sql_insert_journal = sprintf("INSERT INTO os_journal (id_s, id_l, date_ru, date_ua, status, id_subj, course, theme) 
													    VALUES (%s, %s, '%s', '%s', $control, %s, %s, %s)",
													    $row['from'], $row_lname['id'], $row_lname['date_ru'], $row_lname['date_ua']
														, $row_lname['subject'], $row_lname['course'], $row_lname['theme']);
					$res_insert_journal = $mysqli->query($sql_insert_journal);
					$result[$iter]["mark"] = 0;
				}
				$sql_docs1 = "SELECT * FROM os_homework_docs WHERE id_hw='".$row['id']."'";
				$res_docs1 = $mysqli->query($sql_docs1);
				if($res_docs1->num_rows != 0){
					while ($row_docs1 = $res_docs1->fetch_assoc()) {

						$result[$iter][$row_docs1['from']][] = $row_docs1['file_name'];
					}
				}
				$iter++;
			}
			//var_dump($result);
			print(json_encode($result));
		}
		if($_POST['flag'] == '2'){
			$comment = $_POST['comment'];
			//print("<br>".$_POST['status']."<br>");
			//$rework = $_POST['status'] == true?4:2;
			//var_dump($_POST['status']);
			if($_POST['status'] == "true"){
				$rework = 4;
			}
			else{
				$rework = 2;
			}
			//print($rework);
			$id = $_POST['id'];
			$id_hw = $_POST['id_hw'];
			$sql = "SELECT * FROM os_homeworks WHERE id='$id'";
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$id_stud = $row['from'];
			
			$sql = "SELECT * FROM os_subjects WHERE id=(SELECT subj FROM os_homeworks WHERE id='$id')";
			//print($sql);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$subj_name_ru = $row['name_ru'];
			$subj_name_ua = $row['name_ua'];

			$sql = sprintf("INSERT INTO os_events(text_ua,text_ru,link,id_user,date_e,type,read_status) 
				VALUES('Ваше домашнє завдання з предмету <<%s>> було перевірено. ',
				'Ваше домашнее задание по предмету <<%s>> было проверено.','%s',%s,now(),4,0)",
			$subj_name_ua,$subj_name_ru,"http://online-shkola.com.ua/homework/",$id_stud);
			$res = $mysqli->query($sql);


				$sql = "SELECT * FROM os_users WHERE id IN(SELECT id_user FROM os_user_mails WHERE id_mail='4' AND id_user='$id_stud')";
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
				$row = $res->fetch_assoc();
				$sql_l = "SELECT * FROM os_subjects WHERE id=(SELECT subj FROM os_homeworks WHERE id='$id')";
				//print("<br>$sql_l<br>");
				$res_l = $mysqli->query($sql_l);
				$row_l = $res_l->fetch_assoc();
				$sql_n = "SELECT * FROM os_mail_types WHERE id='4'";
				//print("<br>$sql_n<br>");
				$res_n = $mysqli->query($sql_n);
				$row_n = $res_n->fetch_assoc(); 

				$mail_text = sprintf($row_n['template'],$row_l['name_ru']);
				/*print("\n");
				print($row_n['template']);
				print("\n");
				var_dump($row_l['name_ru']);
				print("\n");*/
				$headers = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=utf-8\r\n";
						$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n".
				    	'X-Mailer: PHP/' . phpversion();
				mail($row['email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$mail_text,$headers);
				if ($row['p_email'] != "") {
					mail($row['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$text,$headers);
				}
			$sql = "UPDATE os_homeworks SET comment='$comment', status='$rework', check_status='$rework', change_status=1 WHERE id='$id'";
			print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			if ($_POST['file']!=""){
				if ($_POST['type'] == 'student') {
					$sql = sprintf("INSERT INTO os_homework_docs(id_hw,`from`,file_name) VALUES('%s','student','%s')",$_POST['id'],$_POST['file']);
				}
				if ($_POST['type'] == 'teacher') {
					$sql = sprintf("INSERT INTO os_homework_docs(id_hw,`from`,file_name) VALUES('%s','teacher','%s')",$_POST['id'],$_POST['file']);
				}
			}
			print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$sql = sprintf("UPDATE os_journal SET mark_hw='%s' WHERE id_s=%s
							   AND id_l=(SELECT id_lesson FROM os_lesson_homework WHERE id=%s)",$_POST['mark'],$_POST['id_u'],$_POST['id_hw']);
			print("<br>$sql<br>");
			$res = $mysqli->query($sql);

			$sql = sprintf("UPDATE os_homeworks SET date_h='%s' WHERE id='%s'",Date("Y-m-d"),$_POST['id_hw']);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '3'){
			$lang = $_POST['lang'];
			if($_POST['level'] == 1){
				$sql = sprintf("SELECT * FROM os_homeworks WHERE `from`='%s' AND id_hw = (SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$_POST['id'],$_POST['id_lesson']);
			}
			else{
				$sql = sprintf("SELECT * FROM os_homeworks WHERE id_hw = (SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$_POST['id_lesson']);
			}

			//print($sql);
			$res = $mysqli->query($sql);

			$result = array();
			if($res->num_rows == 0){
				exit();
			}
			while ($row = $res->fetch_assoc()) {
				$sql_user = "SELECT id ,CONCAT(surname,' ',name,' ',patronymic) AS fio FROM os_users WHERE id='".$row['from']."'";
				//print("<br>$sql_user<br>");
				$res_user = $mysqli->query($sql_user);
				$row_user = $res_user->fetch_assoc();
				$result[$row['id']] = array(
					"id" => $row['id'],
					"id_hw" => $row['id_hw'],
					"fio" => $row_user['fio'],
					"id_u" => $row_user['id'],
					"date" => $row['date_h'],
					"comment" => $row['comment'],
					"status" => $row['status'],
					"check_status" => $row['check_status'],
					"status" => $row['change_status']
				);
				$sql_lname = "SELECT id, title_$lang, DATE(date_$lang) AS date_lesson FROM os_lessons 
				WHERE id = (SELECT id_lesson FROM os_lesson_homework WHERE id='".$row['id_hw']."')";
				//print("<br>$sql_lname<br>");
				$res_lname = $mysqli->query($sql_lname);
				$row_lname = $res_lname->fetch_assoc();
				$result[$row['id']]['ltitle'] = $row_lname['title_'.$lang];
				$result[$row['id']]['lid'] = $row_lname['id'];
				$result[$row['id']]['ldate'] = $row_lname['date_lesson'];
				
				
				$sql_hw = sprintf("SELECT id,hw_text_$lang FROM os_lesson_homework WHERE id='%s'",$row['id_hw']);
				//print("<br>$sql_hw<br>");
				$res_hw = $mysqli->query($sql_hw);
				$row_hw = $res_hw->fetch_assoc();
				$result[$row['id']]['text_hw'] = $row_hw['hw_text_'.$lang];

				$sql_test = sprintf("SELECT id_test FROM os_lesson_test WHERE lang='%s' AND type=5 AND id_lesson=%s",
					$_POST['lang'],$row_lname['id']);
				//print("<br>$sql_test<br>");
				//print($sql_test);
				$res_test = $mysqli->query($sql_test);
				$row_test = $res_test->fetch_assoc();
				$result[$row['id']]['c_test_id'] = $row_test['id_test'];


				$sql_mark = sprintf("SELECT mark_hw FROM os_journal WHERE id_s='%s' 
					AND id_l=(SELECT id_lesson FROM os_lesson_homework WHERE id='%s')",$row['from'],$row['id_hw']);
				//print("<br>$sql_mark<br>");
				$res_mark = $mysqli->query($sql_mark);
				$row_mark = $res_mark->fetch_assoc();
				$result[$row['id']]["mark"] = $row_mark['mark_hw'];
				$sql_docs1 = "SELECT * FROM os_homework_docs WHERE `from`='student' AND id_hw='".$row['id']."'";
				$res_docs1 = $mysqli->query($sql_docs1);
				if($res_docs1->num_rows != 0){
					while ($row_docs1 = $res_docs1->fetch_assoc()) {
						$result[$row['id']]["student"][] = $row_docs1['file_name'];
					}
				}
				$sql_docs2 = "SELECT * FROM os_homework_docs WHERE `from`='teacher' AND id_hw='".$row['id']."'";
				$res_docs2 = $mysqli->query($sql_docs2);
				if($res_docs2->num_rows != 0 && $res_docs2->num_rows != NULL){
					while ($row_docs2 = $res_docs2->fetch_assoc()) {
						$result[$row['id']]["teacher"][] = $row_docs2['file_name'];
					}
				}
			}
			print(json_encode($result));
		}
		if ($_POST["flag"] == "4") {
			$id_stud = $_POST["id_u"];
			
			$sql = sprintf("INSERT INTO os_homework_docs(id_hw,`from`,file_name) VALUES('%s','student','%s')",$_POST['id'],$_POST['file']);
			print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			//printf("<br>%s<br>",Date("Y-m-d"));
			$sql = sprintf("UPDATE os_homeworks SET date_h='%s',check_status=3, change_status=1 WHERE id='%s'",Date("Y-m-d"),$_POST['id']);
			print("<br>$sql<br>");
			$res = $mysqli->query($sql);
		}
		if ($_POST["flag"] == "6") {
			$sql = sprintf("UPDATE os_homeworks SET change_status=0 WHERE id='%s'",$_POST['id']);
			print("<br>$sql<br>");
			$res = $mysqli->query($sql);
		}
		if ($_POST["flag"] == "7") {
			$error = false;
			$file = array();
			$file = $_POST["file"];
			$data = "";
		    $files = "";
			var_dump($file);
		    $uploaddir = 'http://online-shkola.com.ua/upload/docs/'; // . - текущая папка где находится submit.php
		    // переместим файлы из временной директории в указанную

		        if( move_uploaded_file( $file['tmp_name'], $uploaddir . basename($file['name']) ) ){
		            $files = realpath( $uploaddir . $file['name'] );
		        }
		        else{
		            $error = true;
		        }
		 
		    $data = $error ? 'Ошибка загрузки файлов.' :  $files ;
		 
		    print_r(json_encode( $data ));
		}

	}
	if( isset( $_GET['uploadfiles'] ) ){
    $error = false;
    $files = array();
 	//var_dump($_FILES);
		if (Cfile::isSecure($_FILES[0])){
			//print("step 1<br />");
			$file = Cfile::Load_hw($_FILES[0]);
			//$size = getimagesize("http://online-shkola.com.ua/upload/hworks/$file");
			if ( !$file ) exit();
			else{
				//print("step 2<br />");
				$files["name"] = $file;
				$files["real_name"] = $_FILES[0]["name"];
				print_r(json_encode( $files ));
			}
		}

	}

?>