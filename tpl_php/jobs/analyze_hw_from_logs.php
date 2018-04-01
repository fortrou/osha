<?php
	/**
	 * анализ фреймов дз из логов для определения корректности созданых дз
	 * dev by @fortrou
	 *
	 **/
	require_once("../autoload_light.php");
	session_start();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$year_num = get_currentYearNum();
	//$file = fopen("journal_items.txt", "r");
	$file_array = file("hw_items.txt"); 
	$iter = 1;
	/*foreach ($file_array as $value) {
		$data = explode(',', $value);
		$sql = sprintf("SELECT * FROM os_homeworks WHERE id=%s", $data[0]);
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			$row = $res->fetch_assoc();
			$sql_lesson = sprintf("SELECT * FROM os_lessons WHERE id IN (SELECT id_lesson FROM os_lesson_homework WHERE id = %s)", $row['id_hw']);
			$res_lesson = $mysqli->query($sql_lesson);
			if($res_lesson->num_rows == 0) continue;
			$row_lesson = $res_lesson->fetch_assoc();
			$sql_lesson_classes = sprintf("SELECT * FROM os_lesson_classes WHERE id_lesson = %s", $row_lesson['id']);
			$res_lesson_classes = $mysqli->query($sql_lesson_classes);
			if($res_lesson_classes->num_rows == 0) continue;
			$classes_array = array();
			while($row_lesson_classes = $res_lesson_classes->fetch_assoc()) {
				$classes_array[] = $row_lesson_classes['id_class'];
			}
			$sql_user = sprintf("SELECT * FROM os_users WHERE id = %s", $row['from']);
			print("<br>$sql_user<br>");
			$res_user = $mysqli->query($sql_user);
			if($res_user->num_rows == 0) {
				continue;
			}
			$row_user = $res_user->fetch_assoc();
			if(in_array($row_user['class'], $classes_array)) {
				print("Jajaja - $iter");
			}
			$iter++;
		}
		
		usleep(1000);
	}*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Проверка лога дз</title>
	<?php require_once("../../tpl_blocks/head.php"); ?>
</head>
<body>
	<?php require_once("../../tpl_blocks/header.php"); ?>
	<div class="container">
		<div class="block0">
			<h1>Проверка лога ДЗ, залить в форму</h1>
			<form action="">
					<p>Залить файл</p>
					<input type="file" name="file_txt">
					<p>Тыцнуть кнопку</p>
					<input type="submit" name="">
			</form>
		</div>
	</div>	
</body>
</html>