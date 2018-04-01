<?php
set_time_limit(7200);
	require_once("../autoload_light.php");
	session_start();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$current_year = get_currentYearNum();
	$iter = 1;

	// main objects
	$options 	  = new Options;
	$db 		  = Database::getInstance();
	$mysqli 	  = $db->getConnection();
	
	// year and week
	$current_year = get_currentYearNum();
	$week_start   = date("Y-m-d",define_week_start_and_end("start", strtotime(date("Y-m-d"))));
	$week_end     = date("Y-m-d",define_week_start_and_end("end", strtotime(date("Y-m-d"))));
	$sql_year = "SELECT * FROM os_year_date WHERE year_number = $current_year";
	$res_year = $mysqli->query($sql_year);
	if($res_year->num_rows != 0) {
		$row_year = $res_year->fetch_assoc();
	} else {
		$row_year = array( "year_start" => 2017,
						   "year_end" => 2018 );
	}

	// options data
	$current_semester   = $options->get_option('semester_current_number');
	$semester_end_date  = $options->get_option('semester_end_date');
	$first_s_day_month  = $options->get_option('first_semester_nominal_date');
	$second_s_day_month = $options->get_option('second_semester_nominal_date');
	$first_semester_array  = explode('|', $first_s_day_month);
	$second_semester_array = explode('|', $second_s_day_month);
	$first_semester_array[0]  = $row_year['year_start'] . '-' . $first_semester_array[0];
	$first_semester_array[1]  = $row_year['year_start'] . '-' . $first_semester_array[1];
	$second_semester_array[0] = $row_year['year_end'] . '-' . $second_semester_array[0];
	$second_semester_array[1] = $row_year['year_end'] . '-' . $second_semester_array[1];
	$target_semester = array();
	if(isset($_POST["go_hw"])) {
		if($_POST['semester'] == 'first_s') {
			$target_semester = $first_semester_array;
		} else if($_POST['semester'] == 'second_s') {
			$target_semester = $second_semester_array;
		}
		$sql_users = "SELECT * FROM os_users WHERE level = 1 AND class = " . $_POST['class'];
		if(!empty($_POST['user_id']) && $_POST['user_id'] != '') {
			$sql_users .= " AND id = " . $_POST['user_id'];
		}
		$res_users = $mysqli->query($sql_users);
		$date_to = Date('Y-m-d', time()+3600*7*24);
		$date_from = Date('Y-m-d', time()-3600*7*24);
		if($res_users->num_rows != 0) {
			while($row_users = $res_users->fetch_assoc()) {
				/*$sql = "SELECT * FROM os_lessons 
								WHERE 1=1 
								  AND id IN (SELECT id_lesson FROM os_lesson_classes
								  							 WHERE id_class = 5) 
								  AND lesson_year = " . $current_year;*/
				/*$sql = "SELECT * FROM os_lessons 
								WHERE 1=1 
								  AND lesson_year = $current_year 
								  AND (date_ru >= '$date_from' 
								  	  OR date_ua >= '$date_from')
								  AND (date_ru <= '$date_to' 
								  	  OR date_ua <= '$date_to')";*/
				$sql = sprintf("SELECT * FROM os_lessons
										WHERE 1=1
										  AND lesson_year = $current_year
										  AND (date_ru >= '%s'
											  OR date_ua >= '%s')
										  AND (date_ru <= '%s'
										  	  OR date_ua <= '%s')", $target_semester[0], $target_semester[0],
										  							$target_semester[1], $target_semester[1]);
				print("<br> $sql <br>");
				$current_data = $row_users;
				$current_data['currentCourse'] = 0;
				$where_clause = array();
				
				$where_clause[] = sprintf("id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=%s )",
					$current_data['class']);
				$where_clause[] = sprintf("course = %s", 
					$current_data['currentCourse']);
				// если это онлайн-школа
				if($current_data['currentCourse'] == 0) {
					$subject_add = sprintf("IN (SELECT id_subject FROM os_student_subjects WHERE id_student=%s)",
						$current_data['id']);
					$where_clause[] = sprintf("subject $subject_add");
				} else {
					$subject_add = sprintf("IN (SELECT id_s FROM os_class_subj WHERE class=%s AND course=%s)",
						$current_data['class'],$current_data['currentCourse']);
					$where_clause[] = sprintf("subject $subject_add");
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
				while ($row = $res->fetch_assoc()) {
					$new_date = Date("Y-m-d", strtotime($row['date_ua']) + 3600 * 24 * 7);
					$sql_lhw = sprintf("SELECT * FROM os_lesson_homework WHERE id_lesson=%s",$row["id"]);
					//print("<br>$sql_lhw<br>");
					$res_lhw = $mysqli->query($sql_lhw);
					if ($res_lhw->num_rows!=0) {
						$row_lhw = $res_lhw->fetch_assoc();
						$sql_homework = sprintf("SELECT * FROM os_homeworks WHERE `from`='%s' AND id_hw='%s'",$current_data["id"],$row_lhw["id"]);
						$res_homework = $mysqli->query($sql_homework);
						print($sql_homework . "<br>");
						if ($res_homework->num_rows == 0) {
							print("<br>incorrect<br>");
							if ($row["is_control"] == 0) {
								$status = 1;
							}
							else{
								$status = 3;
							}
							$date_ru = explode(" ",$row["date_ru"]);
							$date_ua = explode(" ",$row["date_ua"]);
							$sql_create = sprintf("INSERT INTO os_homeworks(date_h, `from`, subj, class, id_hw, status, check_status, last_hw_date) 
														VALUES ('%s', %s, %s, %s, %s, $status, 2, '$new_date')",
								$date_ru[0],$current_data["id"],$row["subject"],$current_data["class"],$row_lhw["id"]);
							$res_create = $mysqli->query($sql_create);
							print("<be>$sql_create<br>");
							$sql_li = "SELECT LAST_INSERT_ID() AS li FROM os_homeworks";
							$res_li = $mysqli->query($sql_li);
							$row_li = $res_li->fetch_assoc();
							print($row_li['li'] . "<br>");
							$iter++;

						}
					}
					
				}
				
			}
		}
		print("iter: $iter");
	}
	if(isset($_POST["go_journal"])) {
		$start = microtime(true);
		if($_POST['semester'] == 'first_s') {
			$target_semester = $first_semester_array;
		} else if($_POST['semester'] == 'second_s') {
			$target_semester = $second_semester_array;
		}
		$date = Date("Y-m-d", strtotime() - 3600*24*31);
		$sql_user = sprintf("SELECT * FROM os_users  WHERE class = %s AND level = 1 AND date_end > '%s'", $_POST['class'], $date);
		if(!empty($_POST['user_id']) && $_POST['user_id'] != '') {
			$sql_user .= " AND id = " . $_POST['user_id'];
		}
		$res_user = $mysqli->query($sql_user);
		$iter = 1;
		if($res_user->num_rows != 0) {
			while($row_user = $res_user->fetch_assoc()) {
				$sql = sprintf("SELECT * FROM os_lessons 
										WHERE 1 = 1 
								  		  AND lesson_year = $current_year
										  AND (date_ru >= '%s' 
							  			   OR date_ua >= '%s')
										  AND (date_ru <= '%s' 
										   OR date_ua <= '%s')
										  AND id 
										   IN (SELECT id_lesson FROM os_lesson_classes 
								   							   WHERE id_class=%s )", $target_semester[0], $target_semester[0],
												  									 $target_semester[1], $target_semester[1], $_POST['class']);
				$sql .= sprintf(" AND subject IN (SELECT id_subject FROM os_student_subjects WHERE id_student=%s)", $row_user['id']);
				print($sql);
				$res = $mysqli->query($sql);
				//print("<br>$sql_user<br>");
				if($res->num_rows != 0) {
					while ($row = $res->fetch_assoc()) {
						$sql_j = sprintf("SELECT * FROM os_journal WHERE id_s = %s AND id_l = %s", $row_user['id'], $row['id']);
						$res_j = $mysqli->query($sql_j);
						if($res_j->num_rows != 0) continue; 
						$sql_insert = sprintf("INSERT INTO os_journal(id_s, id_l, date_ru, date_ua, id_subj) 
													VALUES (%s,%s,'%s','%s',%s)", $row_user['id'], $row['id'], $row['date_ru'], $row['date_ua'], $row['subject']);
						print("<br>$sql_insert<br>");
						$res_insert = $mysqli->query($sql_insert);
						var_dump($res_insert);
						$iter++;
						usleep(1000);
					}
				}
				continue;
			}
		}
		print("<br>iter: $iter<br>");
		echo '<br> Время выполнения скрипта: '.(microtime(true) - $start).' сек. <br>';
	}
	if(isset($_POST['set_dates'])) {
		$start = microtime(true);
		if($_POST['semester'] == 'first_s') {
			$target_semester = $first_semester_array;
		} else if($_POST['semester'] == 'second_s') {
			$target_semester = $second_semester_array;
		}
		$sql = sprintf("SELECT * FROM os_lessons 
								WHERE 1 = 1 
						  		  AND lesson_year = $current_year
								  AND (date_ru >= '%s' 
						 			   OR date_ua >= '%s')
								  AND (date_ru <= '%s' 
									   OR date_ua <= '%s')
								  AND id 
								   IN (SELECT id_lesson FROM os_lesson_classes 
						   							   WHERE id_class=%s )", $target_semester[0], $target_semester[0],
										  									 $target_semester[1], $target_semester[1], $_POST['class']);
		print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			while($row = $res->fetch_assoc()) {
				$new_date = Date("Y-m-d", strtotime($row['date_ua']) + 3600 * 24 * 7);
				$sql_upd = sprintf("UPDATE os_homeworks 
									   SET last_hw_date = '%s' 
									 WHERE id_hw 
									    IN (SELECT id FROM os_lesson_homework 
									    			 WHERE id_lesson = %s)", $new_date, $row['id']);
				printf("<br>%s<br>", $mysqli->affected_rows);
				print("<br> $sql_upd <br>");
				$res_upd = $mysqli->query($sql_upd);
				usleep(300);
			}
		}
		echo '<br> Время выполнения скрипта: '.(microtime(true) - $start).' сек. <br>';
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Homeworks creation script</title>
	<?php require_once("../../tpl_blocks/head.php"); ?>
</head>
<body>
	<?php require_once("../../tpl_blocks/header.php"); ?>
	<div class="content">
		<div class="row">
			<form action="" method="post">
				<table>
					<tr>
						<td>
							<h3>Класс</h3>
							<select name="class" id="">
								<?php 
									$sql = "SELECT * FROM os_class_manager";
									$res = $mysqli->query($sql);
									if($res->num_rows != 0) {
										while($row = $res->fetch_assoc()) {
											$selected = "";
											if($row['class_name'] == '8') $selected = "selected"; 
											printf("<option value='%s' $selected>Класс %s</option>",
													$row['id'],$row['class_name']);
										}
									}
								?>
							</select>
						</td>
						<td>
							<h3>Семестр</h3>
							<select name="semester" id="">
								<option value="first_s">1й семестр</option>
								<option value="second_s">2й семестр</option>
							</select>
						</td>
						<td>
							<h3>Id пользователя</h3>
							<input type="text" name="user_id">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="go_hw" value="Домашки">
						</td>
						<td>
							<input type="submit" name="go_journal" value="Журнал">
						</td>
						<td>
							<input type="submit" name="set_dates"  value="Проставить даты фреймам">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</body>
</html>