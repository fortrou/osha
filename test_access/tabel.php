<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
?>
<!DOCTYPE html> 
<head>  		
	<title>Табель - Журнал - Онлайн Школа</title>
	<meta name="description" content="Как выглядит табель на сайте 'Онлайн-школы 'Альтернатива''">
	<meta name="keywords" content="демо-доступ, табель, онлайн-школа">
	<!--<script type="text/javascript" src="../tpl_js/tabel.js"></script>-->
	<?php
		include ("../tpl_blocks/head.php");
	?>
</head>
<body>
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
			<h1>Табель</h1>
			<a style="float: right;margin-left: 50px;" class="tables_adm_link" 
			href="http://<?php echo $_SERVER['HTTP_HOST'];?>/test_access/journal.php">Ваш журнал</a> 
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru'): ?>
			<div class="print_btn"><a href="#" onclick="window.print()">Печатать табель</a><br>
			<?php else: ?>
			<div class="print_btn"><a href="#" onclick="window.print()">Друкувати табель</a><br>
			<?php endif; ?>
			<div class="tables_adm_table">
						<input type="hidden" name="level" value="1">
			<input type="hidden" name="id" value="3">
				<div style="width:100%;" class="right">
				<table class="tabel">	
					<thead>
						<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru'): ?>
						<tr>
							<th>Предмет</th>
							<th>За<br>1 семестр</th>
							<th>За<br>2 семестр</th>
							<th>За год</th>
							<th>За ГИА</th>
							<th>Итоговая</th>
						</tr>
						<?php else: ?>
						<tr>
							<th>Предмет</th>
							<th>За<br>1 семестр</th>
							<th>За<br>2 семестр</th>
							<th>За рік</th>
							<th>За ДПА</th>
							<th>Підсумкова</th>
						</tr>
						<?php endif; ?>
					</thead>
					
						<?php
							$sql_subjects = "SELECT * FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE class = (SELECT id FROM os_class_manager WHERE is_opened=1))";
							$res_subjects = $mysqli->query($sql_subjects);
							$indexes = "";
							if ($res_subjects->num_rows == 0) {
								print("<h1>Тут пусто</h1>");
							}
							else{
								printf("<tbody>");
								while ($row_subjects = $res_subjects->fetch_assoc()) {
										$mark_1s = rand(5,12);
										$mark_2s = rand(5,12);
										$mark_year = rand(5,12);
										$mark_gia = rand(5,12);
										$mark_sum = ceil(($mark_1s+$mark_2s+$mark_year+$mark_gia)/4);
										//print("<br>$mark_1s - $mark_2s - $mark_year - $mark_gia - $mark_sum<br>");
										printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
											$row_subjects['name_'.$_COOKIE['lang']],$mark_1s,$mark_2s,$mark_year,$mark_gia,$mark_sum);
									}
								print("</tbody>");
							}
						?>
					
				</table>				
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