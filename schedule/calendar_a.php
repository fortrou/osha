<?php
	session_start();
?>
<!DOCTYPE html> 
<head>  		
	<title>Календарь - Расписание - Онлайн Школа</title>
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
			<h1>Календарь</h1>
			<div class="calendar_filter">
			<a class="calendar_link" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/online_school/schedule/diary.php">Перейти к расписанию в дневнике</a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						 
						<td><span>Месяц</span><br>
							<select name="pokaz">
								<option selected value="Сентябрь">Сентябрь</option>
								<option value="Октябрь">Октябрь</option>
								<option value="Ноябырь">Ноябырь</option>
								<option value="Декабрь">Декабрь</option>
								<option value="Январь">Январь</option>
								<option value="Февраль">Февраль</option>
								<option value="Март">Март</option>
								<option value="Апрель">Апрель</option>
								<option value="Май">Май</option>
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
			<div class="calendar_table">
				<div class="left_cal"></div>
				<div class="right_cal"></div>
				<table>
					<tr>
						<td><h4>29 августа, пн</h4>
						
						</td>
						<td><h4>30 августа, вт</h4>
						
						</td>
						<td><h4>31 августа, ср</h4>
						
						</td>
						<td class="active_calendar_table"><h4>1 сентября, чт</h4> 						
							<table>
								<tr>	
									<td>Предмет 1</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 2</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 3</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 4</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 5</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 6</td>
									<td>00:00</td>
								</tr>
							</table>
						</td>
						<td><h4>2 сентября, пт</h4>
							<table>
								<tr>	
									<td>Предмет 1</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 2</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 3</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 4</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 5</td>
									<td>00:00</td>
								</tr>
								<tr>	
									<td>Предмет 6</td>
									<td>00:00</td>
								</tr>
							</table>
						</td>
						<td><h4>3 сентября, сб</h4>
						
						</td>
					</tr>
					
					<tr>
						<td><h4>4 сентября, пн</h4>
						
						</td>
						<td><h4>5 сентября, вт</h4>
						
						</td>
						<td><h4>6 сентября, ср</h4>
						
						</td>
						<td><h4>7 сентября, чт</h4> 						
							
						</td>
						<td><h4>8 сентября, пт</h4>
						
						</td>
						<td><h4>9 сентября, сб</h4>
						
						</td>
					</tr>
					<tr>
						<td><h4>10 сентября, пн</h4>
						
						</td>
						<td><h4>11 сентября, вт</h4>
						
						</td>
						<td><h4>12 сентября, ср</h4>
						
						</td>
						<td><h4>13 сентября, чт</h4> 						
							 
						</td>
						<td><h4>14 сентября, пт</h4>
							 
						</td>
						<td><h4>15 сентября, сб</h4>
						
						</td>
					</tr>
					<tr>
						<td><h4>16 сентября, пн</h4>
						
						</td>
						<td><h4>17 сентября, вт</h4>
						
						</td>
						<td><h4>18 сентября, ср</h4>
						
						</td>
						<td><h4>19 сентября, чт</h4> 						
							 
						</td>
						<td><h4>20 сентября, пт</h4>
							 
						</td>
						<td><h4>21 сентября, сб</h4>
						
						</td>
					</tr>
					<tr>
						<td><h4>22 сентября, пн</h4>
						
						</td>
						<td><h4>23 сентября, вт</h4>
						
						</td>
						<td><h4>24 сентября, ср</h4>
						
						</td>
						<td><h4>25 сентября, чт</h4> 						
							 
						</td>
						<td><h4>26 сентября, пт</h4>
							 
						</td>
						<td><h4>27 сентября, сб</h4>
						
						</td>
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