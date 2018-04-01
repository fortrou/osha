<?php
	session_start();
?>
<!DOCTYPE html> 
<head>  		
	<title>События - Онлайн Школа</title>
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
			
			<div class="doing_filter">
			 
			<form action="#" method="post"> 
			    <table>
					<tr> 						 
						<td><span>Выбрать тип события</span><br>
							<select name="klass">
								<option selected value="1">Все события</option>
								<option value="2">Уроки</option>
								<option value="3">Новости</option>
								<option value="4">Проверка ДЗ</option>
								<option value="5">Новые оценки в табеле</option>
								<option value="6">Оплаты</option>
							</select>
						</td>						 
					</tr>
				</table>
			</form>
			</div>
			<h1>События</h1>
			<div class="clear"></div>
			<div class="doing_table">
				 <div class="new_doing">
					<div class="close_d"><a href="#">x</a></div>
					<h5>Уведомление. <a href="#">Ссылка</a></h5>
					<div class="date_d">01.01.16 в 00:00</div>
				 </div>
				 <div class="clear"></div>
				 <div class="old_doing">
					<h5>Уведомление. <a href="#">Ссылка</a></h5>
					<div class="date_d">01.01.16 в 00:00</div>				 
				 </div>
			</div>
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 