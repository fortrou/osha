<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
		$title = "Календарь - Расписание - Онлайн Школа";
		$calendar = "Календарь";
		$toDiary = "Перейти к расписанию в дневнике";
		$month = "Месяц";
	} else {
		$title = "Календар - Розклад - Онлайн Школа";
		$calendar = "Календар";
		$toDiary = "Перейти до розкладу у щоденнику";
		$month = "Місяць";
	}
	

?>
<!DOCTYPE html> 
<head>  		
	<title><?php echo $title; ?></title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<script src="https://code.jquery.com/jquery-2.2.1.min.js"   integrity="sha256-gvQgAFzTH6trSrAWoH1iPo9Xc96QxSZ3feW6kem+O00="   crossorigin="anonymous"></script>
	<script src="../tpl_js/calendar_test.js"></script>

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
		
			<h1><?php echo $calendar; ?></h1>
			<div class="calendar_filter">
			<a class="calendar_link" href="diary.php"><?php echo $toDiary; ?></a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						 
						<td><span><?php echo $month; ?></span><br>
							<select name="pokaz">
							</select>
						</td>
						
						<input type="text" value="<?=$_COOKIE['lang']?>" hidden name="language">
						

					</tr>
				</table>
			</form>
			</div>
			<div class="calendar_table">
				<div class="left_cal"></div>
				<div class="right_cal"></div>
				<table id="calendar"></table>
			</div>
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 