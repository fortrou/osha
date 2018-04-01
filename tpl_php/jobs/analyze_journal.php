<?php
	/**
	 * regen for journal items where are duplicates for 1 lesson in 2 dates
	 * dev by @fortrou
	 * also analyze journal
	 *
	 **/
	require_once("../autoload_light.php");
	session_start();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$year_num = get_currentYearNum();
	$lesson_from = 3077; // Урок, из которого копируем
	$lesson_to   = 3078; // Урок, в который копируем
	$user_class   = 6;   // Класс по которому ищем пользователей



	$sql_users = "SELECT * FROM os_users WHERE class = $user_class AND level = 1";
	$res_users = $mysqli->query($sql_users);
	if($res_users->num_rows != 0) {
		while($row_users = $res_users->fetch_assoc()) {
			$sql = sprintf("SELECT * FROM os_journal WHERE id_l = $lesson_from AND id_s = %s", $row_users['id']);
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$row = $res->fetch_assoc();
				$sql_upd = sprintf("UPDATE os_journal 
									   SET mark_tr = %s, mark_contr = %s, test_contr = '%s' 
									 WHERE id_l = $lesson_to 
									   AND id_s = %s", 
									   $row['mark_tr'], $row['mark_contr'], $row['test_contr'], $row_users['id']);
				print("<br>$sql_upd<br>");
				$res_upd = $mysqli->query($sql_upd);
					Database::delete('os_journal', array('id' => $row['id']));
				if($mysqli->affected_rows != 0) {
				}


			}
		}
	}

?>