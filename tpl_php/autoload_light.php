<?php
	ini_set('display_errors','Off');
	require_once('functions.php');
	if(!function_exists("__autoload")) {
		function __autoload($name)
		{
			require 'class' . $name . '.php';
		}
	}

  	$db = Database::getInstance();
		$mysqli = $db->getConnection();
	if (!isset($_COOKIE['lang'])) {
		setcookie("lang","ru",time()+1000*60*60*24*7);
		$_SESSION['lang'] = 'ru';
	}
	if(isset($_POST['ua'])){
		unset_cookie("lang");
		setcookie("lang","ua",time()+1000*60*60*24*7);
		$_SESSION['lang'] = 'ua';
		header("Location:".$_SERVER['REQUEST_URI']);
	}
  	if(isset($_POST['ru'])){
		unset_cookie("lang");
		setcookie("lang","ru",time()+1000*60*60*24*7);
		$_SESSION['lang'] = 'ru';
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	header('Content-Type: text/html; charset=utf-8', true);
	check_payStatus();
	function check_payStatus() {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		if(isset($_SESSION['data'])) {
			if($_SESSION['data']['level'] == 1) {
				if($_SESSION['data']['currentCourse'] > 0) {
					$sql = sprintf("SELECT * FROM os_courses_students WHERE id_course=%s AND id_user=%s AND id = (
									SELECT MAX(id) FROM os_courses_students WHERE id_course=%s AND id_user=%s AND payment_verified=1)"
									,$_SESSION['data']['currentCourse'],$_SESSION['data']['id'],$_SESSION['data']['currentCourse'],$_SESSION['data']['id']);
					//print($sql);
					$res = $mysqli->query($sql);
					if($res->num_rows != 0) {
						$row = $res->fetch_assoc();
						//var_dump($row);
						if(strtotime($row['payment_end_date']) < strtotime(Date("Y-m-d"))) {
							header("Location:http://" . $_SERVER['HTTP_HOST'] . "/cabinet/index.php#tab_4");
						}

					} else {
						header("Location:http://" . $_SERVER['HTTP_HOST'] . "/cabinet/index.php#tab_4");
					}
				}
			}
		}
	}
	function check_course_payment($user_id, $course_id) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = sprintf("SELECT * FROM ");
	}
	if(!function_exists("get_currentYearNum")) {
		function get_currentYearNum($current = 1, $month = 8, $year = 2017) {
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$currentDate_params = array( 'day' 	 => (int)Date("d"),
										 'month' => (int)Date("m"),
										 'year'  => (int)Date("Y")
									   );
			if($current == 1) {
				$year_type = $currentDate_params['month'] < 8 ? 'year_end' : 'year_start';
				$sql = sprintf("SELECT * FROM os_year_date WHERE $year_type = %s",
								$currentDate_params['year'], $currentDate_params['year']);
			} else {
				$year_type = $month < 8 ? 'year_end' : 'year_start';
				$sql = sprintf("SELECT * FROM os_year_date WHERE $year_type = %s",
								$year, $year);
			}
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$row = $res->fetch_assoc();
				return $row['year_number'];
			}
			return false;
		}
	}
	function check_and_generate_theme_mark($id_user, $id_theme, $id_class, $id_subject, $semester = 'first_s', $trigger_semester = 0) {
		$year 	 	= Date('Y') . '-';
		$theme_mark = 0;

		// main objects
		$options 	  = new Options;
		$db 		  = Database::getInstance();
		$mysqli 	  = $db->getConnection();

		// year and week
		$current_year = get_currentYearNum();
		$week_start   = date("Y-m-d",define_week_start_and_end("start", strtotime(date("Y-m-d"))));
		$week_end     = date("Y-m-d",define_week_start_and_end("end", strtotime(date("Y-m-d"))));
		
		// options data
		$current_semester   = $options->get_option('semester_current_number');
		$semester_end_date  = $options->get_option('semester_end_date');
		$first_s_day_month  = $options->get_option('first_semester_nominal_date');
		$second_s_day_month = $options->get_option('second_semester_nominal_date');
		$first_semester_array  = explode('|', $first_s_day_month);
		$second_semester_array = explode('|', $second_s_day_month);
		$first_semester_array[0]  = $year . $first_semester_array[0];
		$first_semester_array[1]  = $year . $first_semester_array[1];
		$second_semester_array[0] = $year . $second_semester_array[0];
		$second_semester_array[1] = $year . $second_semester_array[1];

		// user data
		$sql_user_data = sprintf("SELECT * FROM os_users WHERE id = %s", $id_user);
		$res_user_data = $mysqli->query($sql_user_data);
		$row_user_data = $res_user_data->fetch_assoc();

		// script
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
					/*echo "<pre>";
						print_r($row);
					echo "</pre>";*/
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
			if($count)
				$mark = $mark / $count;
			else
				$mark = 0;

			foreach($result_control as $value) {
				$mark_control += (int)$value['mark_contr'] + (int)$value['mark_hw'];
			}
			if($count_control)
				$mark_control = $mark_control / $count_control;
			else 
				$mark_control = 0;
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
			if($res->num_rows == 0) {
				$theme_mark = $theme_mark . '-excepted';
			}
		} else {
			$row_theme = $res_theme->fetch_assoc();
			$theme_mark = $row_theme['mark'];
			if($row_theme['is_redacted'] == 1) {
				$theme_mark = $theme_mark . '-redacted';
			}
		}
		return $theme_mark;
	}
	//if(!function_exists("unset_cookie")) {
		
	//}
?>