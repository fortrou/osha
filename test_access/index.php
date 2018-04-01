<?php 
header( 'Location: /test_access/calendar.php', true, 301 );
?>

<?php

	session_start();

	require_once("../tpl_php/autoload.php");

	$db = Database::getInstance();

	$mysqli = $db->getConnection();
	//var_dump($_SESSION['data']);

?>
<!DOCTYPE html> 

<head>  	

	<?php if($_COOKIE['lang'] == 'ru'): ?>	

		<title>Диалоги - Онлайн Школа</title>

	<?php endif; ?>

	<?php if($_COOKIE['lang'] == 'ua'): ?>	

		<title>Діалоги - Онлайн Школа</title>

	<?php endif; ?>

	<meta name="description" content=" ">

	<meta name="keywords" content=" ">
	<script type="text/javascript" src="../tpl_js/common_chat.js"></script>
	
 



	<?php

		include ("../tpl_blocks/head.php");

	?>

</head>

<body>

	<?php

		include ("head2.php");

	?>
	

	

	<div class="content">
		
		<div class="block0">
		<div class="test_dost">
<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
			<h1>Все материалы, которые вы видите, являются демонстрационными. Функции обучения в демонстрационном доступе ограничены.
			 Для получения полного доступа к нашей онлайн-школе зарегистрируйтесь на сайте и оплатите обучение<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Оплатить обучение</a></h1>
			<?php else: ?>
			<h1>Усі матеріали, які ви бачите, є демонстраційними. Функції навчання в демонстраційному доступі
			 обмежені. Для одержання повного доступу до нашої онлайн-школи зареєструйтесь на сайті і оплатіть навчання<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Сплатити за навчання</a></h1>
			<?php endif; ?>
				<hr>
				<h2>Для контроля обучения вам будут доступны следующие разделы сайта:</h2>
			<a href="calendar.php">- Календарь</a><br>
			<a href="diary.php">- Дневник</a><br>
			<a href="chats.php">- Чаты</a><br>
			<a href="homeworks.php">- Домашние задания</a><br>
			<a href="../tests/completing.php?id=1">- Тест</a><br>
			<a href="tabel.php">- Табель</a><br>
			<a href="journal.php">- Журнал оценок</a>
		<h2>Все страницы доступны в ознакомительном режиме и работают в неполном объеме</h2>
		</div>
		</div>

	</div> 

	

	<?php

		include ("../tpl_blocks/footer.php");

	?>

</body> 

</html> 