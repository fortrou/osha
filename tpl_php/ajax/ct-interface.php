<?php
	/*
	 * интерфейс для связывания курсов и тем с предметами
	 * для организации корректности выборки
	 */
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_POST){
		if($_POST['flag'] == '1') {
			$course = $_POST['course'];
			$class_string = "";
			if(is_array($_POST['selected_class'])) {
				foreach ($_POST['selected_class'] as $value) {
					$class_string .= $value . ", ";
				}
				$class_string = rtrim($class_string, ", ");
			}
			$where_class = "";
			if($class_string != "") {
				$where_class = " AND class IN($class_string)";
			}
			$sql = "SELECT * FROM os_subjects WHERE id IN(SELECT DISTINCT id_s FROM os_class_subj WHERE course=$course $where_class)";
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
		}
		if($_POST['flag'] == '2') {
			$result = get_subjectsOnUser($_POST['course'], $_POST['id_user']);
			/*echo "<pre>";
			print_r($result);
			echo "</pre>";*/
			print(json_encode($result));
		}
		if($_POST['flag'] == '3') {
			$result = save_subjectOnTeacher($_POST['course'], $_POST['id_user'],$_POST['subjects']);
			if($result == true) {
				$result = 'all are good';
			} else {
				$result = 'not good';
			}
			/*echo "<pre>";
			print_r($result);
			echo "</pre>";*/
			print(json_encode($result));
		}
	}

?>