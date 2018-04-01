<?php
session_start();
	require_once("tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "" ){
		$_COOKIE['lang'] = "ru";
	}
	$sql_p1 = sprintf("SELECT text_%s AS cont, price FROM os_elements WHERE categ=6 AND position=1",$_COOKIE['lang']);
	//print($sql_p1);
	$res_p1 = $mysqli->query($sql_p1);
	$row_p1 = $res_p1->fetch_assoc();
	$sql_p2 = sprintf("SELECT text_%s AS cont, price FROM os_elements WHERE categ=6 AND position=2",$_COOKIE['lang']);
	$res_p2 = $mysqli->query($sql_p2);
	$row_p2 = $res_p2->fetch_assoc();
	$sql_p3 = sprintf("SELECT text_%s AS cont, price FROM os_elements WHERE categ=6 AND position=3",$_COOKIE['lang']);
	$res_p3 = $mysqli->query($sql_p3);
	$row_p3 = $res_p3->fetch_assoc();
	$sql_p4 = sprintf("SELECT text_%s AS cont, price FROM os_elements WHERE categ=6 AND position=4",$_COOKIE['lang']);
	$res_p4 = $mysqli->query($sql_p4);
	$row_p4 = $res_p4->fetch_assoc();
	
	$sql_main1 = sprintf("SELECT video FROM os_elements WHERE categ=4 AND position=1");
	$res_main1 = $mysqli->query($sql_main1);
	$row_main1 = $res_main1->fetch_assoc();
	$sql_des2 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=3 AND position=1",$_COOKIE['lang']);
	$res_des2 = $mysqli->query($sql_des2);
	$row_des2 = $res_des2->fetch_assoc();
?>
<!DOCTYPE html> 
<head>  		
	<title>
		<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Главная - Онлайн Школа
		<? else: ?>
		Головна - Онлайн Школа
		<? endif; ?>
	</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" "> 
	<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon">
<!--The 2010 IANA standard but not supported in IE-->
	<link rel="stylesheet" type="text/css" media="all" href="tpl_css/style.css" /> 
	<link rel="stylesheet" type="text/css" href="tpl_css/animate.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>   
	<script type="text/javascript" src="tpl_js/easypaginate.js"></script>
	<script src="tpl_js/eskju.jquery.scrollflow.js"></script>
	<script src="tpl_js/other_scr.js"></script>
	<script src="tpl_js/jquery.sticky.js"></script> 
	<script type="text/javascript">
		function captcha_trigger(form_id) {
			$("#captcha-form input[name = form-id]").val(form_id);
			$("#captcha-form").toggleClass("no-display");
		}
	</script>
</head>
<body style="font-family: circe2;" id="top">
<?php if(isset($_SESSION['data'])): ?>
<input type="hidden" name="level_chat" id="level_chat" value="<?=$_SESSION['data']['level'];?>">
<?php endif; ?>
	</div>  
	
<?php if(!isset($_SESSION['data'])): ?>	
<div>
<a class="block" onClick="open_block()">
<div <? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		style="background: url(../tpl_img/tp_btn.png);"
		<? else: ?>
		style="background: url(../tpl_img/tp_btn2.png);"
		<? endif; ?> class="tp_btn"></div>
</a>
	<div id="contents_tp" style="display:none" onClick="close_block()">
		<form id="form-3" class="tp_forma" method="post" action="<?=$_SERVER['REQUEST_URI']?>" name="tp_forma">
			<h3><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Техподдержка
					<? else: ?>
					Техпідтримка
					<? endif; ?></h3>
			<p><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Ваше обращение будет обработано<br>
			в течение 24 часов
					<? else: ?>
					Ваше звернення буде опрацьовано<br>
			протягом 24 годин
					<? endif; ?></p> 
			<input  type="text" name="name" placeholder="Ваше имя"><br>
			<input  type="text" name="email" placeholder="Электронный адрес"><br>
			<textarea title=""  name="text_message" placeholder="<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>Введите текст вашего сообщения<? else: ?>Введіть текст вашого повідомлення<? endif; ?>"></textarea>
			<?php
				if(isset($_SESSION['capcha-error']) && !empty($_SESSION['capcha-error'])) {
					printf("<p style='color:red'>%s</p>", $_SESSION['capcha-error']);
					unset($_SESSION['capcha-error']);
				}
			?>
			<input type="hidden" name="g-recaptcha-response" value="">
			<input type="hidden" value="1" name="mail">
			<input class="button_tp" value="Отправить" type="button" onclick="captcha_trigger('form-3')">
		</form>
	</div>
</div> 
<?php endif; ?>
<?php if(isset($_SESSION['data']) && $_SESSION['data']['level'] != 4): ?>
<div>
<a class="block" onClick="open_block()">
<div <? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		style="background: url(../tpl_img/tp_btn.png);"
		<? else: ?>
		style="background: url(../tpl_img/tp_btn2.png);"
		<? endif; ?> class="tp_btn"></div>
</a>
<div id="contents_tp" style="display:none" onClick="close_block()">
<form class="tp_forma" method="post" action="<?=$_SERVER['REQUEST_URI']?>" name="tp_forma" >
<h3>
	<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Техподдержка
		<? else: ?>
		Техпідтримка
		<? endif; ?>
		</h3>
<p>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		Ваше обращение будет обработано<br>
в течение 24 часов
		<? else: ?>
		Ваше звернення буде опрацьовано<br>
протягом 24 годин
		<? endif; ?>
		</p> 
<ul id="ac_field_chat" style="overflow-y:auto; height:180px;">
</ul>
<input type='hidden' name="ac_from" value="<?=$_SESSION['data']['id']?>">
<input type='hidden' name="ac_to" value="admin">
<input type='hidden' name="ac_chat_id" id="chat_id" value="<?=$_SESSION['data']['chat_id']?>">
<textarea title="" name="ac_text_chat" class="message" placeholder="<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>Введите текст вашего сообщения<? else: ?>Введіть текст вашого повідомлення<? endif; ?>"></textarea>
<input class="button_tp" type="button" onclick="send()" name="mail_to_admin" value="Отправить">
</form>
</div>
</div>
<script type="text/javascript">  
	//request(d);
	getMessages($("input[name = ac_chat_id]").val());
	var pre_interv = setInterval(getMessages,120000,$("input[name = ac_chat_id]").val());
	$("textarea[name = ac_text_chat]").on('focus',function(){
		//alert(a);
	getMessages($("input[name = ac_chat_id]").val());
	var intervall = setInterval(getMessages,8000,$("input[name = ac_chat_id]").val());
		$(this).on('blur',function(){
		clearInterval(intervall);
					
		})
	})
</script>
<?php endif; ?>
<?php
	if(!isset($_COOKIE["lang"]) || $_COOKIE["lang"] == 'ru') {
		$summerSchool  = "Интересные каникулы";
		$handMade 	   = "Хэнд-мэйд";
		$englishLang   = "Английский <br>язык";
		$mathSubj	   = "Занимательная <br>математика";
		$science	   = "Интересная <br>наука";
		$journalistic  = "Журналистика";
		$photoShop	   = "Компьютерный <br>дизайн";
		$beginLearning = "Начать заниматься";
		$priceAndBegin = "Цена: 350 грн";
		$details	   = "Подробнее";
		$alertMessage  = "Оплатить обучение в Летней школе и получить доступ к личному кабинету можно будет с 01.06.2017";
		$alertModuleID = "Модуль в процессе доработок. Открытие состоится 19.06.2017";
	} else {
		$summerSchool  = "Цікаві канікули";
		$handMade 	   = "Хенд-мейд";
		$englishLang   = "Англійська <br>мова";
		$mathSubj	   = "Пізнавальна <br>математика";
		$science	   = "Цікава <br>наука";
		$journalistic  = "Журналістика";
		$photoShop	   = "Комп'ютерний <br>дизайн";
		$beginLearning = "Почати займатися";
		$priceAndBegin = "Ціна: 350 грн";
		$details	   = "Детальніше";
		$alertMessage  = "Оплатити навчання у Літній школі і отримати доступ до особистого кабінету можна буде з 01.06.2017";
		$alertModuleID = "Модуль в процессi допрацювання. Вiдкриття вiдбудеться 19.06.2017";
	}
?>
	<div id="index_top" class="index_head">
		<div class="index_head_top">
			<a href="#index_top"><img src="tpl_img/logo.png"></a>
			
		<div class="index_head_top2">
			<span style="background: url(tpl_img/c11.png) center left no-repeat;">пн-пт, 11-00 – 18-00</span> <span style="background: url(tpl_img/c22.png) center left no-repeat;">(068) 057-29-02 (073) 023-82-29</span> <span style="background: url(tpl_img/c33.png) center left no-repeat;">shkola.alt@gmail.com</span>
			
		</div>
		
		<div class="index_head_menu">
			<a class="postup" href="#1">
		<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		ПОСТУПИТЬ В ШКОЛУ
		<? else: ?>
		ВСТУПИТИ ДО ШКОЛИ
		<? endif; ?>
		</a>
			
			<a class="menuse" href="#2">
		<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
		О ШКОЛЕ
		<? else: ?>
		ПРО ШКОЛУ
		<? endif; ?>
	</a>
			<a class="menuse" href="#3">
				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				ЦЕНЫ НА ОБУЧЕНИЕ
				<? else: ?>
				ЦІНИ ЗА НАВЧАННЯ
				<? endif; ?>
		</a>
			<a class="menuse" href="#4">
				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				КАК МЫ УЧИМ
				<? else: ?>
				ЯК МИ ВЧИМО
				<? endif; ?>
			</a>
			
			<!-- смена языка -->
			<div class="index_lng">
				<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
					<input type="submit" name="ru" value="РУС"> | 
					<input type="submit" name="ua" value="УКР">
				</form>
			</div>
			<!-- смена языка 
			<a style="margin-left: 50px;" class="lang_ind" href="#">УКР</a> | <a class="lang_ind" href="#">РУС</a>-->
			
		</div>
		</div>
		<div class="index_head_h3">
			<div class="index_head_h3_zag">
				<h3>
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Онлайн-школа «Альтернатива»
					<? else: ?>
					Онлайн-школа «Альтернатива»
					<? endif; ?><br>
					<p>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						Дистанционная школа с получением полного
						<? else: ?>
						Дистанційна школа зі здобуттям повної
						<? endif; ?><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						общего среднего образования с 1 по 11(12) класс
						<? else: ?>
						загальної середньої освіти з 1 до 11 (12) класу
						<? endif; ?><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						и выдачей документов государственного образца
						<? else: ?>
						та видачею документів державного зразка
						<? endif; ?>
					</p></h3> 
			</div>
			<div class="proba_container">
						<div class="index_head_proba">
			<a class="index_head_h3_proba" href="/test_access/">
				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						Попробовать бесплатно
						<? else: ?>
						Спробувати безкоштовно
						<? endif; ?></a>
		</div>
		<div class="index_head_proba_summer">
			<a class="index_head_h3_proba" id="myscroll1" href="#111"><?php echo $summerSchool; ?></a>
		</div>
			</div>
			 
		</div>
		<div class="index_head_login">
			<div class="index_head_login_block">
				
				<?php if(!isset($_SESSION['data'])): ?> 
				
				
				
				 
				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<h5>Начать заниматься</h5>
						 <form action="cabinet/auth.php" method="post"> 
								<input name="name" type="text" placeholder="Логин" required=""><br> 
								<input name="ocenka" type="text" placeholder="Пароль" required=""><br> 
								<a href="forgot.php" class="index_head_login_link">Забыли пароль?</a><br> 
								<input type="submit" value="Войти">	 
								<a href="reg.php" class="index_head_login_link2">Регистрация</a>	 			
								
								
						</form>
				<? else: ?>
						<h5>Почати займатися</h5>
						 <form action="cabinet/auth.php" method="post"> 
								<input name="name" type="text" placeholder="Логін" required=""><br> 
								<input name="ocenka" type="text" placeholder="Пароль" required=""><br> 
								<a href="forgot.php" class="index_head_login_link">Забули пароль?</a><br> 
								<input type="submit" value="Увійти">	 
								<a href="reg.php" class="index_head_login_link2">Реєстрація</a>	 			
								
								
						</form>
				<? endif; ?>
				
				
				<?php endif; ?>
				<?php if(isset($_SESSION['data'])): ?> 
				<div class="head_reg_user">
					
					
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					<a href="/schedule/diary.php">Мои уроки</a><br><br><br><br>
					<a style="color: #e53a24; padding: 10px 44px;" href="/cabinet/goout.php">Выход</a>
					<? else: ?>
					<a href="/schedule/diary.php">Мої уроки</a><br><br><br><br>
					<a style="color: #e53a24; padding: 10px 44px;" href="/cabinet/goout.php">Вихід</a>
					<? endif; ?>
				</div>
				<?php endif; ?>
				
				
				
				
			</div>
		</div>
		
		
	</div>
		<a id='111'></a>
 
		<div  class="index_body_2 scrollflow -pop -opacity" id="float-block">
			<div class="index_body_2_fix">
				<a class="min_log" href="#index_top"><img src="../tpl_img/min_log.png"></a>
				<a class="postup1" href="#1">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					ПОСТУПИТЬ В ШКОЛУ
					<? else: ?>
					ВСТУПИТИ ДО ШКОЛИ
					<? endif; ?></a>			
				<a class="menuse1" href="#2">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					О ШКОЛЕ
					<? else: ?>
					ПРО ШКОЛУ
					<? endif; ?>
				</a>
				<a class="menuse1" href="#3">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					ЦЕНЫ НА ОБУЧЕНИЕ
					<? else: ?>
					ЦІНИ ЗА НАВЧАННЯ
					<? endif; ?>
				</a>
				<a class="menuse1" href="#4">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					КАК МЫ УЧИМ
					<? else: ?>
					ЯК МИ ВЧИМО
					<? endif; ?>
				</a>
				<a class="proba1" href="/test_access/calendar.php">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					ПОПРОБОВАТЬ БЕСПЛАТНО
					<? else: ?>
					СПРОБУВАТИ БЕЗКОШТОВНО
					<? endif; ?></a>
			</div>
		</div> 
		<hr>
		<div class="index_body_3 scrollflow -pop -opacity"> 
		<h1 class="courseHead"><?php echo $summerSchool; ?></h1>
		<table class="courseTable">
			<tr>
				<td>
					<table>
						<tr>
							<td class="coursePhoto"><img src="/tpl_img/handMade.png"></td>
							<td class="courseName"><?php echo $handMade; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td class="coursePhoto"><img src="/tpl_img/english.png"></td>
							<td class="courseName"><?php echo $englishLang; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td class="coursePhoto"><img src="/tpl_img/science.png"></td>
							<td class="courseName"><?php echo $science; ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table>
						<tr>
							<td class="coursePhoto"><img src="/tpl_img/ps.png"></td>
							<td class="courseName"><?php echo $photoShop; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td class="coursePhoto"><img src="/tpl_img/math.png"></td>
							<td class="courseName"><?php echo $mathSubj; ?></td>
						</tr>
					</table>
				</td>
				<td>
					<table>
						<tr>
							<td class="coursePhoto"><img src="/tpl_img/journalistics.png"></td>
							<td class="courseName"><?php echo $journalistic; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table class="orderTable">
			<tr>
				<td class="coursePrice">
					<?php echo $priceAndBegin; ?>
				</td>
				<?php
					/*Temporary block for the course begining*/
					$onClick = "onclick=\"location.href = 'http://" . $_SERVER['HTTP_HOST'] . "/auth_log.php?type=1'\";";
					if(isset($_SESSION['data']) && $_SESSION['data']['level'] == 1) {
						$sql = sprintf("SELECT * FROM os_courses_students WHERE id_user=%s AND payment_end_date = 
																		(SELECT MAX(payment_end_date) FROM os_courses_students WHERE id_user=%s)",
																		$_SESSION['data']['id'],$_SESSION['data']['id']);
						$res = $mysqli->query($sql);
						if($res->num_rows != 0) {
							$row = $res->fetch_assoc();
							if($row['payment_end_date']> Date("Y-m-d")) {
								$onClick = "onclick=\"alert('$alertModuleID')\";'";
							} else {
								$onClick = "onclick=\"location.href = 'http://" . $_SERVER['HTTP_HOST'] . "/cabinet/index.php#tab_4'\"";
							}
						} else {
							$onClick = "onclick=\"location.href = 'http://" . $_SERVER['HTTP_HOST'] . "/cabinet/index.php#tab_4'\"";
						}
					}
				?>
				<td class="courseBeginBtn"><a <?php echo $onClick; ?> ><?php echo $beginLearning; ?></a></td>
				<td class="courseDetailsBtn"><a href="http://online-shkola.com.ua/statics/watch.php?id=38"><?php echo $details; ?></a></td>
			</tr>
		</table>
		</div>
		<div class="index_body_4 little_top scrollflow -pop -opacity">
			<h3>
				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Почему «Альтернатива»?
					<? else: ?>
					Чому «Альтернатива»?
					<? endif; ?>
					</h3>
			<table>
				<tr>
					<td>
					<h4><img src="tpl_img/atestat.png"><br><br>
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Полное общее среднее <br>образование 
					<? else: ?>
					Повна загальна середня<br>освіта
					<? endif; ?>
						<br><br>
					<span><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					с 1 по 11(12) класс с выдачей<br>документов государственного<br>образца, возможность экстерната
					<? else: ?>
					з 1 до 11(12) класу з видачею<br>документiв державного<br>зразка, можливiсть екстернату
					<? endif; ?></span></h4>
 
					</td> 
					<td>
					<h4><img src="tpl_img/coomp.png"><br><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Дистанционное<br>обучение 
					<? else: ?>
					Дистанційне<br>навчання
					<? endif; ?> 
						<br><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<span>возможность заниматься<br>дома и в любом удобном<br>месте, где есть интернет</span>
						<? else: ?>
						<span>можливість займатися<br>вдома і в будь-якому<br>зручному місці, де є<br> інтернет</span>
						<? endif; ?> 
					</h4>
					</td> 
					<td>
					<h4><img src="tpl_img/clock.png"><br><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Свободный график<br>занятий 
					<? else: ?>
					Вільний графік<br>занять
					<? endif; ?>  
						<br><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<span>возможность смотреть уроки<br>в удобное время</span>
						<? else: ?>
						<span>можливiсть дивитися уроки<br>в зручний час</span>
						<? endif; ?>  
					</h4>
					</td> 
					<td>
					<h4><img src="tpl_img/mapper.png"><br><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						Выбор языка<br>обучения
						<? else: ?>
						Вибір мови<br>навчання
						<? endif; ?>   
						<br><br>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<span>обучение проходит на<br>украинском и русском<br>языках</span>
						<? else: ?>
						<span>навчання відбувається<br>українською та російською<br>мовами</span>
						<? endif; ?>
						  
					</h4>
					</td>  
				</tr>
			</table>
		</div>
		<hr id="1" class="blus">
		<div class="index_body_5 scrollflow -pop -opacity">
			<h3><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Как поступить?
					<? else: ?>
					Як вступити?
					<? endif; ?></h3>
			<div class="korpus">
				<input type="radio" name="odin" checked="checked" id="vkl1"/><label for="vkl1">Школа</label><input type="radio" name="odin" id="vkl2"/>
				<label for="vkl2">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Дополнительное образование
					<? else: ?>
					Додаткова освіта
					<? endif; ?></label><input type="radio" name="odin" id="vkl3"/><label for="vkl3">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					Индивидуальные занятия
					<? else: ?>
					Індивідуальні заняття
					<? endif; ?></label>
				<div class="tab11111">
					<?php 
						$sql_ac1 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=5 AND position=1",$_COOKIE['lang']);
						$res_ac1 = $mysqli->query($sql_ac1);
						$row_ac1 = $res_ac1->fetch_assoc();
						$sql_ac2 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=5 AND position=2",$_COOKIE['lang']);
						$res_ac2 = $mysqli->query($sql_ac2);
						$row_ac2 = $res_ac2->fetch_assoc();
						$sql_ac3 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=5 AND position=3",$_COOKIE['lang']);
						$res_ac3 = $mysqli->query($sql_ac3);
						$row_ac3 = $res_ac3->fetch_assoc();
					?>
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					<h4>Как поступить в Онлайн-школу «Альтернатива» <br>
					<span>для получения полного среднего образования с выдачей <br>
					аттестата государственного образца:</span></h4>
					<? else: ?>
					<h4>Як вступити до Онлайн-школи «Альтернатива»  <br>
					<span>для отримання повної середньої освіти з видачею <br>
							атестата державного зразка:</span></h4>
					<? endif; ?>
 
					<?php 
						print($row_ac1['cont']);
					?>
					 
				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					<?php if(!isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
					<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>" target="_blank">Начать обучение</a><?php endif; ?>
				<?php if(isset($_SESSION['data'])): ?><a class="index_body_5_a" href="<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>">Начать обучение</a><?php endif; ?>  
				<a class="index_body_5_a" href="http://online-shkola.com.ua/statics/watch.php?id=19#osn">Подробнее</a> 
					<? else: ?>
					 <?php if(!isset($_SESSION['data'])): ?><a class="index_body_5_a" href="<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>" target="_blank">Почати навчання</a><?php endif; ?>
				<?php if(isset($_SESSION['data'])): ?><a class="index_body_5_a" href="<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>">Почати навчання</a><?php endif; ?>
				<a class="index_body_5_a" href="http://online-shkola.com.ua/statics/watch.php?id=19#osn">Детальніше</a> 
					<? endif; ?>
				</div>
				<div class="tab22222">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					<h4>Как записаться в Онлайн-школу «Альтернатива» <br>
					<span>на получение дополнительного среднего образования без <br>
					перевода из своей школы:</span></h4>
					<? else: ?>
					<h4>Як записатися до Онлайн-школи «Альтернатива»  <br>
					<span>для отримання додаткової середньої освіти без  <br>
					переведення зі своєї школи:</span></h4>
					<? endif; ?>
					<?php 
						print($row_ac2['cont']);
					?>
				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					<?php if(!isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
					<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>" target="_blank">Начать обучение</a><?php endif; ?>
				<?php if(isset($_SESSION['data'])): ?><a class="index_body_5_a" href="<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>">Начать обучение</a><?php endif; ?>  
				<a class="index_body_5_a" href="http://online-shkola.com.ua/statics/watch.php?id=19#dop">Подробнее</a> 
					<? else: ?>
					<?php if(!isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
					<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>" target="_blank">Почати навчання</a><?php endif; ?>
				<?php if(isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
				<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>">Почати навчання</a><?php endif; ?>  
				<a class="index_body_5_a" href="http://online-shkola.com.ua/statics/watch.php?id=19#dop">Детальніше</a>  
					<? endif; ?>
				</div>
				<div class="tab33333">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					<h4>Как записаться на индивидуальные занятия <br>
					<span>с репетитором по любому предмету:</span></h4>
					<? else: ?>
					<h4>Як записатись на індивідуальні заняття <br>
					<span>з репетитором з будь-якого предмета:</span></h4>
					<? endif; ?>
					<?php 
						print($row_ac3['cont']);
					?>
				 
				 <? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					 <?php if(!isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
					 <?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>" target="_blank">Начать обучение</a><?php endif; ?>
				<?php if(isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
				<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>">Начать обучение</a><?php endif; ?>  
				<a class="index_body_5_a" href="http://online-shkola.com.ua/statics/watch.php?id=19#ind">Подробнее</a> 
					<? else: ?>
					<?php if(!isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
					<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>" target="_blank">Почати навчання</a><?php endif; ?>
				<?php if(isset($_SESSION['data'])): ?><a class="index_body_5_a" href="
				<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>">Почати навчання</a><?php endif; ?>  
				<a class="index_body_5_a" href="http://online-shkola.com.ua/statics/watch.php?id=19#ind">Детальніше</a>  
					<? endif; ?>
				</div>
				 
				 
			</div>
			
		</div>
<div id="2" style="    height: 40px;"></div>
		<div class="index_body_6 scrollflow -pop -opacity">
			<h3><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
					О школе
					<? else: ?>
					Про школу
					<? endif; ?>
				</h3>
		</div>
			<hr class="grays">
		<div class="index_body_6 scrollflow -pop -opacity">
			<div class="index_body_6_inner"> 
				<ul id="items">
					<li class="item1">
						<div class="index_body_6_inner_b1">
								
								<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
									<h4>«Альтернатива» для вас, если</h4>
									<? else: ?>
									<h4>«Альтернатива» для вас, якщо</h4>
									<? endif; ?>
								 <table>
								 	<?php 
								 		$sql_elements_9 = sprintf("SELECT * FROM os_elements WHERE categ='9' ORDER BY position DESC");
								 		$res_elements_9 = $mysqli->query($sql_elements_9);
								 		$cnt = 0;
								 		$num = $res_elements_9->num_rows;
								 		while($row_elements_9 = $res_elements_9->fetch_assoc()){
								 			//var_dump($row_elements_9);
								 			$cnt++;
								 			if ($cnt == 1 || $cnt == 3 || $cnt == 5) {
								 				printf("<tr><td><img src='/tpl_img/%s'></td>
								 					<td>%s</td>",$row_elements_9['photo'],$row_elements_9['text_'.$_COOKIE['lang']]);
								 			}
								 			if ($cnt == 2 || $cnt == 4 || $cnt == 6) {
								 				printf("<td><img src='/tpl_img/%s'></td>
								 					<td>%s</td></tr>",$row_elements_9['photo'],$row_elements_9['text_'.$_COOKIE['lang']]);
								 			}
								 		}
								 	?>
								 </table>
							</div>
					</li>
					
					<li class="item2">
						<div class="index_body_6_inner_b2">
								
								<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
									<h4>Наши преимущества</h4>
									<? else: ?>
									<h4>Наші переваги</h4>
									<? endif; ?>
								 <table>
								 	<?php 
								 		$sql_elements_10 = sprintf("SELECT * FROM os_elements WHERE categ='10' ORDER BY position DESC");
								 		$res_elements_10 = $mysqli->query($sql_elements_10);
								 		$cnt = 0;
								 		$num = $res_elements_10->num_rows;
								 		while($row_elements_10 = $res_elements_10->fetch_assoc()){
								 			//var_dump($row_elements_9);
								 			$cnt++;
								 			if ($cnt == 1 || $cnt == 3 || $cnt == 5) {
								 				printf("<tr><td><img src='/tpl_img/%s'></td>
								 					<td>%s</td>",$row_elements_10['photo'],$row_elements_10['text_'.$_COOKIE['lang']]);
								 			}
								 			if ($cnt == 2 || $cnt == 4 || $cnt == 6) {
								 				printf("<td><img src='/tpl_img/%s'></td>
								 					<td>%s</td></tr>",$row_elements_10['photo'],$row_elements_10['text_'.$_COOKIE['lang']]);
								 			}
								 		}
								 	?>
								</table>
							</div>	 
					</li> 
				</ul> 
			</div>
		</div> 
			<hr class="grays"> 
		<div id="3" class="index_body_7 scrollflow -pop -opacity">
			<?php 
				$sql_cost = "SELECT * FROM os_edu_types WHERE id=1";
				$res_cost = $mysqli->query($sql_cost);
				$row_cost = $res_cost->fetch_assoc();
				$sql_cost1 = "SELECT * FROM os_edu_types WHERE id=2";
				$res_cost1 = $mysqli->query($sql_cost1);
				$row_cost1 = $res_cost1->fetch_assoc();
				$sql_cost2 = "SELECT * FROM os_edu_types WHERE id=3";
				$res_cost2 = $mysqli->query($sql_cost2);
				$row_cost2 = $res_cost2->fetch_assoc();
				$sql_cost3 = "SELECT * FROM os_edu_types WHERE id=4";
				$res_cost3 = $mysqli->query($sql_cost3);
				$row_cost3 = $res_cost3->fetch_assoc();
			?>
<?php
	if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"){
		$ourPrices 		  = "Наши цены";
		$fullMiddle 	  = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#osn'>Полное среднее <br><p>образование с выдачей документов государственного образца</p></a>";
		$additionalMiddle = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#dop'>Дополнительное среднее <br><p>образование по всем предметам</p></a>";
		$selectedMiddle   = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#dop'>Дополнительное среднее <br><p>образование по трем выбранным предметам</p></a>";
		$goInOS 		  = "";
		$noSchoolSwap	  = "";
		$price 			  = "Цена";
		$payFor			  = "Оплатить обучение";
		$personalLessons  = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#ind'>Индивидуальные <br><p>занятия с репетитором</p></a>";
		$toZigZag		  = "Перейти на сайт «РГ ЗиГзаг»";
		$from			  = "от";
		$cur_mon          = "грн/мес";
		$cur_hou 		  = "грн/час";
	} else {
		$ourPrices 		  = "Наші ціни";
		$fullMiddle 	  = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#osn'>Повна середня <br><p>освіта з видачею документів державного зразка</p></a>";
		$additionalMiddle = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#dop'>Додаткова середня <br><p>освіта з усіх предметів</p></a>";
		$selectedMiddle   = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#dop'>Додаткова середня <br><p>освіта з трьох обраних предметів</p></a>";
		$goInOS 		  = "";
		$noSchoolSwap	  = "";
		$price 			  = "Ціна";
		$payFor			  = "Оплатити навчання";
		$personalLessons  = "<a class='our-price-cat' href='http://online-shkola.com.ua/statics/watch.php?id=24#ind'>Індивідуальні <br><p>заняття з репетитором</p></a>";
		$toZigZag		  = "Перейти на сайт «РГ ЗіГзаг»";
		$from			  = "від";
		$cur_mon          = "грн/мiс";
		$cur_hou 		  = "грн/год";
	}
?>
<h3><?php echo $ourPrices; ?></h3>
			<div class="index_body_7_left">
				<img src="tpl_img/line_block7.png">
				<table class="price-text"> 
					<tr>
						<td><h4><?php echo $fullMiddle; ?></h4></td>
						<td><h4><?php echo $additionalMiddle; ?></h4></td>
						<td><h4><?php echo $selectedMiddle; ?></h4></td>
					</tr>
				</table>
				<table class="prices"> 
					<tr>
						<td><div class="price-border price1">
							<div class="price-container">
								<p class="price-name"><?php echo $price; ?></p><br>
								<p class="price-value"><? print($row_cost['cost']); ?></p>
								<hr class="price-line">
								<p class="price-currency"><?php echo $cur_mon; ?></p>
							</div>
							</div>
						</td>
						<td><div class="price-border price2">
							<div class="price-container">
								<p class="price-name"><?php echo $price; ?></p><br>
								<p class="price-value"><? print($row_cost1['cost']); ?></p>
								<hr class="price-line">
								<p class="price-currency"><?php echo $cur_mon; ?></p>
							</div>
							</div>
						</td>
						<td><div class="price-border price2">
							<div class="price-container">
								<p class="price-name"><?php echo $price; ?></p><br>
								<p class="price-value"><? print($row_cost2['cost']); ?></p>
								<hr class="price-line">
								<p class="price-currency"><?php echo $cur_mon; ?></p>
							</div>
							</div>
						</td>
					</tr>
				</table>
				
				<a href="<?php if(!isset($_SESSION['data'])){
					 	print("auth_log.php?type=1");
					}
					elseif (isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00" && $_SESSION['data']['level'] == 1) {
						print("cabinet/pay_edu.php");
					}
					elseif(isset($_SESSION['data']) && $_SESSION['data']['date_end'] != "0000-00-00" &&  $_SESSION['data']['level'] == 1) {
						print("cabinet/index.php#tab_3");
					}?>"><?php echo $payFor; ?> </a>
			</div>
			<div class="index_body_7_right">
				<img style="margin-left: -16px;" src="tpl_img/line_block7_r.png">
				<table> 
					<tr>
						<td><h4><?php echo $personalLessons; ?></h4></td> 
					</tr>
				</table>
				<table class="prices-right"> 
					<tr>
						<td><div class="price-border price3">
							<div class="price-container">
								<p class="price-name"><?php echo $price; ?></p><br>
								<p class="price-value">
									<?php echo "<span class='left-position'>" . $from . 
									"</span><span class='left-position'>" . $row_cost3['cost'] . "</span>"; ?>
								</p>
								<hr class="from-price-line">
								<p class="price-currency"><?php echo $cur_hou; ?></p>
							</div>
							</div>
						</td>
					</tr>
				</table>
				<a href="http://rg-zigzag.com.ua/" target="_blank"><?php echo $toZigZag; ?></a>
			</div>
		</div> 
			
		<div class="clear"></div>
		<div id="4" class="index_body_8 scrollflow -pop -opacity">
		
		
		
		
	
		
		
		
		
		
		
		
		
			<h3><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				КАК МЫ УЧИМ
				<? else: ?>
				ЯК МИ ВЧИМО
				<? endif; ?></h3>
			<div class="block8_ellem">
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				<a href="http://online-shkola.com.ua/statics/watch.php?id=25"><img src="tpl_img/block_8_home.png"></a>
				<? else: ?>
				<a href="http://online-shkola.com.ua/statics/watch.php?id=25"><img src="tpl_img/block_8_home_ua.png"></a>
				<? endif; ?>
				
			 
				
					<?php 
					$sql_how = sprintf("SELECT id, text_%s AS cont FROM os_elements WHERE categ='6'",$_COOKIE['lang']);
					//print($sql_how);
					$res_how = $mysqli->query($sql_how);
					//var_dump($res_how);
					$iter = 1;
					while ($row_how = $res_how->fetch_assoc()) {
						printf("<div class='%s block8_ellem_div ellem_div%s poster'>
									%s
					<div class='descrssss'>
						<img src='tpl_img/%s_ss.png'>
					</div> 
			</div>",$iter,$iter,$row_how['cont'],$iter);
						$iter++;
					}
				?>
 
			</div>
		</div> 
<div class="scrollflow -pop -opacity">
		<div class="index_body_9_index post">
		<div class="index_body_9">
			<table>
				<tr>
					<td><h3>Информация</h3>
						<?php 
							$sql_des = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=7 AND position=1",$_COOKIE['lang']);
							$res_des = $mysqli->query($sql_des);
							$row_des = $res_des->fetch_assoc();
							print($row_des['cont']);
						?>
					<!--<ul>
						<li><a href="#">Формы обучения</a></li>
						<li><a href="#">Как учиться в нашей школе</a></li>
						<li><a href="#">Как оплатить обучение с формой оплаты</a></li>
						<li><a href="#">Нормативные документы</a></li>
						<li><a href="#">ВНО и ГИА (ЗНО и ДПА)</a></li>
						<li><a href="#">Что такое дистанционное обучение</a></li>
					</ul>-->
					</td>
					<td>
						
					<!--<h3>О нас</h3>
					<ul>
						<li><a href="#">Онлайн-школа «Альтернатива</a></li>
						<li><a href="#">Наши учителя</a></li>
						<li><a href="#">Администрация</a></li> 
					</ul>
					<h3>Партнеры</h3>
					<ul>
						<li><a href="#">ООО «РГ ЗиГзаг»</a></li>
						<li><a href="#">Образовательный портал «ВнеШколы»</a></li>
					</ul>-->
					<h3>О нас</h3>
					<?php 
							$sql_des = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=7 AND position=2",$_COOKIE['lang']);
							$res_des = $mysqli->query($sql_des);
							$row_des = $res_des->fetch_assoc();
							print($row_des['cont']);
						?>
					<h3>Партнеры</h3>
					<?php 
							$sql_des = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=7 AND position=3",$_COOKIE['lang']);
							$res_des = $mysqli->query($sql_des);
							$row_des = $res_des->fetch_assoc();
							print($row_des['cont']);
						?>
					</td>
					<td><a href="http://online-shkola.com.ua/news/watch_all.php"><h3>Новости</h3></a>
					<ul>
						<?php 
							$sql_news = sprintf("SELECT * FROM os_news ORDER BY id DESC LIMIT 0,5");
							//print("<br>$sql_news<br>");
							$res_news = $mysqli->query($sql_news);
							while($row_news = $res_news->fetch_assoc()){
								printf("<li><a href='http://%s/news/watch.php?id=%s'>%s</a></li>",$_SERVER['HTTP_HOST'],$row_news['id'],$row_news['title_n_'.$_COOKIE['lang']]);
							}
							//print($row_des['cont']);
						?>
						<!--<li><a href="#">Новость №1. Заголовок</a></li>
						<li><a href="#">Новость №1. Заголовок</a></li>
						<li><a href="#">Новость №1. Заголовок</a></li>
						<li><a href="#">Новость №1. Заголовок</a></li>
						<li><a href="#">Новость №1. Заголовок</a></li>-->
					</ul>
					</td>
				</tr>
			</table>
		</div>
		</div> 
		<div class="index_body_10_index post">
		<div class="index_body_10">
						<?php 
							$sql_f1 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=1",$_COOKIE['lang']);
							$res_f1 = $mysqli->query($sql_f1);
							$row_f1 = $res_f1->fetch_assoc();
							$sql_f2 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=2",$_COOKIE['lang']);
							$res_f2 = $mysqli->query($sql_f2);
							$row_f2 = $res_f2->fetch_assoc();
							$sql_f3 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=3",$_COOKIE['lang']);
							$res_f3 = $mysqli->query($sql_f3);
							$row_f3 = $res_f3->fetch_assoc();
						?>
			<table>
				<tr>
					<td>
						<?php 
							print($row_f1['cont']);
						?>
					<!--<ul>
						
						<li>Мы в соцсетях: <a href="#"><img src="tpl_img/utb.png"></a> <a href="#"><img src="tpl_img/vka.png"></a></li>
						<li><a href="#">Частые вопросы</a></li>
						<li><a href="#">ООО «Онлайн-школа» Альтернатива» 2016 г.</a></li>
					</ul>-->
					</td>
					<td>  
						<?php 
							print($row_f2['cont']);
						?>
					<!--<ul>
						<li>Контакты:</li> 
						<li>г. Харьков, ул. Плехановская 18, оф. 816А</li>
						<li>тел: (068) 057-29-02; (073) 023-82-29</li>
					</ul> -->
					</td>
					<td>
						<?php 
							print($row_f3['cont']);
						?>
					<!--<ul>
						<li>email: shkola.alt@gmail.com</li>
						<li>Skype: shkola.alt</li>
					</ul>-->
					</td>
				</tr>
			</table>
			
				
<div class="copyr">
<a href="/"><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>ООО Онлайн-центр "Альтернатива" 2016 г.<? else: ?>ТОВ Онлайн-центр "Альтернатива" 2016 г.<? endif; ?></a>				
				</div>
		</div>
		</div> 
</div> 
			<div id="modal1" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/1_ss.png">
			</div>
			<div id="modal2" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/2_ss.png">
			</div>
			<div id="modal3" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/3_ss.png">
			</div>
			<div id="modal4" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/4_ss.png">
			</div>
			<div id="modal5" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/5_ss.png">
			</div>
			<div id="modal6" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/6_ss.png">
			</div>
			<div id="modal7" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/7_ss.png">
			</div>
			<div id="modal8" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/8_ss.png">
			</div>
			<div id="modal9" class="modal_div23">
				<span class="modal_close23">X</span>
				<img src="tpl_img/9_ss.png">
			</div> 
		<div id="overlay23"></div>
<div id="captcha-form" class="no-display">
	<input type="hidden" value="" name="form-id">
	<center>
		<p>Тест на робота?</p>
		<div class="g-recaptcha" id='t-recaptcha'></div>
		<p style="color:red; cursor: pointer; font-weight: bold;" onclick="captcha_trigger();">Я робот!</p>
	</center>
</div>
<script type="text/javascript">
	var response;
	var callback = function(response) {
		window.response = response;
		if(response != "" && response != null && response != undefined && response != 0) {
			var current_form_id = $("#captcha-form input[name = form-id]").val();
			$("#" + current_form_id + " input[name = g-recaptcha-response]").val(response);
			$("#" + current_form_id).submit();
		}
	}
	var onloadCallback = function() {
		var captcha1 = grecaptcha.render('t-recaptcha', {
			'sitekey'  : '6Ld3SSUTAAAAAE8ae7sW9P9WOLZfCuBBjXEE-ITV',
        	'callback' : callback
        });
		console.log(response);
    };

</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
	function change_slider(){
		var all_c = $(".current").attr("class");
		var arr = all_c.split(' ');
		//alert(arr[0]);
		if(arr[0] == "page1"){
			$(".prev").css("display","block");
			$(".next").css("display","none");
			$(".page1").removeClass("current");
			$(".page2").addClass("current");
			$(".item2").css("display","list-item");
			$(".item1").css("display","none");
		}
		if(arr[0] == "page2"){
			$(".prev").css("display","none");
			$(".next").css("display","block");
			$(".page1").addClass("current");
			$(".page2").removeClass("current");
			$(".item1").css("display","list-item");
			$(".item2").css("display","none");
		}
	}
	$(document).ready(function () {
		var time = setInterval(change_slider,5000);
        $('a.block').click(function (e) {
        	getMessages($("input[name = ac_chat_id]").val());
            $(this).toggleClass('active');
            $('#contents_tp').toggle();
                
            e.stopPropagation();
        });
        $('#contents_tp').click(function (e) {
        	getMessages($("input[name = ac_chat_id]").val());
            e.stopPropagation();
        });
        $('body').click(function () {
            var link = $('a.block');
            if (link.hasClass('active')) {
                link.click();
            }
        });
    });
	</script> 	
 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-80599206-1', 'auto');
  ga('send', 'pageview');
</script>
</body> 
</html> 
