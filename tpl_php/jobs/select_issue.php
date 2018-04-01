<?php
	/**
	 * запросы на выборку из бд
	 * dev by @fortrou
	 *
	 *
	 **/
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$current_year = get_currentYearNum();
	$sql = "SELECT * FROM os_lessons WHERE (teacher_ru = 0 OR teacher_ua = 0) AND lesson_year = $current_year ";
	$res = $mysqli->query($sql);
	$iter = 1;
	echo "<h1> Уроки без учителя </h1>";
	if($res->num_rows != 0) {
		echo "<ul>";
		while ($row = $res->fetch_assoc()) {
			printf("<li><a href='http://online-shkola.com.ua/lessons/redactme.php?id=%s'>Редактировать урок по теме %s</a></li>", $row['id'], $row['title_ru']);

		}
		echo "</ul>";
	}
	echo "<hr>";
	echo "<h1> Сумма баллов больше 12 </h1>";
	$sql = sprintf("SELECT * FROM os_journal 
							WHERE (mark_contr + mark_hw) > 12 
							  AND id_l 
							   IN (SELECT id FROM os_lessons 
							   				WHERE lesson_year = %s)",
							   				$current_year);
	print($sql);
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		echo "<ul>";
		while($row = $res->fetch_assoc()) {
			$sql_user = sprintf("SELECT * FROM os_users WHERE id = %s", $row['id_s']);
			$res_user = $mysqli->query($sql_user);
			if($res_user->num_rows == 0) {
				continue;
			}
			$row_user = $res_user->fetch_assoc();
			
			$sql_lesson = sprintf("SELECT * FROM os_lessons WHERE id = %s", $row['id_l']);
			$res_lesson = $mysqli->query($sql_lesson);
			if($res_lesson->num_rows == 0) {
				continue;
			}
			$row_lesson = $res_lesson->fetch_assoc();
			printf("<li>
						<a href='http://online-shkola.com.ua/cabinet/preview.php?id=%s'>%s %s %s</a> 
						<a href='http://online-shkola.com.ua/lessons/watch.php?id=%s'>%s</a>
						<span>   Mark control: %s | Mark hw: %s</span>
					</li>", 
				$row_user['id'], $row_user['surname'], $row_user['name'], $row_user['patronymic'],
				$row_lesson['id'], $row_lesson['title_ru'], $row['mark_contr'], $row['mark_hw']);

		}
		echo "</ul>";
	}
	echo "<hr>";
	echo "<h1> Теоретическая сумма баллов больше 12 </h1>";
	$sql = sprintf("SELECT * FROM os_lessons 
						 	 WHERE lesson_year = %s",
							 $current_year);
	
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		echo "<ul>";
		while($row = $res->fetch_assoc()) {
			$ru_test = 0;
			$uk_test = 0;
			$hw_mark = 0;
			$sql_test_quest = sprintf("SELECT * FROM os_test_quest 
											   WHERE id_test = (SELECT DISTINCT id_test FROM os_lesson_test 
											   							  WHERE id_lesson = %s
											   								AND lang = 'ru'
											   								AND type = 5)", $row['id']);
			$res_test_quest = $mysqli->query($sql_test_quest);
			if($res_test_quest->num_rows != 0) {
				while($row_test_quest = $res_test_quest->fetch_assoc()){
                    switch ((int)$row_test_quest['type']) {
                        case 1:
                            $ru_test += $row_test_quest['cost'];
                            //var_dump($row_fc['cost']);
                            //print("<br>$max_convert<br>");
                            break;
                        case 2:
                            $sql_a = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s AND correct=1",$row_test_quest['id_q']);
                            $res_a = $mysqli->query($sql_a);
                            $row_a = $res_a->fetch_assoc();
                            //var_dump($row_fc['cost']);
                            //var_dump($row_loc['count(id_a)']);
                            $ru_test += (int)$row_a['count(id_a)']*(int)$row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 3:
                            $sql_a = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row_test_quest['id_q']);
                            $res_a = $mysqli->query($sql_a);
                            $row_a = $res_a->fetch_assoc();
                            $ru_test += (int)$row_a['count(id_a)']*(int)$row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 4:
                            $sql_a = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row_test_quest['id_q']);
                            $res_a = $mysqli->query($sql_a);
                            $row_a = $res_a->fetch_assoc();
                            $ru_test += (int)$row_a['count(id_a)']*(int)$row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 5:
                            $ru_test += $row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                    }
                }
            $sql_test_quest = sprintf("SELECT * FROM os_test_quest 
											   WHERE id_test = (SELECT DISTINCT id_test FROM os_lesson_test 
											   							  WHERE id_lesson = %s
											   								AND lang = 'ua'
											   								AND type = 5)", $row['id']);
			$res_test_quest = $mysqli->query($sql_test_quest);
			if($res_test_quest->num_rows != 0) {
				while($row_test_quest = $res_test_quest->fetch_assoc()){
                    switch ((int)$row_test_quest['type']) {
                        case 1:
                            $uk_test += $row_test_quest['cost'];
                            //var_dump($row_fc['cost']);
                            //print("<br>$max_convert<br>");
                            break;
                        case 2:
                            $sql_a = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s AND correct=1",$row_test_quest['id_q']);
                            $res_a = $mysqli->query($sql_a);
                            $row_a = $res_a->fetch_assoc();
                            //var_dump($row_fc['cost']);
                            //var_dump($row_loc['count(id_a)']);
                            $uk_test += (int)$row_a['count(id_a)']*(int)$row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 3:
                            $sql_a = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row_test_quest['id_q']);
                            $res_a = $mysqli->query($sql_a);
                            $row_a = $res_a->fetch_assoc();
                            $uk_test += (int)$row_a['count(id_a)']*(int)$row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 4:
                            $sql_a = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row_test_quest['id_q']);
                            $res_a = $mysqli->query($sql_a);
                            $row_a = $res_a->fetch_assoc();
                            $uk_test += (int)$row_a['count(id_a)']*(int)$row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 5:
                            $uk_test += $row_test_quest['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                    }
                }
			}
			$sql_hw = sprintf("SELECT * FROM os_lesson_homework WHERE id_lesson = %s", $row['id']);
			$res_hw = $mysqli->query($sql_hw);
			if($res_hw->num_rows != 0) {
				$row_hw  = $res_hw->fetch_assoc();
				$hw_mark = $row_hw['mark'];
			}
			
			if(($ru_test + $hw_mark) > 12 || ($uk_test + $hw_mark) > 12 || ($ru_test + $hw_mark) < 12 || ($uk_test + $hw_mark) < 12 ) {
				printf('<li><a href="http://online-shkola.com.ua/lessons/watch.php?id=%s">%s</a> | test_ru: %s  | test_ua: %s  | mark_hw: %s  </li>', 
					$row['id'], $row['title_ru'], $ru_test, $uk_test, $hw_mark);
			}
		}
		echo "</ul>";
	}
}
	echo "<hr>";
	echo "<h1> Ученики без даты начала учебы </h1>";
	$sql = sprintf("SELECT * FROM os_users 
							WHERE level = 1 
							  AND edu_type = 1 
							  AND date_end <> '0000-00-00' 
							  AND date_start_learning = '0000-00-00'");
	print($sql);
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		echo "<ul>";
		while($row = $res->fetch_assoc()) {
			
			printf("<li>
						<a href='http://online-shkola.com.ua/cabinet/preview.php?id=%s'>%s %s %s</a> 
					</li>", 
					$row['id'], $row['surname'], $row['name'], $row['patronymic']);

		}
		echo "</ul>";
	}

	echo "<hr>";
	echo "<h1> Уроки без темы </h1>";
	$sql = sprintf("SELECT * FROM os_lessons 
							WHERE lesson_year = %s
							  AND theme = 0", $current_year);
	print($sql);
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		echo "<ul>";
		while($row = $res->fetch_assoc()) {
			
			printf("<li>
						<a href='http://online-shkola.com.ua/lessons/watch.php?id=%s'>%s</a> 
					</li>", 
					$row['id'], $row['title_ru']);

		}
		echo "</ul>";
	}