<?php
	require_once("autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if (isset($_POST['name'])) {
		/*$sql = "SELECT * FROM os_users WHERE name LIKE '%".$_POST['name']."%' OR surname LIKE '%".
			$_POST['name']."%' OR patronymic LIKE '%".$_POST['name']."%'";*/
		$sql = "SELECT * FROM os_users WHERE concat(surname, ' ', name, ' ', patronymic) 
		LIKE '%".$_POST['name']."%' OR login LIKE '%".$_POST['name']."%' OR email LIKE '%".$_POST['name']."%' OR id = '".$_POST['name']."'";
		$res = $mysqli->query($sql);


		while ($row = $res->fetch_assoc()) {
			$result[$row['id']] = array(
				"first" => $row['surname'],
				"second" => $row['name'],
				"third" => $row['email']
			);
			if ($row['lock_status'] == 0) {
				$result[$row['id']]['fourth'] = sprintf("<input type='button' onclick=\"lock_unlock(%s)\" value='Заблокировать'>",$row['id']);
			}
			if ($row['lock_status'] == 1) {
				$result[$row['id']]['fourth'] = sprintf("<input type='button' onclick=\"lock_unlock(%s)\" value='Разблокировать'>",$row['id']);
			}
		}
		print_r(json_encode($result));
	}

?>