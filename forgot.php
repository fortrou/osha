<?php
	$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	session_start();
	require_once("tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru') {
		$incorrectLogin = "Вы ввели несуществующий логин";
		$sentPass 		= "Пароль отправлен вам на почту";
		$incorrectMail  = "Электронный адрес не совпадает";
		$login   	    = "Введите ваш login";
		$email 		    = "Введите ваш email";
		$remind 	    = "Напомнить"; 
		$passRecovering = "Восстановление пароля";
		$inputLogAmail  = "Введите ваш логин и почту для восстановления доступа";
	} else {
		$incorrectLogin = "Ви вказали логін, що не існує";
		$sentPass 		= "Пароль надіслали вам на пошту";
		$incorrectMail  = "Електронна адреса не збігається";
		$login   	    = "Введіть ваш login";
		$email 		    = "Введіть ваш email";
		$remind 	    = "Нагадати"; 
		$passRecovering = "Відновлення пароля";
		$inputLogAmail  = "Введіть ваш логін та пошту для відновлення доступу";
	}
	if(isset($_POST['remind'])){
		$login = trim(strip_tags($_POST['login']));
		$email = trim(strip_tags($_POST['email']));
		$sql = "SELECT * FROM os_users WHERE login='$login'";
		$res = $mysqli->query($sql);
		if($res->num_rows == 0){
			$_SESSION['error'] = $incorrectLogin;
		}
		else{
			$row = $res->fetch_assoc();
			if(trim($row['email']) == trim($email)){
				$password = "";
				for ($i=0; $i < 8; $i++) { 
					$password .= $alphabet[rand(0,61)];
					$password .= $alphabet[rand(0,61)];
				}

				//print("$password<br>");
				$header="From: \"Восстановление пароля\"\r\nReply-to: no-reply@online-shkola.com.ua\r\n";
				$header.="Content-type: text/plain; charset=\"utf-8\"";
				$message = trim("Ваш новый пароль: $password \r\n (рекомендуем сменить сразу после входа на сайт)");
				$to = $email;
				mail($to,'Восстановление пароля',$message,$header);
				$password = md5($password."girls");
				$sql_upd = "UPDATE os_users SET password='$password' WHERE login='$login'";
				$res_upd = $mysqli->query($sql_upd);
				$_SESSION['error'] = $sentPass;
				//print($password);
			}
			else{
				$_SESSION['error'] = $incorrectMail;
			}
		}
	}
?>
<!DOCTYPE html> 
<head>  		
	<title>Восстановление пароля - Онлайн Школа</title>
	<meta name="description" content="Форма восстановления пароля на сайте 'Онлайн-школы 'Альтернатива''">
	<meta name="keywords" content="восстановление пароля, онлайн-школа">
	<!--<script type="text/javascript" src="../tpl_js/tabel.js"></script>-->
	<?php
		include ("tpl_blocks/head.php");
	?>
</head>
<body>
	<?php
		include ("tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class="block0"><div class="blockforgot">
			<h3><?php echo $passRecovering; ?></h3>
			<h4><?php echo $inputLogAmail; ?></h4>
			<?php
				if(isset($_SESSION['error']) && $_SESSION['error'] != ""){
					printf("<h3>%s</h3>",$_SESSION['error']);
					unset($_SESSION['error']);
				}
			?>
			<form method="post" action="forgot.php">
				<input type="text" name="login" placeholder="<?php echo $login; ?>" required>
				<input type="text" name="email" placeholder="<?php echo $email; ?>" required>
				<input type="submit" name="remind" value="<?php echo $remind; ?>">
			</form>
		</div> 
	</div> 	</div> 
	
	<?php
		include ("tpl_blocks/footer.php");
	?>
</body> 
</html> 