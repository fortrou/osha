<?php
	session_start();
	if(!isset($_SESSION['data'])) header("Location:../index.php");
  	require_once("../tpl_php/autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
		$timetable = "Расписание ";
		$subject = "Все предметы";
		$themeName = "Название темы";
		$courseOutText = "Вышел";
		$courseMark = "Оценка";
		$outDate = "Дата выхода";
		$completeMark = "Ваша оценка за тему: ";
		$subjProgress = "Прогресс предмета";
	} else {
		$timetable = "Розклад";
		$subject = "Усi предмети";
		$themeName = "Назва теми";
		$courseOutText = "Виїшов";
		$courseMark = "Оцiнка";
		$outDate = "Дата виходу";
		$completeMark = "Ваша оцiнка за тему: ";
		$subjProgress = "Прогрес предмета";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<?php require_once("../tpl_blocks/head.php"); ?>
	<script type='text/javascript' src='../tpl_js/course_diary.1235.js'></script>
</head>
<body>
	<?php /* Блок с мета данными*/ ?>
	<input type="hidden" name="course" value="<?php print($_SESSION['data']['currentCourse']); ?>">
	<input type="hidden" name="level" value="<?php print($_SESSION['data']['level']); ?>">
	<input type="hidden" name="class" value="<?php print($_SESSION['data']['class']); ?>">
	<input type="hidden" name="id" value="<?php print($_SESSION['data']['id']); ?>">
	<input type="hidden" name="user_id" value="0">

	<?php /* Блок с мета данными*/ ?>
	<?php require_once('../tpl_blocks/header.php'); ?>
	<div class="content">
		<div class="block0">
			<h1><? echo $timetable; ?></h1>
			<h3 class="timetable-sub">Предмет</h3>
			<div class="timetable-subject-name-selector">
				<select name='subject'>
					<!--<option value="0" selected><? echo $subject; ?></option>-->
					<?php
						if($_SESSION['data']['level'] != '2') {
							$sql = sprintf("SELECT * FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE class=%s AND course=%s)",
								$_SESSION['data']['class'],$_SESSION['data']['currentCourse']);
						} else if($_SESSION['data']['level'] == '2') {
							$sql = sprintf("SELECT * FROM os_subjects WHERE id IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher=%s AND course=%s)",
								$_SESSION['data']['id'], $_SESSION['data']['currentCourse']);
						}
						$res = $mysqli->query($sql);
						if($res->num_rows != 0) {
							while($row = $res->fetch_assoc()) {
								printf("<option value='%s'>%s</option>",$row['id'],$row['name_' . $_COOKIE['lang']]);
							}
						}
					?>
				</select>
				<?php if(!in_array($_SESSION['data']['level'], array(1,3))): ?>
				<select name="class_id">
					<option value="0" selected>Все классы</option>
					<?php

						switch ($_SESSION['data']['level']) {
							case 2:
								$sql = sprintf("SELECT * FROM os_class_manager WHERE id IN(SELECT DISTINCT id_c FROM os_teacher_class WHERE id_teacher=%s)",
												$_SESSION['data']['id']);
								break;
							case 3:
								$sql = sprintf("SELECT * FROM os_class_manager WHERE id_manager=%s",
												$_SESSION['data']['id']);
								break;
							case 4:
								$sql = sprintf("SELECT * AS class FROM os_class_manager");
								break;
							default: 
								$sql = sprintf("SELECT * AS class FROM os_class_manager");
								break;
						}
						$res = $mysqli->query($sql);
						if($res->num_rows != 0) {
							while($row = $res->fetch_assoc()) {
								printf("<option value='%s'>Класс %s</option>",
										$row['id'], $row['class_name']);
							}
						}
					?>
				</select>
				<?php endif; ?>
				<div class="progress-content">
					<span class="progress-text"><?php echo $subjProgress; ?></span>
					<div class="progress-container">
						<div class="progress-background">
							<img src="../tpl_img/shedule-progress-texture.png" alt="">
						</div>
						<p class="progress-counter">10%</p>
					</div>
				</div>
			</div>
			<div id="main-part" class="float-left-block">
				
			</div>
			<?php if(isset($_SESSION['data']) && $_SESSION['data']['level'] > 1): ?>
				<div id='course-students' class='float-left-block'></div>
			<?php endif; ?>
			<div class="clear"></div>
			<?php if($_SESSION['data']['level'] == 4): ?>
				<input type="text" name="notif_ru" placeholder="Русский вариант">
				<input type="text" name="notif_ua" placeholder="Украинский вариант">
				<input type="button" name="add_notif" value="Добавить примечание">
			<?php endif; ?>
			<ul class="tabel_premich tabel_premich_theme" style="width:630px;">

			</ul>
		</div>
	</div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body>
</html>
