<?php
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if(isset($_POST)) {
		$sql = sprintf("INSERT INTO lancer_debug(`value`) VALUES('%s')", base64_decode($_POST['data']), $_POST['signature']);
		$res = $mysqli->query($sql);
	} else {
		$sql = sprintf("INSERT INTO lancer_debug(`value`) VALUES('ОШИБКА, НЕТ ПОСТ МАССИВА')");
		$res = $mysqli->query($sql);
	}
	$result = json_decode(base64_decode($_POST['data']));
	if(in_array($result->status, array("success","sandbox"))) {
		$headers = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=utf-8\r\n";
						$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n".
				    	'X-Mailer: PHP/' . phpversion();
		$mail_text = sprintf("<div>
								<h1>Админ, произошла оплата</h1>
								Статус: успешно <br>
								Плательщик: %s %s<br>
								Цель оплаты: %s <br>
								Сумма платежа: %s <br>
							</div>", $result->sender_first_name, $result->sender_last_name, $result->description, $result->amount);
		$result_mail = mail("shkola.alt@gmail.com","Произошла оплата",$mail_text,$headers);
		$order_meta = explode('_', $result->order_id);
		$debug_data = '$order_meta: ' . $order_meta[0] . ' --- ' . $order_meta[2] . "\n";
		$sql_debug = sprintf("INSERT INTO lancer_debug(`value`) VALUES('%s')", $debug_data);
		$res_debug = $mysqli->query($sql_debug);
		$sql = sprintf("SELECT * FROM os_courses_meta WHERE id=%s",$order_meta[2]);
		$res = $mysqli->query($sql);
		if ($res->num_rows != 0) {
			$row = $res->fetch_assoc();
			$sql_ed = sprintf("SELECT MAX(payment_end_date) AS till_date FROM os_courses_students WHERE id_user='%s' AND id_course='%s'", 
				$order_meta[0], $order_meta[2]);
			$res_ed = $mysqli->query($sql_ed);
			$need_end = '';
			if($res_ed->num_rows != 0) {
				$row_ed = $res_ed->fetch_assoc();
				if(!in_array($row_ed['till_date'],array('0000-00-00','00-00-0000','','0'))) {
					$need_end = Date("Y-m-d",time($row_ed['till_date'])+(int)$row['payment_period']*24*60*60);
				} else {
					if(time()>strtotime($row['date_from'])) {
						$need_end = Date("Y-m-d",time()+(int)$row['payment_period']*24*60*60);
					} else {
						$need_end = Date("Y-m-d",strtotime($row['date_from'])+(int)$row['payment_period']*24*60*60);
					}
				}
			} else if(time()>strtotime($row['date_from'])) {
				$need_end = Date("Y-m-d",time()+(int)$row['payment_period']*24*60*60);
			} else {
				$need_end = Date("Y-m-d",strtotime($row['date_from'])+(int)$row['payment_period']*24*60*60);
			}
			$debug_data = '$sql_ed: ' . $sql_ed . '\n'
						. '$sql: ' . $sql . '\n'
						. '$need_end: ' . $need_end . '\n';
			$sql = sprintf("INSERT INTO lancer_debug(`value`) VALUES('%s')", $debug_data);
			$res = $mysqli->query($sql);
			$sql_insert = sprintf("INSERT INTO os_courses_students(id_user,id_course,payment_verified,payment_end_date) VALUES(%s,%s,1,'%s')",
				$order_meta[0], $order_meta[2],$need_end);
			$res_insert = $mysqli->query($sql_insert);
		}
	}
?>