<?php
	session_start();
	require_once("tpl_php/autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_GET['change_course']) {
  	$_SESSION['data']['currentCourse'] = $_GET['course'];
  	if($_SESSION['data']['currentCourse'] == 0) {
  		header("Location:http://" . $_SERVER['HTTP_HOST'] . "/schedule/calendar.php");
  	} else {
  		if($_SESSION['data']['level'] > 1) {
  			header("Location:http://" . $_SERVER['HTTP_HOST'] . "/schedule/courseDiary.php");
  		}
  		$sql = sprintf("SELECT * FROM os_courses_students WHERE id_user=%s AND id_course=%s AND id=(
  	   					             SELECT MAX(id) FROM os_courses_students WHERE id_user=%s AND id_course=%s)",
  						               $_SESSION['data']['id'],$_GET['course'],$_SESSION['data']['id'],$_GET['course']);
  		$res = $mysqli->query($sql);
  		if($res->num_rows != 0) {
  			$row = $res->fetch_assoc();
  			if($row['payment_end_date'] >= Date("Y-m-d")) {
  				header("Location:http://" . $_SERVER['HTTP_HOST'] . "/schedule/courseDiary.php");
  			}
  		}
  		header("Location:http://" . $_SERVER['HTTP_HOST'] . "/cabinet/index.php#tab_4");
 		}
 	}
?>