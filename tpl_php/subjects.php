<?php 
session_start();
require 'classDatabase.php';
$db = Database::getInstance();
$mysqli = $db->getConnection();

if ( $_POST['flag']=='1' )
{
	$where = "";
		$id = $_POST['id'];
		if(is_string($id) || is_numeric($id)) {
			$classList = $id;
			$where  = "class='$id'";
			$where2 = "id_c='$id'";
		} else if(is_array($id)) {
			$classList = "";
			foreach ($id as $value) {
				$classList .= $value . ", ";
			}
			$classList = rtrim($classList, ", ");
			$where     = "class IN (" . $classList . ")";
			$where2	   = "id_c IN (" . $classList . ")";
		}
		$where_course = "";
		$teacher_course = "";
		if(isset($_POST['course'])) {
			$where_course = " WHERE course=" . $_POST['course'];
			if($_POST['course']>0) {
				$teacher_course = sprintf(" AND id IN(SELECT id_teacher FROM os_courses_teachers WHERE id_course=%s)", $_POST['course']);
			}
		} else {
			$where_course = " AND course = 0";
		}
		$sql = "SELECT DISTINCT id_s AS id, name_".$_POST['lang']." AS name FROM os_class_subj as a
			JOIN os_subjects as b 
			ON a.id_s = b.id $where_course ";
		if($where != "") {
			$where = " AND " . $where;
			$sql  .= $where;
		}
		if($where != "") {
			$where2 = " WHERE " . $where2;
		}

		$result = $mysqli->query($sql);

		$data = array();

		while ( $row = $result->fetch_assoc() ) 
		{
			$data['subjects'][$row['id']] = $row['name'];
		}

		$sql = sprintf("SELECT concat(name,'(', login, ')' ) as `name`, id 
				FROM os_users AS ou WHERE level = 2 AND id IN 
				(SELECT id_teacher FROM os_teacher_class %s) %s", $where2, $teacher_course);
		//print("<br>$sql<br>");
		$result = $mysqli->query($sql);

		while ( $row = $result->fetch_assoc() )
		{
			$data['teacher'][$row['id']] = $row['name'];
		}
		$sql = sprintf("SELECT * FROM os_themes 
								WHERE id 
								   IN ( SELECT id_theme 
								   		  FROM os_theme_classes 
								   		 WHERE id_class 
								   		    IN (%s))
								  AND theme_course = %s", $classList, $_POST['course']);
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		$data['themes'] = "<option value='0'>Без темы</option>";

		if($res->num_rows != 0) {
			while($row = $res->fetch_assoc()) {
				$data['themes'] .= sprintf("<option value='%s'>%s</option>", $row['id'], $row['theme_name_ru']);
			}
		}
		print_r(json_encode($data));

}
if($_POST['flag'] == '2'){
		$class_id = $_POST['class_id'];
		if(is_string($class_id) || is_numeric($class_id)) {
			$classList = $class_id;
			$where = "class='$class_id'";
		} else if(is_array($class_id)) {
			$classList = "";
			foreach ($class_id as $value) {
				$classList .= $value . ", ";
			}
			$classList = rtrim($classList, ", ");
		}
		$where_course = "";
		$teacher_course = "";
		if(isset($_POST['course'])) {
			$where_course = " AND course=" . $_POST['course'];
			if($_POST['course']>0) {
				$teacher_course = sprintf(" AND id IN(SELECT id_teacher FROM os_courses_teachers WHERE id_course=%s)", $_POST['course']);
			}
		}
		$sql = sprintf("SELECT concat(name,'(', login, ')' ) as `name`, id 
				FROM os_users AS ou WHERE level = 2 AND id IN 
				(SELECT id_teacher FROM os_teacher_class WHERE id_c IN(%s)) AND id IN 
				(SELECT id_teacher FROM os_teacher_subj WHERE id_s=%s $where_course) $teacher_course",$classList,$_POST['subject_id']);
		$result = $mysqli->query($sql);
		$data = "<option value='0'>Без учителя</option>";
		while ( $row = $result->fetch_assoc() )
		{
			$data .= "<option value='".$row['id']."'>".$row['name']."</option>";
		}

		$sql = sprintf("SELECT * FROM os_themes 
								WHERE id 
								   IN ( SELECT id_theme 
								   		  FROM os_theme_classes 
								   		 WHERE id_class 
								   		    IN (%s))
								  AND theme_course = %s
								  AND theme_subject = %s", $classList, $_POST['course'], $_POST['subject_id']);
		/*$res = $mysqli->query($sql);
		$data['themes'] = "<option value='0'>Без темы</option>";

		if($res->num_rows != 0) {
			while($row = $res->fetch_assoc()) {
				$data['themes'] .= sprintf("<option value='%s'>%s</option>", $row['id'], $row['theme_name_ru']);
			}
		}*/

		print_r(json_encode($data));
}
if($_POST['flag'] == '3') {
	$class_id   = $_POST['class_id'];
	$course_id  = $_POST['course_id'];
	$subject_id = $_POST['subject_id'];
	$return_arr = array( 'subject'   => '',
						 'theme'     => '',
						 'teacher' 	  => '' );

	if(is_string($class_id) || is_numeric($class_id)) {
		$classList = $class_id;
		$where = "class='$class_id'";
	} else if(is_array($class_id)) {
		$classList = "";
		foreach ($class_id as $value) {
			$classList .= $value . ", ";
		}
		$classList = rtrim($classList, ", ");
	}
	$where_course = "";
	$teacher_course = "";
	if(isset($course_id)) {
		$where_course = " AND course=" . $course_id;
		if($course_id>0) {
			$teacher_course = sprintf(" AND id IN(SELECT id_teacher FROM os_courses_teachers WHERE id_course=%s)", $course_id);
		}
	}
	$class = "";
	$theme_class = "";
	$subject_class = "";
	$subject = "";
	$theme_subject = "";
	if($classList != 0) {
		$class 	 	   = sprintf(" AND id IN ( SELECT id_teacher FROM os_teacher_class WHERE id_c IN(%s))", $classList);
		$theme_class   = sprintf(" AND id IN ( SELECT id_theme FROM os_theme_classes WHERE id_class IN (%s))", $classList);
		$subject_class = sprintf(" AND id IN ( SELECT id_s FROM os_class_subj WHERE class IN(%s) AND course = %s)", $classList, $course_id);
	}
	if($subject_id != 0) {
		$subject = sprintf(" AND id IN (SELECT id_teacher FROM os_teacher_subj WHERE id_s=%s $where_course)", $subject_id);
		$theme_subject = sprintf( "AND theme_subject = %s",$subject_id);
	}

// собираем учителей
	$sql = sprintf("SELECT concat(name,'(', login, ')' ) as `name`, id 
			FROM os_users AS ou WHERE level = 2 $class $subject $teacher_course");
	$result = $mysqli->query($sql);
	$data = "<option value='0'>Без учителя</option>";
	while ( $row = $result->fetch_assoc() ) {
		$data .= "<option value='".$row['id']."'>".$row['name']."</option>";
	}
	$return_arr['teacher'] = $data;

// собираем темы
	$sql = sprintf("SELECT * FROM os_themes 
							WHERE 1=1
							  $theme_class
							  AND theme_course = %s
							  $theme_subject", $course_id);
	$res = $mysqli->query($sql);
	$data = "<option value='0'>Без темы</option>";
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$data .= sprintf("<option value='%s'>%s</option>", $row['id'], $row['theme_name_ru']);
		}
	}
	$return_arr['theme'] = $data;

// собираем предметы
	$sql = sprintf("SELECT * FROM os_subjects 
						   WHERE 1 = 1
						   		 $subject_class");
	$res = $mysqli->query($sql);
	$data = "<option value='0'>Без предмета</option>";
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$selected = "";
			if($row['id'] == $subject_id) $selected = " selected";
			$data .= sprintf("<option value='%s' $selected>%s</option>", $row['id'], $row['name_ru']);
		}
	}
	$return_arr['subject'] = $data;

	print_r(json_encode($return_arr));

}
if($_POST['flag'] == '4') {
	$sql = "SELECT * FROM os_subjects WHERE 1=1 ";
	if($_SESSION['data']['level'] == 1) {
		$sql .= sprintf(" AND id IN (SELECT id_subject FROM os_student_subjects 
													  WHERE id_student = %s 
													    AND id_subject)
								AND id
								 IN (SELECT id_s FROM os_class_subj 
												WHERE class = %s 
												  AND course = 0)", 
												  $_SESSION['data']['id'], $_SESSION['data']['class']);
	} else if($_SESSION['data']['level'] == 2) {
		$sql .= sprintf(" AND id IN (SELECT id_s FROM os_teacher_subj 
												WHERE id_teacher = %s 
												  AND course = 0 
												  AND id_s 
												   IN (SELECT id_s FROM os_class_subj 
												   				  WHERE class = %s 
												   				    AND course = 0))", 
												   				    $_SESSION['data']['id'], $_POST['class_id']);
	} else if($_SESSION['data']['level'] == 4) {
		$sql .= sprintf(" AND id IN(SELECT id_s FROM os_class_subj 
											   WHERE class = %s 
											     AND course = 0)", $_POST['class_id']);
	}
	//print("<br> \n $sql \n <br>");
	$res = $mysqli->query($sql);
	$data = "";
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$data .= sprintf("<option value='%s'>%s</option>", $row['id'], $row['name_' . $_POST['lang']]);
		}
	}
	print_r(json_encode($data));
}
?>