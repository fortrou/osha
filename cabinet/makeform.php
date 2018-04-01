<?php
ini_set('display_errors','Off');
session_start();
header('Content-Type: text/html; charset=utf-8', true);
require ('../tpl_php/classDatabase.php'); //Все уже придумано за нас ...
require ('../tpl_php/classLiqpay.php');
$db = Database::getInstance();
	$mysqli = $db->getConnection();
$current_date = date("Y-m-d");
$micro = sprintf("%06d",(microtime(true) - floor(microtime(true))) * 1000000); // Ну раз что-то нужно добавить для полной уникализации то ..
$number = date("YmdHis"); //Все вместе будет первой частью номера ордера
$order_id = $number.$micro; //Будем формировать номер ордера таким образом...

if($_GET["e_type"] == 1){
	$merchant_id='i10672147601'; //Вписывайте сюда свой мерчант
	$signature="gedRsHaal5YlgcnXcIcONXE3eIfliWa6pC40l5vZ"; //Сюда вносите public_key
}
else{
	$merchant_id='i97603769660'; //Вписывайте сюда свой мерчант
	$signature="bZAUhVOWNAycyQsKJQi3fgOJI3W0czDlEnLj4DIb"; //Сюда вносите public_key
}
//$desc = $_GET['desc']; //Можно так принять назначение платежа
//$order_id = $_GET['order_id']; //Можно так принять назначение платежа
$price = $_GET['price']; //Все что нужно скрипту - передать в него сумму (вы можете передавать все, вплоть до ордера и описания ...)
 
$liqpay = new Liqpay($merchant_id, $signature);
$html = $liqpay->cnb_form(array(
 'version' => '3',
 'amount' => "$price",
 'currency' => 'UAH',     //Можно менять  'EUR','UAH','USD','RUB','RUR'
 'description' => "Продление оплаты за обучение",  //Или изменить на $desc
 'order_id' => $order_id,
 'customer' => $_SESSION['data']['id']
 ));
if($_GET['type'] == '1'){
	$sql = "UPDATE os_users SET edu_type='".$_GET['e_type']."' WHERE id='".$_SESSION['data']['id']."'";
	$result = $mysqli->query($sql);
	$_SESSION['data']['edu_type'] = $_GET['e_type'];
}
if ($_GET['e_type'] == '1' || $_GET['e_type'] == '2') {
	$sql = sprintf("DELETE FROM os_student_subjects WHERE id_student='%s'",$_SESSION['data']['id']);
	$res = $mysqli->query($sql);
	$sql = sprintf("INSERT INTO os_student_subjects(id_student,id_subject) SELECT %s,id FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE class='%s')",
		$_SESSION['data']['id'],$_SESSION['data']['class']);
	$res = $mysqli->query($sql);
}
if ($_GET['e_type'] == '3') {
	if($_GET["type"] != '2'){
		$subjects = explode(",",$_GET['subjects']);
		//var_dump($subjects);
		$sql = sprintf("DELETE FROM os_student_subjects WHERE id_student='%s'",$_SESSION['data']['id']);
		$res = $mysqli->query($sql);
		foreach ($subjects as $value) {
			$sql = sprintf("INSERT INTO os_student_subjects(id_student,id_subject) VALUES(%s, %s)",
				$_SESSION['data']['id'],$value);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
		}
	}
}


	$sql = sprintf("INSERT INTO os_payment_data(student_id,order_id,order_date,payment) VALUES('%s','%s','$current_date','%s')",
		$_SESSION['data']['id'],$order_id,$price);
	$result = $mysqli->query($sql);
$res = $liqpay->api("payment/data",array(
	'version' => '3',
	'public_key' => $merchant_id,
	'order_id' => '20160427021021344999',
	'info' => 'a'
	));
	// var_dump($res);

?>
<!DOCTYPE html> 
<head>  		
	<title>Личная информация - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/users.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
	<div class="block0">
		<?php
			$sql = "SELECT CONCAT(surname, ' ', name, ' ', patronymic) AS fio FROM os_users WHERE id='".$_SESSION['data']['id']."'";
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$type = "";


			printf("<h1>%s</h1><br>",$row['fio']);
			switch($_GET['e_type']){
				case 1:
					$type = "Общее образование";
					break;
				case 2:
					$type = "Дополнительное образование";
					break;
				case 3:
					$type = "Частичное образование";
					break;
			}
			printf("<p>Вы хотите оплатить доступ за $type</p>
				<p>На сумму: %s</p>",$_GET['price']);
			if($_GET['type'] = 2){
				$sql_tc = sprintf("SELECT * FROM os_edu_types WHERE id=%s",$_GET["e_type"]);
				//print($sql_tc);
				$res_tc = $mysqli->query($sql_tc);
				if($res_tc->num_rows != 0){
					$row_tc = $res_tc->fetch_assoc();
					$month = ceil($_GET['price']/$row_tc['cost']);
					print("<p>На $month месяца(-ев)</p>");
					//print($month);

				}
			}
			if($_GET['e_type'] == 3){
				print("Предметы, которые вы выбрали:<br><ol class='subjects'>");
				$sql_subject = sprintf("SELECT * FROM os_subjects WHERE id IN(SELECT id_subject FROM os_student_subjects WHERE id_student=%s)",$_SESSION['data']['id']);
				$res_subject = $mysqli->query($sql_subject);
				while ($row_subject = $res_subject->fetch_assoc()) {
					printf("<li>%s</li>",$row_subject['name_'.$_COOKIE['lang']]);
				}
				print("</ol>");
			}
			//echo "<br>Оплата временно недоступна!!!<br>";
			echo $html;
		?>
	</div> 
	</div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 