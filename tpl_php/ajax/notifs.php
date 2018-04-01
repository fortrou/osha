<?php
	require_once('../autoload.php');
	session_start();
	$current_year_num = get_currentYearNum();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_POST) {
		/*** Примечания ***/
		if($_POST['flag'] == '1') {
			if(!isset($_POST['type']) || empty($_POST['type'])) {
				$type = '1';
			} else {
				$type = $_POST['type'];
			}
			$sql = sprintf("INSERT INTO os_user_annotations(`text_ru`,`text_ua`, `id_type`) VALUES('%s','%s', %s)", 
				$_POST['text_ru'], $_POST['text_ua'], $type);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '2') {
			if(!isset($_POST['type']) || empty($_POST['type'])) {
				$type = '1';
			} else {
				$type = $_POST['type'];
			}
			$sql = sprintf("SELECT * FROM os_user_annotations WHERE id_type = %s", $type);
			//print($sql);
			$res = $mysqli->query($sql);
			$result = array();
			while ($row = $res->fetch_assoc()) {
				$result[$row['id']] = array(
					"first" => $row['text_'.$_POST["lang"]]
				);
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '5') {
			$sql = "DELETE FROM os_user_annotations WHERE id='".$_POST['id']."'";
			$res = $mysqli->query($sql);
		}
		/*** Примечания ***/
		
		/*** Тематическая оценка ***/
		if($_POST['flag'] == '3') {
			$date_ru = $_POST['date_ru'];
			$date_ua = $_POST['date_ua'];
			$col_n = "";
			$lang = $_POST['lang'];
			switch ($_POST['pos']) {
				case 1:
					$col_n = "mark_tr";
					break;
				case 2:
					$col_n = "mark_contr";
					break;
				case 3:
					$col_n = "mark_hw";
					break;
				case 4:
					$col_n = "mark_com";
					break;
				
				default:
					$col_n = "mark_tr";
					break;
			}
			$sql = sprintf("INSERT INTO os_journal(id_s,date_ru,date_ua,$col_n,status,id_subj,title_t_ru,title_t_ua, year_num) VALUES('%s','$date_ru','$date_ua','%s',2,'%s','%s','%s', '%s')",
				$_POST['pupid'],$_POST['val'],$_POST['subj'],$_POST['name_ru'],$_POST['name_ua'],$current_year_num);
			print($sql);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '4') {
			$sql = "DELETE FROM os_journal WHERE id='".$_POST['id']."'";
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '59') {
			$sql = sprintf("SELECT * FROM os_homework_docs WHERE `from`='student' AND id_hw=(SELECT id FROM os_homeworks
			 WHERE `from`=(SELECT id_s FROM os_journal WHERE id='%s')
			 AND id_hw=(SELECT id FROM os_lesson_homework WHERE id_lesson=(SELECT id_l FROM os_journal WHERE id='%s')))",
				$_POST['id'],$_POST['id']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$str_docs = "";
			while ($row = $res->fetch_assoc()) {
				$str_docs .= sprintf("<div class='simple_doc'>%s<a href='../upload/hworks/%s' download> скачать </a></div>",
					substr($row['file_name'],20,39),$row['file_name']);
			}
			$sql = sprintf("SELECT * FROM os_homework_docs WHERE `from`='teacher' AND id_hw=(SELECT id FROM os_homeworks
			 WHERE `from`=(SELECT id_s FROM os_journal WHERE id='%s')
			 AND id_hw=(SELECT id FROM os_lesson_homework WHERE id_lesson=(SELECT id_l FROM os_journal WHERE id='%s')))",
				$_POST['id'],$_POST['id']);
			$res = $mysqli->query($sql);
			$str_docs_t = "";
			while ($row = $res->fetch_assoc()) {
				$str_docs_t .= sprintf("<div class='simple_doc'>%s<a href='../upload/hworks/%s' download> скачать </a></div>",
					substr($row['file_name'],20,39),$row['file_name']);
			}
			$result = array(
				"students" => $str_docs,
				"teachers" => $str_docs_t
			);
			print(json_encode($result));
		}
		/*** Тематическая оценка ***/
		/*** События ***/
		if($_POST['flag'] == '6') {
			$sql = sprintf("SELECT * FROM os_events WHERE id_user='%s' AND type IN(%s) ORDER BY date_e DESC LIMIT %s,%s",
				$_POST['id'],$_POST['type'],$_POST["bot_lim"],$_POST['top_lim']);
			//print($sql);
			$res = $mysqli->query($sql);
			$result = array();
			$iter = 0;
			while ($row = $res->fetch_assoc()) {
				$result[$iter] = array(
					"first" => $row['text_'.$_POST['lang']],
					"second" => $row['date_e'],
					"third" => "<span onclick=\"read_it(".$row['id'].")\">x</span>",
					"fourth" => $row['read_status'],
					"fifth" => $row['link'],
					"sixth" => $row['id']
				);
				$iter++;
			}
			print(json_encode($result));
		}
		if($_POST['flag'] == '7') {
			$sql = "UPDATE os_events SET read_status=1 WHERE id='".$_POST['id']."'";
			$res = $mysqli->query($sql);
			$result = array();
			
		}
		if($_POST['flag'] == '61') {
			$sql = "SELECT COUNT(*) FROM os_events WHERE id_user='".$_POST['id']."' AND type IN(".$_POST['type'].") ORDER BY date_e DESC";
			//print($sql);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$result = $row["COUNT(*)"];
			print(json_encode($result));
			
		}
		/*** События ***/
		/*** Подписки ***/
		if($_POST['flag'] == '8') {
			$sql = "SELECT * FROM os_user_mails WHERE id_user='".$_POST['user']."' AND id_mail='".$_POST['id']."'";
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			if ($res->num_rows == 0) {
				$sql = "INSERT INTO os_user_mails(id_user,id_mail) VALUES('".$_POST['user']."','".$_POST['id']."')";
				$res = $mysqli->query($sql);
			}
			else{
				$sql = "DELETE FROM os_user_mails WHERE id_user='".$_POST['user']."' AND id_mail='".$_POST['id']."'";
				$res = $mysqli->query($sql);
			}
		}
		/*** Подписки ***/
		/*** Оплаты ***/
		if($_POST['flag'] == '10') {
			$sql = sprintf("UPDATE os_payment_size SET cost='%s' WHERE class='%s' AND acc_type='%s'",
				$_POST['cost'],$_POST['class_id'],$_POST['type']);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '11') {
			$sql = sprintf("SELECT cost FROM os_payment_size WHERE class='%s' AND acc_type='%s'",
				$_POST['class_id'],$_POST['type']);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$result = $row['cost'];
			print(json_encode($result));
			
		}
		/*** Оплаты ***/
		/*** Редакторы ***/
		if($_POST['flag'] == '12') {
			$sql = sprintf("SELECT * FROM os_users WHERE level=1 AND class='%s'",$_POST['class_id']);
			$res = $mysqli->query($sql);
			$result[0] = "";
			while ($row = $res->fetch_assoc()) {
				$result[0] .= "<div class='red_student'><span class='red_fio'>".$row['surname']." ".$row['name']." ".$row['patronymic'].
				"</span><br><form><select size='1' name='red_user_class' onchange=\"change_class(".$row['id'].
					",this.form.red_user_class.value)\">";
					$sql_classes = "SELECT * FROM os_class_manager";
					$res_classes = $mysqli->query($sql_classes);
					if($res_classes->num_rows != "") {
						while($row_classes = $res_classes->fetch_assoc()) {
							if($row_classes['id'] == $row['class'])
								$result[0] .= "<option value='" . $row_classes['id'] . "' selected>" . $row_classes['class_name'] . "</option>";
							else
								$result[0] .= "<option value='" . $row_classes['id'] . "'>" . $row_classes['class_name'] . "</option>";
						}
					} else {
						$result[0] .= $result[0] .= "<option value='" . $row['class'] . "' selected>" . $row['class'] . "</option>";
					}
				$result[0] .= "</select></form><input type='button' name='red_del' onclick=\"delete_student(".$row['id'].")\" value='Удалить'>";
				if($row['lock_status'] == 0) {
					$result[0] .= "<input type='button' name='red_lock' onclick=\"lock_unlock_student(".$row['id'].")\" value='Заблокировать'>";
				}
				else{
					$result[0] .= "<input type='button' name='red_unlock' onclick=\"lock_unlock_student(".$row['id'].")\" value='Разблокировать'>";
				}
				$result[0] .= "</div>"; 
			}
			$sql_man = sprintf("SELECT * FROM os_users WHERE level=3");
			//print($sql);
			$res_man = $mysqli->query($sql_man);
			$sql_new = sprintf("SELECT id_manager FROM os_class_manager WHERE id='%s'",$_POST['class_id']);
			$res_new = $mysqli->query($sql_new);
			$row_new = $res_new->fetch_assoc();
			$result[0] .= "<p>Назначить/поменять куратора класса</p><form><select size='1' class='red_select_manager' name='red_upd_manager'
			onchange=\"update_manager(".$_POST['class_id'].",this.form.red_upd_manager.value)\">"; 
			while ($row_man = $res_man->fetch_assoc()) {
				if($row_new['id_manager'] == 0)
					$result[0] .= "<option value='0' selected>Куратор не назначен</option>";
				if($row_man['id'] == $row_new['id_manager'])
					$result[0] .= sprintf("<option value='%s' selected>%s %s %s</option>",$row_man['id'],$row_man['surname'],$row_man['name'],$row_man['patronymic']);
				else
					$result[0] .= sprintf("<option value='%s'>%s %s %s</option>",$row_man['id'],$row_man['surname'],$row_man['name'],$row_man['patronymic']);
			}
			$result[0] .= "</select>";
			$sql = sprintf("SELECT * FROM os_class_manager WHERE id='%s'",$_POST['class_id']);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$result[1] = $row['class_name'];
			//print($result[0]."<br>");
			print(json_encode($result));
		}
		if($_POST['flag'] == '13') {
			$sql = sprintf("UPDATE os_users SET class='%s' WHERE id='%s'",$_POST['class_id'],$_POST['user_id']);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '14') {
			$sql = sprintf("UPDATE os_class_manager SET id_manager='%s' WHERE id='%s'",$_POST['manager_id'],$_POST['class_id']);
			print($sql);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '15') {
			$sql = sprintf("DELETE FROM os_users WHERE id='%s'",$_POST['student_id']);
			//print($sql);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '36') {
			$sql = sprintf("SELECT * FROM os_class_subj WHERE class='%s'",$_POST['class_id']);
			$res = $mysqli->query($sql);
			$csubjs = array();
			while ($row = $res->fetch_assoc()) {
				$csubjs[] = $row['id_s'];
			}
			$result = "";
			$sql = "SELECT * FROM os_subjects";
			$res = $mysqli->query($sql);
			while($row = $res->fetch_assoc()) {
				if(in_array($row['id'],$csubjs)) {
					$result .= sprintf("<option value='%s' selected>%s</option>",$row['id'],$row['name_ru']);
				}
				else{
					$result .= sprintf("<option value='%s'>%s</option>",$row['id'],$row['name_ru']);
				}
			}
			print(json_encode($result));
		}
		if($_POST['flag'] == '37') {
			$sql = sprintf("DELETE FROM os_class_subj WHERE class='%s'",$_POST['class_id']);
			$res = $mysqli->query($sql);
			foreach ($_POST["subjects"] as $value) {
				$sql = sprintf("INSERT INTO os_class_subj(id_s,class) VALUES(%s,%s)",$value,$_POST['class_id']);
				$res = $mysqli->query($sql);
			}
			//print(json_encode($result));
		}
		if($_POST['flag'] == '38') {
			$sql = sprintf("UPDATE os_class_manager SET class_name='%s' WHERE id='%s'",$_POST['class_name'],$_POST['class_id']);
			$res = $mysqli->query($sql);
			//print(json_encode($result));
		}
		if($_POST['flag'] == '39') {
			$sql = sprintf("SELECT * FROM os_class_manager");
			$res = $mysqli->query($sql);
			$result = "";
			while ($row = $res->fetch_assoc()) {
				if($row['id'] == $_POST['class_id']) {
					$result .= sprintf("<option value='%s' selected>%s</option>",$row['id'],$row['class_name']);
				}
				else{
					$result .= sprintf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
				}
			}
			print(json_encode($result));
		}
		if($_POST['flag'] == '40') {
			$sql = sprintf("INSERT INTO os_class_manager(class_name,id_manager) VALUES('%s','%s')",$_POST['class_name'],$_POST['manager']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$sql = "SELECT MAX(id) FROM os_class_manager";
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			foreach ($_POST["subjects"] as $value) {
				$sql = sprintf("INSERT INTO os_class_subj(id_s,class) VALUES(%s,%s)",$value,$row['MAX(id)']);
				$res = $mysqli->query($sql);
			}
			//print(json_encode($result));
		}
		if($_POST['flag'] == '41') {
			$sql = sprintf("UPDATE os_class_manager SET is_opened='0' WHERE is_opened='1'");
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$sql = sprintf("UPDATE os_class_manager SET is_opened='1' WHERE id='%s'",$_POST['class_id']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);			
			//print(json_encode($result));
		}
		/*** Редакторы ***/
		/*** Оплаты ***/
		if($_POST['flag'] == '16') {
			if($_POST['type_id'] == 1) {
				$merchant_id = 'i10672147601';
				$signature = 'gedRsHaal5YlgcnXcIcONXE3eIfliWa6pC40l5vZ';
			} else {
				$merchant_id = 'i97603769660';
				$signature = 'bZAUhVOWNAycyQsKJQi3fgOJI3W0czDlEnLj4DIb';
			}
			$lang = isset($_COOKIE['lang']) && !empty($_COOKIE['lang']) ? $_COOKIE['lang'] : 'ru';
			$subjects = "";
			$sql = "SELECT * FROM os_edu_types WHERE id='" . $_POST['type_id'] . "'";
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$subjects_array = array();
				$row = $res->fetch_assoc();
				if(isset($_POST['prolong_edu']) && $_POST['prolong_edu'] == 0) {
					if($_POST['type_id'] == 3) {
						$subjects_array = explode(',', $_POST['subjects']);
						if(isset($_POST['subjects']) && !empty($_POST['subjects']) && count($subjects_array) == 3) {
							$sql_subjects = sprintf("SELECT * FROM os_subjects WHERE id IN(%s)", $_POST['subjects']);
							//print("<br>$sql_subjects<br>");
							$res_subjects = $mysqli->query($sql_subjects);
							if($res_subjects->num_rows != 0) {
								while ($row_subjects = $res_subjects->fetch_assoc()) {
									$subjects .= $row_subjects['name_' . $lang] . ', ';
									if($lang == 'ru') 
										$subjects = ' за предметы ' . $subjects;
									else
										$subjects = ' за предмети ' . $subjects;
								}
								$subjects = rtrim($subjects, ', ');
							}
						}
					}
				} else {
					$row['cost'] = (int)$row['cost']*(int)$_POST['monthes'];

				}
				$payment_data = array( 'id' => $_SESSION['data']['id'],
									   'edu_type' => $_POST['type_id'],
									   'subjects' => implode('::', $subjects_array),
									   'is_prolong' => $_POST['prolong_edu'] );
				if($lang == 'ru') {
					$translate_array = array( 'payment_description' => "Оплата за " . $row['name'] . " Онлайн-Школа 'Альтернатива'" . $subjects
											);

				} else {
					$translate_array = array( 'payment_description' => "Сплата за " . $row['name'] . " Онлайн-Школа 'Альтернатива'" . $subjects
											);
				}
				$new_order_id = $_SESSION['data']['id'] . '_' . Date('YmdHis') . '_0';
				$params = array(
				  'amount' 		=> $row['cost'],
				  'version' 	=> '3',
				  'currency'    => 'UAH',     //Можно менять  'EUR','UAH','USD','RUB','RUR'
				  'order_id' 	=> $new_order_id,
				  'language' 	=> $_COOKIE['lang'],
				  'result_url'  => 'http://' . $_SERVER['HTTP_HOST'] . '/cabinet/index.php#tab_1',
				  'server_url'	=> 'http://' . $_SERVER['HTTP_HOST'] . '/billings/payment_result.php',
				  'description' => $translate_array['payment_description'],  //Или изменить на $desc
				  'sender_last_name' 	=> $_SESSION['data']['surname'],
				  'sender_first_name'   => $_SESSION['data']['name'],
				  'product_description' => implode('--+--',$payment_data)
				);
				/*if(isset($_POST['prolong_edu']) && $_POST['prolong_edu'] == 1) {
					$params['sandbox'] = 1; //test payment
				}*/
				$billing_object = new Billing($merchant_id, $signature);
				$billing_object->complete_params($params);
				$return_form = $billing_object->get_form($params);
				/*echo "<br><pre>";
				print_r($return_form);
				echo "</pre><br>";*/
				$result = array( 'cost'   => $row['cost'],
			 					 'inputs' => $return_form );
				print(json_encode($result));
			}
			exit();
		}
		
		if($_POST['flag'] == '18') {
			if(!isset($_SESSION['data']['currentCourse']) || empty($_SESSION['data']['currentCourse']))
				$_SESSION['data']['currentCourse'] = 0;
			$sql = sprintf("SELECT * FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE class IN(%s) AND course=%s)",
				$_POST['class_id'],$_SESSION['data']['currentCourse']);
			//print($sql);
			$res = $mysqli->query($sql);
			$result = array();
			while ($row = $res->fetch_assoc()) {
				$result[$row['id']] = $row['name_'.$_POST['lang']];
			}
			print(json_encode($result));
		}
		if($_POST['flag'] == '19') {
			$sql = "SELECT * FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE class='".$_POST['class_id']."')";
			//print($sql);
			$res = $mysqli->query($sql);
			$sql1 = "SELECT * FROM os_edu_types WHERE id=3";
			$res1 = $mysqli->query($sql1);
			$row1 = $res1->fetch_assoc();
			$result = $row1['cost']/(int)($res->num_rows)*count($_POST['subjects']);
			$result = ceil($result);
			print(json_encode($result));
		}
		/*** Оплаты ***/
		/*** Чаты ***/
		if($_POST['flag'] == '20') {
			/*if($_POST['lang'] == 'ua')
				$result = "<div>Ваші чати</div>";
			if($_POST['lang'] == 'ru')
				$result = "<div>Ваши чаты</div>";*/
				$result = array();
			$sql = sprintf("SELECT * FROM os_lesson_chat WHERE id_lesson='%s' AND lang='%s'",$_POST['id_lesson'],$_POST['lang']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$main_chat = $row['id_chat'];
			$result[0] = "<div onclick=\"common_getMessages(".$main_chat.")\">Чат урока</div>";
			$sql = sprintf("SELECT id, CONCAT(surname,' ',name) AS fi FROM os_users WHERE class IN
				(SELECT class FROM os_lessons WHERE id='%s')",$row['id_lesson']);
			$res = $mysqli->query($sql);
			$result[1] = "";
			while($row = $res->fetch_assoc()) {
				$sql_c = sprintf("SELECT * FROM os_lesson_pupil_chat WHERE id_lesson='%s' AND id_pupil='%s'",$_POST['id_lesson'],$row['id']);
				$res_c = $mysqli->query($sql_c);
				$row_c = $res_c->fetch_assoc();
				//print("<br>$sql_c<br>");
				$result[1] .= "<div class='change_chat'><div onclick=\"common_getMessages(".$row_c['id_chat'].")\">".$row['fi']."</div>
				<div onclick=\"common_getMessages(".$main_chat.")\">x</div></div>";
			}
			//print("<br>$sql<br>");
			
			print(json_encode($result));
		}
		if($_POST['flag'] == '21') {
			/*if($_POST['lang'] == 'ua')
				$result = "<div>Ваші чати</div>";
			if($_POST['lang'] == 'ru')
				$result = "<div>Ваши чаты</div>";*/
			$sql = sprintf("SELECT * FROM os_lesson_chat WHERE id_lesson='%s' AND lang='%s'",$_POST['id_lesson'],$_POST['lang']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$main_chat = $row['id_chat'];
			$result[0] = "<div onclick=\"common_getMessages(".$main_chat.")\">Чат урока</div>";
			$sql = sprintf("SELECT id, CONCAT(surname,' ',name) AS fi FROM os_users WHERE class IN
				(SELECT class FROM os_lessons WHERE id='%s') AND id='%s'",$row['id_lesson'],$_POST['id']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$result[1] = "";
			while($row = $res->fetch_assoc()) {
				$sql_c = sprintf("SELECT * FROM os_lesson_pupil_chat WHERE id_lesson='%s' AND id_pupil='%s'",$_POST['id_lesson'],$row['id']);
				$res_c = $mysqli->query($sql_c);
				$row_c = $res_c->fetch_assoc();
				//print("<br>$sql_c<br>");
				$result[1] .= "<div class='change_chat'><div onclick=\"common_getMessages(".$row_c['id_chat'].")\">".$row['fi']."</div>
				<div onclick=\"common_getMessages(".$main_chat.")\">x</div></div>";
			}
			//print("<br>$result<br>");
			
			print(json_encode($result));
		}
		
		if($_POST['flag'] == '25') {
			
			$sql = sprintf("SELECT * FROM os_users WHERE level IN(%s)",$_POST['type']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			
			$result = "";
			while($row = $res->fetch_assoc()) {
				$result .= sprintf("<option value='%s'><span>%s %s %s\"</span></option>",$row['id'],$row['surname'],$row['name'],$row['patronymic']);
			}
			//print("<br>$sql<br>");
			
			print(json_encode($result));
		}
		if($_POST['flag'] == '26') {
			
			if($_POST['type'] == 1) {
				$sql = sprintf("SELECT * FROM os_users WHERE level IN(%s) AND class='%s'",
					$_POST['type'],$_POST['class_id']);
			}
			if($_POST['type'] == 2) {
				$sql = sprintf("SELECT * FROM os_users WHERE level IN(%s) AND id IN 
					(SELECT id_teacher FROM os_teacher_class WHERE id_c='%s')",
					$_POST['type'],$_POST['class_id'],$_POST['class_id']);
			}
			if($_POST['type'] == 3) {
				$sql = sprintf("SELECT * FROM os_users WHERE level IN(%s) AND id IN 
					(SELECT id_manager FROM os_class_manager WHERE id_class='%s')",
					$_POST['type'],$_POST['class_id'],$_POST['class_id']);
			}
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			
			$result = "";
			while($row = $res->fetch_assoc()) {
				$result .= sprintf("<option value='%s'><span>%s %s %s\n\t</span></option>",$row['id'],$row['surname'],$row['name'],$row['patronymic']);
			}
			//print("<br>$sql<br>");
			
			print(json_encode($result));
		}
		if($_POST['flag'] == '27') {
			$users = array();
			foreach ($_POST['target_id'] as $value) {
				$users[] = $value;
			}
			$users[] = $_POST['from_id'];
			$sql_n = sprintf("INSERT INTO os_chat(id,chat_name,chat_type) SELECT MAX(id)+1,'%s',3 FROM os_chat",$_POST['chat_name']);
			$res_n = $mysqli->query($sql_n);
			$sql_ch = "SELECT MAX(id) FROM os_chat";
			$res_ch = $mysqli->query($sql_ch);
			$row_ch = $res_ch->fetch_assoc();
			foreach ($users as $value) {
				$sql_users = sprintf("INSERT INTO os_chat_users(id_chat, id_user) VALUES(%s,%s)",$row_ch['MAX(id)'],$value);
				$res_users = $mysqli->query($sql_users);
			}
		}
		if($_POST['flag'] == '60') {
			$str_id = "";
			foreach ($_POST['array'] as $value) {
				$str_id .= $value.',';
			}
			$str_id = rtrim($str_id,',');
			
			$sql = sprintf("SELECT * FROM os_users WHERE id IN(%s)",$str_id);
			$res = $mysqli->query($sql);
			
			$result = "";
			while($row = $res->fetch_assoc()) {
				$result .= sprintf("<span>%s %s %s</span><br>",$row['surname'],$row['name'],$row['patronymic']);
			}
			//print("<br>$sql<br>");
			
			print(json_encode($result));
		}
		
		
		if($_POST['flag'] == '35') {
			$sql = sprintf("SELECT * FROM os_user_docs WHERE id_user='%s' ORDER BY id DESC",$_POST['id']);
			$res = $mysqli->query($sql);
			$str_docs = "";
			while ($row = $res->fetch_assoc()) {
				$str_docs .= sprintf("<div class='simple_doc' onclick=\"attach_document('%s','%s','%s')\">%s<a href='../upload/hworks/%s' download> скачать </a></div>",
					$_POST['id_h'],$row['doc_addr'],$row['doc_name'],$row['doc_name'],$row['doc_addr']);
			}
			$result = array(
				"docs" => $str_docs
			);
			print(json_encode($result));
		}
		if($_POST['flag'] == '66') {
			$sql = sprintf("SELECT * FROM os_user_docs WHERE id_user='%s'",$_POST['id']);
			$res = $mysqli->query($sql);
			$str_docs = "";
			while ($row = $res->fetch_assoc()) {
				$str_docs .= sprintf("<div class='simple_doc' onclick=\"attach_document('%s','%s')\">%s<a href='../upload/hworks/%s' download> скачать </a></div>",
					$row['doc_addr'],$row['doc_name'],$row['doc_name'],$row['doc_addr']);
			}
			$result = array(
				"docs" => $str_docs
			);
			print(json_encode($result));
		}
		if($_POST['flag'] == '43') {
			$id_student = $_POST['id_student'];
			$where = array();
			$where_course = "";
			$student_subjects = "";
			if($_SESSION['data']['currentCourse'] == 0) {
				$student_subjects = sprintf(" AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')", 
											$id_student);
				$where[] = sprintf("id_s IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')", $id_student);
			} else {
				$where[] = sprintf("course = %s", $_SESSION['data']['currentCourse']);
			}
			$where_course = sprintf(" AND course = %s", $_SESSION['data']['currentCourse']);
			$where[] = sprintf("id_teacher IN(SELECT id_teacher FROM os_teacher_class WHERE id_c 
				IN(SELECT class FROM os_users WHERE id='%s'))", $id_student);
			$where_statement = "";
			foreach($where as $value) {
				$where_statement .= " AND " . $value;
			}
			/* Get teachers id */
			$sql_students = sprintf("SELECT id FROM os_users WHERE level='2' AND id IN(SELECT id_teacher FROM os_teacher_subj WHERE 1=1 %s)",
									$where_statement);
			//print("<br> \n $sql_students \n <br>");
			$res_students = $mysqli->query($sql_students);
			if($res_students->num_rows != 0) {
				$students = array();
				while ($row_students = $res_students->fetch_assoc()) {
					$students[] = $row_students['id'];
				}
			}
			else{
				print("<br>reverse<br>");
				exit();
			}
			/* Get teachers id */
			/* Check chats with students array */
			$chats = array();
			foreach ($students as $value) {
				$sql_chats = sprintf("SELECT a.id_chat AS ch1, b.id_chat AS ch2 FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
					WHERE a.id_user='%s' AND b.id_user='%s'",$value,$id_student);
				//print("<br> \n $sql_chats \n <br>");
				$res_chats = $mysqli->query($sql_chats);
				if ($res_chats->num_rows != 0) {
					$row_chats = $res_chats->fetch_assoc();
					$chats[] = $row_chats['ch1'];
				}
				else{
					/* Generate new chat */
					//print("<br>reverse<br>");
					$sql_t_name = "SELECT CONCAT(surname,' ',name) AS fi, id FROM os_users WHERE id=$value";
					$res_t_name = $mysqli->query($sql_t_name);
					$row_t_name = $res_t_name->fetch_assoc();
					$sql_s_name = "SELECT CONCAT(surname,' ',name) AS fi, id FROM os_users WHERE id=$id_student";
					$res_s_name = $mysqli->query($sql_s_name);
					$row_s_name = $res_s_name->fetch_assoc();
					$chat_name = "Чат между учителем " . $row_t_name['fi'] . 
								 "(id {$row_t_name['id']}) и учеником " . $row_s_name['fi'] .
								 "(id {$row_s_name['id']})";
					$sql_create = "INSERT INTO os_chat SELECT MAX(id)+1,'$chat_name',1,0 FROM os_chat";
					$res_create = $mysqli->query($sql_create);
					$sql_getChat = "SELECT id FROM os_chat WHERE chat_name='$chat_name'";
					$res_getChat = $mysqli->query($sql_getChat);
					$row_getChat = $res_getChat->fetch_assoc();
					$sql_insert1 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$value);
					$sql_insert2 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$id_student);
					$res_insert1 = $mysqli->query($sql_insert1);
					$res_insert2 = $mysqli->query($sql_insert2);
					/* Generate new chat */
				}
			}
			/* Check chats with students array */
			/* Get chats */
			$result = "";
			$sub_sql = sprintf("SELECT DISTINCT a.id_chat AS ch1
				FROM os_chat_users AS a JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
				WHERE b.id_user='%s' AND a.id_user IN(SELECT DISTINCT id_teacher FROM os_teacher_subj WHERE course=%s)",
				$id_student,$_SESSION['data']['currentCourse']);
			$sub_res = $mysqli->query($sub_sql);
			$chats_id = "";
			
			$sql_finChats = sprintf("SELECT * FROM os_chat WHERE chat_type='1' AND id IN(
											SELECT DISTINCT id_chat FROM os_chat_users as parent 
												   WHERE parent.id_chat IN (
												   SELECT DISTINCT id_chat FROM os_chat_users 
													      WHERE id_user='%s' AND id_chat=parent.id_chat)
														  AND parent.id_chat IN (SELECT DISTINCT id FROM os_chat WHERE chat_type='1')
														  AND parent.id_chat IN (
														  SELECT DISTINCT child.id_chat FROM os_chat_users as child 
															     WHERE parent.id_user IN( 
																 SELECT DISTINCT id_teacher FROM os_teacher_subj 
																        WHERE course=%s)
																 AND parent.id_user IN(
																 SELECT id_teacher FROM os_teacher_class WHERE id_c = %s)))",
				$id_student,$_SESSION['data']['currentCourse'],$_SESSION['data']['class']);
			/*$sql_finChats = sprintf("SELECT * FROM os_chat WHERE chat_type='1' AND id IN(
									 SELECT DISTINCT id_chat FROM os_chat_users 
											WHERE b.id_user='%s' AND id_chat IN(
											SELECT DISTINCT id_chat FROM os_chat_users 
												   WHERE id_user IN(
												   SELECT id_teacher FROM os_teacher_subj WHERE course=%s)))",
				$id_student,$_SESSION['data']['currentCourse']);*/
			//print("<br> \n $sql_finChats \n <br>");
			$res_finChats = $mysqli->query($sql_finChats);
			//var_dump($res_finChats);
			if ($res_finChats->num_rows!=0) {
				while ($row_finChats = $res_finChats->fetch_assoc()) {
					$sql_t_data = sprintf("SELECT * FROM os_users WHERE id=(
													SELECT DISTINCT id_user FROM os_chat_users 
																   WHERE id_chat='%s' AND id_user NOT IN(%s))",
						$row_finChats['id'],$id_student);
					//print("<br> \n $sql_t_data \n <br>");
					$res_t_data = $mysqli->query($sql_t_data);
					if($res_t_data->num_rows == 0) continue;
					$row_t_data = $res_t_data->fetch_assoc();
					$fit = $row_t_data['surname'].' '.$row_t_data['name'];
					$subjects = "";
					$sql_subjects = sprintf("SELECT DISTINCT * FROM os_subjects WHERE 
						id IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher='%s' $where_course) $student_subjects",
						$row_t_data['id']);
						//print("<br>$sql_subjects<br>");
					$res_subjects = $mysqli->query($sql_subjects);
					while ($row_subjects = $res_subjects->fetch_assoc()) {
						$subjects .= $row_subjects['name_'.$_POST['lang']].',';
					}
					$subjects = rtrim($subjects,',');
					if ($subjects != "") {
						$gen_name = "$fit ( $subjects )";
					}
					else{
						$gen_name = "$fit";
					}
					$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_finChats['id']);
						$res_num = $mysqli->query($sql_num);
						$num = "";
						if ($res_num->num_rows != 0) {
							$row_num = $res_num->fetch_assoc();
							if ($row_num['COUNT(id)'] != 0) {
								$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_finChats['id'],$row_num['COUNT(id)']);
							}
						}
					$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s $num</p>",$row_finChats['id'],$gen_name);
				}
			}
			else{
				print("<br>reverse<br>");
				exit();
			}
			/* Get chats */
				print(json_encode($result));
			
		}
		if($_POST['flag'] == '44') {
			$id_student = $_POST['id_student'];
			/* At first, lets take manager's id */
			$sql_manager = sprintf("SELECT id_manager FROM os_class_manager WHERE id=(SELECT class FROM os_users WHERE id='%s')",$id_student);
			$res_manager = $mysqli->query($sql_manager);
			$row_manager = $res_manager->fetch_assoc();
			/* At first, lets take manager's id */
			if($row_manager['id_manager'] == 0) {
				/* if 0 - go out */
				exit();
			}
			else{
				/* lets find chat with manager and our student */
				$sql_getChat = sprintf("SELECT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
					WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='2')",$id_student,$row_manager['id_manager']);
				$res_getChat = $mysqli->query($sql_getChat);
				if($res_getChat->num_rows == 0) {
					
					/* if no chats have been found */
					$sql_t_name = sprintf("SELECT CONCAT(surname,' ',name) AS fi FROM os_users WHERE id='%s'",$row_manager['id_manager']);
					$res_t_name = $mysqli->query($sql_t_name);
					$row_t_name = $res_t_name->fetch_assoc();
					$chat_name = "Чат с классным руководителем ".$row_t_name['fi'];
					$sql_create = "INSERT INTO os_chat SELECT MAX(id)+1,'$chat_name',2,0 FROM os_chat";
					$res_create = $mysqli->query($sql_create);
					$sql_getCrChat = "SELECT MAX(id) FROM os_chat WHERE chat_name='$chat_name'";
					$res_getCrChat = $mysqli->query($sql_getCrChat);
					$row_getCrChat = $res_getCrChat->fetch_assoc();
					$sql_insert1 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getCrChat['MAX(id)'],$row_manager['id_manager']);
					$sql_insert2 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getCrChat['MAX(id)'],$id_student);
					$res_insert1 = $mysqli->query($sql_insert1);
					$res_insert2 = $mysqli->query($sql_insert2);
					/* if no chats have been found */
				}
				/* lets find chat with manager and our student */
			}
			/* And now we can take all chats with our managers, and choose only one */
			$sql_getChat = sprintf("SELECT * FROM os_chat WHERE id = (SELECT DISTINCT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
					WHERE a.id_user='%s' AND b.id_user=(SELECT id_manager FROM os_class_manager WHERE id=(SELECT class FROM os_users WHERE id='%s')) 
					AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='2'))",$id_student,$id_student);
			//print("<br>$sql_getChat<br>");
				$res_getChat = $mysqli->query($sql_getChat);
				$row_getChat = $res_getChat->fetch_assoc();
				$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
						$res_num = $mysqli->query($sql_num);
						$num = "";
						if ($res_num->num_rows != 0) {
							$row_num = $res_num->fetch_assoc();
							if ($row_num['COUNT(id)'] != 0) {
								$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
							}
						}
				if($_POST['lang']=='ua') {
					$row_getChat['chat_name'] = str_replace("Чат с классным руководителем", "Чат з класним керівником", $row_getChat['chat_name']);	
				}
				$result = sprintf("<p onclick=\"common_getMessages(%s)\">%s $num</p>",$row_getChat['id'],$row_getChat['chat_name']);
				print(json_encode($result));
			/* And now we can take all chats with our managers, and choose only one */
		}
		if($_POST['flag'] == '45') {
			$id_student = $_POST['id_student'];
			$result = "";
			/* Lets take all chats with our student on chat_type = 3 */
			$sql_getChat = sprintf("SELECT * FROM os_chat WHERE id IN (SELECT DISTINCT id_chat FROM os_chat_users 
				WHERE id_user='%s' AND id_chat IN(SELECT id FROM os_chat WHERE chat_type='3'))",$id_student,$id_student);
			//print($sql_getChat);
				$res_getChat = $mysqli->query($sql_getChat);
				if($res_getChat->num_rows == 0) {
					exit();
				}
				else{
					$result = "";
					while($row_getChat = $res_getChat->fetch_assoc()) {
						$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
						$res_num = $mysqli->query($sql_num);
						$num = "";
						if ($res_num->num_rows != 0) {
							$row_num = $res_num->fetch_assoc();
							if ($row_num['COUNT(id)'] != 0) {
								$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
							}
						}
						$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s $num</p>",$row_getChat['id'],$row_getChat['chat_name']);
					}	
					print(json_encode($result));
				}
			/* Lets take all chats with our student on chat_type = 3 */
		}
		if ($_POST['flag'] == '46') {
			$id_teacher = $_POST['id_teacher'];
			/* Get students id */
			$where = array();
			$where_course = "";
			$student_subjects = "";
			$where_course_subject = sprintf(" AND course = %s", $_SESSION['data']['currentCourse']);
			if($_SESSION['data']['currentCourse'] == 0) {
				$student_subjects = sprintf(" AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')", 
											$id_teacher);
				$where[] = sprintf("id IN(SELECT id_student FROM os_student_subjects WHERE id_subject 
				IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher='%s'))", $id_teacher);
				$where_course = sprintf(" SELECT id FROM os_users 
												 WHERE level = 1 AND id IN(
												 SELECT id_student FROM os_student_subjects
														WHERE id_subject IN(
														SELECT id_s FROM os_teacher_subj 
															   WHERE id_teacher = %s AND course = %s))", 
															   $_SESSION['data']['id'],$_SESSION['data']['currentCourse']);
			} else {
				$where[] = sprintf("id IN(SELECT id_user FROM os_courses_students WHERE id_course = %s)", $_SESSION['data']['currentCourse']);
				$where_course = sprintf("SELECT id_user FROM os_courses_students WHERE id_course = %s", $_SESSION['data']['currentCourse']);
			}
			$where_statement = "";
			foreach($where as $value) {
				$where_statement .= " AND " . $value;
			}
			//print("<br>$where_course<br>");
			/*$sql_students = sprintf("SELECT id FROM os_users WHERE level='1' %s AND class IN(SELECT id_c FROM os_teacher_class WHERE id_teacher='%s' )",
			$where_statement,$id_teacher);
			//print("<br>$sql_students<br>");
			$res_students = $mysqli->query($sql_students);
			if($res_students->num_rows != 0) {
				$students = array();
				while ($row_students = $res_students->fetch_assoc()) {
					$students[] = $row_students['id'];
				}
			}
			else{
				print("<br>reverse<br>");
				exit();
			}*/
			/* Get students id */
			/* Check chats with students array */
			/*$chats = array();
			foreach ($students as $value) {
				$sql_chats = sprintf("SELECT a.id_chat AS ch1, b.id_chat AS ch2 FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
					WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='1' )",$value,$id_teacher);
				//print("<br>$sql_chats<br>");
				$res_chats = $mysqli->query($sql_chats);
				if ($res_chats->num_rows != 0) {
					$row_chats = $res_chats->fetch_assoc();
					$chats[] = $row_chats['ch1'];
				}
				else{
					// Generate new chat 
					//print("<br>reverse<br>");
					$sql_s_name = "SELECT CONCAT(surname,' ',name) AS fi, id FROM os_users WHERE id='$value'";
					$res_s_name = $mysqli->query($sql_s_name);
					$row_s_name = $res_s_name->fetch_assoc();
					$sql_t_name = "SELECT CONCAT(surname,' ',name) AS fi, id FROM os_users WHERE id='$id_teacher'";
					$res_t_name = $mysqli->query($sql_t_name);
					$row_t_name = $res_t_name->fetch_assoc();
					$chat_name = "Чат между учителем " . $row_t_name['fi'] . 
								 "(id {$row_t_name['id']}) и учеником " . $row_s_name['fi'] .
								 "(id {$row_s_name['id']})";
					$sql_create = "INSERT INTO os_chat SELECT MAX(id)+1,'$chat_name',1,0 FROM os_chat";
					$res_create = $mysqli->query($sql_create);
					$sql_getChat = "SELECT id FROM os_chat WHERE chat_name='$chat_name'";
					$res_getChat = $mysqli->query($sql_getChat);
					$row_getChat = $res_getChat->fetch_assoc();
					$sql_insert1 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$value);
					$sql_insert2 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$id_teacher);
					$res_insert1 = $mysqli->query($sql_insert1);
					$res_insert2 = $mysqli->query($sql_insert2);
					// Generate new chat
				}
			}*/
			/* Check chats with students array */
			/* Get chats */
			$result = "";
			$sql_classes = sprintf("SELECT * FROM os_teacher_class WHERE id_teacher=$id_teacher");
			//print("<br>$sql_classes<br>");
			$res_classes = $mysqli->query($sql_classes);
			if($res_classes->num_rows != 0) {
				while($row_classes = $res_classes->fetch_assoc()) {
					$sql_class = sprintf("SELECT * FROM os_class_manager WHERE id='%s'",$row_classes['id_c']);
					$res_class = $mysqli->query($sql_class);
					$row_class = $res_class->fetch_assoc();
					//print("<br>$sql_class<br>");
					$result .= sprintf("<p class='cat_small_hat'>%s</p>",$row_class['class_name']);
					$sql_finChats = sprintf("SELECT * FROM os_chat 
											  WHERE chat_type='1' 
											    AND id 
											     IN ( SELECT DISTINCT id_chat FROM os_chat_users as parent 
											     				WHERE parent.id_chat 
											     				   IN ( SELECT DISTINCT id_chat FROM os_chat_users 
											     				   				  WHERE id_user='%s' 
											     				   				    AND id_chat=parent.id_chat) 
											     				  AND parent.id_chat 
											     				   IN ( SELECT DISTINCT id FROM os_chat 
											     				   				  WHERE chat_type='1') 
											     				  AND parent.id_chat 
											     				   IN ( SELECT DISTINCT child.id_chat FROM os_chat_users as child 
											     				   				  WHERE parent.id_user 
											     				   				     IN ($where_course) 
											     				   				    AND parent.id_user 
											     				   				     IN ( SELECT id FROM os_users 
											     				   				     	   WHERE class = %s)))", $id_teacher, $row_classes['id_c']);
					/*$sql_finChats = sprintf("SELECT * FROM os_chat WHERE chat_type='1' AND id IN(SELECT a.id_chat AS ch1
						FROM os_chat_users AS a JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
						WHERE b.id_user='%s' AND a.id_user IN(SELECT id FROM os_users WHERE level=1 AND class='%s'))",$id_teacher,$row_classes['id_c']);*/
					//print("<br>$sql_finChats<br>");
					$res_finChats = $mysqli->query($sql_finChats);
					if ($res_finChats->num_rows!=0) {
						while ($row_finChats = $res_finChats->fetch_assoc()) {
							$sql_p_data = sprintf("SELECT DISTINCT * FROM os_users WHERE id IN(SELECT DISTINCT id_user FROM os_chat_users WHERE id_chat='%s' AND id_user NOT IN(%s) 
								AND id_chat IN (SELECT id FROM os_chat WHERE chat_type=1))",
								$row_finChats['id'],$id_teacher);
							//print($sql_p_data);
							$res_p_data = $mysqli->query($sql_p_data);
							$row_p_data = $res_p_data->fetch_assoc();
							$fip = $row_p_data['surname'].' '.$row_p_data['name'];
							$subjects = "";
							$sql_subjects = sprintf("SELECT DISTINCT * FROM os_subjects WHERE 
								id IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher='%s' $where_course_subject)",
								$id_teacher);
							if($_SESSION['data']['currentCourse'] == 0) {
								$sql_subjects .= sprintf("AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",
									$row_p_data['id']);
							}
							//print("<br>$sql_subjects<br>");
							$res_subjects = $mysqli->query($sql_subjects);
							while ($row_subjects = $res_subjects->fetch_assoc()) {
								$subjects .= $row_subjects['name_'.$_POST['lang']].',';
							}
							$subjects = rtrim($subjects,',');
							if ($subjects != "") {
								$gen_name = "$fip ( $subjects )";
							}
							else{
								$gen_name = "$fip";
							}
							
							$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_finChats['id']);
								$res_num = $mysqli->query($sql_num);
								$num = "";
								if ($res_num->num_rows != 0) {
									$row_num = $res_num->fetch_assoc();
									if ($row_num['COUNT(id)'] != 0) {
										$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_finChats['id'],$row_num['COUNT(id)']);
									}
								}
							$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s $num</p>",$row_finChats['id'],$gen_name);
						}
					}
				}
			}
			else{
				print("<br>reverse<br>");
				exit();
			}
			/* Get chats */
				print(json_encode($result));
		}
		if($_POST['flag'] == '47') {
			$id_teacher = $_POST['id_teacher'];
			$result = "";
			/* Lets take all chats with our teacher on chat_type = 3 */
			$sql_getChat = sprintf("SELECT DISTINCT * FROM os_chat WHERE id 
				IN(SELECT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
				WHERE a.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='3')
				AND b.id_user IN(SELECT id FROM os_users WHERE level IN(%s)))",$id_teacher,$_POST['level_access']);
			//print("<br>$sql_getChat<br>");
				$res_getChat = $mysqli->query($sql_getChat);
				if($res_getChat->num_rows == 0) {
					exit();
				}
				else{
					while($row_getChat = $res_getChat->fetch_assoc()) {
						$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
							$res_num = $mysqli->query($sql_num);
							$num = "";
							if ($res_num->num_rows != 0) {
								$row_num = $res_num->fetch_assoc();
								if ($row_num['COUNT(id)'] != 0) {
									$num = sprintf("<span id='%s'>%s $num</span>",$row_getChat['id'],$row_num['COUNT(id)']);
								}
							}
						$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s</p>",$row_getChat['id'],$row_getChat['chat_name']);
					}
					print(json_encode($result));
				}
			/* Lets take all chats with our teacher on chat_type = 3 */
		}
		if($_POST['flag'] == '48') {
			$id_manager = $_POST['id_manager'];
			/* At first, lets take manager's classes */
			$sql_classes = sprintf("SELECT * FROM os_class_manager WHERE id_manager='%s'",$id_manager);
			$res_classes = $mysqli->query($sql_classes);
			/* At first, lets take manager's classes */
			if($res_classes->num_rows == 0) {
			
			
			
				exit();
			}
			else{
				/* lets find chat with our manager and students */
				while($row_classes = $res_classes->fetch_assoc()) {
					$sql_students = sprintf("SELECT * FROM os_users WHERE level='1' AND class='%s'",$row_classes['id']);
					$res_students = $mysqli->query($sql_students);
					if ($res_students->num_rows != 0) {
						while ($row_students = $res_students->fetch_assoc()) {
							$sql_getChat = sprintf("SELECT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
								WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='2')",$id_manager,$row_students['id']);
							$res_getChat = $mysqli->query($sql_getChat);
							if($res_getChat->num_rows == 0) {
								/* if no chats have been found */
								$sql_t_name = sprintf("SELECT CONCAT(surname,' ',name) AS fi FROM os_users WHERE id='%s'",$id_manager);
								$res_t_name = $mysqli->query($sql_t_name);
								$row_t_name = $res_t_name->fetch_assoc();
								$chat_name = "Чат с классным руководителем ".$row_t_name['fi'];
								$sql_create = "INSERT INTO os_chat SELECT MAX(id)+1,'$chat_name',2,0 FROM os_chat";
								$res_create = $mysqli->query($sql_create);
								$sql_getCrChat = "SELECT MAX(id) FROM os_chat WHERE chat_name='$chat_name'";
								$res_getCrChat = $mysqli->query($sql_getCrChat);
								$row_getCrChat = $res_getCrChat->fetch_assoc();
								$sql_insert1 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getCrChat['MAX(id)'],$row_students['id']);
								$sql_insert2 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getCrChat['MAX(id)'],$id_manager);
								$res_insert1 = $mysqli->query($sql_insert1);
								$res_insert2 = $mysqli->query($sql_insert2);
							/* if no chats have been found */
							}
						}
					}
				}
				/* lets find chat with our manager and students */
			}
			/* And now we can take all chats with our manager */
			$sql_classes = sprintf("SELECT * FROM os_class_manager WHERE id_manager='%s'",$id_manager);
			//print("<br>$sql_classes<br>");
			$res_classes = $mysqli->query($sql_classes);
			$result = "";
			/* At first, lets take manager's classes */
			if($res_classes->num_rows == 0) {
				exit();
			}
			else{
				while($row_classes = $res_classes->fetch_assoc()) {
					$result .= sprintf("<p class='cat_hat'>Класс '%s'</p>",$row_classes['class_name']);
					//print("<br>$result<br>");
					$sql_students = sprintf("SELECT * FROM os_users WHERE level='1' AND class='%s'",$row_classes['id']);
					$res_students = $mysqli->query($sql_students);
					if ($res_students->num_rows != 0) {
						while ($row_students = $res_students->fetch_assoc()) {
							$sql_getChat = sprintf("SELECT * FROM os_chat WHERE id = (SELECT DISTINCT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
								WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='2'))",
								$id_manager,$row_students['id']);
							//print("<br>$sql_getChat<br>");
							$res_getChat = $mysqli->query($sql_getChat);
							$row_getChat = $res_getChat->fetch_assoc();
							$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
							$res_num = $mysqli->query($sql_num);
							$num = "";
							if ($res_num->num_rows != 0) {
								$row_num = $res_num->fetch_assoc();
								if ($row_num['COUNT(id)'] != 0) {
									$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
								}
							}
							$lock_stat = "";
							if ($row_getChat['locked']==1) {
								$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok2.png\"></div>",$row_getChat['id']);
							}
							if ($row_getChat['locked']==0) {
								$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok1.png\"></div>",$row_getChat['id']);
							}
							$result .= sprintf("<p onclick=\"common_getMessages(%s)\">Чат с учеником %s %s</p> $lock_stat $num",$row_getChat['id'],$row_students['surname'],$row_students['name']);
						}
					}
				}
			}
			
				print(json_encode($result));
			/* And now we can take all chats with our manager */
		}
		if ($_POST['flag'] == '49') {
			$sql_classes = "SELECT * FROM os_class_manager";
			//print("<br>$sql_classes<br>");
			$res_classes = $mysqli->query($sql_classes);
			$result = "";
			/* At first, lets take manager's classes */
			if($res_classes->num_rows == 0) {
				exit();
			}
			else{
				while($row_classes = $res_classes->fetch_assoc()) {
					$result .= sprintf("<p class='cat_hat'>Класс '%s'</p>",$row_classes['class_name']);
					/* And now we can take all chats with our managers, and choose only one */
					$sql_getChat = sprintf("SELECT DISTINCT * FROM os_chat WHERE id 
						IN(SELECT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
						WHERE a.id_user IN(SELECT id FROM os_users WHERE level='1' AND class='%s') AND b.id_user IN(SELECT id FROM os_users WHERE level='2') 
						AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='1'))",$row_classes['id']);
					//print("<br>$sql_getChat<br>");
					$res_getChat = $mysqli->query($sql_getChat);
					while($row_getChat = $res_getChat->fetch_assoc()) {
						$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
						$res_num = $mysqli->query($sql_num);
						$num = "";
						if ($res_num->num_rows != 0) {
							$row_num = $res_num->fetch_assoc();
							if ($row_num['COUNT(id)'] != 0) {
								$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
							}
						}
						$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s $num</p>",$row_getChat['id'],$row_getChat['chat_name']);
					}
					/* And now we can take all chats with our managers, and choose only one */
				}
			}
				print(json_encode($result));
				
		}
		if ($_POST['flag'] == '50') {
			$result = "";
			$id_manager = $_POST['id_manager'];
			/* And now we can take all chats with our managers, and choose only one */
			$sql_getChat = sprintf("SELECT DISTINCT * FROM os_chat WHERE id 
				IN(SELECT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
				WHERE a.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='3')
				AND b.id_user IN(SELECT id FROM os_users WHERE level IN(%s)))",$id_manager,$_POST['level_access']);
			//print("<br>$sql_getChat<br>");
			$res_getChat = $mysqli->query($sql_getChat);
			if ($res_getChat->num_rows == 0) {
				exit();
			}
			while($row_getChat = $res_getChat->fetch_assoc()) {
				$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
						$res_num = $mysqli->query($sql_num);
						$num = "";
						if ($res_num->num_rows != 0) {
							$row_num = $res_num->fetch_assoc();
							if ($row_num['COUNT(id)'] != 0) {
								$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
							}
						}
				$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s $num</p>",$row_getChat['id'],$row_getChat['chat_name']);
			}
			print(json_encode($result));
			/* And now we can take all chats with our managers, and choose only one */
		}
		if ($_POST['flag'] == '51') {
			$result = "";
			$id_manager = $_POST['id_manager'];
			/* And now we can take all chats with our managers, and choose only one */
			$sql_getChat = sprintf("SELECT DISTINCT * FROM os_chat WHERE id NOT 
				IN(SELECT id_chat FROM os_chat_users WHERE id_user='%s') AND chat_type='3' AND id 
				IN(SELECT id_chat FROM os_chat_users WHERE id_user IN(SELECT id FROM os_users WHERE level IN(%s)))",
				$id_manager,$_POST['level_access']);
			//print("<br>$sql_getChat<br>");
				$res_getChat = $mysqli->query($sql_getChat);
				if($res_getChat->num_rows!=0) {
					while($row_getChat = $res_getChat->fetch_assoc()) {
						$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
						$res_num = $mysqli->query($sql_num);
						$num = "";
						if ($res_num->num_rows != 0) {
							$row_num = $res_num->fetch_assoc();
							if ($row_num['COUNT(id)'] != 0) {
								$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
							}
						}
						
						$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s</p> $num",$row_getChat['id'],$row_getChat['chat_name']);
					}
				}
				print(json_encode($result));
		}
		if ($_POST['flag'] == '52') {
			$result = "";
			$id_admin = $_POST['id_admin'];
			/* Lets get all managers */
			if($_POST['level_access'] == '1,2,3,4' || $_POST['level_access'] == '1' || $_POST['level_access'] == '3') {
				$sql_manager = "SELECT * FROM os_users WHERE level=3";
				$res_manager = $mysqli->query($sql_manager);
				$result .= "<p class='cat_super_hat'>Чаты учеников с классными руководителями:</p>";
				if ($res_manager->num_rows != 0) {
					/* Now lets get their chats */
					while ($row_manager = $res_manager->fetch_assoc()) {
						$result .= sprintf("<p class='cat_hat'>Куратор: %s %s</p>",$row_manager['surname'],$row_manager['name']);
						$sql_classes = sprintf("SELECT * FROM os_class_manager WHERE id_manager='%s'",$row_manager['id']);
						if ($_POST['id_class'] != null && $_POST['id_class'] != "" && $_POST['id_class'] != 0 && ($_POST['level_access']=='1' || $_POST['level_access'] == '1,2,3,4')) {
							if ($_POST['level_access'] == '1,2,3,4') {
								$str_class = sprintf(" AND class IN(0,%s)",$_POST['id_class']);
							}
							else{
								$str_class = sprintf(" AND class IN(%s)",$_POST['id_class']);
							}
							
						}
						else{
							$str_class = "";
						}
						//print("<br>$sql_classes<br>");
						$res_classes = $mysqli->query($sql_classes);
						/* At first, lets take manager's classes */
						if($res_classes->num_rows != 0) {
							while($row_classes = $res_classes->fetch_assoc()) {
								$result .= sprintf("<p class='cat_small_hat'>Класс '%s'</p>",$row_classes['class_name']);
								//print("<br>$result<br>");
								$sql_students = sprintf("SELECT * FROM os_users WHERE level='1' AND class='%s'",$row_classes['id']);
								if($_POST["name"] != "") {
									$sql_students .= sprintf(" AND CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%'",$_POST['name']);
								}
								else if ($_POST['id_subj'] != null && $_POST['id_subj'] != "" && $_POST['id_subj'] != 0) {
									$sql_students .= sprintf(" AND id IN(SELECT id_student FROM os_student_subjects WHERE id_subject IN(%s))",$_POST['id_subj']);
								}
								$res_students = $mysqli->query($sql_students);
								if ($res_students->num_rows != 0) {
									while ($row_students = $res_students->fetch_assoc()) {
										$sql_getChat = sprintf("SELECT * FROM os_chat 
											WHERE id = (SELECT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
											WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='2'))",
											$row_manager['id'],$row_students['id']);
										$res_getChat = $mysqli->query($sql_getChat);
										if($res_getChat->num_rows!=0) {
											$row_getChat = $res_getChat->fetch_assoc();
											$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
												//print("<br>$sql_num<br>");
												$res_num = $mysqli->query($sql_num);
												$num = "";
												if ($res_num->num_rows != 0) {
													$row_num = $res_num->fetch_assoc();
													if ($row_num['COUNT(id)'] != 0) {
														//print("<br>aa<br>");
														$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
													}
												}
												$lock_stat = "";
												if ($row_getChat['locked']==1) {
													$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok2.png\"></div>",$row_getChat['id']);
												}
												if ($row_getChat['locked']==0) {
													$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok1.png\"></div>",$row_getChat['id']);
												}
												//print("<br>$num<br>");
											$result .= sprintf("<p onclick=\"common_getMessages(%s)\">Чат с учеником %s %s</p> $lock_stat $num",
												$row_getChat['id'],$row_students['surname'],$row_students['name']);
											//print("<br>$result<br>");
										}
									}
								}
							}
						}
					}
					/* Now lets get their chats */
				}
			}
			/* Lets get all managers */
			if($_POST['level_access'] == '1,2,3,4' || $_POST['level_access'] == '1' || $_POST['level_access'] == '2') {
				$result .= "<p class='cat_super_hat'>Чаты учеников с учителями:</p>";
				$sql_class = "SELECT * FROM os_class_manager";
				if ($_POST['id_class'] != null && $_POST['id_class'] != "" && $_POST['id_class'] != 0 && ($_POST['level_access']=='1' || $_POST['level_access'] == '1,2,3,4')) {
					if ($_POST['level_access'] == '1,2,3,4') {
						$str_class = sprintf(" AND class IN(0,%s)",$_POST['id_class']);
					}
					else{
						$str_class = sprintf(" AND class IN(%s)",$_POST['id_class']);
					}
					
				}
				else{
					$str_class = "";
				}
				if ($_POST['id_subj'] != null && $_POST['id_subj'] != "" && $_POST['id_subj'] != 0 && ($_POST['level_access']=='1' || $_POST['level_access'] == '1,2,3,4')) {
					$str_subj .= sprintf(" AND id IN(SELECT id_student FROM os_student_subjects WHERE id_subject IN(%s))",$_POST['id_subj']);
				}
				else{
					$str_subj = "";
				}
				if ($_POST["name"] != "") {
					$str_subj = sprintf(" AND CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%'",$_POST['name']);
				}
				$res_class = $mysqli->query($sql_class);
				if($res_class->num_rows != 0) {
					while ($row_class = $res_class->fetch_assoc()) {
						$sql_chats_common = sprintf("SELECT * FROM os_chat WHERE chat_type=1 AND id 
							IN(SELECT id_chat FROM os_chat_users WHERE id_user 
							IN(SELECT id FROM os_users WHERE level=1 AND class=%s $str_subj ORDER BY class))",$row_class['id']);
						//print("<br>$sql_chats_common<br>");
						$res_chats_common = $mysqli->query($sql_chats_common);
						if($res_chats_common->num_rows != 0) {
							$result .= sprintf("<p class='cat_small_hat'>Класс %s</p>",$row_class['class_name']);
							while ($row_chats_common = $res_chats_common->fetch_assoc()) {
								$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_chats_common['id']);
								$res_num = $mysqli->query($sql_num);
								$num = "";
								if ($res_num->num_rows != 0) {
									$row_num = $res_num->fetch_assoc();
									if ($row_num['COUNT(id)'] != 0) {
										$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_chats_common['id'],$row_num['COUNT(id)']);
									}
								}
								$lock_stat = "";
								if ($row_chats_common['locked']==1) {
									$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok2.png\"></div>",
										$row_chats_common['id']);
								}
								if ($row_chats_common['locked']==0) {
									$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok1.png\"></div>",
										$row_chats_common['id']);
								}
								$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s</p> $lock_stat $num",$row_chats_common['id'],$row_chats_common['chat_name']);
							}
						}
					}
				}
			}
			if ($_POST['id_class'] != null && $_POST['id_class'] != "" && $_POST['id_class'] != 0 && ($_POST['level_access']=='1' || $_POST['level_access'] == '1,2,3,4')) {
				if ($_POST['level_access'] == '1,2,3,4') {
					$str_class = sprintf(" AND class IN(0,%s)",$_POST['id_class']);
				}
				else{
					$str_class = sprintf(" AND class IN(%s)",$_POST['id_class']);
				}
				
			}
			else{
				$str_class = "";
			}
			if ($_POST["name"] != "") {
				$str_subj = sprintf(" AND CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%'",$_POST['name']);
			}
			$sql_getChat = sprintf("SELECT DISTINCT * FROM os_chat WHERE id NOT 
				IN(SELECT id_chat FROM os_chat_users WHERE id_user='%s') AND chat_type='3' AND id 
				IN(SELECT id_chat FROM os_chat_users WHERE id_user IN(SELECT id FROM os_users WHERE level IN(%s)$str_class))",
				$id_admin,$_POST['level_access']);
			//print("<br>$sql_getChat<br>");
				$res_getChat = $mysqli->query($sql_getChat);
				if($res_getChat->num_rows!=0) {
					$result .= "<p class='cat_super_hat'>Другие созданные чаты:</p>";
					while($row_getChat = $res_getChat->fetch_assoc()) {
						$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
						$res_num = $mysqli->query($sql_num);
						$num = "";
						if ($res_num->num_rows != 0) {
							$row_num = $res_num->fetch_assoc();
							if ($row_num['COUNT(id)'] != 0) {
								$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
							}
						}
						$lock_stat = "";
						if ($row_getChat['locked']==1) {
							$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok2.png\"></div>",$row_getChat['id']);
						}
						if ($row_getChat['locked']==0) {
							$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok1.png\"></div>",$row_getChat['id']);
						}
						$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s</p> $lock_stat $num",$row_getChat['id'],$row_getChat['chat_name']);
					}
				}
			print(json_encode($result));
		}
		if ($_POST['flag'] == '54') {
			
			$id_student = $_POST['id_student'];
			$id_lesson = $_POST['id_lesson'];
			$lang = $_POST['lang'];
			/* Lets take teacher's id of this lesson */
			$sql_teacher = sprintf("SELECT * FROM os_lessons WHERE id='%s'",$id_lesson);
			//print("<br>$sql_teacher<br>");
			$res_teacher = $mysqli->query($sql_teacher);
			if($res_teacher->num_rows != 0) {
				$row_teacher = $res_teacher->fetch_assoc();
				$id_teacher = $row_teacher['teacher_'.$lang];
				if ($id_teacher == 0) {
					exit();
				}
			} else {
				exit();
			}
			/* Lets take teacher's id of this lesson */
			/* Lets take chat with this teacher and our student */
				$sql_chats = sprintf("SELECT DISTINCT a.id_chat AS ch1 FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
					WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='1')",$id_teacher,$id_student);
				//print("<br>$sql_chats<br>");
				$res_chats = $mysqli->query($sql_chats);
				//var_dump($res_chats);
				//print("<br>".$res_chats->num_rows."<br>");
				if ($res_chats->num_rows == 0) {
					/* Generate new chat */
					//print("<br>reverse<br>");
					$sql_t_name = "SELECT CONCAT(surname,' ',name) AS fi,avatar FROM os_users WHERE id=$id_teacher";
					$res_t_name = $mysqli->query($sql_t_name);
					$row_t_name = $res_t_name->fetch_assoc();
					$sql_s_name = "SELECT CONCAT(surname,' ',name) AS fi FROM os_users WHERE id=$id_student";
					$res_s_name = $mysqli->query($sql_s_name);
					$row_s_name = $res_s_name->fetch_assoc();
					$chat_name = "Чат между учителем ".$row_t_name['fi']." и учеником ".$row_s_name['fi'];
					$sql_create = "INSERT INTO os_chat SELECT MAX(id)+1,'$chat_name',1,0 FROM os_chat";
					$res_create = $mysqli->query($sql_create);
					$sql_getChat = "SELECT id FROM os_chat WHERE chat_name='$chat_name'";
					$res_getChat = $mysqli->query($sql_getChat);
					$row_getChat = $res_getChat->fetch_assoc();
					$sql_insert1 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$id_teacher);
					$sql_insert2 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$id_student);
					$res_insert1 = $mysqli->query($sql_insert1);
					$res_insert2 = $mysqli->query($sql_insert2);
					/* Generate new chat */
				}
			/* Lets take chat with this teacher and our student */
			$sql_chats = sprintf("SELECT * FROM os_chat WHERE id = (SELECT DISTINCT a.id_chat AS ch1 FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
					WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='1'))",$id_teacher,$id_student);
			//print("<br>$sql_chats<br>");
			$res_chats = $mysqli->query($sql_chats);
			$row_chats = $res_chats->fetch_assoc();
			$sql_t_name = "SELECT CONCAT(surname,' ',name) AS fi,avatar FROM os_users WHERE id=$id_teacher";
			$res_t_name = $mysqli->query($sql_t_name);
			$row_t_name = $res_t_name->fetch_assoc();
			$result = array(
				"id" => $row_chats['id'],
				"chat_name" => $row_chats['chat_name']
				);
			$avatar = "../upload/avatars/";
			if (@$row_t_name['avatar'] == "") {
				$avatar .= "default.jpg";
			}
			else{
				$avatar .= @$row_t_name['avatar'];
			}
			$result['avatar'] = $avatar;
			$result['fi'] = $row_t_name['fi'];
				print(json_encode($result));
		}
		if ($_POST['flag'] == '55') {
			
			$id_teacher = $_POST['id_teacher'];
			$id_lesson = $_POST['id_lesson'];
			$lang = $_POST['lang'];
			/* Lets take students's id of this lesson */
			$sql_students = sprintf("SELECT * FROM os_users WHERE level=1 AND class = (SELECT id_class FROM os_lesson_classes WHERE id_lesson='%s') AND id 
				IN(SELECT id_student FROM os_student_subjects WHERE id_subject = (SELECT subject FROM os_lessons WHERE id='%s'))",
				$id_lesson,$id_lesson);
			//print("<br>$sql_students<br>");
			$res_students = $mysqli->query($sql_students);
			
			if ($res_students->num_rows == 0) {
				exit();
			}
			/* Lets take students's id of this lesson */
			/* Lets take chat with our teacher and this students */
			while ($row_students = $res_students->fetch_assoc()) {
				$sql_chats = sprintf("SELECT DISTINCT a.id_chat AS ch1 FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
					WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='1')",$id_teacher,$row_students['id']);
				//print("<br>$sql_chats<br>");
				$res_chats = $mysqli->query($sql_chats);
				//var_dump($res_chats);
				//print("<br>".$res_chats->num_rows."<br>");
				if ($res_chats->num_rows == 0) {
					/* Generate new chat */
					//print("<br>reverse<br>");
					$sql_t_name = "SELECT CONCAT(surname,' ',name) AS fi FROM os_users WHERE id=$id_teacher";
					$res_t_name = $mysqli->query($sql_t_name);
					$row_t_name = $res_t_name->fetch_assoc();
					$sql_s_name = "SELECT CONCAT(surname,' ',name) AS fi FROM os_users WHERE id='".$row_students['id']."'";
					$res_s_name = $mysqli->query($sql_s_name);
					$row_s_name = $res_s_name->fetch_assoc();
					$chat_name = "Чат между учителем ".$row_t_name['fi']." и учеником ".$row_s_name['fi'];
					$sql_create = "INSERT INTO os_chat SELECT MAX(id)+1,'$chat_name',1,0 FROM os_chat";
					//print("<br>$sql_create<br>");
					$res_create = $mysqli->query($sql_create);
					$sql_getChat = "SELECT id FROM os_chat WHERE chat_name='$chat_name'";
					//print("<br>$sql_getChat<br>");
					$res_getChat = $mysqli->query($sql_getChat);
					$row_getChat = $res_getChat->fetch_assoc();
					$sql_insert1 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$id_teacher);
					//print("<br>$sql_insert1<br>");
					$sql_insert2 = sprintf("INSERT INTO os_chat_users(id_chat,id_user) VALUES('%s','%s')",$row_getChat['id'],$row_students['id']);
					//print("<br>$sql_insert2<br>");
					$res_insert1 = $mysqli->query($sql_insert1);
					$res_insert2 = $mysqli->query($sql_insert2);
					/* Generate new chat */
				}
			}
			/* Lets take chat with this teacher and our student */
			
			$result = "";
			$sql_students = sprintf("SELECT * FROM os_users WHERE level=1 AND class = (SELECT id_class FROM os_lesson_classes WHERE id_lesson='%s') AND id 
				IN(SELECT id_student FROM os_student_subjects WHERE id_subject = (SELECT subject FROM os_lessons WHERE id='%s'))",
				$id_lesson,$id_lesson);
			$res_students = $mysqli->query($sql_students);
			while ($row_students = $res_students->fetch_assoc()) {
				$sql_chats = sprintf("SELECT * FROM os_chat WHERE id = (SELECT DISTINCT a.id_chat AS ch1 FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
					WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='1'))",$id_teacher,$row_students['id']);
				//print("<br>$sql_chats<br>");
				$res_chats = $mysqli->query($sql_chats);
				if($res_chats->num_rows == 0) continue;
				$row_chats = $res_chats->fetch_assoc();
				$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_chats['id']);
				//print("<br>$sql_num<br>");
				$res_num = $mysqli->query($sql_num);
				$num = "";
				if ($res_num->num_rows != 0) {
					$row_num = $res_num->fetch_assoc();
					if ($row_num['COUNT(id)'] != 0) {
						$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_chats['id'],$row_num['COUNT(id)']);
					}
				}
				if($row_students['avatar'] != "" && $row_students['avatar'] != "Array")
					$result .= sprintf("<p><img src='../upload/avatars/%s' width='40px' height='40px'>
						<a class='link1' href='#' name='%s' onclick=\"lesson_getSoloChatWithStudent_teacher('%s')\">%s %s %s</a> $num</p>",
						$row_students['avatar'],$row_chats['id'],$row_chats['id'],$row_students['surname'],$row_students['name'],$row_students['patronymic']);
				else
					$result .= sprintf("<p><img src='../upload/avatars/default.jpg' width='40px' height='40px'>
						<a class='link1' href='#' name='%s' onclick=\"lesson_getSoloChatWithStudent_teacher('%s')\">%s %s %s</a> $num</p>",
						$row_chats['id'],$row_chats['id'],$row_students['surname'],$row_students['name'],$row_students['patronymic']);
			}
				print(json_encode($result));
		}
		if($_POST['flag'] == '56') {
			$id_chat = $_POST['id_chat'];
			$sql_student = sprintf("SELECT * FROM os_users WHERE id = (SELECT id_user FROM os_chat_users WHERE id_chat='%s' AND id_user IN(SELECT id FROM os_users WHERE level = 1))",$id_chat);
			//print("<br>$sql_student<br>");
			$res_student = $mysqli->query($sql_student);
			$row_student = $res_student->fetch_assoc();
			$result = array(
				'fio' => $row_student['surname'].' '.$row_student['name'].' '.$row_student['patronymic']
			);
			$avatar = "../upload/avatars/";
			if ($row_student['avatar'] == "") {
				$avatar .= "default.jpg";
			}
			else{
				$avatar .= $row_student['avatar'];
			}
			$result['avatar'] = $avatar;
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '63') {
			$result = "";
			$id_admin = $_POST['id_admin'];
			if ($_POST['id_class'] != null && $_POST['id_class'] != "" && $_POST['id_class'] != 0 && ($_POST['level_access']=='1' || $_POST['level_access'] == '1,2,3,4')) {
				if ($_POST['level_access'] == '1,2,3,4') {
					$str_class = sprintf(" AND class IN(0,%s)",$_POST['id_class']);
				}
				else{
					$str_class = sprintf(" AND class IN(%s)",$_POST['id_class']);
				}
				
			}
			else{
				$str_class = "";
			}
			if ($_POST["name"] != "") {
				$str_class = sprintf(" AND CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%'",$_POST['name']);
			}
			/* And now we can take all chats with our managers, and choose only one */
			$sql_getChat = sprintf("SELECT DISTINCT * FROM os_chat WHERE id 
				IN(SELECT a.id_chat FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat = b.id_chat
				WHERE a.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='3')
				AND b.id_user IN(SELECT id FROM os_users WHERE level IN(%s)$str_class))",$id_admin,$_POST['level_access']);
			//print("<br>$sql_getChat<br>");
			$res_getChat = $mysqli->query($sql_getChat);
			if ($res_getChat->num_rows == 0) {
				exit();
			}
			while($row_getChat = $res_getChat->fetch_assoc()) {
				$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
				$res_num = $mysqli->query($sql_num);
				$num = "";
				if ($res_num->num_rows != 0) {
					$row_num = $res_num->fetch_assoc();
					if ($row_num['COUNT(id)'] != 0) {
						$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
					}
				}
				$lock_stat = "";
				if ($row_getChat['locked']==1) {
					$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok2.png\"></div><div onclick=\"delete_chat(%s)\" class=\"xx_unlock\"><img src=\"/tpl_img/xx.png\"></div>",
						$row_getChat['id'],$row_getChat['id']);
				}
				if ($row_getChat['locked']==0) {
					$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok1.png\"></div><div onclick=\"delete_chat(%s)\" class=\"xx_unlock\"><img src=\"/tpl_img/xx.png\"></div>",
						$row_getChat['id'],$row_getChat['id']);
				}
				//print("<br>$lock_stat<br>");
				$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s</p> $lock_stat $num",$row_getChat['id'],$row_getChat['chat_name']);
			}
			print(json_encode($result));
			/* And now we can take all chats with our managers, and choose only one */
		}
		if($_POST['flag'] == '65') {
			$result = "";
			$id_admin = $_POST['id_admin'];
			if ($_POST['id_class'] != null && $_POST['id_class'] != "" && $_POST['id_class'] != 0 && ($_POST['level_access']=='1' || $_POST['level_access'] == '1,2,3,4')) {
				if($_POST['level_access'] == '1,2,3,4') {
					$str_class = sprintf(" AND class IN(0,%s)",$_POST['id_class']);
				}
				else{
					$str_class = sprintf(" AND class IN(%s)",$_POST['id_class']);
				}
			}
			else{
				$str_class = "";
			}
			if ($_POST["name"] != "") {
				$str_class = sprintf(" AND CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%'",$_POST['name']);
			}
			/* And now we can take all chats with our managers, and choose only one */
			$sql_getChat = sprintf("SELECT DISTINCT * FROM os_chat WHERE id NOT 
				IN(SELECT id_chat FROM os_chat_users WHERE id_user='%s') AND chat_type='3' AND id 
				IN(SELECT id_chat FROM os_chat_users WHERE id_user IN(SELECT id FROM os_users WHERE level IN(%s)$str_class))",$id_admin,$_POST['level_access']);
			//print("<br>$sql_getChat<br>");
			$res_getChat = $mysqli->query($sql_getChat);
			if ($res_getChat->num_rows == 0) {
				exit();
			}
			while($row_getChat = $res_getChat->fetch_assoc()) {
				$sql_num = sprintf("SELECT COUNT(id) FROM os_chat_messages WHERE id_chat='%s' AND read_status='1'",$row_getChat['id']);
				$res_num = $mysqli->query($sql_num);
				$num = "";
				if ($res_num->num_rows != 0) {
					$row_num = $res_num->fetch_assoc();
					if ($row_num['COUNT(id)'] != 0) {
						$num = sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row_getChat['id'],$row_num['COUNT(id)']);
					}
				}
				$lock_stat = "";
				if ($row_getChat['locked']==1) {
					$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok2.png\"></div><div onclick=\"delete_chat(%s)\" class=\"xx_unlock\"><img src=\"/tpl_img/xx.png\"></div>",
						$row_getChat['id'],$row_getChat['id']);
				}
				if ($row_getChat['locked']==0) {
					$lock_stat .= sprintf("<div class=\"lock_unlock\" onclick=\"lock_unlock(%s)\"><img src=\"/tpl_img/zamok1.png\"></div><div onclick=\"delete_chat(%s)\" class=\"xx_unlock\"><img src=\"/tpl_img/xx.png\"></div>",
						$row_getChat['id'],$row_getChat['id']);
				}
				//print("<br>$lock_stat<br>");
				$result .= sprintf("<p onclick=\"common_getMessages(%s)\">%s</p> $lock_stat $num",$row_getChat['id'],$row_getChat['chat_name']);
			}
			print(json_encode($result));
			/* And now we can take all chats with our managers, and choose only one */
		}
		if($_POST['flag'] == '64') {
			
			/* Just lets change lock_status */
			$sql = sprintf("SELECT * FROM os_chat WHERE id='%s'",$_POST['id_chat']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			if ($res->num_rows != 0) {
				$row = $res->fetch_assoc();
				if ($row['locked'] == 0) {
					$new_loc = 1;
				}
				else{
					$new_loc = 0;
				}
				$sql = sprintf("UPDATE os_chat SET locked='%s' WHERE id='%s'",$new_loc,$_POST['id_chat']);
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
			}
			exit();
			
			/* Just lets change lock_status */
		}
		if($_POST['flag'] == '68') {
			
			/* Just lets change lock_status */
			$sql = sprintf("DELETE FROM os_chat WHERE id='%s'",$_POST['id_chat']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			/* Just lets change lock_status */
			$sql = sprintf("DELETE FROM os_chat_messages WHERE id_chat='%s'",$_POST['id_chat']);
			$res = $mysqli->query($sql);
			$sql = sprintf("DELETE FROM os_chat_users WHERE id_chat='%s'",$_POST['id_chat']);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '69') {
			$result = "";
			$id_admin = $_POST['id_admin'];
			$result = "";
			if ($_POST['id_class'] != null && $_POST['id_class'] != "" && $_POST['id_class'] != 0 && ($_POST['level_access']=='1' || $_POST['level_access'] == '1,2,3,4')) {
				if($_POST['level_access'] == '1,2,3,4') {
					$str_class = sprintf(" AND class IN(0,%s)",$_POST['id_class']);
				}
				else{
					$str_class = sprintf(" AND class IN(%s)",$_POST['id_class']);
				}
			}
			else{
				$str_class = "";
			}
			if ($_POST["name"] != "") {
				$str_name = sprintf(" AND CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%'",$_POST['name']);
			}
			else{
				$str_name = "";
			}
			//var_dump($_POST);
			/* And now we can take all chats with our managers, and choose only one */
			if ($_POST["level_access"] == "3" || $_POST["level_access"] == "1,2,3,4" ) {
				$sql = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE level=3$str_name";
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
				$result .= sprintf("<p class='cat_hat'>Техподдержка с менеджерами</p>");
				while($row = $res->fetch_assoc()) {
					$result .= sprintf("<p> <a href='index.php?id=%s'>%s</a>",
						$row['chat_id'],$row['fi']);
					$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row['chat_id']."'";
					//print("<br>$sql_new<br>");
					$res_new = $mysqli->query($sql_new);
					if ($res_new->num_rows != 0) {
						$row_new = $res_new->fetch_assoc();
						if($row_new['COUNT(*)'] != 0)
							$result .= sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row["id"],$row_new['COUNT(*)']);
					}
				}
			}
			if ($_POST["level_access"] == "2" || $_POST["level_access"] == "1,2,3,4" ) {
				$sql = "SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE level=2$str_name";
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
				$result .= sprintf("<p class='cat_hat'>Техподдержка с учителями</p>");
				while($row = $res->fetch_assoc()) {
					$result .= sprintf("<p> <a href='index.php?id=%s'>%s</a>",
						$row['chat_id'],$row['fi']);
					$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row['chat_id']."'";
					//print($sql_new);
					$res_new = $mysqli->query($sql_new);
					if ($res_new->num_rows != 0) {
						$row_new = $res_new->fetch_assoc();
						if($row_new['COUNT(*)'] != 0)
							$result .= sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row["id"],$row_new['COUNT(*)']);
					}			
				}
			}
			if ($_POST["level_access"] == "1" || $_POST["level_access"] == "1,2,3,4" ) {
				$sql_classes = "SELECT * FROM os_class_manager";
				$res_classes = $mysqli->query($sql_classes);
				$result .= sprintf("<p class='cat_hat'>Техподдержка с учениками</p>");
				if ($res_classes->num_rows!=0) {
					while ($row_classes = $res_classes->fetch_assoc()) {
						$sql = sprintf("SELECT id, CONCAT(surname,' ',name) AS fi, chat_id FROM os_users WHERE level=1 AND class='%s'$str_name",
							$row_classes['id']);
						//print("<br>$sql_new<br>");
						$res = $mysqli->query($sql);
						if($res->num_rows!=0) {
								$result .= sprintf("<div class='chat-show-hide'><p class='cat_small_hat'>Класс %s</p>",$row_classes['class_name']);
							while($row = $res->fetch_assoc()) {
								$result .= sprintf("<p> <a href='index.php?id=%s'>%s</a>",
								$row['chat_id'],$row['fi']);
								$sql_new = "SELECT COUNT(*) FROM os_chat_messages WHERE read_status=1 AND id_chat='".$row['chat_id']."'";
								//print($sql_new);
								$res_new = $mysqli->query($sql_new);
								if ($res_new->num_rows != 0) {
									$row_new = $res_new->fetch_assoc();
									if($row_new['COUNT(*)'] != 0)
										$result .= sprintf("<div class='oc_sobs' id='%s'>%s</div>",$row["id"],$row_new['COUNT(*)']);
								}
							}
							$result .= "</div>";
						}
					}
				}
			}
			//print("<p>$result</p>");
			print(json_encode($result));
			/* And now we can take all chats with our managers, and choose only one */
		}
		if($_POST['flag'] == '70') {
			$id_student = $_POST["id_student"];
			$sql = sprintf("SELECT * FROM os_users WHERE id='$id_student'");
			$res = $mysqli->query($sql);
			//print($sql."\n");
			$row = $res->fetch_assoc();
			
			$sql_cnt = sprintf("SELECT COUNT(*) FROM os_chat_messages WHERE id_chat='%s' AND id_user NOT IN('%s') AND read_status=1",
				$row["chat_id"],$id_student);
			//print($sql_cnt."\n");
			$res_cnt = $mysqli->query($sql_cnt);
			
			$row_cnt = $res_cnt->fetch_assoc();
			$result = $row_cnt["COUNT(*)"];
			print(json_encode($result));
		}
		/*** Чаты ***/
		/*** Подписки ***/
		if($_POST['flag'] == '30') {
			$class_clause = "";
			if($_POST['class_id'] != 0) {
				$class_clause = "AND class = " . $_POST['class_id'];
			} else {
				$class_clause = sprintf("AND class IN (SELECT id_c FROM os_teacher_class WHERE id_teacher=%s)", $_SESSION['data']['id']);
			}
			$sql = sprintf("SELECT * FROM os_subjects WHERE id 
				IN(SELECT id_s FROM os_class_subj WHERE course=%s $class_clause) AND id 
				IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher='%s' AND course=%s)",
				$_SESSION['data']['currentCourse'],$_POST['teacher_id'],$_SESSION['data']['currentCourse']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$result = array();
			if($res->num_rows != 0) {
				while ($row = $res->fetch_assoc()) {
					$result[$row['id']] = $row['name_'.$_POST['lang']];
				}
			}
			$result[0] = 0;
			print(json_encode($result));
		}
		if ($_POST['flag'] == '31') {
				/*$sql = "SELECT * FROM os_users WHERE name LIKE '%".$_POST['name']."%' OR surname LIKE '%".
					$_POST['name']."%' OR patronymic LIKE '%".$_POST['name']."%'";*/
				$sql = "SELECT * FROM os_users WHERE level = 1 AND (concat(surname, ' ', name, ' ', patronymic) 
				LIKE '%".$_POST['name']."%' OR login LIKE '%".$_POST['name']."%') ORDER BY id DESC";
				$res = $mysqli->query($sql);
				$iter = 0;
				while ($row = $res->fetch_assoc()) {
					if($row['edu_type']	!= 0) {
						$sql_size = sprintf("SELECT cost FROM os_edu_types WHERE id='%s'",$row['edu_type']);
						$res_size = $mysqli->query($sql_size);
						$row_size = $res_size->fetch_assoc();
						$cost = $row_size['cost'];
					}
					else{
						$cost="---";
					}
					$result[$iter] = array(
						"first" => $row['surname'],
						"second" => $row['name'],
						"third" => $row["patronymic"],
						"fourth" => $cost,
						"sixth" => $row['date_end']."<span class='up_date' onclick=\"open_date_modal(".$row['id'].")\">Продлить</span>",
						"seven" => $row['id']
					);
					switch($row["edu_type"]) {
						case 0: 
							$result[$iter]["fifth"] = "Тип не установлен";
							break;
						case 1: 
							$result[$iter]["fifth"] = "Общее";
							break;
						case 2: 
							$result[$iter]["fifth"] = "Дополнительное";
							break;
						case 3: 
							$result[$iter]["fifth"] = "Частичное";
							break;
						default:
							$result[$iter]["fifth"] = "Тип не установлен";
							break;
					}
					$result[$iter]['fifth'] .= "<span class='up_type' onclick=\"open_type_modal(".$row['id'].")\">  ( Изменить )</span>";
					
					$iter++;
				}
				print_r(json_encode($result));
			
		}
		if ($_POST['flag'] == '33') {
			$sql = sprintf("SELECT * FROM os_users WHERE id='%s'",$_POST['id']);
			//print($sql);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$sql_types = "SELECT * FROM os_edu_types";
			$res_types = $mysqli->query($sql_types);
			if($row['edu_type'] != 0) {
				$types = "<option value='0'>Не установлено</option>";
			} 
			if($row['edu_type'] == 0) {
				$types = "<option value='0' selected>Не установлено</option>";
			} 
			while($row_types = $res_types->fetch_assoc()) {
				//print($row_types['id'] . " - - " . $row['id']."<br>");
				if($row_types['id'] == $row['edu_type']) {
					$types .= sprintf("<option value='%s' selected>%s</option>",$row_types['id'],$row_types['name']);
				}
				if($row_types['id'] != $row['edu_type']) {
					$types .= sprintf("<option value='%s'>%s</option>",$row_types['id'],$row_types['name']);
				}
			}
			$subjects = "";
			$sql_subects = sprintf("SELECT * FROM os_subjects WHERE id IN (SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_POST['id']);
			$res_subjects = $mysqli->query($sql_subects);
			if ($res_subjects->num_rows!=0) {
				while ($row_subjects = $res_subjects->fetch_assoc()) {
					$subjects .= sprintf("<div>%s</div>",$row_subjects['name_'.$_POST['lang']]);
				}
				
			}
			$result = array(
				"types" => $types,
				"subjects" => $subjects,
				"class" => $row["class"],
				"date_end" => $row['date_end'],
				"current_type" => $row['edu_type']
				);
			print_r(json_encode($result));
			
				exit();
		}
		if ($_POST['flag'] == '34') {
			//print($_POST['edu_type']);
			$sql = sprintf("UPDATE os_users SET edu_type='%s' WHERE id='%s'",$_POST['edu_type'], $_POST['id']);
			$res = $mysqli->query($sql);
			/**** Манипуляции с предметами ****/
			$sql_s = "DELETE FROM os_student_subjects WHERE id_student='".$_POST['id']."'";
			//print($sql_s);
			$res_s = $mysqli->query($sql_s);
			//print($_POST['edu_type']);
			//var_dump($_POST['subjects']);
			if($_POST['edu_type'] == 1 || $_POST['edu_type'] == 2) {
				$sql = sprintf("SELECT * FROM os_users WHERE id=%s",$_POST["id"]);
				$res = $mysqli->query($sql);
				$row = $res->fetch_assoc();
				$sql_s = sprintf("INSERT INTO os_student_subjects(id_student,id_subject) SELECT %s,id FROM os_subjects WHERE id 
					IN(SELECT id_s FROM os_class_subj WHERE class='%s')",$_POST['id'],$row['class']);
				//print("<br>$sql_s<br>");
				$res_s = $mysqli->query($sql_s);
			}
			if($_POST['edu_type'] == 3) {
				foreach ($_POST['subjects'] as $value) {
					$sql_s = sprintf("INSERT INTO os_student_subjects(id_student,id_subject) VALUES(%s,%s)",$_POST['id'],$value);
					//print("<br>$sql<br>");
					$res_s = $mysqli->query($sql_s);
				}
			}
			
			//print("<br>$sql_upd<br>");
			//exit();
		}
		if($_POST['flag'] == '42') {
				/*$sql = "SELECT * FROM os_users WHERE name LIKE '%".$_POST['name']."%' OR surname LIKE '%".
					$_POST['name']."%' OR patronymic LIKE '%".$_POST['name']."%'";*/
					$cmp_date = date("Y-m-d");
				$sql = sprintf("SELECT * FROM os_users WHERE level = 1 AND edu_type IN(%s) AND class IN(%s)",$_POST['type'],$_POST['class_id']);
				if ($_POST['status'] == '0') {
					$sql .= " AND date_end<='$cmp_date'";
				}
				if ($_POST['status'] == '1') {
					$sql .= " AND date_end>'$cmp_date'";
				}
				$sql .= " ORDER BY id DESC";
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
				if($res->num_rows == 0) {
					exit();
				}
				$iter = 0;
				while ($row = $res->fetch_assoc()) {
					if($row['edu_type']	!= 0) {
						$sql_size = sprintf("SELECT cost FROM os_edu_types WHERE id='%s'",$row['edu_type']);
						$res_size = $mysqli->query($sql_size);
						$row_size = $res_size->fetch_assoc();
						$cost = $row_size['cost'];
					}
					else{
						$cost="---";
					}
					$result[$iter] = array(
						"first" => $row['surname'],
						"second" => $row['name'],
						"third" => $row["patronymic"],
						"fourth" => $cost,
						"sixth" => $row['date_end']."<span class='up_date' onclick=\"open_date_modal(".$row['id'].")\">Продлить</span>",
						"seven" => $row['id']
					);
					switch($row["edu_type"]) {
						case 0: 
							$result[$iter]["fifth"] = "Тип не установлен";
							break;
						case 1: 
							$result[$iter]["fifth"] = "Общее";
							break;
						case 2: 
							$result[$iter]["fifth"] = "Дополнительное";
							break;
						case 3: 
							$result[$iter]["fifth"] = "Частичное";
							break;
						default:
							$result[$iter]["fifth"] = "Тип не установлен";
							break;
					}
					$result[$iter]['fifth'] .= "<span class='up_type' onclick=\"open_type_modal(".$row['id'].")\">  ( Изменить )</span>";
					$iter++;
					
				}
				print_r(json_encode($result));
			
		}
		if($_POST['flag'] == '57') {
			$sql = sprintf("DELETE FROM os_class_manager WHERE id='%s'",$_POST['id_class']);
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '58') {
			$cmp_date = date("Y-m-d");
			$sql = sprintf("SELECT * FROM os_users WHERE id='%s'",$_POST['id']);
			//print($_POST['id']);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$sql_types = "SELECT * FROM os_edu_types";
			$res_types = $mysqli->query($sql_types);
			//print("<br>".$row['date_end']."<br>");
				if($row['date_end']!=NULL && $row['date_end']!="" && $row['date_end']!="0000-00-00") {
					$date_of_end = strtotime($row['date_end'])+$_POST['days']*24*3600;
				}
				else{
					$date_of_end = strtotime($cmp_date)+$_POST['days']*24*3600;
				}
				//print($date_of_end);
			$sql_sum = sprintf("SELECT * FROM os_edu_types WHERE id='%s'",$_POST['edu_type']);
			$res_sum = $mysqli->query($sql_sum);
			$row_sum = $res_sum->fetch_assoc();
			//print("<br>$date_of_end<br>");
			//$date_of_end += $_POST['days']*24*3600;
			$p_sum = $_POST['days']*(ceil((int)$row_sum['cost']/30));
			//print("<br>$p_sum<br>");
			//$cur_sum = $row["current_money"];
			$date_of_end = date("Y-m-d",$date_of_end);
			$sql_upd = sprintf("UPDATE os_users SET date_end='%s' WHERE id='%s'",$date_of_end,$_POST['id']);
			$res_upd = $mysqli->query($sql_upd);
		}
		/*** Подписки ***/
		/*** Домашки ***/
		if($_POST['flag'] == '62') {
			$pre_month_date = Date("Y-m-d", (strtotime(Date("Y-m-d"))-60*60*24*30));
			$sql = "SELECT COUNT(*) FROM os_homeworks WHERE 1=1";
			$where_clause = array();
			if($_SESSION['data']['currentCourse'] == 0) {
				$where_clause[] = sprintf("id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson IN(SELECT id FROM os_lessons WHERE course=%s))",
					$_SESSION['data']['currentCourse']);
			} else {
				// выборка по курсу, если не онлайн школа
				$where_clause[] = sprintf("id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson IN(SELECT id FROM os_lessons WHERE course=%s))",
					$_SESSION['data']['currentCourse']);
			}
			if($_SESSION['data']['level'] == 1) {
				// добавляем from текущего юзера
				$where_clause[] = sprintf("`from`=%s",$_SESSION['data']['id']);
			} else {
				// добавляем from строки поиска 
				$where_clause[] = sprintf("`from` IN(SELECT id FROM os_users WHERE CONCAT(surname,' ',name,' ',patronymic) LIKE '%%%s%%')",$_POST['name']);
			}
			if($_POST['subj_id'] != 0) {
				$where_clause[] = sprintf("subj = %s",
					$_POST['subj_id']);
			} else {
				if($_SESSION['data']['level'] == 1) {
					$where_clause[] = sprintf("subj IN (SELECT id_subject FROM os_student_subjects WHERE id_student = %s)",
						$_SESSION['data']['id']);
				}
				if($_SESSION['data']['level'] == 2) {
					$where_clause[] = sprintf("subj IN (SELECT id_s FROM os_teacher_subj WHERE id_teacher = %s)",
						$_SESSION['data']['id']);
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
			if($_SESSION['data']['currentCourse'] == 0) {
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
			$sql .= " ORDER BY date_h DESC";
			$res = $mysqli->query($sql);
			if ($res->num_rows==0) {
				$result = 0;
			}
			else{
				$row = $res->fetch_assoc();
				$result = $row["COUNT(*)"];
			}
			print_r(json_encode($result));
		}
		/*** Домашки ***/
		/*** фреймы ***/
		if($_POST['flag'] == '67') {
			$sql = sprintf("UPDATE os_user_frames SET is_displayed=0 WHERE id_user=%s AND id_frame=%s",$_POST['id_user'], $_POST['type']);
			$res = $mysqli->query($sql);
			//print("<br>$sql<br>");
		}
		/*** фреймы ***/
		if($_POST['flag'] == '71') {
			$sql = sprintf("UPDATE os_journal 
							   SET mark_contr = 0, test_contr = '', is_completed = 0 
							 WHERE id_s = %s 
							   AND id_l = %s", $_POST['id_user'], $_POST['id_lesson']);
			//print("\n $sql \n");
			$res = $mysqli->query($sql);
			if($mysqli->affected_rows != 0) {
				print_r(json_encode('yep'));
			} else {
				print_r(json_encode('nope'));
			}
		}
		if($_POST['flag'] == '72') {
			$sql = sprintf("UPDATE os_journal 
							   SET mark_contr = %s 
							 WHERE id = %s", $_POST['mark'], $_POST['id_journal']);
			//print("\n $sql \n");
			$res = $mysqli->query($sql);
			if($mysqli->affected_rows != 0) {
				print_r(json_encode('yep'));
			} else {
				print_r(json_encode('nope'));
			}
		}
		if($_POST['flag'] == '73') {
			$sql = sprintf("UPDATE os_tabel_cont 
							   SET %s = '', %s_redacted = 0 
							 WHERE id_tabel='%s' 
							   AND class='%s' 
							   AND subject = (SELECT name_ru FROM os_subjects 
							   								WHERE id='%s')", 
							   								$_POST['type'], $_POST['type'], $_POST['user_id'], $_POST['class_id'], $_POST['subj']);
			//print("\n $sql \n");
			$res = $mysqli->query($sql);
			if($mysqli->affected_rows != 0) {
				print_r(json_encode('yep'));
			} else {
				print_r(json_encode('nope'));
			}
		}
		if($_POST['flag'] == '74') {
			$new_date = Date("Y-m-d", time() + 3600 * 24 * 7);
			$sql = sprintf("UPDATE os_homeworks SET last_hw_date = '%s' WHERE id = %s", $new_date, $_POST['hw_id']);
			$res = $mysqli->query($sql);
			if($mysqli->affected_rows != 0) {
				print_r(json_encode('yep'));
			} else {
				print_r(json_encode('nope'));
			}
		}
/******    74       ******************///////////////
	}
?>