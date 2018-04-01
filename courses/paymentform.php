<?php
	session_start();
	require_once("../tpl_php/autoload.php");
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
			$sql = sprintf("SELECT * FROM os_courses_meta WHERE id='%s'",$_POST['course_id']);
			$res = $mysqli->query($sql);
			
			$result = "";
			if($res->num_rows!=0) {
				$row = $res->fetch_assoc();
			}
	/*Liqpay keys*/
	$merchant_id = 'i97603769660'; //Вписывайте сюда свой мерчант
	$signature = "bZAUhVOWNAycyQsKJQi3fgOJI3W0czDlEnLj4DIb"; //Сюда вносите public_key
	if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
		$coursePayment = "Оплата курса";
		$payCourseButton = "Оплатить курс";
		$studentName = "ФИО";
		$courseName = "Название курса";
		$sum = "Сумма";
		$days = "Дней";
		$course_name = $row['course_name_ru'];
	} else {
		$coursePayment = "Оплата курса";
		$payCourseButton = "Оплатити курс";
		$studentName = "ФIО";
		$courseName = "Назва курсy";
		$sum = "Сума";
		$days = "Днiв";
		$course_name = $row['course_name_ua'];
	}
	$liqpay = new Liqpay($merchant_id,$signature);
	$params = array(
	  'version' 	=> '3',
	  'amount' 		=> 0.5,
	  'result_url'  => 'http://online-shkola.com.ua/courses/cPaymentRes.php',
	  'currency'    => 'UAH',     //Можно менять  'EUR','UAH','USD','RUB','RUR'
	  'description' => "Оплата за курс Летняя Школа",  //Или изменить на $desc
	  'language' 	=> $_COOKIE['lang'],
	  'order_id' 	=> $order_id,
	  'sender_first_name' => $_SESSION['data']['name'],
	  'sender_last_name' => $_SESSION['data']['surname']
	);
	$params = $liqpay->cnb_params($params);
 	$data = base64_encode( json_encode($params) );
 	$signature = $liqpay->cnb_signature($params);
?>
<!DOCTYPE html>
<html>
<head>
	<?php require_once("../tpl_blocks/head.php"); ?>
</head>
<body>
	<?php require_once('../tpl_blocks/course_header.php'); ?>
	<div class="content">
		<div class="block0">
			<h1 class="course-payment-header"><? echo $coursePayment; ?></h1>
			<div class="course-payment-container">
				<table>
					<tr><td><p><?php echo $studentName; ?>: <?php echo $_SESSION['data']['name'] . " " . $_SESSION['data']['surname'] .
					 " " . $_SESSION['data']['patronymic'] . " "; ?></p></td></tr>
					<tr><td><p><?php echo $courseName; ?>: <?php echo $course_name; ?></p></td></tr>
					<tr><td><p><?php echo $sum; ?>: <?php echo $row['course_price'] . " грн"; ?><span>|</span><?php echo $days; ?>: <?php echo $row['payment_period']; ?></p></td></tr>
					<tr><td><p><a><?php echo $row['course_desc_link']; ?>Информация по курсу</a></p></td></tr>					
					<tr><td><input class="course-payment-submit" type="submit" name="" value="<?php echo $payCourseButton; ?>"></td></tr>
					<tr><td><?php print($html); ?></td></tr>
				</table>
			</div>
		</div>
	</div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body>
</html>