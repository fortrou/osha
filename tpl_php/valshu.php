<?php
	require_once("autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if (isset($_POST['type']) 
		&& isset($_POST['subj']) 
		&& isset($_POST['class_id']) 
		&& isset($_POST['user_id']) 
		&& isset($_POST['state']) 
		&& isset($_POST['mark'])) {
		
		if((int)$_POST['state'] == 1){
			$sql = sprintf("SELECT %s FROM os_tabel_cont WHERE id_tabel = '%s' AND subject = (SELECT name_ru FROM os_subjects WHERE id='%s') AND class = '%s'",
			$_POST['type'],$_POST['user_id'],$_POST['subj'], $_POST['class_id']);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			print(json_encode($row[$_POST['type']]));
		}
		if((int)$_POST['state'] == 2){
			$sql = sprintf("UPDATE os_tabel_cont 
							   SET %s = %s, %s_redacted = 1
							 WHERE id_tabel='%s' 
							   AND class='%s' 
							   AND subject = (SELECT name_ru FROM os_subjects 
							   								WHERE id='%s')",
				$_POST['type'], $_POST['mark'], $_POST['type'], $_POST['user_id'], $_POST['class_id'], $_POST['subj']);
			$res = $mysqli->query($sql);
			
			$sql = "SELECT * FROM os_subjects WHERE id='".$_POST['subj']."'";
			//print($sql);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$subj_name_ru = $row['name_ru'];
			$subj_name_ua = $row['name_ua'];
			$sql = htmlspecialchars(sprintf("INSERT INTO os_events(text_ua,text_ru,link,id_user,date_e,type,read_status) 
				VALUES('У вашому табелі з`явилася нова оцінка з предмету <<%s>>',
				'В вашем табеле появилась новая оценка по предмету <<%s>>','%s',%s,now(),5,0)",
			$subj_name_ru,$subj_name_ua,"http://online-shkola.com.ua/jurnal/tabel.php",$_POST['user_id']));
			//print($sql);
			$res = $mysqli->query($sql);
				
				$sql = "SELECT * FROM os_users WHERE id IN(SELECT id_user FROM os_user_mails WHERE id_mail='5' AND id_user='".$_POST['user_id']."')";
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
				$row = $res->fetch_assoc();
				$sql_n = "SELECT * FROM os_mail_types WHERE id='5'";
				//print("<br>$sql_n<br>");
				$res_n = $mysqli->query($sql_n);
				$row_n = $res_n->fetch_assoc(); 

				$mail_text = $row_n['template'];
				/*var_dump($mail_text);
				print("<br>");
				var_dump($row['email']);*/
				$headers= "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <no-reply@online-shkola.com.ua>\r\n";
				$headers .= "Reply-To: $mail" . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
				mail($row['email'],"Новая оценка в табеле",$mail_text,$headers);

			$sql = sprintf("SELECT %s FROM os_tabel_cont WHERE id_tabel = '%s' AND subject = (SELECT name_ru FROM os_subjects WHERE id='%s') AND class = '%s'",
			$_POST['type'],$_POST['user_id'],$_POST['subj'], $_POST['class_id']);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			print(json_encode($row[$_POST['type']]));
		}
		
	}

?>