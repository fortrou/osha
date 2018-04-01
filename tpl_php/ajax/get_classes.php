<?php
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_POST){
		$sql = "SELECT * FROM os_teacher_class WHERE id_teacher='".$_POST['id']."'";
		//print($sql);
		$res = $mysqli->query($sql);

		$result = array();
		while ($row = $res->fetch_assoc()) {
			$result[$row['id_c']] = array(
				"first" => $row['id_c']
			);
		}
		$sql1 = "SELECT * FROM os_teacher_class WHERE id_teacher='".$_POST['id']."'";
		//print($sql);
		$res1 = $mysqli->query($sql1);

		$all_classes = "";
		while ($row1 = $res1->fetch_assoc()) {
			$all_classes .= $row1['id_c'].',';
		}
		$all_classes = rtrim($all_classes,",");
		$result[0] = array(
			"first" => $all_classes
		);
		print_r(json_encode($result));
	}

?>