<?php 
	session_start();
	require ('../tpl_php/autoload.php');
	if(!isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) header("Location: ../");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if ( isset($_POST['send']) )
	{
		//var_dump($_POST);

		try 
		{
			$_POST['lesson_year'] = get_currentYearNum() ? get_currentYearNum() : 1;
			if(isset($_POST['same_lang'])){
				$_POST['date_ua'] = $_POST['date_ru'];
				$_POST['title_ua'] = $_POST['title_ru'];
				$_POST['teacher_ua'] = $_POST['teacher_ru'];
				$_POST['video_ua'] = $_POST['video_ru'];
				$_POST['links_ua'] = $_POST['links_ru'];
			}
			if (isset($_POST["control"])) {
				$_POST["control"] = 1;
			}
			else{
				$_POST["control"] = 0;
			}
			if(isset($_POST['is_verbal'])) {
				$_POST['is_verbal'] = 1;
			} else {
				$_POST['is_verbal'] = 0;
			}
			//var_dump($_POST);
			$id = Lesson::Create($_POST);
			if ( $id ){
				$classList = "";
				foreach ($_POST['class'] as $value) {
					$classList .= $value . ", ";
				}
				$where_array = array();
				$where_array[] = "id_course=" . $_POST['course'];
				$where_array[] = "payment_end_date>=" . Date("Y-m-d");
				$classList = rtrim($classList, ", ");
				$sql_pre = "SELECT id FROM os_users WHERE class IN($classList) ";
				if($_POST['course'] != 0) {
					if(count($where_array)) {
						$where_string = "";
						foreach ($where_array as $value) {
							$where_string .= $value . " AND ";
						}
						$where_string = rtrim($where_string, " AND ");
					}
					$sql_pre .= " AND id IN(SELECT DISTINCT id_user FROM os_courses_students WHERE $where_string GROUP BY id_course, id_user HAVING MAX(id))";
				}
				$res_pre = $mysqli->query($sql_pre);
				$sql_l = sprintf("SELECT id FROM os_lessons WHERE title_ua='%s' AND title_ru='%s' ORDER BY id DESC LIMIT 1",
					$_POST['title_ua'],$_POST['title_ru']);
				$res_l = $mysqli->query($sql_l);
				$row_l = $res_l->fetch_assoc();
				while($row_pre = $res_pre->fetch_assoc()){
					$sql_s = "SELECT * FROM os_subjects WHERE id='".$_POST['subject']."'";
					$res_s = $mysqli->query($sql_s);
					$row_s = $res_s->fetch_assoc();
					/*$sql = sprintf("INSERT INTO os_events(text_ua,text_ru,link,id_user,date_e,type,read_status) 
						VALUES('Був створений новий онлайн-урок з предмету <<%s>>. Назва: %s. Дата проведення: %s ',
						'Был добавлен новый урок по предмету <<%s>>. Название: %s. Дата проведения: %s ','%s',%s,now(),2,0)",
					$row_s['name_ru'],$_POST['title_ua'],$_POST['date_ua'],$row_s['name_ua'],
					$_POST['title_ru'],$_POST['date_ru'],"http://online-shkola.com.ua/schedule/calendar.php",$row_pre['id']);
					$res = $mysqli->query($sql);*/
					if($_POST['control'] == 0){
						$sql_j = sprintf("INSERT INTO os_journal(id_s,id_l,date_ru,date_ua,status,id_subj, course, theme) VALUES(%s,%s,'%s','%s',1,%s,%s,%s)",
							$row_pre['id'],$row_l['id'],$_POST['date_ru'],$_POST['date_ua'],$_POST['subject'],$_POST['course'],$_POST['theme']);
					}
					else{
						$sql_j = sprintf("INSERT INTO os_journal(id_s,id_l,date_ru,date_ua,status,id_subj, course, theme) VALUES(%s,%s,'%s','%s',3,%s,%s,%s)",
							$row_pre['id'],$row_l['id'],$_POST['date_ru'],$_POST['date_ua'],$_POST['subject'],$_POST['course'],$_POST['theme']);
					}
					$res_j = $mysqli->query($sql_j);
					//print("<br>$sql_j<br>");

				}

				header("Location: stage2.php?id=" . $id);
			}
			else
				print("Smth is wrong!");

		} 
		catch (Exception $e) 
		{
			print($e->getMessage());
		}
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Создание онлайн-урока</title>

	<?php include '../tpl_blocks/head.php'; ?>

	<script src="../tpl_js/lessons.js"></script>
	<script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>


</head>
<body>
	
	<?php include '../tpl_blocks/header.php'; ?>
	<div class="content">
		<div class="block0">
			<input type="hidden" name="lang" value="ru">
	<form class="lesson-create" action="" method="post">
		<label><input type="checkbox" name="control">Сделать контрольным</label>
		<label><input type="checkbox" name="same_lang" value="1">Заполнить все в одном варианте</label>
		<label><input type="checkbox" name="is_verbal">Устный урок</label>
		<table>
		<tr>
			<td>
				<table>
					<tr>
						<td>
							<p style="text-align: start;margin-left: 30px;">Курсы</p>
			<?php
				/*Courses meta*/
				$sql = "SELECT * FROM os_courses_meta";
				$res = $mysqli->query($sql);
			?>
			<select id="course_list" class="select-width-200" name="course" data-filter="lesson-create">
				<option value="0">Онлайн-школа</option>
				<?php
					while ($row = $res->fetch_assoc()) {
						printf("<option value='%s'>%s</option>",$row['id'],$row['course_name_ru']);
					}
				?>
			</select>
			<p style="text-align: start;margin-left: 30px;">Темы</p>
			<?php
				/*Courses meta*/
				$sql = "SELECT * FROM os_themes";
				$res = $mysqli->query($sql);
			?>
			<select id="theme_list" class="select-width-200" name="theme">
			<option value="0">Без темы</option>
				<?php
					while ($row = $res->fetch_assoc()) {
						printf("<option value='%s'>%s</option>",$row['id'],$row['theme_name_ru']);
					}
				?>
			</select>
						</td>
						<td>
							<p style="text-align: start;margin-left: 30px;">Класс</p>
			<?php
				$sql = "SELECT * FROM os_class_manager";
				$res = $mysqli->query($sql);
			?>
			<select id="class" class='multiple-class' name="class[]" multiple data-filter="lesson-create">
				<?php
					while ($row = $res->fetch_assoc()) {
						printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
					}
				?>
			</select>
						</td>
					</tr>
				</table>
<table class="lesson-create-teach-container">
	<tr>
		<td>
						<p>Предмет</p>
			<select id="subject" class="select-width-200" name="subject" data-filter="lesson-create">
				<option value="0">--</option>
			</select>
		</td>
		<td>
			<p>Учитель</p>
			RU
			<select id="teacher_ru" name="teacher_ru" class="select-width-200">
				<option>--</option>
			</select><br>
			UA
			<select id="teacher_ua" name="teacher_ua" class="select-width-200">
				<option>--</option>
			</select>
		</td>
	</tr>
</table>

			</td>
			<td>

			<p>Дата проведения(RU)</p>
			<input name="date_ru" type="datetime-local" required ></input>
			<p>Дата проведения(UA)</p>
			<input name="date_ua" type="datetime-local" ></input>
			</td>
			<td>
			<p>Название урока</p>
			<input name="title_ru" required="" placeholder="RU">
			<input name="title_ua" placeholder="UA">



			<p>Ссылка на видео-трансляцию</p>
			<input name="video_ru" required="" placeholder="RU">
			<input name="video_ua" placeholder="UA">
			</td>
		</tr>
		</table>
		<p>Дополнительные ссылки</p>
		<textarea name="links_ru" id="" required="" cols="100" rows="10" >RU</textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('links_ru');
			</script>

		<textarea name="links_ua" id="" cols="100" rows="10" >UA</textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('links_ua');
			</script>
	
		<input type="submit" value="Далее" name="send">

	</form>
</div> 
	</div> 

	<?php include '../tpl_blocks/footer.php'; ?>

</body>
</html>