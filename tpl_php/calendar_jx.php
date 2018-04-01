<?php
session_start();
if ( $_POST )
{
	require 'classDatabase.php';

	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if ( $_POST['start_month'] < 10 ) 
		$start_month = '0' . strval($_POST['start_month']);
	else
		$start_month = strval($_POST['start_month']);

	if ( $_POST['start_day'] < 10 )
		$start_day = '0' . strval($_POST['start_day']);
	else
		$start_day = strval($_POST['start_day']);

	if ( $_POST['end_month'] < 10 ) 
			$end_month = '0' . strval($_POST['end_month']);
		else
			$end_month = strval($_POST['end_month']);

	if ( $_POST['end_day'] < 10 )
		$end_day = '0' . strval($_POST['end_day']);
	else
		$end_day = strval($_POST['end_day']);
	
	$class = "";
	$subject = "";
	if($_POST['class_n'] != 0) {
		$class = sprintf(" AND a.id IN( SELECT id_lesson FROM os_lesson_classes WHERE id_class = %s )", $_POST['class_n']);
	}
	if($_POST['subject_v'] != 0) {
		$subject = sprintf(" AND subject = %s", $_POST['subject_v']);
	} else {
		if($_SESSION['data']['level'] == 1) {
			$subject = sprintf(" AND subject IN(SELECT id_subject FROM os_student_subjects WHERE id_student = %s)", $_SESSION['data']['id']);
		}
	}
	$end_date = strval($_POST['start_year']) . '-' . $end_month . '-' . $end_day; 
	$date = strval($_POST['start_year']) . '-' . $start_month . '-' . $start_day;

	$query = "SELECT a.id, b.name_". $_POST['language'] .", DATE(date_". $_POST['language'] .") as days, 
			  TIME(date_". $_POST['language'] .") as hours, title_" . $_POST['language'] . 
			  " as title 
			  FROM os_lessons as a
			  JOIN os_subjects as b
			  ON  a.subject = b.id
			  WHERE '$date' <= DATE(date_". $_POST['language'] .") AND a.course = 0 ";

	if ( !$_POST['isDiary'] ) 
		$query .= "AND '$end_date' >= DATE(date_". $_POST['language'] .")";
	else
		$query .= "AND '$end_date' > DATE(date_". $_POST['language'] .")";

	$query .= $class;
	$query .= $subject;

	//print("<br>$query<br>");

	$res = $mysqli->query($query);
	if($res->num_rows == 0){
		exit();
	}
	while ( $row = $res->fetch_assoc() )
	{
		$timing[strval($row['days'])][strval($row['hours']).$row['id']] = array(
			'days' => $row['days'],
			'hours' => $row['hours'],
			'name' => $row['name_'.$_POST['language']] ,
			'theme' => $row['title'],
			'id' => $row['id']
		);
	}

	print_r(json_encode($timing));

}
else
	print_r(json_encode( array( 'error' => '1') ) );
?>