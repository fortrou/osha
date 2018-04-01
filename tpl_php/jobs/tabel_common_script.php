<?php
	/**
	 * скрипт проставления оценок в табель
	 *
	 * dev by fortrou
	 **/
	session_start();
	if(!isset($_SESSION['data']) || (isset($_SESSION['data']) && $_SESSION['data']['level'] != 4)) header("Location: http://online-shkola.com.ua");
	require_once("../autoload_light.php");
	$current_year = get_currentYearNum();
	$year 	 = Date('Y') . '-';
	$options = new Options;
	$current_semester   = $options->get_option('semester_current_number');
	$semester_end_date  = $options->get_option('semester_end_date');
	$first_s_day_month  = $options->get_option('first_semester_nominal_date');
	$second_s_day_month = $options->get_option('second_semester_nominal_date');
	$first_semester_array  = explode('|', $first_s_day_month);
	$second_semester_array = explode('|', $second_s_day_month);
	//print("$first_s_day_month \n");
	/*$first_semester_array[0]  = $year . $first_semester_array[0];
	$first_semester_array[1]  = $year . $first_semester_array[1];
	$second_semester_array[0] = $year . $second_semester_array[0];
	$second_semester_array[1] = $year . $second_semester_array[1];*/
	$sql_year_dates = sprintf("SELECT * FROM os_year_date WHERE year_number = %s", $current_year);
	$res_year_dates = $mysqli->query($sql_year_dates);
	$row_year_dates = $res_year_dates->fetch_assoc();
	$sql = sprintf("SELECT * FROM os_users 
							WHERE class = %s 
							  AND level = 1
							  AND date_end <> '0000-00-00'
							  AND edu_type = 1
							  AND id = 52", 
							$_POST['classes']);
	if(isset($_POST['go'])) {
		$sql .= " AND date_start_learning <> '0000-00-00'";
		$column = $_POST['tabel_parametr'];
		$res = $mysqli->query($sql);
		print("<br>$sql<br>");
		if($res->num_rows != 0) {
			while($row = $res->fetch_assoc()) {
				$sql_subjects = sprintf("SELECT * FROM os_subjects 
												 WHERE id 
												    IN (SELECT DISTINCT id_subject FROM os_student_subjects 
												  								  WHERE id_student = %s)", 
											$row['id']);
				$res_subjects = $mysqli->query($sql_subjects);
				print('<br>$sql_subjects<br>');
				$student_subjects = array();
				$tabel_status = '';
				if($res_subjects->num_rows != 0) {
					while($row_subjects = $res_subjects->fetch_assoc()) {
						$student_subjects[] = array( "id"   => $row_subjects['id'],
													 "name" => $row_subjects['name_ru'] );
					}
					if(count($student_subjects) != 0) {
						foreach ($student_subjects as $value) {
							$sql_tabel_content = sprintf("SELECT * FROM os_tabel_cont WHERE id_tabel=%s AND class='%s' AND subject='%s'",
														  $row['id'], $row['class'], $value['name']);
							$res_tabel_content = $mysqli->query($sql_tabel_content); 
							if ($res->num_rows == 0) {
								$sql_in1 = sprintf("INSERT INTO os_tabel_cont(id_tabel,class,subject,first_s,second_s,year,gia,final)
													VALUES(%s, %s, '%s', '', '', '', '', '')",
													$row['id'], $row['class'], $value['name']);
								$res_in1 = $mysqli->query($sql_in1);
							}
							$sql_ii = sprintf("SELECT * FROM os_tabel_prev 
													   WHERE id_pupil='%s' 
													     AND id_class='%s' 
													     AND id_tabel='%s'",
								$row['id'],$row['class'],$row['id']);
							$res_ii = $mysqli->query($sql_ii);
							if ($res_ii->num_rows == 0) {
								$sql_in2 = sprintf("INSERT INTO os_tabel_prev( id_pupil, id_class, id_tabel ) 
														 VALUES ( %s, %s, %s )",
									$row['id'], $row['class'], $row['id']);
								$res_in2 = $mysqli->query($sql_in2);
							}
							if($_POST['classes'] != 11) {
								if($column != "year") {
									$date_between = 'AND id IN (SELECT theme FROM os_lessons WHERE 1=1 AND lesson_year = ' . $current_year;
									if($column == 'first_s') {
										$locale_begin = $row_year_dates['year_start'] . '-' . $first_semester_array[0];
										$locale_end = $row_year_dates['year_start'] . '-' . $first_semester_array[1];
									}
									if($column == 'second_s') {
										$locale_begin = $row_year_dates['year_end'] . '-' . $second_semester_array[0];
										$locale_end = $row_year_dates['year_end'] . '-' . $second_semester_array[1];
									}
									$date_between .= sprintf(" AND date_ua BETWEEN '%s' AND '%s' )", $locale_begin, $locale_end);
									$sql_theme = sprintf("SELECT * FROM os_themes 
																  WHERE theme_subject = %s 
																    AND theme_course  = 0
																    AND id 
																     IN (SELECT id_theme FROM os_theme_classes
																     					WHERE id_class = %s)
																        $date_between
															   ORDER BY id ASC",
															$value['id'], $row['class']);
									$res_theme    = $mysqli->query($sql_theme);
									$theme_sum    = 0;
									$count_themes = 0;
									print("<br>$sql_theme<br>");
									if($res_theme->num_rows != 0) {
										$count_themes = $res_theme->num_rows;
										print("<br>$count_themes<br>");
										while($row_theme = $res_theme->fetch_assoc()) {
											$theme_mark 	 = check_and_generate_theme_mark($row['id'], $row_theme['id'], $row['class'], $value['id']);
											$theme_mark_data = explode('-', $theme_mark);
											if($theme_mark_data[1] != 'excepted') {
												$theme_sum  += $theme_mark_data[0];
											} else {
												$count_themes--;
											}
										}
										if($count_themes)
											$theme_sum = round($theme_sum/$count_themes);
										
										if($theme_sum != 0) {
											$sql_update_tabel = sprintf("UPDATE os_tabel_cont 
																			SET $column = %s  
																		  WHERE id_tabel = %s 
																		    AND class = %s 
																		    AND subject = '%s'",
																		    $theme_sum, $row['id'], $row['class'], $value['name']);
											print("<br>$sql_update_tabel<br>");
											$res_update_journal = $mysqli->query($sql_update_tabel);
											if($mysqli->affected_rows > 0) {
												$tabel_status = '1';
											}
										}
									}
								} else {
									/*if(strtotime($row['date_start_learning']) > strtotime($row_year_dates['year_end'] . '-' . $second_semester_array[0])) {
										$sql_update_tabel = sprintf("UPDATE os_tabel_cont 
																		SET year = second_s  
																	  WHERE id_tabel = %s 
																	    AND class = %s 
																	    AND subject = '%s'",
																	    $row['id'], $row['class'], $value['name']);
									} else {}*/
									$sql_update_tabel = sprintf("UPDATE os_tabel_cont 
																	SET year = (second_s + first_s) / 2  
																  WHERE id_tabel = %s 
																    AND class = %s 
																    AND subject = '%s'",
																    $row['id'], $row['class'], $value['name']);
									print("<br>$sql_update_tabel<br>");
									$res_update_journal = $mysqli->query($sql_update_tabel);
									if($mysqli->affected_rows > 0) {
										$tabel_status = '1';
									}
								}
							} else {
								$date_between = 'AND id IN (SELECT theme FROM os_lessons WHERE 1=1 AND lesson_year = ' . $current_year;
								$locale_begin = $row_year_dates['year_start'] . '-' . $first_semester_array[0];
								$locale_end = $row_year_dates['year_end'] . '-' . $second_semester_array[1];
								$date_between .= sprintf(" AND date_ua BETWEEN '%s' AND '%s' )", $locale_begin, $locale_end);
								$sql_theme = sprintf("SELECT * FROM os_themes 
															  WHERE theme_subject = %s 
															    AND theme_course  = 0
															    AND id 
															     IN (SELECT id_theme FROM os_theme_classes
															     					WHERE id_class = %s)
															        $date_between
														   ORDER BY id ASC",
														$value['id'], $row['class']);
								$res_theme    = $mysqli->query($sql_theme);
								$theme_sum    = 0;
								$count_themes = 0;
								print("<br>$sql_theme<br>");
								if($res_theme->num_rows != 0) {
									$count_themes = $res_theme->num_rows;
									print("<br>$count_themes<br>");
									while($row_theme = $res_theme->fetch_assoc()) {
										$theme_mark 	 = check_and_generate_theme_mark($row['id'], $row_theme['id'], $row['class'], $value['id']);
										$theme_mark_data = explode('-', $theme_mark);
										if($theme_mark_data[1] != 'excepted') {
											$theme_sum  += $theme_mark_data[0];
										} else {
											$count_themes--;
										}
									}
									if($count_themes)
										$theme_sum = round($theme_sum/$count_themes);
									
									if($theme_sum != 0) {
										$sql_update_tabel = sprintf("UPDATE os_tabel_cont 
																		SET year = %s  
																	  WHERE id_tabel = %s 
																	    AND class = %s 
																	    AND subject = '%s'",
																	    $theme_sum, $row['id'], $row['class'], $value['name']);
										print("<br>$sql_update_tabel<br>");
										$res_update_journal = $mysqli->query($sql_update_tabel);
										if($mysqli->affected_rows > 0) {
											$tabel_status = '1';
										}
									}
								}
							}
						}
					}
					if($tabel_status != '') {
						$sql_n = "SELECT * FROM os_mail_types WHERE id='6'";
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
						var_dump(mail($row['email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$mail_text,$headers));
						if ($row['p_email'] != "") {
							mail($row['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$text,$headers);
						}
					}
				}
			}
		}
	}
	if(isset($_POST['switch'])) {
		$sql .= "AND date_start_learning <> '0000-00-00'";
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			while($row = $res->fetch_assoc()) {
				$sql_journal = sprintf("SELECT * FROM os_journal 
												WHERE 1 = 1
												  AND id_s = %s
												  AND (mark_contr + mark_hw) < 2
												  AND id_l 
												   IN (SELECT id FROM os_lessons
												   				WHERE subject 
												   				   IN (SELECT DISTINCT id_subject FROM os_student_subjects
												   				   								 WHERE id_student = %s)
												   				  AND id
												   				   IN (SELECT id_lesson FROM os_lesson_classes
												   									   WHERE id_class = %s
												   									   	 AND is_verbal = 0
												   									   	 AND DATE(date_ua) > '%s')
												   				  AND lesson_year = %s)", 
												   				  $row['id'], $row['id'], $row['class'], $row['date_start_learning'], $current_year);
				//print("<br>$sql_journal<br>");
				$res_journal = $mysqli->query($sql_journal);
				if($res_journal->num_rows != 0) {
					while($row_journal = $res_journal->fetch_assoc()) {
						$mark_hw	= $row_journal['mark_hw'];
						$mark_contr = $row_journal['mark_contr'];
						if($mark_hw == 1 || $mark_contr == 1) {
							$mark_contr++;
						} else {
							$mark_contr = 2;
						}
						/*echo "<pre>";
						print_r($row_journal);
						echo "</pre>";*/
						$sql_update_journal = sprintf("UPDATE os_journal 
														  SET mark_contr = %s, test_contr = '', is_completed = 1
														WHERE id = %s", $mark_contr, $row_journal['id']);
						$res_update_journal = $mysqli->query($sql_update_journal);
						print("<br>$sql_update_journal<br>");
					}
				}
			}
		}
	}
	if(isset($_POST['clear'])) {
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			while($row = $res->fetch_assoc()) {
				if($_POST['tabel_parametr'] == 'first_s') {
					$year = Date("Y") - 1;
					$date_from = $year . '-' . $first_semester_array[0];
					$date_end = $year . '-' . $first_semester_array[1];
				} else {
					$year = Date("Y");
					$date_from = $year . '-' . $second_semester_array[0];
					$date_end = $year . '-' . $second_semester_array[1];
				}
				$sql_lesson = sprintf("SELECT id FROM os_lessons
												WHERE subject 
												   IN (SELECT DISTINCT id_subject FROM os_student_subjects
								   				   								 WHERE id_student = %s)
								   				  AND id
								   				   IN (SELECT id_lesson FROM os_lesson_classes
								   									   WHERE id_class = %s)
								   				  AND ((date_ru > '%s' OR date_ua > '%s')
								   				  AND (date_ru < '%s' OR date_ua < '%s'))
								   				  AND lesson_year = %s",
								   				  $row['id'], $row['class'], $date_from, $date_from, $date_end, $date_end, $current_year);
				//print("<br>$sql_lesson<br>");
				$res_lesson = $mysqli->query($sql_lesson);
				if($res_lesson->num_rows != 0) {
					while($row_lesson = $res_lesson->fetch_assoc()) {
						$sql_journal = sprintf("SELECT * FROM os_journal
														WHERE id_s = %s
														  AND id_l = %s 
														  AND 1 < (SELECT COUNT(id) FROM os_journal 
														  						   WHERE id_s = %s
														  						     AND id_l = %s )", 
														  						     $row['id'], $row_lesson['id'], $row['id'], $row_lesson['id']);
						//print("<br>$sql_journal<br>");
						$res_journal = $mysqli->query($sql_journal);
						if($res_journal->num_rows >= 2) {
							$array_data = array();
							$temp_array = array();
							while($row_journal = $res_journal->fetch_assoc()) {
								$array_data[$row_journal['id']] = ($row_journal['mark_contr'] + $row_journal['mark_hw']);
							}
							if(count($array_data)) {
								$temp_array = array_flip($array_data);
								asort($array_data);
								array_pop($array_data);
								$string = '';

								foreach ($array_data as $key => $value) {
									$string .= $key . ', ';
								}
								$string = rtrim($string, ', ');
								if($string != '') {
									$sql_delete = sprintf("DELETE FROM os_journal WHERE id IN(%s)", $string);
									print("<br>$sql_delete<br>");
									$res_delete = $mysqli->query($sql_delete);
									if($mysqli->affected_rows != 0) {
										print("<br>good<br>");
									} else {
										print("<br>bad<br>");
									}
								}
							} 
						}
						usleep(1000);
					}
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<?php require_once("../../tpl_blocks/head.php"); ?>
</head>
<body>
	<?php require_once("../../tpl_blocks/header.php"); ?>
	<div class="content">
		<div class="row">
			<form action="" method="post">
				<table>
					<thead>
						<tr>
							<td title="Выберите КЛАСС Уроков">
								<h2>Для всех</h2>
								<select name="classes" id="">
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
								<h2>Для табеля и дублей</h2>
								<select name="tabel_parametr" id="">
									<option value="first_s">1й семестр</option>
									<option value="second_s">2й семестр</option>
									<option value="year">год</option>
								</select>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td title="Поехали ставить табель">
								<input type="submit" name="go" value="Ставим табель?">
							</td>
							<td title="Таки заменяем?">
								<input type="submit" name="switch" value="Заменяем 0 на 2?">
							</td>
							<td title="Помогите Даше найти дубликаты. Помогли?">
								<input type="submit" name="clear" value="Отправляемся в поиск дубликатов?">
							</td>
						</tr>
						<!--<tr>
							<td></td>
							<td title="Удалить ДЗ по классу и дате, по всем пользователям">
								<input type="submit" name="delete_on_dates" value="Удаление! (Только используя даты и класс)">
							</td>
							<td></td>
							<td title="Удалить ДЗ по классу и дате, по выбранному пользователю">
								<input type="submit" name="delete_on_user" value="Удаление! (С использованием идентификатора пользователя)">
							</td>
						</tr>-->
					</tbody>
				</table>
			</form>

		</div>
	</div>
</body>
</html>