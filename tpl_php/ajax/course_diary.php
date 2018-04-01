<?php
	require_once('../autoload_light.php');
	require_once('../functions.php');
	session_start();
	/*echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";*/
	if(!isset($_POST['lang']) || $_POST['lang'] == 'ru') {
		$test_mark = " б. - тестовое ДЗ";
		$hw_mark   = " б. - творческое ДЗ";
		$hints 	   = array( "common" => "Подсчитывается количество уроков, которые вы открыли",
							"already_is" => "Вышел",
							"theme_mark" => "Тематическая оценка: " );
	} else {
		$test_mark = " б. - тестове ДЗ";
		$hw_mark   = " б. - творче ДЗ";
		$hints 	   = array( "common" => "Підраховується кількість уроків, які ви відкривали",
							"already_is" => "Вышел",
							"theme_mark" => "Тематична оцiнка: ");
	}
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$current_year = get_currentYearNum();
	$week_start = date("Y-m-d",define_week_start_and_end("start", strtotime(date("Y-m-d"))));
	$week_end   = date("Y-m-d",define_week_start_and_end("end", strtotime(date("Y-m-d"))));
	if($_POST){
		if($_POST['flag'] == '1') {
			if(!isset($_POST['lesson_year']) || $_POST['lesson_year'] == 0 || empty($_POST['lesson_year'])) {
				$_POST['lesson_year'] = $current_year;
			}
			$add_element = '';
			$where_subject = "";
			$where_class   = "";
			$and_lesson_year = '';
			$add_element_lesson = "";
			$where_class_lesson = "";
			if($_POST['course_id'] == 0)
				$and_lesson_year = sprintf(" AND lesson_year = %s", $_POST['lesson_year']);
			if($_POST['subject'] != 0) {
				$where_subject = " AND theme_subject=" . $_POST['subject'];
			} else {
				switch($_POST['level']) {
					case 2:
						$where_subject = sprintf(" AND theme_subject IN(SELECT DISTINCT id_s FROM os_teacher_subj WHERE id_teacher=%s AND course=%s)",
												   $_POST['high_id'], $_POST['course_id']);
						break;
				}
			}
			if($_POST['class_id'] != 0) {
				$where_class = sprintf(" AND id IN(SELECT id_theme FROM os_theme_classes WHERE id_class=%s)", $_POST['class_id']);
				$where_class_lesson = sprintf(" AND id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=%s)", $_POST['class_id']);
			} else {
				switch($_POST['level']) {
					case 1: 
						$where_class = sprintf(" AND id IN(SELECT id_theme FROM os_theme_classes WHERE id_class IN(
														   SELECT DISTINCT class FROM os_users WHERE id=%s))", 
												 $_POST['high_id']);
						$where_class_lesson = sprintf(" AND id IN(SELECT id_lesson FROM os_lessons_classes WHERE id_class IN(
														   	SELECT DISTINCT class FROM os_users WHERE id=%s))", 
												 $_POST['high_id']);
						break;
					case 2:
						$where_class = sprintf(" AND id IN(SELECT id_theme FROM os_theme_classes WHERE id_class IN(
														   SELECT DISTINCT id_c FROM os_teacher_class WHERE id_teacher=%s))", 
												 $_POST['high_id']);
						$where_class_lesson = sprintf(" AND id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class IN(
														   SELECT DISTINCT id_c FROM os_teacher_class WHERE id_teacher=%s))", 
												 $_POST['high_id']);
						break;
					case 3:
						$where_class = sprintf(" AND id IN(SELECT id_theme FROM os_theme_classes WHERE id_class IN(
														   SELECT DISTINCT id FROM os_class_manager WHERE id_manager=%s))", 
												 $_POST['high_id']);
						$where_class_lesson = sprintf(" AND id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class IN(
														   SELECT DISTINCT id FROM os_class_manager WHERE id_manager=%s))", 
												 $_POST['high_id']);
						break;
					case 4:
						/*$where_class = sprintf(" AND id IN(SELECT id_theme FROM os_theme_classes WHERE id_class IN(
														   SELECT DISTINCT id_c FROM os_teacher_class WHERE id_teacher=%s))", 
												 $_POST['high_id']);
						$where_class_lesson = sprintf(" AND id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class IN(
														   SELECT DISTINCT id_c FROM os_teacher_class WHERE id_teacher=%s))", 
												 $_POST['high_id']);*/
						break;
				}

			}
			
			if($_POST['course_id'] == 0) {
				if(!isset($_POST['archieve_year']) || empty($_POST['archieve_year'])) {
					$add_element = sprintf(" AND id IN(SELECT DISTINCT theme FROM os_lessons WHERE lesson_year = %s)", $current_year);
					$add_element_lesson = sprintf(" AND lesson_year = %s", $current_year);
				}
			}
			$sql_user_data = sprintf("SELECT * FROM os_users WHERE id = %s", $_POST['id']);
			$res_user_data = $mysqli->query($sql_user_data);
			$row_user_data = $res_user_data->fetch_assoc();
			$sql = sprintf("SELECT * FROM os_themes WHERE ((theme_course=%s $where_subject $where_class) OR id = 0) $add_element ORDER BY id ASC"
				,$_POST['course_id']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$result = "";
				$where_class   = "";
				if($_POST['class'] != 0) {
					$where_class = sprintf(" AND id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=%s)", $_POST['class_id']);
				}
				while($row = $res->fetch_assoc()) {
					$attribute_set = '';
					$lessons_iter = 0;
					$theme_mark = 0;
					$sql_week_lessons = sprintf("SELECT COUNT(*) AS cnt FROM os_lessons 
												  WHERE theme  = %s
												  	AND course = %s 
												    AND date_ru >= '%s' 
												    AND date_ua >= '%s'
												    AND date_ru <= '%s' 
												    AND date_ua <= '%s'
												    AND subject = %s
												    $where_class
												    $add_element_lesson",
												    $row['id'], $_POST['course_id'], $week_start, $week_start, $week_end, $week_end, $_POST['subject']);
					//print("<br>$sql_week_lessons<br>");
					$res_week_lessons = $mysqli->query($sql_week_lessons);
					if($res_week_lessons->num_rows != 0) {
						$row_week_lessons = $res_week_lessons->fetch_assoc();
						if($row_week_lessons['cnt'] != 0)
							$attribute_set .= ' data-open="1"';
					} else {
						$row_week_lessons = array( "cnt" => 0 );
					}
						$percentage = 0;
						$percentage_full = 0;
						$sql_visited = sprintf("SELECT * FROM os_journal WHERE visit_status=1 AND id_s=%s AND id_l 
														   IN (SELECT id FROM os_lessons WHERE theme=%s AND subject = %s %s 
														   AND id 
														    IN (SELECT id_lesson FROM os_lesson_classes 
																		        WHERE id_class 
																			       IN (SELECT class FROM os_users 
																				WHERE id = %s)))",
														   $_POST['id'],$row['id'],$_POST['subject'],$and_lesson_year,$_POST['id']);
						$res_visited = $mysqli->query($sql_visited);
						$sql_unvisited = sprintf("SELECT * FROM os_journal WHERE id_s=%s AND id_l 
														     IN (SELECT id FROM os_lessons WHERE theme=%s AND subject = %s %s
														 	AND id 
														     IN (SELECT id_lesson FROM os_lesson_classes 
																		         WHERE id_class 
																			        IN (SELECT class FROM os_users 
																				 WHERE id = %s)))",
														     $_POST['id'],$row['id'],$_POST['subject'],$and_lesson_year, $_POST['id']);
						$res_unvisited = $mysqli->query($sql_unvisited);
						if($res_visited->num_rows != 0 && $res_unvisited->num_rows != 0) {
							$visited_rows = $res_visited->num_rows;
							$unvisited_rows = $res_unvisited->num_rows;
							$percentage = ceil($visited_rows/$unvisited_rows * 100);
						}
						$sql_visited = sprintf("SELECT * FROM os_journal WHERE visit_status=1 AND id_s=%s AND id_l 
														   IN (SELECT id FROM os_lessons WHERE subject=%s %s
														  AND id 
														   IN (SELECT id_lesson FROM os_lesson_classes 
																		       WHERE id_class 
																			      IN (SELECT class FROM os_users 
																			   WHERE id = %s)))",
														   $_POST['id'], $_POST['subject'],$and_lesson_year, $_POST['id']);
						$res_visited = $mysqli->query($sql_visited);
						$sql_unvisited = sprintf("SELECT * FROM os_journal WHERE id_s=%s AND id_l 
														     IN (SELECT id FROM os_lessons WHERE subject=%s %s
														    AND id 
														     IN (SELECT id_lesson FROM os_lesson_classes 
																		         WHERE id_class 
																			        IN (SELECT class FROM os_users 
																				 WHERE id = %s)))",
														     $_POST['id'], $_POST['subject'],$and_lesson_year, $_POST['id']);
						$res_unvisited = $mysqli->query($sql_unvisited);
						$full_persentage_bar = '';
						if($res_visited->num_rows != 0 && $res_unvisited->num_rows != 0) {
							$visited_rows = $res_visited->num_rows;
							$unvisited_rows = $res_unvisited->num_rows;
							$percentage_full = ceil($visited_rows/$unvisited_rows * 100);
						} else {
							$percentage_full = 0;
						}

							$full_persentage_bar = '<div class="progress-background">
														<img src="../tpl_img/shedule-progress-texture.png" alt="" style="width:' . $percentage_full . '%;">
													</div>
													<div class="progress-hint">' . $hints['common'] . '</div>
													<p class="progress-counter">' . $percentage_full . '%</p>';
					$sql_lessons = sprintf("SELECT * FROM os_lessons 
													WHERE course=%s 
													  AND theme=%s 
													  AND subject = %s
													  $where_class 
													  $add_element_lesson 
													  $where_class_lesson
												 ORDER BY date_%s"
						, $_POST['course_id'], $row['id'], $_POST['subject'], $_POST['lang']);
					//print("<br>\n $sql_lessons \n<br>");
					$res_lessons = $mysqli->query($sql_lessons);
					if($res_lessons->num_rows != 0) {

						$result .= "<div class='timetable-tab-container timetable-closed' $attribute_set id='timetable_container_" . $row['id'] . "'>";
						$result .= "<table class='timetable-head' onclick='timetable_spoiler(" . $row['id'] . ")'><tr>
										<td class='theme-image'></td>
										<td>" . $row['theme_name_' . $_POST['lang']] . "</td>
										<td>
											<div class='progress-container'>
											<div class='progress-background'>
												<img src='../tpl_img/shedule-progress-texture.png' alt='' style='width:$percentage%;'>
											</div>
											<div class='progress-hint'>" . $hints['common'] . "</div>
											<p class='progress-counter'>$percentage%</p>
											</div>
										</td>
									</tr></table>";
						$result .= "<table class='lessons-container'>";
						while($row_lessons = $res_lessons->fetch_assoc()) {
							$class_string = '';
							$is_verbal = '';
							if($row_lessons['is_control'] == 1) {
								$class_string .= ' control-string-journal';
							}
							if($row_lessons['is_verbal'] == 1) {
								$is_verbal = '<span class="vernal-hint"> У</span>';
							}
							if(Date("Y-m-d", strtotime($row_lessons['date_' . $_POST['lang']])) <= Date("Y-m-d",time())) {
								$sql_journal = sprintf("SELECT * FROM os_journal WHERE id_s=%s AND id_l=%s", $_POST['id'], $row_lessons['id']);
								//print("<br>$sql_journal<br>");
								$res_journal = $mysqli->query($sql_journal);
								if($res_journal->num_rows != 0) {
									$row_journal = $res_journal->fetch_assoc();
                                	$image = "";
									if($row_journal['visit_status'] == 1) {
										$image = "<img src='../tpl_img/checked.png' alt=''>";
										$help = sprintf("<img class='help' src='../tpl_img/help-button.png' alt=''> $is_verbal
															<div class='mark-description'>
																%s %s 
																<br> 
																%s %s 
															</div>", $row_journal['mark_contr'], $test_mark, $row_journal['mark_hw'], $hw_mark);
										$mark = (int)$row_journal['mark_contr'] + (int)$row_journal['mark_hw'];
										$mark_contr = (int)$row_journal['mark_contr'] . $test_mark;
										$mark_hw = "" . (int)$row_journal['mark_hw'] . $hw_mark;
									} else {
										if(Date("Y-m-d", strtotime($row_lessons['date_' . $_POST['lang']])) == Date("Y-m-d",time()))
											$image = "<img src='../tpl_img/circle.png' alt=''>";
										$help = sprintf("<img class='help' src='../tpl_img/help-button.png' alt=''> $is_verbal
															<div class='mark-description'>
																%s %s 
																<br> 
																%s %s 
															</div>", $row_journal['mark_contr'], $test_mark, $row_journal['mark_hw'], $hw_mark);;
										$mark = (int)$row_journal['mark_contr'] + (int)$row_journal['mark_hw'];
										$mark_contr = "";
										$mark_hw = "";
									}
									$theme_mark += (int)$row_journal['mark_contr'] + (int)$row_journal['mark_hw'];
									$link = "<a href='../lessons/watch.php?id=" . $row_lessons['id'] . "'>" . $row_lessons['title_' . $_POST['lang']] . "</a>";
									if($_POST['course_id'] != 0) {
										$hint_text = $hints['already_is'];
									} else {
										$hint_text = Date("Y-m-d", strtotime($row_lessons['date_' . $_POST['lang']]));
									}
									$pre_result = "<tr class='$class_string'>
													<td class='theme-image'>$image</td>
													<td class='theme-container'>" . $link . "</td>
													<td class='question-container'>" . $hint_text . "</td>
													<td class='question-container'><span>$mark</span> $help</td>
												</tr>";
									$result .= $pre_result;
									//print("<br>$result<br>");
								} else {
								    $sql_j = sprintf("INSERT INTO os_journal(id_s,id_l,date_ru,date_ua,status,id_subj, course, theme) VALUES(%s,%s,'%s','%s',1,%s,%s,%s)",
							            $_POST['id'],$row_lessons['id'],$row_lessons['date_ru'],$row_lessons['date_ua'],
							            $row_lessons['subject'],$_POST['course_id'],$row['id']);
							            $res_j = $mysqli->query($sql_j);
								}
							} else {
								if($_POST['course_id'] != 0) {
									$hint_text = $row_lessons['date_' . $_POST['lang']];
								} else {
									$hint_text = Date("Y-m-d", strtotime($row_lessons['date_' . $_POST['lang']]));
								}
								$result .= "<tr class='inactive'>
												<td class='theme-image'></td>
												<td class='theme-container'>" . $row_lessons['title_' . $_POST['lang']] . "</td>
												<td class='question-container'>" . $hint_text . "</td>
												<td class='question-container'>Оценка</td>
											</tr>";
							}
							$lessons_iter++;
						}
						if($lessons_iter == 0) {
							$lessons_iter = 1;
						}
						if($_POST['course_id'] == 0) {
							$theme_mark = explode('-', check_and_generate_theme_mark($_POST['id'], $row['id'], $row_user_data['class'], $row['theme_subject']));
							if($_SESSION['data']['level'] == 4) {
								$is_redacted = '';
								if(isset($theme_mark[1]) && !empty($theme_mark[1]) && $theme_mark[1] == 'redacted') {
									$is_redacted = '<span class="theme-mark-redacted"> (РЕД) </span>';
								}
								$mark = sprintf('<input type="text" value="%s" 
														class="theme-mark-input" 
														oninput="(update_theme_mark(this))"
														data-user="%s"
														data-theme="%s"
														data-class="%s"
														data-subject="%s"> %s', 
														$theme_mark[0], $_POST['id'], $row['id'], $row_user_data['class'], $row['theme_subject'], $is_redacted);
							} else {
								$mark = "<span>" . 
											$theme_mark[0]
										. "</span>";
							}
							$result .= sprintf("<tr class='third-journal-thematic'>
													<td></td>
													<td colspan='2'>%s</td>
													<td>%s</td>
												</tr>", 
												$hints['theme_mark'], $mark);
												//round(($theme_mark/$lessons_iter),1));
						}
						$result .= "</table>";
						$result .= "</div>";
					}
					$iter++;
				}
				$data = array( "percentage"   => $full_persentage_bar,
							   "date_reg"	  => $row_user_data['date_start_learning'], 
							   "student_data" => $row_user_data['surname'] . " " . 
							   					 $row_user_data['name'] . " " . 
							   					 $row_user_data['patronymic'] . "  |  Класс: " .
							   					 $row_user_data['class'] . " | ID: " . 
							   					 $row_user_data['id'],
							   "result" 	  => $result);

				print_r(json_encode($data));
			}
			exit();
		}
		if($_POST['flag'] == '2') {
			$where_subject = "";
			if($_POST['subject'] != 0) {
				$where_subject = " AND theme_subject=" . $_POST['subject'];
			}
			$sql = sprintf("SELECT * FROM os_themes WHERE theme_course=%s $where_subject AND id IN(SELECT id_theme FROM os_theme_classes WHERE id_class=%s) ORDER BY id DESC"
				,$_POST['course_id'],$_POST['class_id']);
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$result = "";
				while($row = $res->fetch_assoc()) {
					$sql_lessons = sprintf("SELECT * FROM os_lessons WHERE course=%s AND theme=%s AND id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=%s) ORDER BY date_%s"
						,$_POST['course_id'],$row['id'],$_POST['class_id'], $_POST['lang']);
					$res_lessons = $mysqli->query($sql_lessons);
					if($res_lessons->num_rows != 0) {
						$percentage = 0;
						$sql_visited = sprintf("SELECT * FROM os_journal WHERE visit_status=1 AND id_s=%s AND id_l 
														   IN (SELECT id FROM os_lessons WHERE theme=%s)",$_POST['id'],$row['id']);
						$res_visited = $mysqli->query($sql_visited);
						$sql_unvisited = sprintf("SELECT * FROM os_journal WHERE id_s=%s AND id_l 
														     IN (SELECT id FROM os_lessons WHERE theme=%s)",$_POST['id'],$row['id']);
						$res_unvisited = $mysqli->query($sql_unvisited);
						if($res_visited->num_rows != 0 && $res_unvisited->num_rows != 0) {
							$visited_rows = $res_visited->num_rows;
							$unvisited_rows = $res_unvisited->num_rows;
							$percentage = ceil($visited_rows/$unvisited_rows * 100);

						}
					}
				}
			}
		}
		if($_POST['flag'] == '3') {
			$sql = sprintf("SELECT * FROM os_users WHERE id=%s",$_POST['id']);
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$row = $res->fetch_assoc();
				switch($row['level']) {
					case 1:
						exit();
						break;
					default:
						$sql_users = sprintf("SELECT * FROM os_users WHERE 1 = 1 AND level = 1 ");
						if($_POST['course_id'] != 0) {
							$sql_users .= sprintf(" AND id IN(SELECT id_user FROM os_courses_students WHERE id_course=%s)", 
													$_POST['course_id']);
						}
						if($_POST['class_id'] != 0) {
							$sql_users .= sprintf(" AND class=%s", $_POST['class_id']);
						}
						break;
				}
				//print($sql_users);
				$res_users = $mysqli->query($sql_users);
				if($res_users->num_rows != 0) {
					$result = "";
					while($row_users = $res_users->fetch_assoc()) {
						$result .= sprintf("<div data-rel='%s' onclick='get_lessonsOnThemes_Teacher(%s)'>
												<p>%s %s</p>
											</div>",$row_users['id'],$row_users['id'],$row_users['surname'],$row_users['name']);
					}
					print_r(json_encode($result));
				}
				exit();
			}
			exit();
		}
	}
	if($_POST['flag'] == 4) {
		update_theme_mark($_POST['user_id'], $_POST['theme_id'], $_POST['class_id'], $_POST['subject_id'], $_POST['mark']);
	}
	/*function check_and_generate_theme_mark($id_user, $id_theme, $id_class, $id_subject) {
		$theme_mark = 0;
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$current_year = get_currentYearNum();
		$week_start = date("Y-m-d",define_week_start_and_end("start", strtotime(date("Y-m-d"))));
		$week_end   = date("Y-m-d",define_week_start_and_end("end", strtotime(date("Y-m-d"))));
		$sql_user_data = sprintf("SELECT * FROM os_users WHERE id = %s", $id_user);
		$res_user_data = $mysqli->query($sql_user_data);
		$row_user_data = $res_user_data->fetch_assoc();
		$sql_theme = sprintf("SELECT * FROM os_theme_mark 
									  WHERE id_user = %s
									    AND id_subject = %s
									    AND id_class = %s
									    AND id_theme = %s
									    AND is_redacted = 1",
								$id_user, $id_subject, $id_class, $id_theme);
		//print("<br> $sql_theme <br>\n");
		//print("\n $id_theme \n");
		$res_theme = $mysqli->query($sql_theme);
		if($res_theme->num_rows == 0) {
			$sql = sprintf("SELECT * FROM os_journal 
									WHERE id_s = %s 
									  AND id_l 
									   IN (SELECT id FROM os_lessons
													WHERE theme = %s
													  AND subject = %s 
													  AND is_verbal = 0
													  AND id
													   IN (SELECT id_lesson FROM os_lesson_classes 
																		   WHERE id_class = %s
																			 AND DATE(date_ua) > '%s'))",
								$id_user, $id_theme, $id_subject, $id_class, $row_user_data['date_start_learning']);
			$res = $mysqli->query($sql);
			$result = array();
			$result_control = array();
			if($res->num_rows != 0) {
				while($row = $res->fetch_assoc()) {
					if($row['status'] == 1) {
						$result[] = array( "mark_contr" => $row['mark_contr'], 
										   "mark_hw"    => $row['mark_hw'] );
					} else {
						$result_control[] = array( "mark_contr" => $row['mark_contr'], 
										   		   "mark_hw"    => $row['mark_hw'] );
					}
				}
			}
			$mark = 0;
			$mark_control = 0;
			$count = count($result);
			$count_control = count($result_control);
			foreach($result as $value) {
				$mark += (int)$value['mark_contr'] + (int)$value['mark_hw'];
			}
			$mark = $mark / $count;
			foreach($result_control as $value) {
				$mark_control += (int)$value['mark_contr'] + (int)$value['mark_hw'];
			}
			$mark_control = $mark_control / $count_control;
			
			if($count_control == 0 && $count == 0) {
				$theme_mark = 0;
			} else if($count_control == 0) {
				$theme_mark = round($mark);
			} else if($count == 0 || $row_user_data['class'] == 11) {
				$theme_mark = round($mark_control);
			} else {
				$theme_mark = round(($mark + $mark_control)/2);
			}
			$sql_theme = sprintf("SELECT * FROM os_theme_mark 
									  WHERE id_user = %s
									    AND id_subject = %s
									    AND id_class = %s
									    AND id_theme = %s",
								$id_user, $id_subject, $id_class, $id_theme);
			$res_theme = $mysqli->query($sql_theme);
			if($res_theme->num_rows == 0) {
				$sql_insert_theme = sprintf("INSERT INTO os_theme_mark(id_user, id_subject, id_class, id_theme, id_year, mark)
												  VALUES (%s, %s, %s, %s, %s, %s)",
												  $id_user, $id_subject, $id_class, $id_theme, $current_year, $theme_mark);
			} else {
				$sql_insert_theme = sprintf("UPDATE os_theme_mark 
											    SET mark = %s
											  WHERE id_user = %s 
											    AND id_subject = %s 
											    AND id_class = %s 
											    AND id_theme = %s
											    AND id_year = %s",
											    $theme_mark, $id_user, $id_subject, $id_class, $id_theme, $current_year);
			}
			$res_insert_theme = $mysqli->query($sql_insert_theme);
		} else {
			$row_theme = $res_theme->fetch_assoc();
			$theme_mark = $row_theme['mark'];
		}
		if($row_theme['is_redacted'] == 1) {
			$theme_mark = $theme_mark . '-redacted';
		}
		return $theme_mark;
	}*/
	function update_theme_mark($user_id, $theme_id, $class_id, $subject_id, $mark) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$current_year = get_currentYearNum();
		$sql_insert_theme = sprintf("UPDATE os_theme_mark 
									    SET mark = %s, is_redacted = 1 
									  WHERE id_user = %s 
									    AND id_subject = %s 
									    AND id_class = %s 
									    AND id_theme = %s
									    AND id_year = %s",
									    $mark, $user_id, $subject_id, $class_id, $theme_id, $current_year);
		//print("<br> \n $sql_insert_theme \n <br>");
		$res_insert_theme = $mysqli->query($sql_insert_theme);
		if($mysqli->affected_rows > 0) return true;
	}

?>