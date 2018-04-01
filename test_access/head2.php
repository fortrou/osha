<div class="header">

		<div class="block0">

			<div class="default"> 
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		<ul id="nav7">

							<li class="no_bg"><a href="http://online-shkola.com.ua/index.php"><img src="/tpl_img/logo.png"></a></li>

							<li><img src="http://online-shkola.com.ua/tpl_img/arrow_m.png"><a href="#">Расписание</a> 

					  <ul>

							<li><a href="http://online-shkola.com.ua/test_access/diary.php">Дневник</a></li>

							<li><a href="http://online-shkola.com.ua/test_access/calendar.php">Каледарь</a></li> 

					  </ul></li>

							<li><img src="http://online-shkola.com.ua/tpl_img/arrow_m.png"><a href="#">Журнал</a>

					  <ul>

							<li><a href="http://online-shkola.com.ua/test_access/journal.php">Предметный журнал</a></li>

							<li><a href="http://online-shkola.com.ua/test_access/tabel.php">Табель</a></li>

					  </ul></li>

							<li><a style="padding: 10px 7px 10px 7px;" href="http://online-shkola.com.ua/test_access/homeworks.php">Домашнее задание</a> </li>

							<li><a style="padding: 10px 7px 10px 7px;" href="http://online-shkola.com.ua/test_access/chats.php">Сообщения</a> </li>

						<!--	<li><img src="http://online-shkola.com.ua/tpl_img/arrow_m.png"><a href="#">Профиль</a> 

					<ul>

						<li><a href="http://online-shkola.com.ua/cabinet/index.php#tab_1">Личная информация</a></li>

						<li><a href="http://online-shkola.com.ua/cabinet/goout.php">Выход</a></li>

					</ul></li> -->

				</ul> 
		<? else: ?>
		<ul id="nav7">

							<li class="no_bg"><a href="http://online-shkola.com.ua/index.php"><img src="/tpl_img/logo.png"></a></li>

							<li><img src="http://online-shkola.com.ua/tpl_img/arrow_m.png"><a href="#">Розклад</a> 

					  <ul>

							<li><a href="http://online-shkola.com.ua/test_access/diary.php">Щоденник</a></li>

							<li><a href="http://online-shkola.com.ua/test_access/calendar.php">Календар</a></li> 

					  </ul></li>

							<li><img src="http://online-shkola.com.ua/tpl_img/arrow_m.png"><a href="#">Журнал</a>

					  <ul>

							<li><a href="http://online-shkola.com.ua/test_access/journal.php">Предметний журнал</a></li>

							<li><a href="http://online-shkola.com.ua/test_access/tabel.php">Табель</a></li>

					  </ul></li>

							<li><a style="padding: 10px 7px 10px 7px;" href="http://online-shkola.com.ua/test_access/homeworks.php">Домашнє завдання</a> </li>

							<li><a style="padding: 10px 7px 10px 7px;" href="http://online-shkola.com.ua/test_access/chats.php">Повiдомлення</a> </li>

						<!--	<li><img src="http://online-shkola.com.ua/tpl_img/arrow_m.png"><a href="#">Профиль</a> 

					<ul>

						<li><a href="http://online-shkola.com.ua/cabinet/index.php#tab_1">Личная информация</a></li>

						<li><a href="http://online-shkola.com.ua/cabinet/goout.php">Выход</a></li>

					</ul></li> -->

				</ul> 
		<? endif; ?>
				

		  </div>
<!-- смена языка -->
		<div class="language">
			<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
				<input type="submit" name="ru" value="">
				<input type="submit" name="ua" value="">
			</form>
		</div>
		<!-- смена языка -->
			 

		</div>

	</div>