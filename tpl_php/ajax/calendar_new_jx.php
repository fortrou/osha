<?php
require ('../autoload.php');
session_start();
if ( $_POST )
{
	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if($_POST['month'] < 10)
		$month = '0'.$_POST['month'];
	else
		$month = $_POST['month'];
	$start_day = '01';
	$end_day = $_POST['end_day'];
	$end_day = '31';
	$year = $_POST['year'];
	//$end_day = '36';
	if(isset($_SESSION['data']) && $_SESSION['data']['level'] > 1) {
		$year_num = get_currentYearNum(0, $month, $year);
	} else {
		$year_num = get_currentYearNum();
	}
	if(!$year_num) exit();
	$lang = $_POST['language'];
	$beg_date = "$year-$month-$start_day";
	$end_date = "$year-$month-$end_day";
	$class = "";
	$subject = "";
	if(isset($_POST['class_n'])) {
		if($_POST['class_n'] != 0) {
			$class = sprintf(" AND a.id IN( SELECT id_lesson FROM os_lesson_classes WHERE id_class = %s )", $_POST['class_n']);
		} else if(isset($_SESSION['data']) && $_SESSION['data']['level'] == 2) {
			$class = sprintf(" AND a.id IN( SELECT id_lesson FROM os_lesson_classes WHERE id_class IN( 
								SELECT id_c FROM os_teacher_class WHERE id_teacher = %s ))", 
								$_SESSION['data']['id']);
		}
	}
	if($_POST['subjects'] != 0) {
		$subject = sprintf(" AND subject = %s", $_POST['subjects']);
	} else {
	    if(isset($_SESSION['data']) && $_SESSION['data']['level'] == 2) {
	        $subject = sprintf(" AND subject IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher = %s AND course = 0) ", $_SESSION['data']['id']);
	    } else if(isset($_SESSION['data']) && $_SESSION['data']['level'] == 1) {
	        $subject = sprintf(" AND subject IN(SELECT id_subject FROM os_student_subjects WHERE id_student = %s) ", $_SESSION['data']['id']);
	    }
	}
	$sql = sprintf("SELECT a.id, b.name_%s, DATE(a.date_%s) as days, 
			  TIME(a.date_%s) as hours, title_%s FROM os_lessons as a
			  JOIN os_subjects as b
			  ON  a.subject = b.id WHERE 1=1 $class AND DATE(a.date_%s) BETWEEN '%s' AND '%s' $subject AND a.lesson_year = %s ORDER BY a.date_%s",
			  $lang,$lang,$lang,$lang,$lang,$beg_date,$end_date,$year_num,$lang);
	$res = $mysqli->query($sql);
	$result = array();
	$iter = 0;
	if($res->num_rows == 0)
		exit();
	while ($row = $res->fetch_assoc()){
		$result[$iter] = array (
			"id"   => $row['id'],
			"name" => $row['name_'.$lang],
			"days" => $row['days'],
			"hours" => $row['hours'],
			"title" => $row['title_'.$lang]
		);
		$iter++;
	}
	//$result = var_dump($result);
	print(json_encode($result));

}

?>