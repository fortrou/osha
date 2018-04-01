<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
?>
<!--<!DOCTYPE html>-->
<html>
<head>  		
	<title><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Журнал оценок - Журнал
		<? else: ?>
		Журнал оцінок - Журнал
		<? endif; ?> - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/journal.1234.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	<div class="content">
		<div class="no-display green-system-field"></div>
		<div id="print">
			<input type="hidden" name="id_journal" value="">
			<span onclick="easy_unprint()">X</span>
			<table id="tab_down">
				<tr>
					<td><h4><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Результаты теста
		<? else: ?>
		Результати тесту
		<? endif; ?></h4></td>
					<td colspan="2"><h4><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Файлы с творческого ДЗ
		<? else: ?>
		Файли з творчого ДЗ
		<? endif; ?></h4></td>
				</tr>
				<tr>
					<td>

					</td>
					<td>
						<h4><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Файлы ученика
		<? else: ?>
		Файли учня
		<? endif; ?></h4>
					</td>
					<td>
						<h4><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Файлы учителя
		<? else: ?>
		Файли вчителя
		<? endif; ?></h4>
					</td>
				</tr>
				<tr>
					<td id="results">

					</td>
					<td class="student_docs">

					</td>
					<td class="teacher_docs">

					</td>
				</tr>
			</table>
		</div>
		<?php
			if($_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=3";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=3",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(3, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div class="block0">
			<h1><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Журнал оценок
		<? else: ?>
		Журнал оцінок
		<? endif; ?></h1>
			<div class="tabel_filter">
			 <a class="calendar_link"></a>
			
		 <a class="tables_adm_link" style="float: right; margin-left: 50px;" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/jurnal/tabel.php">Табель</a>
			 <?php if($_SESSION['data']['level'] >= 2): ?>
			<a class="tabel_link" href="#"><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Добавить тематическую
		<? else: ?>
		Додати тематичну
		<? endif; ?></a>  
			 
			<div id="tabel_link_content" style="display: none;">
				<form action="#" method="post"> 
					<input name="name_ua" type="text" placeholder="Введіть українську назву тематичної оцінки"><br/>
					<input name="name_ru" type="text" placeholder="Введите русское название тематической оценки"><br/>
					<select name="position">
								<option value="1">Тренировочный тест</option>
								<option value="2">Тестовое д/з</option>
								<option value="3">Творческое д/з</option>
								<option value="4">Общее д/з</option> 
					</select><br/>
					<p>Дата в русской версии</p>
					<input type="date" name="date_ru">
					<input type="date" name="date_ua"> 
					<input name="mark" type="text" placeholder="Введите оценку от 0 до 12"><br/>
					<input type="button" name="set_t" value="Сохранить">			
					<input type="button" value="Отмена" onclick="easy_close()">					
				</form>
			</div>  
			<?php endif; ?>

			<form action="#" method="post"> 
				<input type="text" value="<?=$_COOKIE['lang']?>" hidden name="language">
			    <input type="hidden" name="id" value="<?=$_SESSION['data']['id'];?>">
			    <input type="hidden" name="level" value="<?=$_SESSION['data']['level'];?>">
			    <input type="hidden" name="del_id" id="del_id" value="0">
			    <table>
					<tr> 						 
						<td><span>Предмет</span><br>
							<select name="subject">
								<?php
									if($_SESSION['data']['level'] > 2 ){
										$class = $_SESSION['data']['class'];
										$query = "SELECT * FROM os_subjects WHERE id IN (SELECT id_s FROM os_class_subj WHERE class='$class')";
										//print("<br>$query<br>");
										$result = $mysqli->query($query);
										//var_dump($result);
										while($row = $result->fetch_assoc()){
											printf("<option value='%s'>%s</option>",$row['id'],$row['name']);
										}
									}

									if($_SESSION['data']['level'] == 1 ){
										/*$sql = sprintf("SELECT * FROM os_subjects WHERE id 
											IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_SESSION['data']['id']);
										$result = $mysqli->query($sql);
										$subjects_all = "";
										//print("<br>$sql<br>");
										while($row = $result->fetch_assoc()){
											$subjects_all .= $row['id'].",";
										}
										$subjects_all = rtrim($subjects_all,",");*/
									}
									/*print("<option value='$subjects_all' selected>Все предметы</option>");*/
									$sql = sprintf("SELECT * FROM os_subjects WHERE id 
										IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_SESSION['data']['id']);
									//print($sql);
									$res = $mysqli->query($sql);
										while ($row = $res->fetch_assoc()) {
											printf("<option value='%s'>%s</option>",$row['id'],$row['name_'.$_COOKIE['lang']]);
										}
									
								?>

							</select>
						</td>
						<?php if($_SESSION['data']['level'] >= 2): ?>
						<td><span>Класс</span><br>
							<select name="class">
							<?php
							if($_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3){
								$sql = "SELECT * FROM os_class_manager";
							}
							if($_SESSION['data']['level'] == 2){
								$sql = sprintf("SELECT * FROM os_class_manager WHERE id IN(SELECT id_c FROM os_teacher_class WHERE id_teacher='%s')",
								$_SESSION['data']['id']);
							}
							$res = $mysqli->query($sql);
							if($res->num_rows != 0){
								while($row = $res->fetch_assoc()){
									printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
								}
							}
							?>
							</select>
						</td>
						<?php endif; ?>
						
					</tr>
				</table>
			</form>
			<?php if($_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3 || $_SESSION['data']['level'] == 2): ?>
			<form action="#" method="post"> 
				<input type="search" name="search" placeholder="ученик"> <input type="submit" value="Поиск"> 
			</form>
			<?php endif; ?> 
			</div>  
			<?php
				if($_SESSION['data']['level'] > 1) {
					print('<div class="student-data-block no-display" style="margin-top: -15px; margin-bottom: 20px;"></div>');
				}
			?>
			<div class="tabel_table">
				<?php if($_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3 || $_SESSION['data']['level'] == 2): ?>
					<div class="tabel_left_b">
						<select name="students" size="6">

						</select>
					</div>
				<?php endif; ?>
				<div class="tabel_right_b">
					<table class="rb_table">
						<thead>
							<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
							<tr>
								<th>Тема урока</th>
								<th>Дата</th>
								<th>Тренировочный тест</th>
								<th>Тестовое д/з</th>
								<th>Творческое д/з</th>
								<th>Общее д/з</th>
								<?php if(isset($_SESSION['data']['level']) && $_SESSION['data']['level'] != 3): ?>
								<th>Печать</th>
								<?php endif; ?>
							</tr>
							<? else: ?>
							<tr>
								<th>Тема уроку</th>
								<th>Дата</th>
								<th>Тренувальний тест</th>
								<th>Тестове д/з</th>
								<th>Творче д/з</th>
								<th>Загальне д/з</th>
								<?php if(isset($_SESSION['data']['level']) && $_SESSION['data']['level'] != 3): ?>
								<th>Друк</th>
								<?php endif; ?>
							</tr>
							<? endif; ?>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					
					<?php if($_SESSION['data']['level'] == 4): ?>
						<input type="text" name="notif_ru" placeholder="Русский вариант">
						<input type="text" name="notif_ua" placeholder="Украинский вариант">
						<input type="button" name="add_notif" value="Добавить примечание">
					<?php endif; ?>
					<ul class="tabel_premich tabel_premich_theme">

					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 