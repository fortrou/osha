<?php 
session_start();
	require 'tpl_php/autoload.php';
	if(isset($_COOKIE['lang'])) {
		$currentLang = $_COOKIE['lang'];
	} else {
		$currentLang = 'ru';
	}
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if (isset($_POST['send_form'])) {
		if($_POST["g-recaptcha-response"]) {
			$response = $reCaptcha->verifyResponse(
		        $_SERVER["REMOTE_ADDR"],
		        $_POST["g-recaptcha-response"]
		    );
		}
		print("00");
		if($response != null && $response->success) {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$header="From: \"$name\"\r\nReply-to: $email\r\n";
			$header.="Content-type: text/plain; charset=\"utf-8\"";
			$message = trim("От пользователя: ".$_POST['name']."\n".$_POST['text'] . "\n" . "email: $email");
			$to = "shkola.alt@gmail.com";
			if(preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email)) {
				mail($to,'Обратная связь',$message,$header);
			}
			header("Location:contacts.php");
		} else {
			$_SESSION['captcha_error'] = 'ВЫ РОБОТ!';
		}
	}
	if (isset($_POST['call_me'])) {
		if($_POST["g-recaptcha-response"]) {
			$response = $reCaptcha->verifyResponse(
		        $_SERVER["REMOTE_ADDR"],
		        $_POST["g-recaptcha-response"]
		    );
		}
		if($response != null && $response->success) {
			$phone = $_POST['tel1'];
			$header="From: \"Перезвоните мне\"\r\nReply-to: no-reply@online-shkola.com.ua\r\n";
			$header.="Content-type: text/plain; charset=\"utf-8\"";
			$message = trim("Телефон пользователя: $phone");
			$to = "shkola.alt@gmail.com";
			mail($to,'Заказ звонка',$message,$header);
			header("Location:contacts.php");
		} else {
			$_SESSION['captcha_error'] = 'ВЫ РОБОТ!';
		}
	}
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
		$lang_array = array( "callback_form" => "Форма обратной связи",
							 "name" => "Ваше имя",
							 "mail" => "Ваша почта",
							 "message" => "Ваше сообщение",
							 "send" => "Отправить",
							 "call_me_form" => "«Заказать обратный звонок»",
							 "number" => "Укажите свой номер",
							 "call_me" => "Перезвоните мне" );
	} else {
		$lang_array = array( "callback_form" => "Форма зворотнього зв'язку",
							 "name" => "Ваше ім'я",
							 "mail" => "Ваша пошта",
							 "message" => "Ваше повідомлення",
							 "send" => "Відправити",
							 "call_me_form" => "«Замовити зворотній дзвінок»",
							 "number" => "Вкажіть свій номер",
							 "call_me" => "Передзвоніть мені" );
	}
 ?>
<!DOCTYPE html> 
<head>  		
	<title>Контакты - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("tpl_blocks/head.php");
	?>
</head>
<body id="top">
	<?php
		include ("tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class="block0">
		<div class="block0_cont">
			<div class="cont_block">
				<?php 
					$sql_des = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=15 AND position=1",$currentLang);
					$res_des = $mysqli->query($sql_des);
					$row_des = $res_des->fetch_assoc();
					print($row_des['cont']);
				?>
			</div>
		<?php
			if(isset($_SESSION['capcha-error']) && !empty($_SESSION['capcha-error'])) {
				printf("<p style='color:red'>%s</p>", $_SESSION['capcha-error']);
				unset($_SESSION['capcha-error']);
			}
		?>
		<table>
			<tr>
				<td>
					<h3><?php echo $lang_array['callback_form']; ?></h3>
					<form action="#" method="post" data-trigger="show-captcha" id="form-1"> 
						<input type="text" name="name" placeholder="<?php echo $lang_array['name']; ?>"><br>
						<input type="text" name="email" placeholder="<?php echo $lang_array['mail']; ?>"><br>
						<textarea rows="10" cols="45" name="text" placeholder="<?php echo $lang_array['message']; ?>"></textarea><br>
						<input type="hidden" name="g-recaptcha-response" value="">
						<input type="hidden" value="1" name="send_form">
						<input type="button" data-trigger="captured" value="<?php echo $lang_array['send']; ?>" onclick="captcha_trigger('form-1')"> 
					</form>
					
				</td>
				<td>
					<h3><?php echo $lang_array['call_me_form']; ?></h3>
					<form action="#" method="post" data-trigger="show-captcha" id="form-2"> 
						<input type="text" name="tel1" placeholder="<?php echo $lang_array['number']; ?>"><br>
						<input type="hidden" name="g-recaptcha-response" value="">
						<input type="hidden" value="1" name="call_me">
						<input type="button" data-trigger="captured" value="<?php echo $lang_array['call_me']; ?>" onclick="captcha_trigger('form-2')"> 
					</form>
				</td>
			</tr>
		</table> 
			
</div>
</div> 
	</div> 
	
	<?php
		include ("tpl_blocks/footer.php");
	?>
</body> 
</html> 