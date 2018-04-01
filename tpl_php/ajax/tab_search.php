<?php
	require_once("../autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if (isset($_POST['fio'])) {
		/*$sql = "SELECT * FROM os_users WHERE name LIKE '%".$_POST['name']."%' OR surname LIKE '%".
			$_POST['name']."%' OR patronymic LIKE '%".$_POST['name']."%'";*/
		$sql = "SELECT * FROM os_users WHERE level = 1 AND concat(surname, ' ', name, ' ', patronymic) LIKE '%".$_POST['fio']."%'";
		$res = $mysqli->query($sql);


		while ($row = $res->fetch_assoc()) {
			$result[$row['id']] = array(
				"first" => $row['surname'],
				"second" => $row['name'],
				"forth" => $row['class']
			);
			$new_sql = "SELECT * FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE class='".$row['class']."')";
			//print($new_sql);
			$new_res = $mysqli->query($new_sql);
			while($new_row = $new_res->fetch_assoc()){
				$result[$row['id']]['third'][] = $new_row['name'];
				$result[$row['id']]['sixth'][] = $new_row['id'];
			}	
		}
		print_r(json_encode($result));
	}

?>