<?php 
	session_start();
	require ('../tpl_php/autoload.php');
	if (!isset($_GET['id']) || !isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) {
		header("Location: ../");
	}
	$id = (int)$_GET['id'];
	if ( $_POST['send'] )
	{
		/*echo "<pre>";
			var_dump($_POST);
		echo "</pre>";
		print("<br>");*/
		$sql = sprintf("UPDATE os_homeworks SET subj='%s' WHERE id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$_POST["subject"],$_GET["id"]);
		$res = $mysqli->query($sql);
		$sql = sprintf("UPDATE os_journal SET id_subj='%s' WHERE id_l='%s'",$_POST["subject"],$_GET["id"]);
		$res = $mysqli->query($sql);

		Lesson::Update($_GET['id'] , $_POST);

		header("Location:".$_SERVER['REQUEST_URI']);
	}
	if (isset($_POST["control"])) {
		$sql = sprintf("UPDATE os_lessons SET is_control=1 WHERE id='%s'",$_GET["id"]);
		$res = $mysqli->query($sql);
		$sql = sprintf("UPDATE os_journal SET status=3 WHERE id_l='%s'",$_GET["id"]);
		$res = $mysqli->query($sql);
		$sql = sprintf("UPDATE os_homeworks SET status=3 WHERE id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$_GET["id"]);
		$res = $mysqli->query($sql);
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	if (isset($_POST["uncontrol"])) {
		$sql = sprintf("UPDATE os_lessons SET is_control=0 WHERE id='%s'",$_GET["id"]);
		$res = $mysqli->query($sql);
		$sql = sprintf("UPDATE os_journal SET status=1 WHERE id_l='%s'",$_GET["id"]);
		$res = $mysqli->query($sql);
		$sql = sprintf("UPDATE os_homeworks SET status=1 WHERE id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$_GET["id"]);
		$res = $mysqli->query($sql);
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	if (isset($_POST["verbal"])) {
		$sql = sprintf("UPDATE os_lessons SET is_verbal=1 WHERE id='%s'",$_GET["id"]);
		$res = $mysqli->query($sql);
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	if (isset($_POST["unverbal"])) {
		$sql = sprintf("UPDATE os_lessons SET is_verbal=0 WHERE id='%s'",$_GET["id"]);
		$res = $mysqli->query($sql);
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	$_SESSION['referer'] = "redact";
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$sql = "SELECT * FROM os_lessons WHERE id=$id";
	//print("<br>$sql<br>");
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	foreach ($row as $key => $value) {
		$_POST[$key] = htmlspecialchars_decode($value);
	}
	if ( $_POST['simpt'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ru';
		$_SESSION['lesson']['test_type'] = 4;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,%s,'%s')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		//print($sql);
		$result = $mysqli->query($sql);
		$_SESSION['referer'] = "redact";
		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['hardt'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ru';
		$_SESSION['lesson']['test_type'] = 5;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,%s,'%s')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		//print($sql);
		$result = $mysqli->query($sql);
		$_SESSION['referer'] = "redact";
		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['simptu'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ua';
		$_SESSION['lesson']['test_type'] = 4;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,%s,'%s')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		//print($sql);
		$result = $mysqli->query($sql);
		$_SESSION['referer'] = "redact";
		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['hardtu'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ua';
		$_SESSION['lesson']['test_type'] = 5;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,%s,'%s')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		//print($sql);
		$result = $mysqli->query($sql);
		$_SESSION['referer'] = "redact";
		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['hwtext'] )
	{
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		$sql = sprintf("INSERT INTO os_lesson_homework(id_lesson,hw_text_ru,hw_text_ua,mark) VALUES(%s,'','',0)",$_GET["id"]);
		$res = $mysqli->query($sql);
		$sql = sprintf("SELECT id FROM os_lesson_homework WHERE id_lesson='%s'",$_GET["id"]);
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();

		$sql_users = sprintf("SELECT * FROM os_users WHERE level = 1 AND class = (SELECT class FROM os_lessons WHERE id='%s')",$_GET["id"]);
			//print("<br>$sql_users<br>");
			$res_users = $mysqli->query($sql_users);
			while($row_users = $res_users->fetch_assoc()){
				$date = explode(' ',$_POST['date_ua']);
				//var_dump($date);
				if ($_POST["is_control"] = 1) {
					$status=3;
				}
				else{
					$status=1;
				}
				$sql_new = sprintf("INSERT INTO os_homeworks(date_h,`from`,subj,class,id_hw,status) VALUES('%s',%s,%s,%s,%s)",
					$date[0],$row_users['id'],$_POST['subject'],$_POST['class'],$row['id']);
				//print("<br>$sql_new<br>");
				$res_new = $mysqli->query($sql_new);
			}
		//var_dump($row);
		header("Location:redacthw.php?id=".$row['id']);
	}
	unset($_SESSION['referer']);
	//var_dump($_POST);
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Редактирование онлайн-урока</title>

	<?php include '../tpl_blocks/head.php'; ?>
	<script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>
	<script src="../tpl_js/lessons.js"></script>
</head>
<body>
	
	<?php include '../tpl_blocks/header.php'; ?>
	<div class="content">
		<div class="block0">
	<?php
		$result = $mysqli->query($sql);
		$flags = array(0,0,0,0,0,0);
		$sql = "SELECT * FROM os_lesson_test WHERE id_lesson='$id'";
		//print("<br>$sql<br>");
		$result = $mysqli->query($sql);
		while($row = $result->fetch_assoc()){
			if($row['type'] == 4 && $row['lang'] == 'ru'){
				$flags[0] = 1;
				$simptr = $row["id_test"];
			}
			if($row['type'] == 5 && $row['lang'] == 'ru'){
				$flags[1] = 1;
				$hardtr = $row["id_test"];
			}
			if($row['type'] == 4 && $row['lang'] == 'ua'){
				$flags[2] = 1;
				$simptu = $row["id_test"];
			}
			if($row['type'] == 5 && $row['lang'] == 'ua'){
				$flags[3] = 1;
				$hardtu = $row["id_test"];
			}
		}
		$sql = "SELECT * FROM os_lesson_homework WHERE id_lesson='$id'";
		$result = $mysqli->query($sql);
		if ($result->num_rows != 0) {
			$row_hw = $result->fetch_assoc();
			$id_hw = $row_hw["id"];
			$flags[4] = 1;
		}
	?>
	<form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
		<?php
			if($flags[0] != 1){
				print("<input type='submit' name='simpt' value='Создать тренировочный тест[rus]'><br>");
			}
			else{
				print("<a href='../tests/testred.php?tid=$simptr' target='_blank'>Редактировать тренировочный тест[rus]</a><br>");
			}

			if($_POST['same_lang']!=1){
				if($flags[2] != 1){
					print("<input type='submit' name='simptu' value='Создать тренировочный тест[ukr]'><br>");
				}
				else{
					print("<a href='../tests/testred.php?tid=$simptu' target='_blank'>Редактировать тренировочный тест[ukr]</a><br>");
				}
			}

			if($flags[1] != 1){
				print("<input type='submit' name='hardt' value='Создать тестовое ДЗ[rus]'><br>");
			}
			else{
				print("<a href='../tests/testred.php?tid=$hardtr' target='_blank'>Редактировать тестовое ДЗ[rus]</a><br>");
			}

			if($_POST['same_lang']!=1){
				if($flags[3] != 1){
					print("<input type='submit' name='hardtu' value='Создать тестовое ДЗ[ukr]'><br>");
				}
				else{
					print("<a href='../tests/testred.php?tid=$hardtu' target='_blank'>Редактировать тестовое ДЗ[ukr]</a><br>");
				}
			}
			if($flags[4] != 1){
				print("<input type='submit' name='hwtext' value='Создать творческое ДЗ'><br>");
			}
			else{
				print("<a href='redacthw.php?id=$id_hw' target='_blank'> творческое ДЗ</a><br>");
			}

		?>
	</form>
	<form action="" method="post">
		<div class="block_adm_rr">
			<?php
			if ($_POST["is_control"] == 1) {
				print("<input name='uncontrol' type='submit' value='Сделать обычным(Не контрольным)'>");
			}
			else{
				print("<input name='control' type='submit' value='Сделать контрольным'>");
			}
			if ($_POST["is_verbal"] == 1) {
				print("<input name='unverbal' type='submit' value='Сделать оцениваемым'>");
			}
			else{
				print("<input name='verbal' type='submit' value='Сделать устным'>");
			}
			$sql = sprintf("SELECT * FROM os_subjects WHERE id IN (SELECT id_s FROM os_class_subj WHERE class IN(
								   SELECT id_class FROM os_lesson_classes WHERE id_lesson=%s))",$id);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);

			?>
			<p>Предмет</p>		
			<select name="subject" multiple size="7" class="select-width-200">
		<?php
		
			if ($_POST["subject"] == 0) {
				print("<option value='0' selected>Не выбран предмет</option>");
			}
			else{
				print("<option value='0'>Не выбран предмет</option>");
			}
			while ($row = $res->fetch_assoc()) {
				if ($_POST["subject"] == $row["id"]) {
					printf("<option value='%s' selected>%s</option>",$row["id"],$row["name_ru"]);
				}
				else{
					printf("<option value='%s'>%s</option>",$row["id"],$row["name_ru"]);
				}
			}
		?>
			</select>
			<?php
				/*Courses meta*/
				$sql = sprintf("SELECT * FROM os_themes WHERE theme_subject = %s AND id IN (SELECT id_theme FROM os_theme_classes WHERE id_class IN ( SELECT id_class FROM os_lesson_classes WHERE id_lesson = %s))", $_POST['subject'], $_POST['id']);
				$res = $mysqli->query($sql);
			?>
			<select id="theme_list" class="select-width-200" name="theme">
			<option value="0">Без темы</option>
				<?php
					while ($row = $res->fetch_assoc()) {
						$selected = "";
						if($_POST['theme'] == $row['id']) $selected = " selected";
						printf("<option value='%s' $selected>%s</option>",$row['id'],$row['theme_name_ru']);
					}
				?>
			</select>
		</div>
		<div class="block_adm_rr">
		<p>Дата проведения(UA)</p>
		<input name="date_ua" type="datetime-local" required value="<? print( strftime("%Y-%m-%dT%H:%M" , strtotime($_POST['date_ua']))) ?>"></input>
		<p>Дата проведения(RU)</p>
		<input name="date_ru" type="datetime-local" required value="<? print( strftime("%Y-%m-%dT%H:%M" , strtotime($_POST['date_ru']))) ?>"></input>
		</div>
		
		<div class="block_adm_rr">
		<p>Название урока</p>
		<label>Русск. язык<input name="title_ru" required="" value="<?=$_POST['title_ru'] ?>" placeholder="UA">
		<label>Укр. мова<input name="title_ua" required="" value="<?=$_POST['title_ua'] ?>" placeholder="RU">
		</div>
		
		<?php if($_SESSION['data']['level']!=2): ?>
		<div class="block_adm_rr">
			<?php
				$sql_teachers = sprintf("SELECT * FROM os_users WHERE level=2 AND 
					id IN (SELECT id_teacher FROM os_teacher_class WHERE id_c IN (SELECT id_class FROM os_lesson_classes WHERE id_lesson='%s')) AND
					id IN (SELECT id_teacher FROM os_teacher_subj WHERE id_s=(SELECT DISTINCT subject FROM os_lessons WHERE id='%s'))",$_GET['id'],$_GET['id']);
				//print("<br>$sql_teachers<br>");
				$res_teachers = $mysqli->query($sql_teachers);
			?>
		<p>Учитель</p>
		UA
		<select id="teacher_ua" name="teacher_ua">
			<option value="0">Учитель не выбран</option>
			<?php 
			while ($row_teachers = $res_teachers->fetch_assoc()) {
				if($_POST["teacher_ua"] == $row_teachers['id'])
					printf("<option selected value='%s'>%s %s ( %s )</option>",
						$row_teachers['id'],$row_teachers['surname'],$row_teachers['name'],$row_teachers['login']);
				else
					printf("<option value='%s'>%s %s ( %s )</option>",
						$row_teachers['id'],$row_teachers['surname'],$row_teachers['name'],$row_teachers['login']);
			}
			?>
		</select>
		RU
		<select id="teacher_ru" name="teacher_ru">
			<option value="0">Учитель не выбран</option>
			<?php
			$res_teachers = $mysqli->query($sql_teachers);
			while ($row_teachers = $res_teachers->fetch_assoc()) {
				if($_POST["teacher_ru"] == $row_teachers['id'])
					printf("<option selected value='%s'>%s %s ( %s )</option>",
						$row_teachers['id'],$row_teachers['surname'],$row_teachers['name'],$row_teachers['login']);
				else
					printf("<option value='%s'>%s %s ( %s )</option>",
						$row_teachers['id'],$row_teachers['surname'],$row_teachers['name'],$row_teachers['login']);
			}
			
			?>
		</select>
		</div>
		<?php endif; ?>

		
		<div class="block_adm_rr">
		<p>Ссылка на видео-трансляцию</p>
		<label>Русск. язык<input name="video_ru" required="" value="<?=$_POST['video_ru'] ?>" placeholder="UA"></label>
		<label>Укр. мова<input name="video_ua" required="" value="<?=$_POST['video_ua'] ?>" placeholder="RU"></label>
		</div>
		

		<?php if($_SESSION['data']['level']!=2): ?>
		<div class="block_adm_rr">		
		<p>Конспект</p>
		<span>Українською мовою</span>
		<textarea name="summary_ua"  cols="100" rows="10"><? print($_POST['summary_ua']); ?></textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('summary_ua');
			</script>
		<span>На русском языке</span>
		<textarea name="summary_ru"  cols="100" rows="10"><? print($_POST['summary_ru']); ?></textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('summary_ru');
			</script>
		</div>
		
		<?php endif; ?>
		<?php if($_SESSION['data']['level']!=2): ?>
		<div class="block_adm_rr">
		<p>Дополнительные ссылки</p>
		<span>Українською мовою</span>
		<textarea name="links_ua" id="" required="" cols="100" rows="10" ><? print($_POST['links_ua']); ?></textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('links_ua');
			</script>
		<span>На русском языке</span>
		<textarea name="links_ru" id="" required="" cols="100" rows="10" ><? print($_POST['links_ru']); ?></textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('links_ru');
			</script>
		</div>
		<?php endif; ?>
		
		
		
		<input type="submit" value="Сохранить" name="send">
		<hr>
		<div class="back_to_less"><a href="watch.php?id=<?=$id?>">Вернуться в урок</a></div>
	</form>
</div> 
	</div> 

	<?php include '../tpl_blocks/footer.php'; ?>

</body>
</html>