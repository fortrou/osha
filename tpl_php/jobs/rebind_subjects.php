<?php
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$sql = "SELECT * FROM os_users WHERE level = 1 AND (edu_type = 1 OR edu_type = 2)";
	$res = $mysqli->query($sql);
	$iter = 1;
	if($res->num_rows != 0) {
		while ($row = $res->fetch_assoc()) {
			$class = $row['class'];
			// DELETE binds
			print($row['id'] . ' - -- - ' . $row['login'] . '<br>');
			$sql_delete  = sprintf( "DELETE FROM os_student_subjects WHERE id_student = %s", $row['id'] );
			$res_delete  = $mysqli->query($sql_delete);
			// REBIND SUBJECTS
			$sql_subject = sprintf( "SELECT * FROM os_subjects WHERE id IN ( SELECT id_s FROM os_class_subj WHERE class = %s AND course = %s )", $class, 0 );
			print("<br>$sql_subject<br>");
			$res_subject = $mysqli->query($sql_subject);
			if($res_subject->num_rows != 0) {
				while($row_subjects = $res_subject->fetch_assoc()) {
					$sql_insert   = sprintf( "INSERT INTO os_student_subjects ( id_student, id_subject ) VALUES ( %s, %s )", $row['id'], $row_subjects['id'] );
					$res_insert   = $mysqli->query($sql_insert);
				}
			}
			$iter++;
		
		}
	}
	print("<br>$iter<br>");
?>