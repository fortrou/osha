<?php
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_POST){
		if($_POST['flag'] == '1') {
			$sql = sprintf("INSERT INTO os_courses_meta(create_date) VALUES('%s')",Date('Y-m-d'));
			$res = $mysqli->query($sql);
			$sql = "SELECT * FROM os_courses_meta WHERE id = (SELECT MAX(id) FROM os_courses_meta)";
			$res = $mysqli->query($sql);
			
			$result = "";
			if($res->num_rows!=0) {
				$row = $res->fetch_assoc();
				$result .= sprintf("<li onclick='load_course(%s)'>Курс %s: %s</li>",$row['id'],$row['id'],$row['course_name_ru']);
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '2') {
			$err = array();
			if($_POST['value'] != "") {
				$value = $_POST['value'];
			} else {
				$err[] = "incorrect value";
			}
			if($_POST['field'] != "") {
				$field = $_POST['field'];
			} else {
				$err[] = "incorrect field";
			}
			if($_POST['course_id'] != "") {
				$course_id = $_POST['course_id'];
			} else {
				$err[] = "incorrect course_id";
			}
			if(count($err) == 0) {
				$sql = "UPDATE os_courses_meta SET $field='$value' WHERE id=$course_id";
				$res = $mysqli->query($sql);
			}
			print_r(json_encode(array(
						'success' => count($err)?false:true,
						'error'	  => $err
					)
				)
			);
		}
		if($_POST['flag'] == '3') {
			$sql = "SELECT * FROM os_courses_meta ORDER BY create_date, id";
			$res = $mysqli->query($sql);
			
			$result = "";
			if($res->num_rows!=0) {
				while($row = $res->fetch_assoc()) {
					$result .= sprintf("<li onclick='load_course(%s)'>Курс %s: %s</li>",$row['id'],$row['id'],$row['course_name_ru']);
				}
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '4') {
			$err = array();
			if(!in_array($_POST['course_id'], array("",0))) {
				$course_id = $_POST['course_id'];
			} else {
				$err[] = "incorrect course_id";
			}
			$result = array();
			if(count($err) == 0) {
				$sql = "SELECT * FROM os_courses_meta WHERE id=$course_id";
				$res = $mysqli->query($sql);
				if($res->num_rows != 0) {
					$row = $res->fetch_assoc();
					foreach ($row as $key => $value) {
						$result[$key] = $value;
					}
					$sql_classes = "SELECT * FROM os_class_manager WHERE is_opened=0";
					$res_classes = $mysqli->query($sql_classes);
					if($res_classes->num_rows != 0) {
						while($row_classes = $res_classes->fetch_assoc()) {
							$temp_result = "";
							$temp_array = array();
							$temp_result .= "<div class='select-block'>";
							$temp_result .= "<p> Класс: " . $row_classes['class_name'] . "<p>";
							$temp_result .= sprintf("<select size='8' multiple name='course_class_%s' class='course_class' 
								onchange='save_subjects_course(this.name,%s)'>",
								$row_classes['id'],$row_classes['id']);
							$sql_items = sprintf("SELECT * FROM os_class_subj WHERE course=$course_id AND class='%s'",$row_classes['id']);
							//print($sql_items);
							$res_items = $mysqli->query($sql_items);
							if ($res_items->num_rows != 0) {
								while($row_items = $res_items->fetch_assoc()) {
									$temp_array[] = $row_items['id_s'];
								}
							}
							$sql_subjects = "SELECT * FROM os_subjects WHERE name_ru <> '' AND name_ua <> ''";
							$res_subjects = $mysqli->query($sql_subjects);
							if($res_subjects->num_rows != 0) {
								while($row_subjects = $res_subjects->fetch_assoc()) {
									if(in_array($row_subjects['id'], $temp_array)) $selected = " selected";
									else $selected = "";
									$temp_result .= sprintf("<option value='%s'$selected>%s</option>",
											$row_subjects['id'],$row_subjects['name_ru']);
								}
							}
							$temp_result .= sprintf("</select>
							<p onclick='clean_subjects_for_course_and_class(%s,%s)' style='cursor:pointer;'>Очистить предметы</p></div>", $row['id'], $row_classes['id']);
							$result['subjects'] .= $temp_result;
						}
						$result['subjects'] .= "<div class='clear'></div>";
					}
				} else {
					$err[] = "There are no courses with such id";
				}
			}
			print_r(json_encode(array(
						'course'  => $result,
						'success' => count($err)?false:true,
						'error'	  => $err
					)
				)
			);
		}
		if($_POST['flag'] == '5') {
			$err = array();
			if($_POST['value'] != "") {
				$value = $_POST['value'];
			} else {
				$err[] = "incorrect value";
			}
			if($_POST['field'] != "") {
				$field = $_POST['field'];
			} else {
				$err[] = "incorrect field";
			}
			if($_POST['course_id'] != "") {
				$course_id = $_POST['course_id'];
			} else {
				$err[] = "incorrect course_id";
			}
			if(count($err) == 0) {
				if ($field == "is_onMain") {
					$sql = "UPDATE os_courses_meta SET $field=0";
					$res = $mysqli->query($sql);
				}
				$sql = "UPDATE os_courses_meta SET $field=$value WHERE id=$course_id";
				$res = $mysqli->query($sql);
			}
			print_r(json_encode(array(
						'success' => count($err)?false:true,
						'error'	  => $err
					)
				)
			);
		}
		if($_POST['flag'] == '6') {
			$sql = "DELETE FROM os_courses_meta WHERE id=" . $_POST['course_id'];
			$res = $mysqli->query($sql);
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '7') {
			$err = array();
			if($_POST['value']) {
				$value = $_POST['value'];
			} else {
				$err[] = "incorrect value";
			}
			if($_POST['class_name'] != "") {
				$class_name = $_POST['class_name'];
			} else {
				$err[] = "incorrect item_type";
			}
			if($_POST['course_id'] != "") {
				$course_id = $_POST['course_id'];
			} else {
				$err[] = "incorrect course_id";
			}
			if(count($err) == 0) {
				$sql = "DELETE FROM os_class_subj WHERE course='$course_id' AND class='$class_name'";
				$res = $mysqli->query($sql);
				$temp_result = "";
				foreach ($value as $val) {
					$temp_result .= "($val,$class_name,$course_id), ";
				}
				$temp_result = rtrim($temp_result,', ');
				if($temp_result != "") {
					$sql = "INSERT INTO os_class_subj(id_s,class,course) VALUES $temp_result";
					print($sql);
					$res = $mysqli->query($sql);
					if($mysqli->affected_rows == 0) {
						$err[] = "No rows were inserted";
					}
				}
			}
			print_r(json_encode(array(
						'success' => count($err)?false:true,
						'error'	  => $err
					)
				)
			);
		}
		if($_POST['flag'] == '8') {
			$sql = "SELECT * FROM os_users WHERE level = 1 AND (concat(surname, ' ', name, ' ', patronymic) 
				LIKE '%".$_POST['name']."%' OR login LIKE '%".$_POST['name']."%') ORDER BY id DESC";
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$result = "";
				while($row = $res->fetch_assoc()) {
					$sql_course_meta = "SELECT * FROM os_courses_meta WHERE is_active=1";
					//print("<br>$sql_course_meta<br>");
					$res_course_meta = $mysqli->query($sql_course_meta);
					if($res_course_meta->num_rows != 0){
						while($row_course_meta = $res_course_meta->fetch_assoc()) {
							$sql_course_payment = sprintf("SELECT * FROM os_courses_students WHERE id_course=%s AND id_user=%s AND id = (
														     SELECT MAX(id) FROM os_courses_students WHERE id_course=%s AND id_user=%s AND payment_verified=1)",
														     $row_course_meta['id'],$row['id'],$row_course_meta['id'],$row['id']);
							//print("<br>$sql_course_payment<br>");
							$res_course_payment = $mysqli->query($sql_course_payment);
							if($res_course_payment->num_rows != 0) {
								$row_course_payment = $res_course_payment->fetch_assoc();
								/* course meta */
								$course_name 	 = $row_course_meta['course_name_' . $_COOKIE['lang']];
								$result .= sprintf("<tr>
												     <td><a target='_blank' href='preview.php?id=%s'>%s %s %s</a></td>
												     <td>$course_name</td>
												     <td>%s <span class='up_type' onclick=\"open_course_modal(%s,%s)\">Продлить</span></td>
												   </tr>",
												   $row['id'],$row['surname'],$row['name'],$row['patronymic'],$row_course_payment['payment_end_date'],$row['id'],$row_course_meta['id']);
							}
						}
					}
				}
			} else exit();
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '9') {
			$result = array();
			$sql_course_payment = sprintf("SELECT * FROM os_courses_students WHERE id_course=%s AND id_user=%s AND id = (
										     SELECT MAX(id) FROM os_courses_students WHERE id_course=%s AND id_user=%s AND payment_verified=1)",
										     $_POST['id_course'],$_POST['id_user'],$_POST['id_course'],$_POST['id_user']);
			$res_course_payment = $mysqli->query($sql_course_payment);
			if($res_course_payment->num_rows != 0) {
				$row_course_payment = $res_course_payment->fetch_assoc();
				$sql_course_meta = sprintf("SELECT * FROM os_courses_meta WHERE id=%s",$row_course_payment['id_course']);
				$res_course_meta = $mysqli->query($sql_course_meta);
				$course_name = "";
				if($res_course_meta->num_rows != 0) {
					$row_course_meta = $res_course_meta->fetch_assoc();
					$course_name 	 = $row_course_meta['course_name_' . $_COOKIE['lang']];
				}
				$result['course_name'] 	  = $course_name;
				$result['course_pay_end'] = $row_course_payment['payment_end_date'];
			} else exit();
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '10') {
			$result 	   = array();
			$id_user 	   = $_POST['id_user'];
			$id_course 	   = $_POST['id_course'];
			$payment_times = $_POST['payment_times'];
			$sql_course_meta = "SELECT * FROM os_courses_meta WHERE id=$id_course";
			$res_course_meta = $mysqli->query($sql_course_meta);
			if($res_course_meta->num_rows != 0) {
				$row_course_meta = $res_course_meta->fetch_assoc();
				$sql_student_payment = "SELECT * FROM os_courses_students WHERE id_course=$id_course AND id_user=$id_user AND id = (
											SELECT MAX(id) FROM os_courses_students WHERE id_course=$id_course AND id_user=$id_user AND payment_verified=1)";
				$res_student_payment = $mysqli->query($sql_student_payment);
				if($res_student_payment->num_rows != 0) {
					$row_student_payment = $res_student_payment->fetch_assoc();
					/*print("<pre>");
					print_r($row_student_payment);
					print("</pre>");*/
					$need_end = '';
					if(!in_array($row_student_payment['payment_end_date'],array('0000-00-00','00-00-0000','','0'))) {
						//print($payment_times*(int)$row_course_meta['payment_period']*24*60*60);
						$need_end = Date("Y-m-d",strtotime($row_student_payment['payment_end_date'])+$payment_times*(int)$row_course_meta['payment_period']*24*60*60);
					} else {
						if(time()>strtotime($row_course_meta['date_from'])) {
							$need_end = Date("Y-m-d",strtotime()+$payment_times*(int)$row_course_meta['payment_period']*24*60*60);
						} else {
							$need_end = Date("Y-m-d",strtotime($row_course_meta['date_from'])+$payment_times*(int)$row_course_meta['payment_period']*24*60*60);
						}
					}
					$sql_insert = sprintf("INSERT INTO os_courses_students(id_user,id_course,payment_verified,payment_end_date) VALUES(%s,%s,1,'%s')",
						$id_user, $id_course,$need_end);
					$res_insert = $mysqli->query($sql_insert);
					if($mysqli->affected_rows!=0) {
						$result['status'] 	 = 'success';
						$result['date_till'] = "Доступ к курсу продлен до $need_end";
					}
				} else {
					$result['status'] = 'error';
				}
			} else {
				$result['status'] = 'error';
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '11') {
			$where = "";
			if($_POST['course_course'] != 0 ) {
				$where .= " AND id IN(SELECT DISTINCT id_user FROM os_courses_students WHERE id_course=" . $_POST['course_course'] .")";
			}
			if($_POST['course_class'] != 0) {
				$where .= " AND class=" . $_POST['course_class'];
			}
			if($_POST['course_status'] != 0) {
				if($_POST['course_status'] == 1) {
					$where .= " AND id IN(SELECT DISTINCT id_user FROM os_courses_students WHERE payment_end_date<'" . Date("Y-m-d") ."')";
				} else {
					$where .= " AND id IN(SELECT DISTINCT id_user FROM os_courses_students WHERE payment_end_date>='" . Date("Y-m-d") ."')";
				}
			}
			$result = array();
			$sql = "SELECT * FROM os_users WHERE level = 1";
			$sql .= $where;
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$result = "";
				while($row = $res->fetch_assoc()) {
					$sql_course_meta = "SELECT * FROM os_courses_meta WHERE is_active=1";
					$res_course_meta = $mysqli->query($sql_course_meta);
					if($res_course_meta->num_rows != 0){
						while($row_course_meta = $res_course_meta->fetch_assoc()) {
							$sql_course_payment = sprintf("SELECT * FROM os_courses_students WHERE id_course=%s AND id_user=%s AND id = (
														     SELECT MAX(id) FROM os_courses_students WHERE id_course=%s AND id_user=%s AND payment_verified=1)",
														     $row_course_meta['id'],$row['id'],$row_course_meta['id'],$row['id']);
							//print("<br>$sql_course_payment<br>");
							$res_course_payment = $mysqli->query($sql_course_payment);
							if($res_course_payment->num_rows != 0) {
								$row_course_payment = $res_course_payment->fetch_assoc();
								/* course meta */
								$course_name 	 = $row_course_meta['course_name_' . $_COOKIE['lang']];
								$result .= sprintf("<tr>
												     <td><a target='_blank' href='preview.php?id=%s'>%s %s %s</a></td>
												     <td>$course_name</td>
												     <td>%s <span class='up_type' onclick=\"open_course_modal(%s,%s)\">Продлить</span></td>
												   </tr>",
												   $row['id'],$row['surname'],$row['name'],$row['patronymic'],$row_course_payment['payment_end_date'],$row['id'],$row_course_meta['id']);
							}
						}
					}
				}
			} else exit();
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '12') {
			$sql = sprintf("DELETE FROM os_class_subj WHERE course=%s AND class=%s", $_POST['course_id'], $_POST['class_id']);
			$res = $mysqli->query($sql);
			$result = array();
			$sql_classes = "SELECT * FROM os_class_manager WHERE is_opened=0";
			$res_classes = $mysqli->query($sql_classes);
			if($res_classes->num_rows != 0) {
				while($row_classes = $res_classes->fetch_assoc()) {
					$temp_result = "";
					$temp_array = array();
					$temp_result .= "<div class='select-block'>";
					$temp_result .= "<p> Класс: " . $row_classes['class_name'] . "<p>";
					$temp_result .= sprintf("<select size='8' multiple name='course_class_%s' class='course_class' 
						onchange='save_subjects_course(this.name,%s)'>",
						$row_classes['id'],$row_classes['id']);
					$sql_items = sprintf("SELECT * FROM os_class_subj WHERE course=%s AND class='%s'",$_POST['course_id'],$row_classes['id']);
					//print($sql_items);
					$res_items = $mysqli->query($sql_items);
					if ($res_items->num_rows != 0) {
						while($row_items = $res_items->fetch_assoc()) {
							$temp_array[] = $row_items['id_s'];
						}
					}
					$sql_subjects = "SELECT * FROM os_subjects WHERE name_ru <> '' AND name_ua <> ''";
					$res_subjects = $mysqli->query($sql_subjects);
					if($res_subjects->num_rows != 0) {
						while($row_subjects = $res_subjects->fetch_assoc()) {
							if(in_array($row_subjects['id'], $temp_array)) $selected = " selected";
							else $selected = "";
							$temp_result .= sprintf("<option value='%s'$selected>%s</option>",
									$row_subjects['id'],$row_subjects['name_ru']);
						}
					}
					$temp_result .= sprintf("</select>
					<p onclick='clean_subjects_for_course_and_class(%s,%s)' style='cursor:pointer;'>Очистить предметы</p></div>", $_POST['course_id'], $row_classes['id']);
					$result['subjects'] .= $temp_result;
				}
				$result['subjects'] .= "<div class='clear'></div>";
				print(json_encode($result));
			}
			exit();

		}
	}
?>