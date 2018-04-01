<?php
	/**
	 * script to execute
	 * dev by fortrou
	 *
	 *
	 **/
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$sql = "SELECT * FROM os_lessons WHERE lesson_year = 2";
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$sql_update = sprintf("UPDATE os_journal SET date_ru = '%s', date_ua = '%s', id_subj = %s WHERE id_l = %s", 
				$row['date_ru'], $row['date_ua'], $row['subject'], $row['id'] );
			$res_update = $mysqli->query($sql_update);
			usleep(1000);
		}
	}

?>