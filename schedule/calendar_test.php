<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	

?>
<!DOCTYPE html> 
<head>  		
	<title>Календарь - Расписание - Онлайн Школа</title>
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
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class="block0">
			<?php if(!isset($_SESSION['data'])): ?>
			<h1>Этот контент является ознакомительным, для того, чтобы получить полный доступ вам необходимо 
				<a href="http://online-shkola.com.ua/reg.php">зарегистрироваться</a></h1>
		<?php endif; ?>
		
			<h1>Календарь</h1>
			<div class="calendar_filter">
			<a class="calendar_link" href="diary.php">Перейти к расписанию в дневнике</a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						 
						<td><span>Месяц</span><br>
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