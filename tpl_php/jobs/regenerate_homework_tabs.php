<?php
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$sql = "SELECT * FROM os_homeworks WHERE class = 0";
	$res = $mysqli->query($sql);
	$iter = 1;
	if($res->num_rows != 0) {
		while ($row = $res->fetch_assoc()) {
			$sql_user = sprintf("SELECT * FROM os_users WHERE id = %s", $row['from']);
			print("<br>$sql_user<br>");
			$res_user = $mysqli->query($sql_user);
			if($res_user->num_rows != 0) {
				$row_user = $res_user->fetch_assoc();
				if($row_user['level'] > 1) continue;
				$sql_update = sprintf("UPDATE os_homeworks SET class = %s WHERE id = %s", $row_user['class'], $row['id']);
				print("<br>$sql_update<br>");
				$res_update = $mysqli->query($sql_update);
			}
			continue;
		}
	}
	print("<br>$iter<br>");
?>