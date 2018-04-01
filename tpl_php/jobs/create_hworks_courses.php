<?php
	/**
	 * generate hw_frames for courses
	 * dev by fortrou
	 *
	 **/
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$current_year = get_currentYearNum();
	$current_date = Date("Y-m-d");
	$id_course    = 1;
	$iter = 1;
	$sql_users = sprintf("SELECT * FROM os_users 
								  WHERE level = 1 
								    AND id 
								     IN ( SELECT id_user FROM os_courses_students 
								     			  		WHERE id_course=%s 
								     			    	  AND payment_end_date >= '%s')", $id_course, $current_date);

	$res_users = $mysqli->query($sql_users);
	if($res_users->num_rows != 0) {
		while($row_users = $res_users->fetch_assoc()) {
			$sql = "SELECT * FROM os_lessons WHERE 1=1 AND course = " . $id_course;
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				while($row = $res->fetch_assoc()) {
					$sql_lhw = sprintf("SELECT * FROM os_lesson_homework WHERE id_lesson=%s",$row["id"]);
					$res_lhw = $mysqli->query($sql_lhw);
					if ($res_lhw->num_rows!=0) {
						$row_lhw = $res_lhw->fetch_assoc();
						$sql_homework = sprintf("SELECT * FROM os_homeworks WHERE `from`='%s' AND id_hw='%s'",$row_users["id"],$row_lhw["id"]);
						$res_homework = $mysqli->query($sql_homework);
						//print($sql_homework . "<br>");
						if ($res_homework->num_rows == 0) {
							//print("<br>incorrect<br>");
							if ($row["is_control"] == 0) {
								$status = 1;
							}
							else{
								$status = 3;
							}
							$date_ru = explode(" ",$row["date_ru"]);
							$date_ua = explode(" ",$row["date_ua"]);
							$sql_create = sprintf("INSERT INTO os_homeworks(date_h,`from`,subj,class,id_hw,status,check_status) VALUES('%s',%s,%s,%s,%s,$status,2)",
								$date_ru[0],$row_users["id"],$row["subject"],$row_users["class"],$row_lhw["id"]);
							$res_create = $mysqli->query($sql_create);
							//print("<be>$sql_create<br>$iter<br>");
							$sql_li = "SELECT LAST_INSERT_ID() AS li FROM os_homeworks";
							$res_li = $mysqli->query($sql_li);
							$row_li = $res_li->fetch_assoc();
							print($row_li['li'] . "<br>");
							$iter++;

						}
					}
				}
			}
		}
	}
	print("iter: $iter");
?>