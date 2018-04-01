<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
		$title = "Дневник - Расписание - Онлайн Школа";
		$diary = "Дневник";
		$toCalendar = "Перейти к расписанию в календаре";
		$show = "Показывать";
		$date_from = "Дата с";
		$date_to = "Дата до";
	} else {
		$title = "Щоденник - Розклад - Онлайн Школа";
		$diary = "Щоденник";
		$toCalendar = "Перейти до розкладу в календар";
		$show = "Показувати";
		$date_from = "Дата від";
		$date_to = "Дата до";
	}
?>
<!DOCTYPE html> 
<head>  		
	<title><?php echo $title; ?></title>
	<meta name="description" content="Как выглядит дневник в расписании на сайте 'Онлайн-школы 'Альтернатива''">
	<meta name="keywords" content="демо-доступ, дневник, онлайн-школа">

	<?php
		include ("../tpl_blocks/head.php");
	?>

	<script src="../tpl_js/diary_test.js"></script>

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
		
		
			<h1><?php echo $diary; ?></h1>
			<div class="diary_filter">
			<a class="calendar_link" href="calendar.php"><?php echo $toCalendar; ?></a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						<td><span><?php echo $date_from; ?></span><br>
							<input type="date" name="date_s">
						</td>
						<td><span><?php echo $date_to; ?></span><br>
							<input type="date" name="date_do">
						</td>
						<td><span><?php echo $show; ?></span><br>
							<select name="pokaz">
								<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
								<option value="7">1 неделя</option>
								<option value="14">2 недели</option>
								<option value="21">3 недели</option>
								<option value="28">4 недели</option>
								<option value="35">5 недель</option>
								<option value="42">6 недель</option>
								<option value="49">7 недель</option>
								<?php else: ?>
								<option value="7">1 тиждень</option>
								<option value="14">2 тижні</option>
								<option value="21">3 тижні</option>
								<option value="28">4 тижні</option>
								<option value="35">5 тижнів</option>
								<option value="42">6 тижнів</option>
								<option value="49">7 тижнів</option>
								<?php endif; ?>
							</select>
						</td>


					</tr>

					<input type="text" value="<?=$_COOKIE['lang']?>" hidden name="language">
					

				</table>
			</form>
			</div>
			<div class="diary_table">
				<table></table>
			</div>

		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 