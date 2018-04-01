<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
?>
<!DOCTYPE html> 
<head>  		
	<title>Дневник - Расписание - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<?php
		include ("../tpl_blocks/head.php");
	?>

	<script src="../tpl_js/diary_test.js"></script>

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
		
			<h1>Дневник</h1>
			<div class="diary_filter">
			<a class="calendar_link" href="calendar.php">Перейти к расписанию в календаре</a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						<td><span>Дата с</span><br>
							<input type="date" name="date_s">
						</td>
						<td><span>Дата до</span><br>
							<input type="date" name="date_do">
						</td>
						<td><span>Показывать</span><br>
							<select name="pokaz">
								<option value="7">1 неделя</option>
								<option value="14">2 недели</option>
								<option value="21">3 недели</option>
								<option value="28">4 недели</option>
								<option value="35">5 недель</option>
								<option value="42">6 недель</option>
								<option value="49">7 недель</option>
								</select>
						</td>

						<td><span>Класс</span><br>
							<select name="class">
								<option value="1">1 класс</option>
								<option value="2">2 класс</option>
								<option value="3">3 класс</option>
								<option value="4">4 класс</option>
								<option value="12">12 класс</option>
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