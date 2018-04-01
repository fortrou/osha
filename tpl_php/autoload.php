<?php
	ini_set('display_errors','Off');
  	require_once('functions.php');
  	cookie_ip_control(2);
  	if(!function_exists("__autoload")) {
		function __autoload($name)
		{
			require 'class' . $name . '.php';
		}
	}
	require_once "recaptchalib.php";
	// ваш секретный ключ
	$secret = "6Ld3SSUTAAAAAH7lgoBxcaTWQofOFFIFXoJy-pWB";
	// пустой ответ
	$response = null;
	// проверка секретного ключа
	$reCaptcha = new ReCaptcha($secret);
	
	/*if(!isset($_SESSION['data']) && isset($_COOKIE['udata'])){
  		$udata = explode($_COOKIE['udata'], "212-212");
  		$user = User::auth( $udata[0] , $udata[1] );
		//print_r($_SESSION);
		if($user == false){
			header('Location:../index.php');
		}
		else{
			header('Location:../schedule/calendar.php');
		}
		//$user ? print('Success auth!') : $_SESSION['message'] = "Ошибка авторизации";
  	}*/
  	//header("Location:../index.php");

  	if(isset($_SESSION['data']) && !isset($_SESSION['data']['currentCourse'])) {
  		$_SESSION['data']['currentCourse'] = 0;
  	}
  	if (!isset($_COOKIE['lang'])) {
  		$_SESSION['lang'] = 'ru';
		setcookie("lang","ru",time()+1000*60*60*24*7);
		//print("<br>a<br>");
	}
	if(isset($_POST['ua'])){
		setcookie("lang");
		setcookie("lang","ua",time()+1000*60*60*24*7);
		$_SESSION['lang'] = 'ua';
		header("Location:".$_SERVER['REQUEST_URI']);
	}
  	if(isset($_POST['ru'])){
		setcookie("lang");
		setcookie("lang","ru",time()+1000*60*60*24*7);
		$_SESSION['lang'] = 'ru';
		header("Location:".$_SERVER['REQUEST_URI']);
	}
header('Content-Type: text/html; charset=utf-8', true);
$cmp_date = date("Y-m-d");
$cmp_time = date("H:i:s");
$pre_time = date("H:i:s",time()-20*60);
//print("$cmp_time<br>$pre_time");
  	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$merchant_id='i66668551581'; //Вписывайте сюда свой мерчант
	$signature="ZO3al467EcSehZ5Qil3E9nOzgGWVPzZWLn3lBvTx"; //Сюда вносите public_key
	$liqpay = new Liqpay($merchant_id, $signature);
	if (isset($_SESSION['data'])) {
		$sql = sprintf("SELECT * FROM os_lessons WHERE DATE(date_%s)='$cmp_date' AND TIME(date_%s) BETWEEN '$cmp_time' AND '$pre_time' 
			AND id IN(SELECT DISTINCT id_l FROM os_journal WHERE is_anonsed=0 AND id_s='%s')",
			$_COOKIE['lang'],$_COOKIE['lang'],$_SESSION['data']['id']);
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		if($res->num_rows != 0){
			while ($row = $res->fetch_assoc()) {
				$sql_ue = sprintf("INSERT INTO os_events(text_ua,text_ru,link,id_user,date_e,type,read_status) VALUES('Урок %s недавно начался', 
					'Урок %s нещодавно розпочався', 'http://online-shkola.com.ua/lessons/watch.php?id=%s'),%s,now(),6,0",
				$row['title_ru'],$row['title_ua'],$row['id'],$_SESSION['data']['id']);
				$res_ue = $mysqli->query($sql_ue);
				$sql_uj = sprintf("UPDATE os_journal SET is_anonsed=1 WHERE id_s='%s' AND id_l='%s'",$_SESSION['data']['id'],$row['id']);
				$res_uj = $mysqli->query($sql_uj);
			}
		}
	}
if(isset($_SESSION['data'])){
	$sql = "SELECT * FROM os_payment_data WHERE student_id ='".$_SESSION['data']['id']."' AND pay_status = 0";
	$res = $mysqli->query($sql);
	$cur_sum = 0;
	//var_dump($_SESSION['date_end']);
	//print($sql);
	if($res->num_rows != 0){
		while($row = $res->fetch_assoc()){
			$liqpay_res = $liqpay->api("payment/data",array(
				'version' => '3',
				'public_key' => $merchant_id,
				'order_id' => $row['order_id'],
				'info' => 'a'
			));

			if($liqpay_res->status != "error"){
				$cur_sum += $row['payment'];
			}
			$sql_s = sprintf("UPDATE os_payment_data SET pay_status = 1 WHERE id=%s",$row['id']);
			$res_s = $mysqli->query($sql_s);
			/*print("<br>");
				print($liqpay_res->status);
			print("<br>");*/
		}
		$sql_sum = sprintf("SELECT * FROM os_edu_types WHERE id=%s",$_SESSION['data']['edu_type']);
		$res_sum = $mysqli->query($sql_sum);
		$row_sum = $res_sum->fetch_assoc();
		$day = (int)$row_sum['cost'];
		$date_of_end = "";
		//print("<br>$cur_sum<br>");
		$day = $cur_sum/$day*30;
		//print($day);
		if($_SESSION['data']['date_end']!=NULL && $_SESSION['data']['date_end']!="" && $_SESSION['data']['date_end']!="0000-00-00" ){
			$date_of_end = strtotime($_SESSION['data']['date_end'])+$day*24*3600;
		}
		else{
			$date_of_end = strtotime($cmp_date)+$day*24*3600;
		}
		//print("<br>$date_of_end<br>");
		$date_of_end = date("Y-m-d",$date_of_end);
		$sql_upd = sprintf("UPDATE os_users SET date_end='%s', current_money='%s' WHERE id='%s'",$date_of_end,$cur_sum,$_SESSION['data']['id']);
		$res_upd = $mysqli->query($sql_upd);
		$_SESSION['data']['date_end'] = $date_of_end;
		//print("<br>$date_of_end<br>");
	}
	if($_SESSION['data']['accept_status'] == "" && $_SESSION['data']['level'] == 1 && $_SESSION['data']['accept_status'] != "accepted"){
		//print("a");
		header("Location:../cabinet/accept_profile.php");
	}
	if(isset($_SESSION['data'])){
		$sql = "SELECT lock_status FROM os_users WHERE id='".$_SESSION['data']['id']."'";
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		if($row['lock_status'] == 1)
			header("Location:../lock_page.php");
	}
	if(!preg_match("~news~", $_SERVER['REQUEST_URI']) && !preg_match("~statics~", $_SERVER['REQUEST_URI'])) {
		if(isset($_SESSION['data']) && $_SESSION['data']['date_end'] == "0000-00-00"   
			&& ($_SESSION['data']['level'] == 1 || $_SESSION['data']['level'] == 0))
			header("Location:../cabinet/pay_edu.php");
		if(isset($_SESSION['data']) && !in_array($_SESSION['data']['date_end'],array('0000-00-00','','00-00-0000'))
			&& (strtotime($_SESSION['data']['date_end']) < strtotime(Date("Y-m-d"))) 
			&& ($_SESSION['data']['level'] == 1 || $_SESSION['data']['level'] == 0))
			header("Location:../cabinet/index.php#tab_3");
	}
	
}
	if(isset($_POST['mail'])) {
		if($_POST["g-recaptcha-response"]) {
			$response = $reCaptcha->verifyResponse(
		        $_SERVER["REMOTE_ADDR"],
		        $_POST["g-recaptcha-response"]
		    );
		}
		if($response != null && $response->success) {
	  	//var_dump($_COOKIE["lang"]);
	  		$name = htmlspecialchars(strip_tags(trim($_POST['name'])));
	  		$email = "shkola.alt@gmail.com";
	  		//$email = "fortrou@gmail.com";
	  		$subj = "Техподдержка";
	  		$mail = htmlspecialchars(strip_tags(trim($_POST['email'])));
			$message = htmlspecialchars(strip_tags(trim($_POST['text_message'])));
			$ip = get_ip();
			$client = $_SERVER['HTTP_USER_AGENT'];
			$message = " E-mail: ".$_POST['email']." \n Имя: $name \n $message" . " \n Ip-addres $ip \n Client $client";
	  		$headers = "From: $mail" . "\r\n" .
	  			//"To: fortrou@gmail.com \r\n" .
			    "Reply-To: $mail" . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			if(preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $mail)) {
				mail($email,$subj,$message,$headers);
			}
			header("Location:".$_SERVER['REQUEST_URI']);
		} 
		else {
			$_SESSION['capcha-error'] = "Вы робот!";
		}
	}
  	
  	function get_subjectsOnUser($id_course = 0,$id_user = 0) {
		if($id_course == 0 || $id_user == 0) return false;
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = sprintf("SELECT * FROM os_users WHERE id=$id_user");
		$res = $mysqli->query($sql);
		$user_subjects_array = array();
		$user_subjects_result = "";
		if($res->num_rows != 0) {
			$row = $res->fetch_assoc();
			if($row['level'] == 2) {
				$sql_subjects = "SELECT * FROM os_subjects WHERE id IN(SELECT DISTINCT id_s FROM os_teacher_subj WHERE id_teacher=$id_user AND course=$id_course )";
				$res_subjects = $mysqli->query($sql_subjects);
				if($res_subjects->num_rows != 0) {
					while($row_subjects = $res_subjects->fetch_assoc()) {
						$user_subjects_array[] = $row_subjects['id'];
					}
				}
				$sql_subjects_all = "SELECT * FROM os_subjects WHERE id IN(SELECT DISTINCT id_s FROM os_class_subj WHERE course=$id_course 
																			  AND class IN(SELECT id_c FROM os_teacher_class WHERE id_teacher=$id_user))";
				$res_subjects_all = $mysqli->query($sql_subjects_all);
				if($res_subjects_all->num_rows != 0) {
					while($row_subjects_all = $res_subjects_all->fetch_assoc()) {
						if(in_array($row_subjects_all['id'], $user_subjects_array)) $selected = ' selected';
						else $selected = '';
						$user_subjects_result .= sprintf("<option value='%s'$selected>%s</option>",$row_subjects_all['id'],$row_subjects_all['name_' . $_COOKIE['lang']]);
					}
				} else {
					$user_subjects_result .= "<option value='0' disabled selected>нет предметов в базе</option>";
				}

			}
			return $user_subjects_result;
		}
		return false;
	}
	function save_subjectOnTeacher($id_course = 0, $id_user = 0, $subjects = array()) {
		if($id_course == 0 || $id_user == 0 || !count($subjects)) return false;
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "DELETE FROM os_teacher_subj WHERE course=$id_course AND id_teacher=$id_user";
		$res = $mysqli->query($sql);
		$values_list = "";
		foreach ($subjects as $value) {
			$values_list .= "($id_user, $value, $id_course), ";
		}
		$values_list = rtrim($values_list, ", ");
		if($values_list != "") {
			$sql = "INSERT INTO os_teacher_subj(id_teacher, id_s, course) VALUES $values_list";
			$res = $mysqli->query($sql);
			return true;
		}
		return false;
	}
	if(!function_exists("get_currentYearNum")) {
		function get_currentYearNum($current = 1, $month = 8, $year = 2017) {
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$currentDate_params = array( 'day' 	 => (int)Date("d"),
										 'month' => (int)Date("m"),
										 'year'  => (int)Date("Y")
									   );
			if($current == 1) {
				$year_type = $currentDate_params['month'] < 8 ? 'year_end' : 'year_start';
				$sql = sprintf("SELECT * FROM os_year_date WHERE $year_type = %s",
								$currentDate_params['year'], $currentDate_params['year']);
			} else {
				$year_type = $month < 8 ? 'year_end' : 'year_start';
				$sql = sprintf("SELECT * FROM os_year_date WHERE $year_type = %s",
								$year, $year);
			}
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$row = $res->fetch_assoc();
				return $row['year_number'];
			}
			return false;
		}
	}
 ?>