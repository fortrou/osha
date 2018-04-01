<?php
	require_once('../autoload.php');
	session_start();
	$current_year_num = get_currentYearNum();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_POST) {
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
					print("<br>$sql_finChats<br>");
					$res_finChats = $mysqli->query($sql_finChats);
					if ($res_finChats->num_rows!=0) {
						while ($row_finChats = $res_finChats->fetch_assoc()) {
							$sql_p_data = sprintf("SELECT DISTINCT id, name, surname FROM os_users WHERE id IN(SELECT DISTINCT id_user FROM os_chat_users WHERE id_chat='%s' AND id_user NOT IN(%s) 
								AND id_chat IN (SELECT id FROM os_chat WHERE chat_type=1))",
								$row_finChats['id'],$id_teacher);
							//print($sql_p_data);
							$res_p_data = $mysqli->query($sql_p_data);
							$row_p_data = $res_p_data->fetch_assoc();
							$fip = $row_p_data['surname'].' '.$row_p_data['name'];
							/*$subjects = "";
							$sql_subjects = sprintf("SELECT DISTINCT name_{$_POST['lang']} AS new_name FROM os_subjects WHERE 
								id IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher='%s' $where_course_subject)",
								$id_teacher);
							if($_SESSION['data']['currentCourse'] == 0) {
								$sql_subjects .= sprintf("AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",
									$row_p_data['id']);
							}
							//print("<br>$sql_subjects<br>");
							$res_subjects = $mysqli->query($sql_subjects);
							while ($row_subjects = $res_subjects->fetch_assoc()) {
								$subjects .= $row_subjects['new_name'].',';
							}
							$subjects = rtrim($subjects,',');
							if ($subjects != "") {
								$gen_name = "$fip ( $subjects )";
							}
							else{*/
								$gen_name = "$fip";
							//}
							
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
	}
?>