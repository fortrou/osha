<?php
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if(isset($_POST)) {
		/*$sql = sprintf("INSERT INTO lancer_debug(`value`) VALUES('%s')", $_POST['data']);
		$res = $mysqli->query($sql);*/
		$sql = sprintf("INSERT INTO lancer_debug(`value`) VALUES('%s')", base64_decode($_POST['data']), $_POST['signature']);
		$res = $mysqli->query($sql);
	} else {
		$sql = sprintf("INSERT INTO lancer_debug(`value`) VALUES('ОШИБКА, НЕТ ПОСТ МАССИВА')");
		$res = $mysqli->query($sql);
	}
	$result = json_decode(base64_decode($_POST['data']));
	if(in_array($result->status, array("success","sandbox"))) {
		
		$order_meta = explode('_', $result->order_id);
		$payment_data = explode('--+--', $result->product_description);
		$sql_edu = sprintf("SELECT * FROM os_edu_types WHERE id = %s", $payment_data[1]);
		$res_edu = $mysqli->query($sql_edu);
		$days_to_prolong = 0;
		if($res_edu->num_rows != 0) {
			$row_edu = $res_edu->fetch_assoc();
			$payment_amount  = (int)$result->amount;
			$days_to_prolong = ($payment_amount / (int) $row_edu['cost']) * 30;
		}
		$sql = sprintf("SELECT * FROM os_users WHERE id = %s",$payment_data[0]);
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			$row = $res->fetch_assoc();
			if(!in_array($row['date_end'], array('0000-00-00','00-00-0000','','0'))) {
				$need_end = Date("Y-m-d",strtotime($row['date_end'])+($days_to_prolong*24*60*60));
			} else {
				$need_end = Date("Y-m-d",time()+($days_to_prolong*24*60*60));
				if($payment_data[1] == 3) {
					$subjects_to_add = explode('::', $payment_data[2]);
					foreach ($subjects_to_add as $value) {
						$sql_add_subject = sprintf("INSERT INTO os_student_subjects (id_student, id_subject) VALUES (%s, %s)",$payment_data[0],$value);
						$res_add_subject = $mysqli->query($sql_add_subject);
					}
				} else if(in_array($payment_data[1], array(1,2))) {
					$sql_add_subject = sprintf("INSERT INTO os_student_subjects (id_student, id_subject) 
													 SELECT %s, id 
													   FROM os_subjects 
													  WHERE id 
													    IN( SELECT id_s 
													    	  FROM os_class_subj 
													    	 WHERE class = ( SELECT class 
													    	 				   FROM os_users 
													    	 				  WHERE id = %s )
													    	   AND course = 0)",$payment_data[0],$payment_data[0]);
					$res_add_subject = $mysqli->query($sql_add_subject);
				}
				// Дебаг инсерта предметов
				/*$mail_text = sprintf("Mail debug: %s", var_export($sql_add_subject, true));
				$result_mail = mail("shkola.alt@gmail.com","Произошла оплата",$mail_text,$headers);*/
			}
			$sql_upd_edu = sprintf("UPDATE os_users SET edu_type = %s, date_end = '%s' WHERE id = %s",$payment_data[1],$need_end,$payment_data[0]);
			$res_upd_edu = $mysqli->query($sql_upd_edu);
			$mail_add = "";
			if($payment_data[3] == 1) {
				$mail_add .= "<h2 color='red'>!!!Данная оплата является продлением!!!</h2><br>";
			}
		}
		$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=utf-8\r\n";
					$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n".
			    	'X-Mailer: PHP/' . phpversion();
		$mail_text = sprintf("<div>
								<h1>Админ, произошла оплата</h1>
								%s
								id платежа в системе liqpay: %s<br>
								Статус: успешно <br>
								Плательщик: %s %s<br>
								Цель оплаты: %s <br>
								Сумма платежа: %s <br>
							</div>", 
							$mail_add, $result->order_id, $result->sender_first_name, $result->sender_last_name, $result->description, $result->amount);
		$result_mail = mail("shkola.alt@gmail.com","Произошла оплата",$mail_text,$headers);
	}
?>