<?php
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_POST){
		if($_POST['flag'] == "subjects"){
			$classes = "";
			$t_id = $_POST['t_id'];
			foreach($_POST['id'] AS $val){
				$classes .= "'$val', ";
			}
			$classes = rtrim($classes,', ');
			$lang = isset($_COOKIE['lang'])?$_COOKIE['lang']:'ru';
			$sql = "SELECT * FROM os_subjects WHERE id IN(SELECT DISTINCT id_s FROM os_teacher_subj WHERE id_teacher='$t_id' AND course=0)";
			//print($sql);
			$res = $mysqli->query($sql);
			$result1 = array();
			while ($row = $res->fetch_assoc()) {
				$result1[] = $row['id'];
			}
			$sql = "SELECT * FROM os_subjects WHERE id IN(SELECT DISTINCT id_s FROM os_class_subj WHERE class IN($classes) AND course=0)";
			//print($sql);
			$res = $mysqli->query($sql);
			$result = array();
			if($res->num_rows != 0) {
				while ($row = $res->fetch_assoc()) {
					$result[$row['id']] = array(
						"first" => $row['name_'.$lang],
						"second" => $result1
					);
				}
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == "classes_upd"){
			$classes = "";
			$t_id = $_POST['t_id'];
			foreach($_POST['id'] AS $val){
				$classes .= "'$val', ";
			}
			$classes = rtrim($classes,', ');
			$sql = "DELETE FROM os_teacher_class WHERE id_teacher = '$t_id'";
			//print($sql);
			$res = $mysqli->query($sql);
			
			//print($sql);
			foreach($_POST['id'] AS $val){
				$sql = "INSERT INTO os_teacher_class(id_teacher,id_c) VALUES('$t_id','$val')";
				$res = $mysqli->query($sql);
			}
			
		}
		if($_POST['flag'] == "subjects_upd"){
			$subjects = "";
			$t_id = $_POST['t_id'];
			foreach($_POST['id'] AS $val){
				$subjects .= "'$val', ";
			}
			$subjects = rtrim($subjects,', ');
			$sql = "DELETE FROM os_teacher_subj WHERE id_teacher = '$t_id' AND course=0";
			//print($sql);
			$res = $mysqli->query($sql);
			
			//print($sql);
			foreach($_POST['id'] AS $val){
				$sql = "INSERT INTO os_teacher_subj(id_teacher,id_s) VALUES('$t_id','$val')";
				$res = $mysqli->query($sql);
			}
		}
	}
?>