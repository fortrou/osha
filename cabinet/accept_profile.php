<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8', true);
	//require_once("../tpl_php/autoload.php");
	require_once("../tpl_php/classDatabase.php");

	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if (isset($_POST['check'])) {
		if ($_POST['code'] == $_SESSION['data']['activation']) {
			$sql = "UPDATE os_users SET accept_status='accepted' WHERE id='".$_SESSION['data']['id']."'";
			$res = $mysqli->query($sql);
			$_SESSION['data']['accept_status'] = "accepted";
			//print("<br>$sql<br>");
			header("Location: ../schedule/calendar.php");
		}
		else{
			$_SESSION['error'] = 'Некорректный код';
		}
	}
	if (isset($_POST['resend'])) {
		$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
		$headers .= "From: online-shkola.com.ua\r\n"; 
		$headers .= "Bcc: no-reply@online-shkola.com.ua\r\n"; 
		$mail_text = sprintf("<div style=''><span style=''>Здравствуйте, уважаемый(-ая) %s %s %s,<br> по вашей просьбе было отправлено письмо с кодом активации.<br>
			Если это не вы, пожалуйста, игнорируйте данное письмо.</span><br>
			<span style=''>Ваш код(введите его в форму активации) <b>%s</b></span></div>",
			$_SESSION['data']['surname'],$_SESSION['data']['name'],$_SESSION['data']['patronymic'],md5("silence".$_SESSION['data']['login']));
		mail($_SESSION['data']['email'],"Подтверждение регистрации на сайте online-shkola.com.ua",$mail_text,$headers);
		header("Location:".$_SERVER['REQUEST_URI']);
	}

?>
<!DOCTYPE html> 
<head>  		
	<title>Подтверждение аккаунта - Просмотр профиля - Онлайн Школа</title>
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
		<?php 
			if (isset($_SESSION['error'])) {
				print("<span color='red'>".$_SESSION['error']."</span>");
				unset($_SESSION['error']);
			}
			
		?>
		<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
			<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				<p>Введите сюда код активации, пришедший вам на e-mail</p>
				<input type="text" name="code" style="width:400px;">
				<input type="submit" name="check" value="Подтвердить аккаунт">
				<p>Не пришло письмо? Попробуйте отправить его еще раз</p>
				<input type="submit" name="resend" value="Отправить письмо с подтверждением">
			<? else: ?>
				<p>Введіть сюди код активації, що прийшов вам на e-mail</p>
				<input type="text" name="code" style="width:400px;">
				<input type="submit" name="check" value="Підтвердити реєстрацію">
				<p>Не прийшов лист? Спробуйте надіслати його ще раз</p>
				<input type="submit" name="resend" value="Надіслати листа з підтвердженням">
			<? endif; ?>
		</form>
	</div>
</div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 