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
	<meta name="description" content="Как выглядит журнал оценок на сайте 'Онлайн-школы 'Альтернатива''">
	<meta name="keywords" content="демо-доступ, журнал оценок, онлайн-школа">

	
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/journal.js"></script>
</head>
<body>
	<input type="hidden" name="language" value="<?php echo isset($_COOKIE['lang'])?$_COOKIE['lang']:'ru'; ?>">
	<?php
		include ("head2.php");
	?>
	
	<div class="content">
		<div class="alt_title_test">
		<div class="block0">
			<?php if(!isset($_SESSION['data'])): ?>
<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
			<h1>Все материалы, которые вы видите, являются демонстрационными. Функции обучения в демонстрационном доступе ограничены.
			 Для получения полного доступа к нашей онлайн-школе зарегистрируйтесь на сайте и оплатите обучение<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Оплатить обучение</a></h1>
			<?php else: ?>
			<h1>Усі матеріали, які ви бачите, є демонстраційними. Функції навчання в демонстраційному доступі
			 обмежені. Для одержання повного доступу до нашої онлайн-школи зареєструйтесь на сайті і оплатіть навчання<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Сплатити за навчання</a></h1>
			<?php endif; ?>
		<?php endif; ?> 
		</div>
		</div>
		<div class="block0">
			<h1><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Журнал оценок
		<? else: ?>
		Журнал оцінок
		<? endif; ?></h1>
			<a class="tables_adm_link" style="float: right; margin-left: 50px;" href="tabel.php">Табель</a>
			<br>
			 <div class="tabel_filter">
			 <a class="calendar_link"></a>
			 
			<form action="#" method="post"> 
			    <table>
					<tbody><tr> 						 
						<td><span>Предмет</span><br>
							<?php
								$sql_subjects = "SELECT * FROM os_subjects WHERE id 
								IN(SELECT id_s FROM os_class_subj WHERE class = 
									(SELECT id FROM os_class_manager WHERE is_opened=1))";
								$res_subjects = $mysqli->query($sql_subjects);
								$indexes = "";
							?>
							<select name="subject">
								<?php
									if ($res_subjects->num_rows == 0) {
										print("<option>--</option>");
									}
									else{
										while ($row_subjects = $res_subjects->fetch_assoc()) {
											$indexes .= $row_subjects['id'].", ";

											printf("<option value='%s'>%s</option>",$row_subjects['id'],$row_subjects['name_'.$_COOKIE['lang']]);
										}
										$indexes = rtrim($indexes,', ');
									}
								?>
							</select>
						</td>
					</tr>
				</tbody></table>
				<input type="hidden" name="indexes" value="<?php print($indexes); ?>">
			</form>
			 
			</div>
			
<div class="tabel_table">
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
							</tr>
							<? else: ?>
							<tr>
								<th>Тема уроку</th>
								<th>Дата</th>
								<th>Тренувальний тест</th>
								<th>Тестове д/з</th>
								<th>Творче д/з</th>
								<th>Загальне д/з</th>
							</tr>
							<? endif; ?>
						</thead>
						<?php
							$res_subjects = $mysqli->query($sql_subjects);
							if ($res_subjects->num_rows == 0) {
								print("<h1>Тут пусто</h1>");
							}
							else{
								printf("<tbody>");
								while ($row_subjects = $res_subjects->fetch_assoc()) {
										$sql_lessons = sprintf("SELECT title_%s AS title,DATE(date_%s) AS date_of FROM os_lessons WHERE id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=(
										                        SELECT id FROM os_class_manager WHERE is_opened=1)) AND subject='%s'",
											$_COOKIE['lang'],$_COOKIE['lang'],$row_subjects['id']);
										//print("<br>$sql_lessons<br>");
										$res_lessons = $mysqli->query($sql_lessons);
										if ($res_lessons->num_rows != 0) {
											while ($row_lessons = $res_lessons->fetch_assoc()) {
												$mark_simple = rand(5,12);
												$mark_control = rand(1,6);
												$mark_hw = rand(1,6);
												$mark_sum = $mark_control+$mark_hw;
												printf("<tr class='for_subject_%s'><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
													$row_subjects['id'],$row_lessons['title'],$row_lessons['date_of'],$mark_simple,$mark_control,$mark_hw,$mark_sum);
											}
										}
								}
								print("</tbody>");
							}
						?>
					</table>
					<ul class="tabel_premich">

					</ul>
				</div>
				<div class="clear"></div>
			</div>

			
			</div>  
			
		</div> 
	</div> 
	<script type="text/javascript">
		var $indexes = $("input[name = indexes]").val().split(', ');
		//alert($indexes);
		$("select[name=subject]").change(function(){
			//alert($(this).val());
			for (var id = 0; id < $indexes.length; id++) {
				//alert(id);
				$(".for_subject_"+$indexes[id]).css("display","none");
			};
			$(".for_subject_"+$(this).val()).css("display","");
		})
	</script>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 