<?php
	session_start();
	if (!isset($_SESSION['data'])) {
		header("Location:calendar_test.php");
	}
	require_once("../tpl_php/autoload.php");
	if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
		$timetable_name = "Тематическое расписание";
		$subject = "Все предметы";
		$themeName = "Название темы";
		$courseOutText = "Вышел";
		$courseMark = "Оценка";
		$outDate = "Дата выхода";
		$completeMark = "Ваша оценка за тему: ";
		$subjProgress = "Прогресс предмета";
	} else {
		$timetable_name = "Тематичний розклад";
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
<html lang="en">
<head>
	<title>Календарь - Расписание - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script src="../tpl_js/course_diary.1235.js"></script>

</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	<input type="hidden" name="is_archieve" value="1">
	<input type="hidden" name="course" value="<?php print($_SESSION['data']['currentCourse']); ?>">
	<input type="hidden" name="level" value="<?php print($_SESSION['data']['level']); ?>">
	<input type="hidden" name="class" value="<?php print($_SESSION['data']['class']); ?>">
	<input type="hidden" name="id" value="<?php print($_SESSION['data']['id']); ?>">
	<input type="hidden" name="user_id" value="0">
	<div class="content">
		<?php
			if($_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=9";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=9",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(9, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div class="block0">
			<h1><?php print($timetable_name); ?></h1>
			<div class="filter-block">
				<div class="timetable-subject-name-selector">
					<form action="" class="filter-form">
						<h3 class="timetable-sub">Предмет</h3>
						<select name="subject" id="subject">
							<?php
								$sql = "SELECT DISTINCT * FROM os_subjects WHERE 1=1 ";
								if($_SESSION['data']['level'] == 1) {
									$sql .= sprintf(" AND id IN(SELECT id_subject FROM os_student_subjects WHERE id_student = %s)", 
										$_SESSION['data']['id']);
								}
								if($_SESSION['data']['level'] == 2) {
									$sql .= sprintf(" AND id IN(SELECT id_s FROM os_teacher_subj WHERE id_teacher = %s AND course = 0)", 
										$_SESSION['data']['id']);
								}
								if(in_array($_SESSION['data']['level'], array(3,4))) {
									$sql .= " AND id IN(SELECT DISTINCT id_s FROM os_class_subj WHERE course = 0)";
								}
								$res = $mysqli->query($sql);
								if($res->num_rows != 0) {
									while ($row = $res->fetch_assoc()) {
										printf("<option value='%s'>%s</option>",$row['id'],$row['name_' . $_COOKIE['lang']]);
									}
								}
							?>
						</select>
					<?php if($_SESSION['data']['level'] > 1): ?>
						<select name="class" id="class">
							<?php
								$sql = "SELECT DISTINCT * FROM os_class_manager WHERE 1=1 ";
								if($_SESSION['data']['level'] == 2) {
									$sql .= sprintf(" AND id IN(SELECT DISTINCT id_c FROM os_teacher_class WHERE id_teacher='%s')", 
										$_SESSION['data']['id']);
								}
								$res = $mysqli->query($sql);
								if($res->num_rows != 0) {
									while ($row = $res->fetch_assoc()) {
										printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
									}
								}
							?>	
						</select>
					<?php endif; ?>
						<?php
							if($_SESSION['data']['level'] == 1) {
								if(!empty($_SESSION['data']['archieve_access'])) {
									$archieve = unserialize($_SESSION['data']['archieve_access']);
								}
							}
						?>
						<?php if(count($archieve) > 0): ?>
						<!--<select name="archieve" id="archieve">
							
						</select>-->
						<?php else: ?>
						<?php
							printf("");
						?>
						<?php endif; ?>
					</form>
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
			</div>
			<?php
				if($_SESSION['data']['level'] > 1) {
					print('<div class="student-data-block no-display"></div>');
				}
			?>
			<div id="main-part" class="float-left-block" style="margin-top: 40px;">
				
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