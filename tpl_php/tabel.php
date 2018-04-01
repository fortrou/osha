<?php
	require_once("autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if(isset($_POST['user_id'])){
		$sql = sprintf("SELECT * FROM os_users WHERE id='%s'",$_POST['user_id']);
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		$class = $row['class'];
		
		if($row["edu_type"] == 1 || $row["edu_type"] == 2){
			$sql_subjects = "SELECT * FROM os_subjects WHERE id IN (SELECT id_s FROM os_class_subj WHERE class='".$class."')";
			$res_subjects = $mysqli->query($sql_subjects);
			while($row_subjects = $res_subjects->fetch_assoc()){
				$sql = sprintf("SELECT * FROM os_tabel_cont WHERE id_tabel='%s' AND class='%s' AND subject='%s'",$_POST['user_id'],$class,$row_subjects["name_ru"]);
				//print($sql);
				//echo "\n";
				$res = $mysqli->query($sql); 
				if ($res->num_rows == 0) {
					$sql_in1 = sprintf("INSERT INTO os_tabel_cont(id_tabel,class,subject,first_s,second_s,year,gia,final)
					VALUES('%s','%s','%s','','','','','')",$_POST['user_id'],$class,$row_subjects['name_ru']);
					$res_in1 = $mysqli->query($sql_in1);
				}
			}
			/*$sql_in2 = sprintf("INSERT INTO os_tabel_prev(id_pupil,id_class,id_tabel) VALUES('%s','%s','%s')",
				$_POST['user_id'],$class,$_POST['user_id']);
			$res_in2 = $mysqli->query($sql_in2);*/
		}
		if ($row["edu_type"] == 3) {
			$sql_subjects = "SELECT * FROM os_subjects WHERE id IN (SELECT id_s FROM os_class_subj WHERE class='".$class."') 
			AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='".$row["id"]."')";
			$res_subjects = $mysqli->query($sql_subjects);
			while($row_subjects = $res_subjects->fetch_assoc()){
				$sql = sprintf("SELECT * FROM os_tabel_cont WHERE id_tabel='%s' AND class='%s' AND subject='%s'",$_POST['user_id'],$class,$row_subjects["name_ru"]);
				//print($sql);
				//echo "\n";
				$res = $mysqli->query($sql); 
				if ($res->num_rows == 0) {
					$sql_in1 = sprintf("INSERT INTO os_tabel_cont(id_tabel,class,subject,first_s,second_s,year,gia,final)
					VALUES('%s','%s','%s','','','','','')",$_POST['user_id'],$class,$row_subjects['name_ru']);
					$res_in1 = $mysqli->query($sql_in1);
				}
			}
			
		}
		$sql_ii = sprintf("SELECT * FROM os_tabel_prev WHERE id_pupil='%s' AND id_class='%s' AND id_tabel='%s'",$_POST['user_id'],$class,$_POST['user_id']);
		$res_ii = $mysqli->query($sql_ii);
		if ($res_ii->num_rows == 0) {
			$sql_in2 = sprintf("INSERT INTO os_tabel_prev(id_pupil,id_class,id_tabel) VALUES('%s','%s','%s')",
				$_POST['user_id'],$class,$_POST['user_id']);
			$res_in2 = $mysqli->query($sql_in2);
		}

		$sql_out = sprintf("SELECT * FROM os_tabel_cont WHERE id_tabel='%s' AND class='%s'",$_POST['user_id'],$class);
		if ($_POST['level'] == 2) {
			$sql_out .= sprintf(" AND subject IN (SELECT name_ru FROM os_subjects WHERE id IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher='%s')
				AND id IN(SELECT id_s FROM os_class_subj WHERE class='%s')
				AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s'))",$_POST['self_id'],$class,$row["id"]);
		}
		if ($_POST['level'] == 1) {
			$sql_out .= sprintf(" AND subject IN (SELECT name_ru FROM os_subjects WHERE id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s') 
				AND id IN(SELECT id_s FROM os_class_subj WHERE class='%s'))",$_POST['self_id'],$class);
		}
		if($_POST["level"] == 3 || $_POST["level"] == 4){
			$sql_out .= sprintf(" AND subject IN (SELECT name_ru FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE class='%s')
				AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s'))",$class,$row["id"]);
		}
		//print("<br>$sql_out<br>");
		$res_out = $mysqli->query($sql_out);
		$tabel = array();
		while ($row_out = $res_out->fetch_assoc()) {
			$tabel[$row_out['id']] = array(
				'first_s' => $row_out['first_s'],
				'second_s' => $row_out['second_s'],
				'year' => $row_out['year'],
				'first_s_redacted' => $row_out['first_s_redacted'],
				'second_s_redacted' => $row_out['second_s_redacted'],
				'year_redacted' => $row_out['year_redacted'],
				'gia' => $row_out['gia'],
				'final' => $row_out['final']
			);
			$sql_subj = sprintf("SELECT * FROM os_subjects WHERE name_ru='%s'",$row_out['subject']);
			$res_subj = $mysqli->query($sql_subj);
			$row_subj = $res_subj->fetch_assoc();
			$tabel[$row_out['id']]['subj_id'] = $row_subj['id'];
			$tabel[$row_out['id']]['subject'] = $row_subj['name_'.$_POST['lang']];
		}
		print_r(json_encode($tabel));
	}


?>