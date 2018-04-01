<?php
	require_once("../autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$sql = "SELECT * FROM os_chat WHERE chat_type=1";
	print($sql);
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		var_dump($res->num_rows);
		while($row = $res->fetch_assoc()) {
			$chat_users = array();
			$sql_users = sprintf("SELECT id_user FROM os_chat_users WHERE id_chat=%s", $row['id']);
			$res_users = $mysqli->query($sql_users);
			if($res_users->num_rows != 0) {
				while($row_users = $res_users->fetch_assoc()) {
					$chat_users[] = $row_users['id_user'];
				}
			}
			$chat_name = "";
			$sql_t_name = sprintf("SELECT CONCAT(surname,' ',name) AS fi, level, id FROM os_users WHERE id=%s",$chat_users[1]);
			$res_t_name = $mysqli->query($sql_t_name);
			if($res_t_name->num_rows == 0) continue;
			$row_t_name = $res_t_name->fetch_assoc();
			if($row_t_name['level'] == 2) {
				$teacher = $row_t_name['fi'];
			} else {
				$student = $row_t_name['fi'];
			}
			$sql_s_name = sprintf("SELECT CONCAT(surname,' ',name) AS fi, level, id FROM os_users WHERE id=%s",$chat_users[0]);
			$res_s_name = $mysqli->query($sql_s_name);
			if($res_s_name->num_rows == 0) continue;
			$row_s_name = $res_s_name->fetch_assoc();
			if($row_s_name['level'] == 2) {
				$teacher = $row_s_name['fi'];
			} else {
				$student = $row_s_name['fi'];
			}
			$chat_name = "Чат между учителем " . $teacher . "(id {$row_t_name['id']}) и учеником ". $student . "(id {$row_s_name['id']})";
			$sql_update = sprintf("UPDATE os_chat SET chat_name = '%s' WHERE id=%s", 
								   $chat_name, $row['id']);
			$res_update = $mysqli->query($sql_update);
			print("$chat_name <br>");
			var_dump($res_update);
			echo "<br>";
			usleep(5000);
		}
	}
?>