<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$cmp_date = date("Y-m-d");
	/*echo "<pre>";
	print_r($_POST);
	echo "</pre>";*/
	if(isset($_POST['lock_target'])){
		$sql = "UPDATE os_users SET lock_status=1 WHERE id='".$_POST['id_target']."'";
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		//var_dump($res);
		header("Location:".$_SERVER['REQUEST_URI']);
	}

	if(isset($_POST['unlock_target'])){
		$sql = "UPDATE os_users SET lock_status=0 WHERE id='".$_POST['id_target']."'";
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		//var_dump($res);
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	if(isset($_POST['delete_target'])){
		$sql = "DELETE FROM os_users WHERE id='".$_POST['id_target']."'";
		$res = $mysqli->query($sql);
		header("Location:index.php#tab_4");
	}

	if(!isset($_GET['id']) || !isset($_SESSION['data']) || $_SESSION['data']['level']<3 || $_SESSION['data']['id']==$_GET['id']){
		header("Location:index.php#tab_4");
	}
	if(isset($_POST["redact_target"])){
		User::redactPD_1($_POST,$_GET['id'],$_POST['login_target']);
		//var_dump($_POST);

		//header("Location:".$_SERVER['REQUEST_URI']);
	}
	
		$user = User::loadUser($_GET['id']);
	if (isset($_POST['update_pay'])) {
		if($user->uniGet('date_end')!=NULL && $user->uniGet('date_end')!="" && $user->uniGet('date_end')!="0000-00-00"){
			$date_of_end = strtotime($user->uniGet('date_end'))+$_POST['days']*24*3600;
			//print("<br>a<br>");
		}
		else{
			$date_of_end = strtotime($cmp_date)+$_POST['days']*24*3600;
			//print("<br>b<br>");
		}
		//print($date_of_end);
		$sql_sum = sprintf("SELECT * FROM os_edu_types WHERE id='%s'",$_POST['edu_type']);
		$res_sum = $mysqli->query($sql_sum);
		$row_sum = $res_sum->fetch_assoc();
		//print($date_of_end);
		//$date_of_end += $_POST['days']*24*3600;
		$p_sum = $_POST['days']*(ceil((int)$row_sum['cost']/30));
		//print("<br>$p_sum<br>");
		$cur_sum = $user->uniGet("current_money");
		$date_of_end = date("Y-m-d",$date_of_end);
		$sql_upd = sprintf("UPDATE os_users SET date_end='%s', current_money='%s' WHERE id='%s'",$date_of_end,$cur_sum,$user->uniGet('id'));
		//print("<br>$sql_upd<br>");
		//$res_upd = $mysqli->query($sql_upd);
	}
	//var_dump($user);	
	if($row['level'] > $_SESSION['data']['level'])
		header("Location:".$_SERVER['HTTP_REFERER']);
	if(isset($_POST['add_to_course'])) {
		if($_POST['is_student'] == 1) {
			$sql = sprintf("INSERT INTO os_courses_students(id_user,id_course,payment_verified,payment_end_date) VALUES(%s,%s,1,'%s')",
				$_POST['id_user'],$_POST['id_course'],$_POST['start_date']);
			$res = $mysqli->query($sql);
			header("Location:".$_SERVER['HTTP_REFERER']);
		} else {
			$sql = sprintf("INSERT INTO os_courses_teachers(id_teacher,id_course) VALUES(%s,%s)",
				$_POST['id_user'],$_POST['id_course']);
			$res = $mysqli->query($sql);
			header("Location:".$_SERVER['HTTP_REFERER']);
		}
	}
	if(isset($_POST['delete_from_course'])) {
		if($_POST['is_student'] == 1) {
			$sql = sprintf("DELETE FROM os_courses_students WHERE id_user=%s AND id_course=%s",
				$_POST['id_user'],$_POST['id_course']);
			$res = $mysqli->query($sql);
			header("Location:".$_SERVER['HTTP_REFERER']);
		} else {
			$sql = sprintf("DELETE FROM os_courses_teachers WHERE id_teacher=%s AND id_course=%s",
				$_POST['id_user'],$_POST['id_course']);
			$res = $mysqli->query($sql);
			header("Location:".$_SERVER['HTTP_REFERER']);
		}
	}
?>
<!DOCTYPE html> 
<head>  		
	<title><?print($user->uniGet('surname').' '.$user->uniGet('name'))?> - Просмотр профиля - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<script type="text/javascript" src="../tpl_js/users.js"></script>
	<script src="../tpl_js/creation.js"></script>

	<?php
		include ("../tpl_blocks/head.php");
	?>
</head>
<body>
	<input type="hidden" name="lang" value="<?php echo $_COOKIE['lang'] ?>"?>
	<?php
		include ("../tpl_blocks/header.php");
	?>
<div class="content">
		<div class="block0">
	<?php
		if($user->uniGet('lock_status') == 1){
			printf("<h1>Профиль пользователя заблокирован</h1>");
		}
		//print($user->uniGet('level'));
		switch ($user->uniGet('level')) {
			case 1:
				require_once("req_mods/stud_prof.php");
				break;
			case 2:
				require_once("req_mods/teacher_prof.php");
				break;
			case 3:
				require_once("req_mods/man_prof.php");
				break;
			case 4:
				require_once("req_mods/man_prof.php");
				break;
		}
	?>
</div></div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 