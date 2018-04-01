<?php
if(!isset($_GET['id']))
	header("Location:".$_SERVER['HTTP_REFERER']);
	session_start();
	require_once('../tpl_php/autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	try 
	{
		$lesson = Lesson::Load($_GET['id']);
	} 
	catch (Exception $e) 
	{
		print($e->getMessage());
	}
?>
<!DOCTYPE html> 
<head>  		
	<title>Табель - Журнал - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	
	<?php
		include ("../tpl_blocks/head.php");
	?>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	<div class="content">
		<div class="block0">
		<?php if(!isset($_SESSION['data'])): ?>
			<h1>Этот контент является ознакомительным, для того, чтобы получить полный доступ вам необходимо 
				<a href="http://online-shkola.com.ua/reg.php">зарегистрироваться</a></h1>
		<?php endif; ?>
		
	
	<div class="content">
		<?php
			if ($lesson->getContrTest($_COOKIE['lang'])!=false) {
				printf("<div id='hover_minis_1'><button class='cover'><a href='../tests/completing.php?id=%s'>Контрольный тест</a></button>",
					$lesson->getContrTest($_COOKIE['lang']));
				if ($_SESSION['data']['level'] == 4) {
					printf("<span><button class='cover'>
					<a href='../tests/testred.php?tid=%s'>Редактировать тест</a>
					</button></span>",$lesson->getContrTest($_COOKIE['lang']));
				} 
				print("</div>");
			}
			if ($lesson->getHW($_COOKIE['lang'])!=false) {
				printf("<div id='hover_minis_2'><button class='cover'><a href='lookhw.php?id=%s'>Творческое домашнее задание</a></button>",
					$lesson->getHW($_COOKIE['lang']));
				if ($_SESSION['data']['level'] == 4) {
					printf("<span><button class='cover'>
					<a href='redacthw.php?id=%s'>Редактировать творческое ДЗ</a>
					</button></span>",$lesson->getHW($_COOKIE['lang']));
				} 
				print("</div>");
			}
		?>
		<div class="clear"></div>
	</div> 
	
</div> 
	</div> 
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 