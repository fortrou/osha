<?php
error_reporting(0);
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	//print("arg");
	session_start();
	if($_POST){
		if($_POST['flag'] == '1'){
			$sql_daf = sprintf("SELECT * FROM os_users WHERE id='%s'",$_POST['from']);
			$res_daf = $mysqli->query($sql_daf);
			$row_daf = $res_daf->fetch_assoc();
			$sql_mail = "SELECT * FROM os_mail_types WHERE id=2";
			$res_mail = $mysqli->query($sql_mail);
			$row_mail = $res_mail->fetch_assoc();

			//var_dump($_POST['doc']);
			if(isset($_SESSION['data']) && $_SESSION['data']['level'] == 1) {
				if($_SESSION['data']['currentCourse'] != 0) {
					$sql_course = sprintf("SELECT * FROM os_courses_meta WHERE id = %s", $_SESSION['data']['currentCourse']);
					$res_course = $mysqli->query($sql_course);
					if($res_course->num_rows != 0) {
						$row_course = $res_course->fetch_assoc();
						$_POST['message'] = "Сообщение из курса " . $row_course['course_name_ru'] . "<br>" . $_POST['message'];
					} 
				}
			}
			$fio = sprintf("%s %s %s ( %s )",$row_daf['surname'],$row_daf['name'],$row_daf['patronymic'],$row_daf['login']);
			$sql = "INSERT INTO os_chat_messages(id_chat,id_user,message,doc,read_status,`date`) 
			VALUES('" . $_POST['chat_id'] . "','" . $_POST['from'] . "','" . $_POST['message'] . "','" . $_POST['doc'] . "',1,now())";
			//print($sql);
			$res = $mysqli->query($sql);
			$sql = sprintf("SELECT * FROM os_users WHERE id IN(SELECT id_user FROM os_chat_users WHERE id_user NOT IN(%s) AND id_chat='%s') AND level=1",$_POST['from'],$_POST['chat_id']);
			$res = $mysqli->query($sql);
			while ($row = $res->fetch_assoc()) {
				$sql_mails = sprintf("SELECT * FROM os_user_mails WHERE id_user='%s' AND id_mail=2 AND yep='1'",$row['id']);
				//print("<br>$sql_mail<br>");
				$res_mails = $mysqli->query($sql_mails);
				//var_dump($res_mail);
				//print("<br>");
				if ($res_mails->num_rows!=0) {
					//var_dump($row_mail);
					//print("<br>");
					if ($row_mail["status"]!=1) {
						$headers = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=utf-8\r\n";
						$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n".
				    	'X-Mailer: PHP/' . phpversion();
						
						$text = sprintf($row_mail["template"],$fio);
						//var_dump($row_mail["template"]);
						//print("<br>$text<br>");
						var_dump(mail($row['email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$text,$headers));
						//print("<br>".$row['email']);
						//print("<br>");
						if($row['p_email']!=""){
							//print("<br>$text<br>");
							var_dump(mail($row['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$text,$headers));
							//print("<br>".$row['p_email']);
							//print("<br>");
						}
					}
					//$fio;
				}
			}
		}
		if($_POST['flag'] == '5'){
			$id_teacher = $_POST['from'];
			$id_lesson = $_POST['id_lesson'];
			/*if (!isset($_POST['doc']) || $_POST['doc'] == "" || $_POST['doc'] == 0 || $_POST['doc'] == NULL || $_POST['doc'] == false) {
				$_POST['doc'] = "";
			}*/
			$result = "";
			$sql_teacher = sprintf("SELECT * FROM os_users WHERE id='%s'",$id_teacher);
			$res_teacher = $mysqli->query($sql_teacher);
			$row_teacher = $res_teacher->fetch_assoc();
			$sql_mail = "SELECT * FROM os_mail_types WHERE id=2";
			$res_mail = $mysqli->query($sql_mail);
			$row_mail = $res_mail->fetch_assoc();

			$fio = sprintf("%s %s %s ( %s )",$row_teacher['surname'],$row_teacher['name'],$row_teacher['patronymic'],$row_teacher['login']);
			$sql_students = sprintf("SELECT * FROM os_users WHERE level=1 AND class = (SELECT class FROM os_lessons WHERE id='%s') AND id 
				IN(SELECT id_student FROM os_student_subjects WHERE id_subject = (SELECT subject FROM os_lessons WHERE id='%s'))",
				$id_lesson,$id_lesson);
			//print("<br>$sql_students<br>");
			$res_students = $mysqli->query($sql_students);
			while ($row_students = $res_students->fetch_assoc()) {
				$sql_mail = sprintf("SELECT * FROM os_user_mails WHERE id_user='%s' AND id_mail=2",$row_students['id']);
				$res_mail = $mysqli->query($sql_mail);
				if ($res_mail->num_rows!=0) {
					//var_dump($row_mail);
					if ($row_mail["status"]!=1) {
						$headers= "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=utf-8\r\n";
						$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n";
						$text = sprintf($row_mail["template"],$fio);
						//var_dump($row_mail["template"]);
						//print("<br>$text<br>");
						var_dump(mail($row_students['email'],"Рассылка от ONLINE-SHKOLA.com.ua",$text,$headers));
						//print("<br>".$row_students['email']);
						print("<br>");
						if($row_students['p_email']!=""){
						var_dump(mail($row_students['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua",$text,$headers));
						print("<br>".$row_students['p_email']);
							print("<br>");
						}
					}
					$fio;
				}
				$sql_chats = sprintf("SELECT * FROM os_chat WHERE id = (SELECT DISTINCT a.id_chat AS ch1 FROM os_chat_users AS a LEFT JOIN os_chat_users AS b ON a.id_chat=b.id_chat 
					WHERE a.id_user='%s' AND b.id_user='%s' AND a.id_chat IN(SELECT id FROM os_chat WHERE chat_type='1'))",$id_teacher,$row_students['id']);
				//print("<br>$sql_chats<br>");
				$res_chats = $mysqli->query($sql_chats);
				$row_chats = $res_chats->fetch_assoc();
				$sql = sprintf("INSERT INTO os_chat_messages(id_chat,id_user,message,doc,read_status,`date`) VALUES('%s','%s','%s','%s','%s',now())",
					$row_chats['id'],$id_teacher,trim(strip_tags($_POST['message'])),$_POST['doc'],1);
				$res = $mysqli->query($sql);
				//print("<br>$sql<br>");
				}
			
		}
		if($_POST['flag'] == '6'){
			$sql = "UPDATE os_chat_messages SET read_status=0 
			WHERE read_status=1 AND id_chat='".$_POST['id_chat']."' AND id_user NOT IN('".$_POST['from']."')";
			//print($sql);
			$res = $mysqli->query($sql);
			exit();
		}
		if($_POST['flag'] == '2'){

			$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['id_chat']."' ORDER BY `date` ASC";
			//print($sql);
			$res = $mysqli->query($sql);
			$result = "";
			while($row = $res->fetch_assoc()){
				$result .= "<li>";
				$sql_n = "SELECT CONCAT(surname,' ',name) AS fi, level FROM os_users WHERE id='".$row['id_user']."'";
				//print("<br>$sql_n<br>");
				$res_n = $mysqli->query($sql_n);
				$row_n = $res_n->fetch_assoc();
				if($row_n['level'] == 4){
					$result .= "<span>administration</span><br><span>".$row['message']."</span></li>";
				}
				else{
					$result .= "<span>".$row_n['fi']."</span><br><span>".$row['message']."</span></li>";	
				}
			}
			$data = array(
				"data" => $result,
				"date" => $row_date,
				"cid" => $_POST['chat_id']
			);
			print(json_encode($data));
		}
		if($_POST['flag'] == '3'){
			if (ob_get_level() == 0) ob_start();
			$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['chat_id']."' ORDER BY `date` DESC LIMIT 1";
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$query_date = $_POST['time_a'];
			$row_date = $row['date'];
			print($_POST['chat_id']);
			$i = 0;
			$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['chat_id']."' ORDER BY `date` DESC LIMIT 1";
			//print($sql);
				$res = $mysqli->query($sql);
				$row = $res->fetch_assoc();
			while ($query_date > $row_date) {
				
				$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['chat_id']."' ORDER BY `date` DESC LIMIT 1";
				$res = $mysqli->query($sql);
				$row = $res->fetch_assoc();
				++$i;
				flush();
				sleep(2);
				//flush();
			}
			$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['chat_id']."' AND `date` < '$row_date' ORDER BY `date` DESC";
			//print($sql);
			$res = $mysqli->query($sql);
			$result = "";
			while($row = $res->fetch_assoc()){
				$result .= "<li>";
				$sql_n = "SELECT CONCAT(surname,' ',name) AS fi, level FROM os_users WHERE id='".$row['id_user']."'";
				//print("<br>$sql_n<br>");
				$res_n = $mysqli->query($sql_n);
				$row_n = $res_n->fetch_assoc();
				if($row_n['level'] == 4){
					$result .= "<span class='ac_admin'>administration</span><br><span>".$row['message']."</span>";
					if ($row['doc']!="") {
						$result .= sprintf("<a href='http://online-shkola.com.ua/upload/docs/%s' download>Скачать файл</a>",$row['doc']);
					}
					$result .= "</li>";
				}
				else{
					$result .= "<span class='ac_user'>".$row_n['fi']."</span><br><span>".$row['message']."</span>";
					if ($row['doc']!="") {
						$result .= sprintf("<a href='http://online-shkola.com.ua/upload/docs/%s' download>Скачать файл</a>",$row['doc']);
					}
					$result .= "</li>";
				}
			}
			$data = array(
				"data" => $result,
				"date" => $row_date,
				"cid" => $_POST['chat_id']
			);
			
			print(json_encode($data));
			ob_end_flush();
		}
		if($_POST['flag'] == '4'){
			/*$sql = "UPDATE os_chat_messages SET read_status=0 
			WHERE read_status=1 AND id_chat='".$_POST['id_chat']."' AND id_user NOT IN('".$_POST['from']."')";
			//print($sql);
			$res = $mysqli->query($sql);*/
			$chat_users = select_chatUsers($_POST['id_chat']);
			if(!$chat_users) exit();
			if(array_search($_SESSION['data']['id'], $chat_users) !== false)
				unset($chat_users[array_search($_SESSION['data']['id'], $chat_users)]);
			if($_SESSION['data']['level'] == 4) {
				if(array_search('admin', $chat_users) !== false)
					unset($chat_users[array_search('admin', $chat_users)]);
			}
			/*echo "<pre>";
			print_r($chat_users);
			echo "</pre><br>";*/
			$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['id_chat']."' ORDER BY `date` ASC";
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$result = "";
			while($row = $res->fetch_assoc()){
				//var_dump($row['doc']);
				//print("</br>");
				$sql_n = "SELECT id,CONCAT(surname,' ',name) AS fi, level, chat_id FROM os_users WHERE id='".$row['id_user']."'";
				//print("<br>$sql_n<br>");
				$res_n = $mysqli->query($sql_n);
				$row_n = $res_n->fetch_assoc();
				$name_first = "";
				$name_second = "";
				if($row['read_status'] == 0){
					$class = "read";
				}
				else{
					$class = "unread";
				}
				if($row_n['id'] == $_POST['from']){
					$result .= "<div class='first_m $class'><span>".$row['date']."</span><br><span>".$row_n['fi']."</span><br><span>".$row['message']."</span>";
					if ($row['doc']!="") {
						$result .= sprintf("<br><span><a href='http://online-shkola.com.ua/upload/hworks/%s' download>Скачать файл</a></span>",$row['doc']);
					}
					$result .= "</div>";
					if($row_n['chat_id'] == $_POST['id_chat'])
						$name_first = $row_n['fi'];
					else 
						$name_first = $row_n['fi'];
					
				}
				else{
					$result .= "<div class='second_m $class'><span>".$row['date']."</span><br><span>".$row_n['fi']."</span><br><span>".$row['message']."</span>";
					if ($row['doc']!="") {
						$result .= sprintf("<a href='http://online-shkola.com.ua/upload/hworks/%s' download>Скачать файл</a>",$row['doc']);
					}
					$result .= "</div>";
					if($row_n['chat_id'] == $_POST['id_chat'])
						$name_second = $row_n['fi'];
					else
						$name_second = $row_n['fi'];
				}


				$pre_val = $row_n['fi'];
				}
				
				$sql_level = sprintf("SELECT * FROM os_chat WHERE id='%s'",$_POST['id_chat']);
				//print("<br>$sql_level<br>");
				$res_level = $mysqli->query($sql_level);
				$row_level = $res_level->fetch_assoc();
				$locked = $row_level['locked'];
				if ($row_level['chat_type'] == 0) {
					$level = "admin";
				}
				$sql_la = sprintf("SELECT * FROM os_users WHERE id='%s'",$_POST['from']);
				//print("<br>$sql_la<br>");
				$res_la = $mysqli->query($sql_la);
				$row_la = $res_la->fetch_assoc();
				if ($row_la['level'] == 4) {
					$la = 4;
					$_POST['from'] = "admin";
				}
				$avatar = "";
				if ($level != "admin" || ( $level == "admin" && $la == 4)) {
					$sql = sprintf("SELECT DISTINCT id_user FROM os_chat_users 
						WHERE id_user NOT IN('%s') AND id_chat='%s'",$_POST['from'],$_POST['id_chat']);
					//print("<br>$sql<br>");
					$user_array = array();
					$res = $mysqli->query($sql);
					while($row = $res->fetch_assoc()){
						$user_array[] = $row['id_user'];
					}
					//var_dump($user_array);
					//print("<br>".count($user_array)."<br>");
					if($level == "admin"  && $_POST['from'] != "admin"){
						$avatar .= "../tpl_img/admin.png";
					}
					else{
						if (count($user_array) == 1) {
							$sql_ava = sprintf("SELECT * FROM os_users WHERE id='%s'",$user_array[0]);
							//print("<br>$sql_ava<br>");
							$res_ava = $mysqli->query($sql_ava);
							$row_ava = $res_ava->fetch_assoc();
							$avatar .= "../upload/avatars/";
							if ($row_ava['avatar'] == "") {
								$avatar .= "default.jpg";
							}
							else{
								$avatar .= $row_ava['avatar'];
							}
						}
						else{
							$avatar .= "../tpl_img/system_users.png";
						}
					}
				}
				else{
					$avatar .= "../tpl_img/admin.png";
				}
				//print("<br>$avatar<br>");
				$chat_head = '';

				foreach ($chat_users as $value) {
					if($value == 'admin') {
						$chat_head .= "Главный Администратор, ";
					} else {
						$sql_user_info = "SELECT * FROM os_users WHERE id = $value";
						$res_user_info = $mysqli->query($sql_user_info);
						if($res_user_info->num_rows != 0) {
							$row_user_info = $res_user_info->fetch_assoc();
							$added_str = '';
							if($_SESSION['data']['level'] > 1) {
								$added_str .= ' ( ';
								if($row_user_info['level'] == 1) {
									$added_str .= ' ученик, класс: ' . $row_user_info['class'];
								} else if($row_user_info['level'] == 2) {
									$added_str .= ' учитель';
								} else if($row_user_info['level'] == 3) {
									$added_str .= ' менеджер';
								}
								$added_str .= ' ); ';
							}
							$added_str = rtrim($added_str, '; ');
							$chat_head .= $row_user_info['name'] . ' ' . $row_user_info['surname'] . $added_str . ', ';
						}
					}
					$chat_head = rtrim($chat_head, ', ');
				}
			$data = array(
				"data" => $result,
				"date" => $row_date,
				"chat_name" => $chat_head,
				"name_f" => $name_first,
				"name_s" => $name_second,
				"cid" => $_POST['chat_id'],
				"avatar" => $avatar,
				"locked" => $locked
			);
			if($name_pupil != "")
				$data['name_pupil'] = $name_pupil;
			else
				$data['name_pupil'] = "Чат урока";
			//var_dump($data);
			print(json_encode($data));
		}
	}
	function select_chatUsers($id_chat = 0) {
		if($id_chat == 0) return false;
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "SELECT * FROM os_chat_users WHERE id_chat = $id_chat";
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			$result_array = array();
			while($row = $res->fetch_assoc()) {
				$result_array[] = $row['id_user'];
			}
			if(count($result_array) != 0) {
				return $result_array;
			}
		}
		return false;
	}
?>