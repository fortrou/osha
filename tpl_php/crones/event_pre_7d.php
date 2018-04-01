<?php
	require_once("../autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$cur_date = date("Y-m-d",strtotime(date("Y-m-d"))+3600*24*7);
	$sql = "SELECT * FROM os_users WHERE date_end='$cur_date' AND date_end NOT IN('0000-00-00') AND evented=0";
	print($sql);
	$res = $mysqli->query($sql);
	$str_t1 = strtotime($cur_date);
	while ($row = $res->fetch_assoc()) {
		$str_t2 = strtotime($row['date_end']);
		$str_days = ceil(($str_t1 - $str_t2)/60/60/24);
		//if($str_days <= 7){
			print("<br>true<br>");
			$sql_upd = sprintf("UPDATE os_users SET evented=1 WHERE id='%s'",$row['id']);
			$res_upd = $mysqli->query($sql_upd);
			$sql_event = sprintf("INSERT INTO os_events(text_ua,text_ru,link,id_user,date_e,type,read_status) 
				VALUES('Срок сплати за навчання закінчується через тиждень','Срок оплаты заканчивается через неделю','http://online-shkola.com.ua/cabinet/index.php#tab_3',%s,now(),6,0)",
				$row['id']);
			$res_event = $mysqli->query($sql_event);
			$sql_upd = sprintf("UPDATE os_users SET evented=0 WHERE id='%s'",$row['id']);
			$res_upd = $mysqli->query($sql_upd);
		//}
		//print("<br>$str_days<br>");
	}
?>