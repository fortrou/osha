<?php
session_start();
?>
<!DOCTYPE html> 
<head>  		
	<title>Дневник - Расписание - Онлайн Школа</title>
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
			<h1>Дневник</h1>
			<div class="diary_filter">
			<a class="calendar_link" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/online_school/schedule/calendar.php">Перейти к расписанию в календаре</a>
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
								<option selected value="5">5 дней</option>
								<option value="10">10 дней</option>
								<option value="15">15 дней</option>
								<option value="20">20 дней</option>
								<option value="25">25 дней</option>
								<option value="30">30 дней</option>
							</select>
						</td>
						<!-- КЛАСС ВИДЕНЬ ТОЛЬКО АДМИНАМ -->
						<td><span>Класс</span><br>
							<select name="klass">
								<option selected value="1">1 класс</option>
								<option value="2">2 класс</option>
								<option value="3">3 класс</option>
								<option value="4">4 класс</option>
								<option value="5">5 класс</option>
								<option value="6">6 класс</option>
							</select>
						</td>
						<!-- КЛАСС ВИДЕНЬ ТОЛЬКО АДМИНАМ -->
					</tr>
				</table>
			</form>
			</div>
			<div class="diary_table">
				<table>
					<tr>
						<th rowspan="6"><span>Понедельник 1</span></th>
						<th>Предмет</th>
						<th>Тема</th>
						<th>Время</th>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
				</table>
				<table class="now_day">
					<tr>
						<th rowspan="6"><span>Вторник 2</span></th>
						<th>Предмет</th>
						<th>Тема</th>
						<th>Время</th>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
				</table>
				<table>
					<tr>
						<th rowspan="6"><span>Среда 3</span></th>
						<th>Предмет</th>
						<th>Тема</th>
						<th>Время</th>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
				</table>
				<table>
					<tr>
						<th rowspan="6"><span>Четверг 4</span></th>
						<th>Предмет</th>
						<th>Тема</th>
						<th>Время</th>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
				</table>
				<table>
					<tr>
						<th rowspan="6"><span>Пятница 5</span></th>
						<th>Предмет</th>
						<th>Тема</th>
						<th>Время</th>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
				</table>
				<table>
					<tr>
						<th rowspan="6"><span>Суббота 6</span></th>
						<th>Предмет</th>
						<th>Тема</th>
						<th>Время</th>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
				</table>
				<table>
					<tr>
						<th rowspan="6"><span>Воскресенье 7</span></th>
						<th>Предмет</th>
						<th>Тема</th>
						<th>Время</th>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
					<tr> 
						<td>Предмет 1</td>
						<td>Тема 1</td>
						<td>00:00</td>
					</tr>
				</table>
			</div>
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 