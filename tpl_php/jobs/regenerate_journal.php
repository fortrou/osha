<?php
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$year_num = get_currentYearNum();
	$date_to = Date('Y-m-d', time()+3600*7*24);
	$date_from = Date('Y-m-d', time()-3600*7*24);
	$sql = "SELECT * FROM os_lessons 
					WHERE 1 = 1 
					  AND lesson_year = $year_num
					  AND (date_ru >= '$date_from' 
				  	   OR date_ua >= '$date_from')
					  AND (date_ru <= '$date_to' 
					   OR date_ua <= '$date_to')";
	$res = $mysqli->query($sql);
	$iter = 1;
	if($res->num_rows != 0) {
		while ($row = $res->fetch_assoc()) {
			$sql_user = sprintf("SELECT * FROM os_users  WHERE class IN (SELECT id_class FROM os_lesson_classes WHERE id_lesson = %s)", $row['id']);
			print("<br>$sql_user<br>");
			$res_user = $mysqli->query($sql_user);
			if($res_user->num_rows != 0) {
				while($row_user = $res_user->fetch_assoc()) {
					if($row_user['level'] > 1) continue;
					$sql_j = sprintf("SELECT * FROM os_journal WHERE id_s = %s AND id_l = %s", $row_user['id'], $row['id']);
					$res_j = $mysqli->query($sql_j);
					if($res_j->num_rows != 0) continue; 
					$sql_insert = sprintf("INSERT INTO os_journal(id_s, id_l, date_ru, date_ua, id_subj) 
												VALUES (%s,%s,'%s','%s',%s)", $row_user['id'], $row['id'], $row['date_ru'], $row['date_ua'], $row['subject']);
					print("<br>$sql_insert<br>");
					$res_insert = $mysqli->query($sql_insert);
					var_dump($res_insert);
					$iter++;
					usleep(1000);
				}
			}
			continue;
		}
	}
	print("<br>$iter<br>");
?>