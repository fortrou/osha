<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
//var_dump($_SESSION['data']);
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
		$noChats = "Нет чатов с вашим участием";
		$withTeacher = "Чаты с учителями";
		$withManager = "Чат с классным руководителем";
		$otherChats = "Другие чаты";
		$techHelp = "Техподдержка";
		$write = "Написать сообщение...";
		$send = "Отправить";
		$attach = "Прикрепить";
	} else {
		$noChats = "Немає чатів з вами";
		$withTeacher = "Чати з вчителями";
		$withManager = "Чат з класним керівником";
		$otherChats = "Інші чати";
		$techHelp = "Техпідтримка";
		$write = "Написати повідомлення...";
		$send = "Відправити";
		$attach = "Прикріпити";
	}
?>
<!DOCTYPE html> 
<head>
	<meta name="description" content="Как выглядят чаты с учителями на сайте 'Онлайн-школы 'Альтернатива''">
	<meta name="keywords" content="демо-доступ, чаты, онлайн-школа">
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
		<div class="alt_title_test">
		<div class="block0">
			<?php if(!isset($_SESSION['data'])): ?>
<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
			<h1>Все материалы, которые вы видите, являются демонстрационными. Функции обучения в демонстрационном доступе ограничены.
			 Для получения полного доступа к нашей онлайн-школе зарегистрируйтесь на сайте и оплатите обучение<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Оплатить обучение</a></h1>
			<?php else: ?>
			<h1>Усі матеріали, які ви бачите, є демонстраційними. Функції навчання в демонстраційному доступі
			 обмежені. Для одержання повного доступу до нашої онлайн-школи зареєструйтесь на сайті і оплатіть навчання<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Сплатити за навчання</a></h1>
			<?php endif; ?>
		<?php endif; ?> 
		</div>
		</div>
		<div class="block0">
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>	
			<h1>Сообщения</h1>
			<?php else: ?>
			<h1>Повідомлення</h1>
			<?php endif; ?>

			<div class="chats_uchitel">				

	<div class="left">
		<h3><?php echo $withTeacher; ?></h3>
		<div class="chats_with_my_pupils"><p><?php echo $noChats; ?></p></div>
		
		<h3><?php echo $withManager; ?></h3>
		<div class="chats_with_my_pupils"><p><?php echo $noChats; ?></p></div>
	
		<h3><?php echo $otherChats; ?></h3>
		<div class="chats_with_me_teacher"><p><?php echo $noChats; ?></p></div>
	
		<h3><?php echo $techHelp; ?></h3>
		<div class="our_chats">
		<p><a><?php echo $techHelp; ?></a></p><div class="oc_sobs">0</div><p></p>
					
		</div>
	</div>
				

<div class="right">
			<h3 id="chat_name"></h3>
		<div class="chats_okno" id="com_chat_field"></div>
		<div class="chats_okno_bottom">
			<span id="com_to_chat"></span><span id="com_from_chat">Великий Манве</span>
			<textarea placeholder="<?php echo $write; ?>" name="com_text_message"></textarea>
			<input type="hidden" name="file_attached" value="">
			<div class="chats_btn"><input type="button" name="com_send"  value="<?php echo $send; ?>">
			<span onclick="open_docs_modal(4)"><?php echo $attach; ?></span></div>
			<div id="attached"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
		

		</div>

	</div> 

	

	<?php

		include ("../tpl_blocks/footer.php");

	?>

</body> 

</html> 