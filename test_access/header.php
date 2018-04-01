<?php
	/*$mta = explode("/",$_SERVER['DOCUMENT_ROOT']);
    $mtel = $mta[count($mta) - 1]."";*/
	//print("<br>".$_SERVER['HTTP_HOST']."<br>");
	$mtel = $_SERVER['HTTP_HOST'];
?>

<div class="header">
		
		        	<?php if($_COOKIE['lang'] == "ru" || !isset($_COOKIE['lang'])):?>
			<ul id="nav7">
			  <li class="no_bg"><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/index.php"><img src="/tpl_img/logo.png"></a></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#">Расписание</a> 
				  <ul>
					<li><a href="diary.php">Дневник</a></li>
					<li><a href="calendar.php">Каледарь</a></li> 
				  </ul></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#">Журнал</a>
				  <ul>
					<li><a href="journal.php">Предметный журнал</a></li>
					<li><a href="tabel.php">Табель</a></li>
				  </ul></li>
			  <li><a href="homeworks.php">Домашнее задание <span>0</span></a> </li>
				<?php if($_SESSION['data']['level'] == 1): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/events">События <span>0</span></a> </li>
				<?php endif; ?>
			  <li><a href="chats.php">Сообщения <span>0</span></a> </li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#">Профиль</a> 
				<ul>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_1">Личная информация</a></li>
					<?php if($_SESSION['data']['level'] == 3): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_4">Списки пользователей</a></li>
					<?php endif; ?>
					<?php if($_SESSION['data']['level'] == 1): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_2">Управление рассылками</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_3">Управление оплатами</a></li>
					<?php endif; ?>
					<li><a href="http://<?=$mtel;?>/cabinet/goout.php">Выход</a></li>
				</ul></li> 
			</ul>
					<?php endif; ?>
					<?php if($_COOKIE['lang'] == "ua"):?>
			<ul id="nav7">
			  <li class="no_bg"><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/index.php"><img src="/tpl_img/logo.png"></a></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#">Розклад</a> 
				  <ul>
					<li><a href="diary.php">Щоденник</a></li>
					<li><a href="calendar.php">Календар</a></li> 
				  </ul></li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#">Журнал</a>
				  <ul>
					<li><a href="journal.php">Предметний журнал</a></li>
					<li><a href="tabel.php">Табель</a></li>
				  </ul></li>
			  <li><a href="homeworks.php">Домашнє завдання <span>0</span></a> </li>
				<?php if($_SESSION['data']['level'] == 1): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/events">Події <span>0</span></a> </li>
				<?php endif; ?>
			  <li><a href="chats.php">Повідомлення <span>0</span></a> </li>
			  <li><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_img/arrow_m.png"><a href="#">Профиль</a> 
				<ul>
					<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_1">Персональна інформація</a></li>
					<?php if($_SESSION['data']['level'] == 3): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_4">Списки користувачів</a></li>
					<?php endif; ?>
					<?php if($_SESSION['data']['level'] == 1): ?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_2">Керування розсилками</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/cabinet/index.php#tab_3">Керування сплатами</a></li>
					<?php endif; ?>
					<li><a href="http://<?=$mtel;?>/cabinet/goout.php">Вихід</a></li>
				</ul></li> 
			</ul>
					<?php endif; ?>

			
		<!-- смена языка -->
		<div class="language">
			<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
				<input type="submit" name="ru" value="">
				<input type="submit" name="ua" value="">
			</form>
		</div>
		<!-- смена языка -->
		</div>
<?php if(isset($_SESSION['data'])): ?>
<input type="hidden" name="level_chat" id="level_chat" value="<?=$_SESSION['data']['level'];?>">
<?php endif; ?>
	</div>  
	

<?php if(!isset($_SESSION['data'])): ?>	
<div>
<a class="block" onClick="open_block()">
<div class="tp_btn"></div>
</a>
<div id="contents_tp" style="display:none" onClick="close_block()">
<form class="tp_forma" method="post" action="<?=$_SERVER['REQUEST_URI']?>" name="tp_forma">
<h3>Техподержка</h3>
<p>Ваше обращение будет обработано <br>
в течение 24 часов</p> 
<input  type="text" name="name" placeholder="Ваше имя"><br>
<input  type="text" name="mail" placeholder="Электронный адрес"><br>
<textarea title=""  name="text_message" placeholder="Введите текст вашего собщения"></textarea>
<input class="button_tp" type="submit" name="mail">
</form>
</div>
</div> 
<?php endif; ?>
