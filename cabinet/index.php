<?php
ini_set('display_errors','Off');
session_start();
if(!isset($_SESSION['data'])){
header("Location:../index.php");
}
require_once '../tpl_php/classUser.php';
require_once '../tpl_php/classFile.php';
require_once '../tpl_php/classDatabase.php';
require_once '../tpl_php/classBilling.php';
require_once '../tpl_php/classLiqpay.php';
require_once '../tpl_php/functions.php';

$cmp_date = date("Y-m-d");
/*if(!isset($_GET['tab'])){
    header("Location:index.php#tab1");
}*/
//var_dump($_SESSION['data']);
//var_dump($_POST);
$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if (!isset($_COOKIE['lang'])) {
		$_SESSION['lang'] = 'ru';
		setcookie("lang","ru",time()+1000*60*60*24*7);
	}
	if(isset($_POST['ua'])){
		setcookie("lang","ua",time()+1000*60*60*24*7);
		$_SESSION['lang'] = 'ua';
		header("Location:".$_SERVER['REQUEST_URI']);
	}
  	if(isset($_POST['ru'])){
		setcookie("lang","ru",time()+1000*60*60*24*7);
		$_SESSION['lang'] = 'ru';
		header("Location:".$_SERVER['REQUEST_URI']);
	}
header('Content-Type: text/html; charset=utf-8', true);
$cmp_date = date("Y-m-d");
$cmp_time = date("H:i:s");
$pre_time = date("H:i:s",time()-20*60);
if(isset($_SESSION['data'])){
	$sql = "SELECT * FROM os_payment_data WHERE student_id ='".$_SESSION['data']['id']."' AND pay_status = 0";
	//print($sql);
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
		$day = (int)$row_sum['cost']/30;
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
	/*if($_SESSION['data']['date_end'] == "0000-00-00" && isset($_SESSION['data'])
		&& ($_SESSION['data']['level'] == 1 || $_SESSION['data']['level'] == 0))
		header("Location:../cabinet/pay_edu.php");*/

}


	//var_dump($_POST);
if(isset($_POST['send_kvit'])){
	//print("allkhu akbar!");
	if(File::isValidImg($_FILES['kvitancia1'])){
		//print("alala");
		$kvit_name = File::LoadUpdImg($_FILES['kvitancia1'],$_SESSION['data']['login']);
		if($kvit_name != false){
			$sql = sprintf("INSERT INTO os_bills(image_bill,id_student, `date`) VALUES('%s','%s','%s')",
				$kvit_name,$_SESSION['data']['id'],$cmp_date);
			//print($sql);
			$res = $mysqli->query($sql);
			header("Location:".$_SERVER['REQUEST_URI']);
		}
		else{
			print("Квитанция не отправлена");
		}
	}
}
if (isset($_POST["update_mails"])) {
	$sql = "SELECT * FROM os_mail_types";
	$res = $mysqli->query($sql);
	$sql_if = "SELECT * FROM os_user_mails WHERE id_user='".$_SESSION['data']['id']."'";
	$res_if = $mysqli->query($sql_if);
	$mail_arr = array();
	$mail_arr["all"] = array();
	$mail_arr["yep"] = array();
	while ($row_if = $res_if->fetch_assoc()) {
		$mail_arr["all"][] = $row_if['id_mail'];
		$mail_arr["yep"][] = $row_if['yep'];
	}
	//var_dump($_POST["checkbox"]);
	while($row = $res->fetch_assoc()){
		if ($_POST["checkbox"][$row['id']] == "on") {
			$sql_new = sprintf("UPDATE os_user_mails SET yep=1 WHERE id_user='%s' AND id_mail='%s'",$_SESSION['data']['id'],$row['id']);
		}
		else{
			$sql_new = sprintf("UPDATE os_user_mails SET yep=0 WHERE id_user='%s' AND id_mail='%s'",$_SESSION['data']['id'],$row['id']);
		}
		//print("<br>$sql_new<br>");
		$res_new = $mysqli->query($sql_new);
	}
	header("Location:index.php#tab_2");
}
if (isset($_POST['save_resp'])) {
	$sql = "SELECT * FROM os_mail_types";
	$res = $mysqli->query($sql);
	while($row = $res->fetch_assoc()){
		$sql_upd_resp = sprintf("UPDATE os_mail_types SET template='%s' WHERE id='%s'",$_POST[$row['id']."_resp"],$row['id']);
		$res_upd_resp = $mysqli->query($sql_upd_resp);
	}
	header("Location:index.php#tab_2");
}
if(isset($_POST['send'])){
	User::update_avatar($_FILES['avatar'],$_SESSION['data']['login']);
	User::redactPD($_POST, $_SESSION['data']['id'], $_SESSION['data']['login']);
	//if($_SESSION['data']['level'] == 1)
		header("Location:index.php#tab_1");
	/*else
		header("Location:index.php#tab3");*/
}
if(isset($_POST['send_pem'])){
	User::redactPD($_POST, $_SESSION['data']['id'], $_SESSION['data']['login']);
	//if($_SESSION['data']['level'] == 1)
		header("Location:index.php#tab_2");
	/*else
		header("Location:index.php#tab3");*/
}
if (isset($_POST['change_cost'])) {
	$sql_upd_cost = "UPDATE os_edu_types SET cost='".$_POST['cost1']."' WHERE id=1";
	$res_upd_cost = $mysqli->query($sql_upd_cost);
	$sql_upd_cost = "UPDATE os_edu_types SET cost='".$_POST['cost2']."' WHERE id=2";
	$res_upd_cost = $mysqli->query($sql_upd_cost);
	$sql_upd_cost = "UPDATE os_edu_types SET cost='".$_POST['cost3']."' WHERE id=3";
	$res_upd_cost = $mysqli->query($sql_upd_cost);
	$sql_upd_cost = "UPDATE os_edu_types SET cost='".$_POST['cost4']."' WHERE id=4";
	$res_upd_cost = $mysqli->query($sql_upd_cost);
	header("Location:".$_SERVER['REQUEST_URI']);
}
	$sql = "SELECT * FROM os_mail_types";
	$res = $mysqli->query($sql);
	while($row = $res->fetch_assoc()){
		$tdel = sprintf("%s_del",$row['id']);
		$toff = sprintf("%s_off",$row['id']);
		$ton  = sprintf("%s_on",$row['id']);
		if (isset($_POST[$toff])) {
			$sql_t = "UPDATE os_mail_types SET status=1 WHERE id='".$row['id']."'";
			$res_t = $mysqli->query($sql_t);
		}
		if (isset($_POST[$ton])) {
			$sql_t = "UPDATE os_mail_types SET status=0 WHERE id='".$row['id']."'";
			$res_t = $mysqli->query($sql_t);
		}
		if (isset($_POST[$tdel])) {
			$sql_t = "DELETE FROM os_mail_types WHERE id='".$row['id']."'";
			$res_t = $mysqli->query($sql_t);
		}
	}
	if (isset($_POST['accept_password'])) {
		$cur_user_id = $_SESSION['data']['id'];
		$ins_cur_password = md5($_POST['cur_password'].'girls');
		$passw_n1 = trim(strip_tags($_POST['new_password']));
		$passw_n2 = trim(strip_tags($_POST['new_password_accept']));

		if($ins_cur_password == $_SESSION['data']['password']){

			if($passw_n1 == $passw_n2){

				$passw_n1 = md5($passw_n1.'girls');
				//print("<br>$passw_n1<br>");
				$sql_password = sprintf("UPDATE os_users SET password='%s' WHERE id='%s'",$passw_n1,$_SESSION['data']['id']);
				$res_password = $mysqli->query($sql_password);
				$_SESSION['data']['password'] = $passw_n1;
				header("Location:index.php?#tab_1");

			}
			else{
				$_SESSION['error'] = "Пароли не совпадают";
			}
		}
		else{
			$_SESSION['error'] = "Введенный текущий пароль некорректен";
		}

	}
	//print($_SESSION['data']['password']);
	$sql_sum = sprintf("SELECT * FROM os_edu_types WHERE id=%s",$_SESSION['data']['edu_type']);
	$res_sum = $mysqli->query($sql_sum);
	if($res_sum->num_rows != 0)
		$row_sum = $res_sum->fetch_assoc();
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
	<!--<script type="text/javascript" src="req_mods/pays.js"></script>-->
	<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
	<style>
		#fake, #real {
		width: 200px;
		}
		#real {
			height:200px;
			position: absolute;
			display: none;
		}
	</style>
</head>
<body>
	<input type="hidden" name='date_end' value="<?php print($_SESSION['data']['date_end']); ?>">

	<div id="course_apply_payment" >
		<div class="close" onclick="close_course_modal()">X</div>
		<input type="hidden" value="" name="prolong_id_user">
		<input type="hidden" value="" name="prolong_id_course">
		<p>Продлить на указанное количество периодов оплаты</p>
		<select name="course_times">
			<option value="1">На 1 период оплаты</option>
			<option value="2">На 2 периода оплаты</option>
			<option value="3">На 3 периода оплаты</option>
			<option value="4">На 4 периода оплаты</option>
			<option value="5">На 5 периодов оплаты</option>
			<option disabled>В ОБРАТНУЮ СТОРОНУ</option>
			<option value="-1">На -1 период оплаты</option>
			<option value="-2">На -2 периода оплаты</option>
		</select>
		<p id="course_meta_info"></p>
		<p id="course_well_update">Изменения успешно сохранены</p>
		<p id="course_failed_update">Возникли непредвиденные ошибки</p>
		<input type="button" name="prolong_continue_course" value="Продлить">
	</div>
	<input type="hidden" name="lang" value="<?php echo $_COOKIE['lang'] ?>"?>
	<?php
		include ("../tpl_blocks/header.php");
	?>

	<div class="content">
		<?php
			if($_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=8";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=8",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(8, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<input type="hidden" name="userlevel" value="<?=$_SESSION['data']['level']?>">
		<div id="edu_type_assign" >
			<div class="close" onclick="close_type_modal()">X</div>
			<select name='red_pay_edu_type'></select>
					<script type="text/javascript">
						$(function() {
						    $("#realed").css({
						        "top": $("#fakeed").offset().top + $("#fakeed").outerHeight(),
						        "left": $("#fakeed").offset.left
						    });
						    $("#fakeed").click(function() {
						        $("#realed").show().focus();
						    });
						    $("#realed").mouseleave(function() {
						        $("#realed").hide();
						    });
						    $("#realed").change(function() {
						    	if($("#realed").val().length > 3){
						    		$("input[name = update_pay]").attr("disabled","disabled");
						    		$("#failed_update").empty();
						    		$("#failed_update").append("Нельзя выбрать больше 3х предметов");
						    	}
						    	if($("#realed").val().length <= 3){
						    		$("#failed_update").empty();
						    		$("input[name = update_pay]").attr("disabled",false);
						    	}
						    	if ($("#fakeed option").length != 0) {
						            $("#fakeed option").html(""+$("#realed").val());
						        } else {
						            $("#fakeed").append("<option>" + $("#realed").val() + "</option>");
						        }
						    });
						    $("input[name = days]").on("input",function(){
						    	//alert('a')
						    	/*if($(this).val()<0){
						    		//alert("a");
						    		$("input[name = update_pay]").attr("disabled","disabled");
						    		$("#fail_amount").empty();
						    		$("#fail_amount").append("Нельзя отрицательное количество дней");
						    	}
						    	else{
						    		//alert("b");*/
						    		$("input[name = update_pay]").attr("disabled",false);
						    		$("#fail_amount").empty();
						    	//}
							});
						});
					</script>
					<br>
					<input type="hidden" value="" name="red_pay_class">
					<input type="hidden" value="" name="red_pay_id">
					<div id="red_pay_subjects" style="display:none; height:100px;overflow-y:auto;"></div>
					<p id="failed_update"></p>
						<select id="fakeed" style="position:relative;"></select>
						<select id="realed" size="5" multiple="multiple" name='red_pay_subjects[]' id="red_pay_subjects" style="height:150px;top:5px;position:absolute;"></select><br>
					<input type="button" name="change_type" value="Изменить тип">
		</div>
		<div id="dark_bg" style="display:none; position:absolute; left:35%;top:40%;">
			<div id="pay_assign" class="mod_w">
				<div class="close" onclick="close_date_modal()">X</div>
				<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
					<span id="date_end"></span><br>

					<input type="hidden" value="" name="red_pay_class">
					<input type="hidden" value="" name="red_pay_id">
					<label> Введите количество дней, на которое хотите продлить образование данному ученику<br>
						<p id="fail_amount"></p>
						<input type="hidden" name="red_edu_type" value=""></input>
						<br><input type="text" name="days"></label>
					<input type="button" name="update_pay" value="Продлить">
				</form>
			</div>
		</div>
		<div class="block0">
			<?php
				if(isset($_SESSION['error']) && $_SESSION['error'] != ""){
					printf("<h3>%s</h3>",$_SESSION['error']);
					unset($_SESSION['error']);
				}
			?>
			<div class="cabinet">
			<!-- *ДОСТУП УЧЕНИКА -->
                        <?php if($_SESSION['data']['level'] == 1): ?>
					<div class="tabbed-area adjacent">

                <div class="no_tabs" id="tab_1">
                   <?php
						//var_dump($_SESSION);
						require_once("req_mods/pdata.php");
					?>
					<form method="post" action="<?php print($_SERVER['REQUEST_URI'])?>#tab_1">
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<p>Введите старый пароль</p>
						<input type="text" name="cur_password"></input>
						<p>Введите новый пароль</p>
						<input type="text" name="new_password"></input>
						<p>Подтвердите новый пароль</p>
						<input type="text" name="new_password_accept"></input><br>
						<input type="submit" name="accept_password" value="Сменить пароль"></input>
						<? else: ?>
						<p>Введіть старий пароль</p>
						<input type="text" name="cur_password"></input>
						<p>Введіть новий пароль</p>
						<input type="text" name="new_password"></input>
						<p>Підтвердіть новий пароль</p>
						<input type="text" name="new_password_accept"></input><br>
						<input type="submit" name="accept_password" value="Змінити пароль"></input>
						<? endif; ?>

					</form>
                </div>

                <div class="no_tabs" id="tab_2">
                	<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
                    <h1>Управление рассылками</h1>
					<form name="form1" method="post" action="">
					<input type="checkbox" class="radio" name="total" value="checkbox" onClick="checkAll(this.form,this.checked)"> <label>Выделить все</label><br>
					<? else: ?>
					<h1>Керування розсилками</h1>
					<form name="form1" method="post" action="">
					<input type="checkbox" class="radio" name="total" value="checkbox" onClick="checkAll(this.form,this.checked)"> <label>Виділити все</label><br>
					<? endif; ?>
						<?php
							$sql = "SELECT * FROM os_mail_types";
							$res = $mysqli->query($sql);
							$sql_if = "SELECT * FROM os_user_mails WHERE id_user='".$_SESSION['data']['id']."'";
							$res_if = $mysqli->query($sql_if);
							$mail_arr = array();
							$mail_arr["all"] = array();
							$mail_arr["yep"] = array();
							while ($row_if = $res_if->fetch_assoc()) {
								$mail_arr["all"][] = $row_if['id_mail'];
								$mail_arr["yep"][] = $row_if['yep'];
							}
							//var_dump($mail_arr["yep"]);
							while($row = $res->fetch_assoc()){
								if(in_array($row['id'], $mail_arr["all"]) && $mail_arr["yep"][$row['id']-1] == 1){
									printf("<input type='checkbox' class=\"radio\" name='checkbox[%s]' checked><label>%s</label><br>",
										$row['id'],$row['type_'.$_COOKIE["lang"]]);
								}
								else{
									printf("<input type='checkbox' class=\"radio\" name='checkbox[%s]'><label>%s</label><br>",
										$row['id'],$row['type_'.$_COOKIE["lang"]]);
								}
							}
						?>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<input type="submit" name="update_mails" value="Сохранить" style="background: #1E9CB7;
    color: #fff;
    text-decoration: none;
    padding: 5px 15px;
    border-radius: 5px;
    margin: 10px 10px;
    line-height: 20px;
    font-weight: normal;
    border: none;">
						</form>
						<h4>Дополнительный e-mail рассылки</h4>
						<form name="form2" method="post" action="">
							<input type="text" name="p_email" value="<?php print($_SESSION['data']['p_email']); ?>"><br>
							<input style="background: #1E9CB7;
    color: #fff;
    text-decoration: none;
    padding: 5px 15px;
    border-radius: 5px;
    margin: 10px 10px;
    line-height: 20px;
    font-weight: normal;
    border: none;" type="submit" value="Сохранить" name="send_pem">
						</form>
						<? else: ?>
						<input type="submit" name="update_mails" value="Зберегти" style="background: #1E9CB7;
    color: #fff;
    text-decoration: none;
    padding: 5px 15px;
    border-radius: 5px;
    margin: 10px 10px;
    line-height: 20px;
    font-weight: normal;
    border: none;">
						</form>
						<h4>Додатковий e-mail розсилки</h4>
						<form name="form2" method="post" action="">
							<input type="text" name="p_email" value="<?php print($_SESSION['data']['p_email']); ?>"><br>
							<input style="background: #1E9CB7;
    color: #fff;
    text-decoration: none;
    padding: 5px 15px;
    border-radius: 5px;
    margin: 10px 10px;
    line-height: 20px;
    font-weight: normal;
    border: none;" type="submit" value="Зберегти" name="send_pem">
						</form>
						<? endif; ?>
                </div>

                <div class="no_tabs" id="tab_3">
                	<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<h1>Оплата школы</h1>
					<? else: ?>
						<h1>Оплата школи</h1>
					<? endif; ?>


						<script type="text/javascript">
							$(document).ready(function () {
							$('a.oplata_od').click(function (e) {
								$(this).toggleClass('active');
								$('#oplata_od_content').toggle();
								e.stopPropagation();
							});
							$('#oplata_od_content').click(function (e) {
								e.stopPropagation();
							});
							$('body').click(function () {
								var link = $('a.oplata_od');
								if (link.hasClass('active')) {
									link.click();
										}
									});
								});
						</script>
						<script type="text/javascript">
							$(document).ready(function () {
							$('a.oplata_od2').click(function (e) {
								$(this).toggleClass('active');
								$('#oplata_od_content2').toggle();
								e.stopPropagation();
							});
							$('#oplata_od_content2').click(function (e) {
								e.stopPropagation();
							});
							$('body').click(function () {
								var link = $('a.oplata_od2');
								if (link.hasClass('active')) {
									link.click();
										}
									});
								});
						</script>
						<script type="text/javascript">
							$(document).ready(function () {
							$('a.oplata_od3').click(function (e) {
								$(this).toggleClass('active');
								$('#oplata_od_content3').toggle();
								e.stopPropagation();
							});
							$('#oplata_od_content3').click(function (e) {
								e.stopPropagation();
							});
							$('body').click(function () {
								var link = $('a.oplata_od3');
								if (link.hasClass('active')) {
									link.click();
										}
									});
								});
						</script>

					<div class="infos_oplata">
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
							<p>Вид доступа:
						<? else: ?>
							<p>Вид доступу:
						<? endif; ?>
							<?php
								
									if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
										$types = array (
												0 => "Полное образование",
												1 => "Дополнительное образование",
												2 => "Частичное образование",
												3 => "Тип образования не установлен"
											);
										$yourSubjects = "Ваши предметы";
										$pay_card = "Сгенерировать платежку";
										$attach = "Прикрепить файл";
										$pay_send = "Отправить квитанцию";
										$message = "Чтобы отправить копию чека об уплате квитанции, сфотографируйте ее или отсканируйте, нажмите на кнопку “Прикрепить файл”, выберите файл и, когда он загрузится, нажмите на кнопку “Отправить квитанцию” и отправьте ее нам. Загружать можно файлы формата: .png, .jpg, .jpeg";
										$translate_payments = array( 'pay_info' => 'Информация об оплате',
																     'pay_text' => '<p>1) Онлайн-оплата обучения</p>
							<p>Осуществляется с помошью интернет-сервиса электронных платежей <a href="http://www.liqpay.com" target="_blank">www.liqpay.com</a>.
							Средства перечисляются автоматически на счёт Онлайн-школы за вычетом комиссии в течение 1 банковского дня.</p>
							<p>2) Оплата обучения по квитанции.</p>
							<p>Вы можете сгенерировать квитанцию для оплаты, распечатать ее, оплатить обучение в любом банке
							и загрузить нам обратно оплаченный чек, чтобы мы продлили вам обучение.</p>',
																     'prolong_payment' => 'Продлить оплату',
																     'auto_pay_txt' => 'Автоматическая оплата (при помощи систем электронной коммерции)',
																     'pay' => 'Оплатить',
																     'pay_through_bank' => 'Оплата через банк (нажмите на кнопку «Сгенерировать платежку», <br>
								чтобы получить реквизиты для оплаты в банке)',
																	 'monthes' => array( 'месяц','месяца','месяцев' ),
																	 'pay_term' => 'Срок оплаты'
																   );
									} else {
										$types = array (
												0 => "Повна освіта",
												1 => "Додаткова освіта",
												2 => "Часткова освіта",
												3 => "Тип освіти не зазначено"
											);
										$yourSubjects = "Ваші предмети";
										$pay_card = "Створити квитанцію";
										$attach = "Прикріпити файл";
										$pay_send = "Відправити квитанцію";
										$message = "Щоб надіслати копію чека про оплату квитанції, сфотографуйте її або відскануйте, натисніть на кнопку “Прикріпити файл”, виберіть файл і, коли він завантажиться, натисніть на кнопку “Відправити квитанцію” і надішліть її нам. Завантажувати можна файли формату: .png .jpg, .jpeg";
										$translate_payments = array( 'pay_info' => 'Інформація про оплату',
																     'pay_text' => '<p>1) Онлайн-оплата навчання</p>
							<p>Здійснюється за допомогою інтернет-сервісу електронних платежів <a href="http://www.liqpay.com" target="_blank">www.liqpay.com</a>.
							Кошти автоматично перераховуються на рахунок Онлайн-школи з врахуванням комісії протягом 1 банківського дня.</p>
							<p>2) Оплата навчання по квитанції.</p>
							<p>Ви можете створити квитанцію для оплати, роздрукувати її, оплатити навчання в будь-якому
								банку і завантажити назад нам оплачений чек, щоб ми продовжили вам доступ до навчання.</p>',
																     'prolong_payment' => 'Подовжити оплату',
																     'auto_pay_txt' => 'Автоматична оплата (за допомогою систем електронної комерції)',
																     'pay' => 'Сплатити',
																     'pay_through_bank' => 'Оплата в банку (натисніть на кнопку «Створити квитанцію», <br>
								щоб отримати реквізити для оплати в банку)',
																	 'monthes' => array( 'місяць','місяці','місяців' ),
																	 'pay_term' => 'Термін оплати'
																   );
									}
								if($_SESSION['data']['edu_type']!=0){
									switch ($_SESSION['data']['edu_type']) {
										case 1:
											print($types[0]);
											break;
										case 2:
											print($types[1]);
											break;
										case 3:
											print($types[2]);
												$sql_s = sprintf("SELECT * FROM os_subjects WHERE id
													IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_SESSION['data']['id']);
												$res_s = $mysqli->query($sql_s);
												if ($res_s->num_rows!=0) {
													print("(");
													$subjects = "";
													while ($row_s = $res_s->fetch_assoc()) {
														$subjects .= $row_s['name_'.$_COOKIE['lang']].", ";
													}
													$subjects = rtrim($subjects,", ");
													print($subjects);
													print(")");
												}

											break;
										default:
											print($types[3]);
											break;
									}
								}
							?>
						</p>
						<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<p>Сумма оплаты в месяц:
						<?php
							print($row_sum['cost']." грн");
						?>
						</p>
						<p>Оплачен до:
							<?php
							if($_SESSION["data"]["date_end"]!="0000-00-00")
								print($_SESSION["data"]["date_end"]);
							else
								print("Дата не установлена");
							?>
						</p>
						<? else: ?>
						<p>Сума оплати в місяць:
						<?php
							print($row_sum['cost']." грн");
						?>
						</p>
						<p>Сплачено до:
							<?php
							if($_SESSION["data"]["date_end"]!="0000-00-00")
								print($_SESSION["data"]["date_end"]);
							else
								print("Дата не встановлена");
							?>
						</p>
						<? endif; ?>
					</div>

					<a class="oplata_od"><h6><?php print($translate_payments['pay_info']); ?></h6> </a>
					<div id="oplata_od_content" style="display: none;">
						<div id="oplata_od_content_text">
							<?php print($translate_payments['pay_text']); ?>
						</div>
					</div>

					<a class="oplata_od3"><h6><?php print($translate_payments['prolong_payment']); ?></h6> </a>
					<div id="oplata_od_content3"  style="display: block;">
						<div id="oplata_od_content_text3">
							<h4><?php print($translate_payments['auto_pay_txt']); ?></h4>
					
								<center>
									<?php
										if($_SESSION['data']['edu_type'] == 3){
											$sql_s = sprintf("SELECT * FROM os_subjects WHERE id
												IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_SESSION['data']['id']);
											$res_s = $mysqli->query($sql_s);
											if ($res_s->num_rows!=0) {
												print("$yourSubjects: <br><ol>");
												while ($row_s = $res_s->fetch_assoc()) {
													printf("<li>%s</li>",$row_s['name_'.$_COOKIE['lang']]);
												}
												print("</ol>");
											}
										}
									?>
									<form action='https://www.liqpay.com/api/checkout' method='post'>
									<table>
										<tr>
											<td>
												<?php print($translate_payments['pay_term']); ?><br>
												<select name="pay_time_student">
													<?php
														$selected = '';
														for($i = 1; $i <= 9; $i++) {
															if($i == 1) $selected = ' selected';
															printf('<option value="%s" $selected>%s</option>',$i, plural_form($i, $translate_payments['monthes']));
														}
													?>
												</select>
											</td>
											<td>
												<input type="text" name="sum_text1" required hidden><div id="sum_text1">0 грн.</div>
												<input type="hidden" name="edu_type_pc" value="<?php print($_SESSION['data']['edu_type']); ?>">
												<input type="hidden" name="edu_id_pc" value="<?php print($_SESSION['data']['id']); ?>">
											</td>
											<td>
												<div id="signature_items"></div>
												<input type="submit" name="pay3" value="<?php print($translate_payments['pay']); ?>">
											</td>
										</tr>
									</table>
								</form></center>
							<h4><?php print($translate_payments['pay_through_bank']); ?></h4>
								

							<center>
								<table>
									<tr>
										<?php if($_SESSION["data"]["edu_type"] == 1): ?>
										<td style="vertical-align: top;"><a class="oplata_ssilkas" href="http://online-shkola.com.ua/statics/watch.php?id=32"><?php echo $pay_card; ?></a></td>
										<?php else: ?>
											<?php if($_SESSION["data"]["edu_type"] == 2): ?>
											<td style="vertical-align: top;"><a class="oplata_ssilkas" href="http://online-shkola.com.ua/statics/watch.php?id=34&type=2"><?php echo $pay_card; ?></a></td>
											<?php elseif($_SESSION["data"]["edu_type"] == 3): ?>
											<td style="vertical-align: top;"><a class="oplata_ssilkas" href="http://online-shkola.com.ua/statics/watch.php?id=34&type=3"><?php echo $pay_card; ?></a></td>
											<?php endif; ?>
										<?php endif; ?>
										<td style="vertical-align: top;">
											<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>#tab_3" enctype="multipart/form-data">
												<script>
												      $(function (){
															if($('#chose_file').length)
														{
															$('#chose_file').click(function(){
																$('#chose_file_input').click();
																return(false);
															});

															$('#chose_file_input').change(function(){
																$('#chose_file_text').html($(this).val());
															}).change(); // .change() в конце для того чтобы событие сработало при обновлении страницы
														}
													});
												</script>
												<a id="chose_file" href=""><?php echo $attach; ?></a>
												<span id="chose_file_text"></span>
												<input id="chose_file_input" type="file" name="kvitancia1"><br>
												<input type="submit" name="send_kvit" value="<?php echo $pay_send; ?>">
											</form>
										</td>
									</tr>
									<tr>
										<td colspan='2'>
											<span class="projs"><?php echo $message; ?></span>
										</td>
									</tr>
								</table>


							 </center>
						</div>
					</div>








					<?php
					
						//var_dump($_SESSION);
						//require_once("req_mods/pay_manager.php");
					?>
					<?php
						if(!isset($_COOKIE["lang"]) || $_COOKIE["lang"] == 'ru') {
							$paymentControl  = "Оплата курсов";
							$paymentInfo 	   = "Информация об оплате";
							$electroAutoPayment = "Автоматическая оплата (при помощи систем электронной коммерции)";
							$course = "Курс";
							$sum = "Сумма";
							$duration ="Срок";
							$courseBankPayment = "Оплатить через банк (нажмите на кнопку 'Сгенерировать платежку', чтобы получить реквезиты для оплаты в банке)";
							$payAll = 'Оплатить все';
							$generateBill = "Сгенерировать платежку";
							$attachFile = "Прикрепить файл";
							$sendBill = "Отправить квитанцию";
							$payedTill = "Оплачено до";
							$continue = "Продлить";
							$sendCheckInstructions = 'Чтобы отправить копию чека об оплате квитанции, сфотографируйте ее или отсканируйте,
								нажмите на кнопку "Прикрепить файл", выберите файл, и когда он загрузится,
								нажмите на кнопку "Отправить квитанцию" и отправьте ее нам.
								Загружать можно файлы формата: .png,.jpeg,.jpg';
							$pay = 'Оплатить';

						} else {
							$paymentControl  = "Оплата курсiв";
							$paymentInfo 	   = "Iнформацiя щодо оплати";
							$electroAutoPayment = "Автоматична оплата (за допомогою систем електронної комерції)";
							$course = "Курс";
							$sum = "Сума";
							$duration ="Термін";
							$courseBankPayment = "Сплатити через банк (натисніть на кнопку 'Сгенерувати платiжку', щоб отримати реквізити для Сплати у банку)";
							$payAll = 'Сплатити все';
							$generateBill = "Сгенерувати платiжку";
							$attachFile = "Прикріпити файл";
							$sendBill = "Відправити квитанцію";
							$payedTill = "Сплачено до";
							$continue = "Подовжити";
							$sendCheckInstructions = 'Щоб вiдправити копію чека об оплаті квитанції, сфотографуйте її або вiдскануйте,
								натисніть на кнопку "Прикріпити файл", виберіть файл, i коли вiн загрузиться,
								натисніть на кнопку "Вiдправити квитанцію" и вiдправте її нам.
								Завантажити дозволяэться файли формату: .png,.jpeg,.jpg';
							$pay = 'Сплатити';
							
						}
					?>
                </div>
				<div class="no_tabs" id="tab_4">
					<h1 class="course-pay-tab-h1"><?php echo $paymentControl; ?></h1>
					<div class="course-pay-tab-info">
						<h3><?php echo $paymentInfo; ?></h3>
						<img class="question-tab" src="/tpl_img/question.png" alt="">
						<div class="info-hint">
							<?php echo $translate_payments['pay_text']; ?>
						</div>
					</div>
					<div class="table_course_container course-pay-tab-info">
						<h3><?php echo $electroAutoPayment; ?></h3>
						<table>
							<tr>
								<td><?php echo $course; ?></td>
								<td><?php echo $sum; ?></td>
								<td></td>
								<td><?php echo $duration; ?></td>
							</tr>
							<?php
								/*Liqpay keys*/
								$merchant_id = 'i97603769660'; //Вписывайте сюда свой мерчант
								$signature = "bZAUhVOWNAycyQsKJQi3fgOJI3W0czDlEnLj4DIb"; //Сюда вносите public_key
								$liqpay = new Liqpay($merchant_id,$signature);

								$lang = $_COOKIE['lang'];
								$sql_courses = "SELECT * FROM os_courses_meta WHERE is_active=1";
								$res_courses = $mysqli->query($sql_courses);
								$payment_all_form = '<a href="">' . $payAll . '</a>';
								$course_part = "";
								$full_course_price = 0;
								if ($res_courses->num_rows) {
									while($row_courses = $res_courses->fetch_assoc()) {
										$course_part .= $row_courses['id'] . '-|-';
										$full_course_price += (int)$row_courses['course_price'];
										$order_id = $_SESSION['data']['id'] . '_' . Date('YmdHis') . '_' . $row_courses['id'];
										$sql_student_course = sprintf("SELECT * FROM os_courses_students WHERE id_course=%s AND id_user=%s AND id = 
																		(SELECT MAX(id) FROM os_courses_students WHERE id_course=%s AND id_user=%s)"
											,$row_courses['id'],$_SESSION['data']['id'],$row_courses['id'],$_SESSION['data']['id']);
										$res_student_course = $mysqli->query($sql_student_course);
										
										$params = array(
										  'version' 	=> '3',
										  'amount' 		=> $row_courses['course_price'],
										  'result_url'  => 'http://' . $_SERVER['HTTP_HOST'] . '/cabinet/index.php#tab_4',
										  'server_url'	=> 'http://' . $_SERVER['HTTP_HOST'] . '/courses/serverpart.php',
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
										$input_data = sprintf("<input type='hidden' name='data' value='%s'>", $data);
										$input_signature = sprintf("<input type='hidden' name='signature' value='%s'>", $signature);	
										if($res_student_course->num_rows) {
											$row_student_course = $res_student_course->fetch_assoc();
											$payment_end_date = "!НЕ ОПЛАЧЕНО!";
											if(!in_array($row_student_course['payment_end_date'], array('0000-00-00','00-00-0000','','0'))) {
												$payment_end_date = Date("d-m-Y",strtotime($row_student_course['payment_end_date']));
											}
											$to_pay = sprintf("<td>
															<form action='https://www.liqpay.com/api/checkout' method='post'><input type='hidden' name='course_id' value='%s' >
															$input_data
															$input_signature
															<input type='submit' value='$continue'>
															</form></td>
															   <td>$payedTill %s</td>",$row_courses['id'] ,$payment_end_date);
											$class = 'payed_course_row';
										} else {
											$to_pay = sprintf("<td>
															<form action='https://www.liqpay.com/api/checkout' method='post'><input type='hidden' name='course_id' value='%s' >
															$input_data
															$input_signature
															<input type='submit' value='$pay'>
															</form></td>
															   <td>Срок оплаты: %s дней</td>",$row_courses['id'] ,$row_courses['payment_period']);
											$class = 'unpayed_course_row';
										}
									
										printf("<tr class='$class'>
													<td onclick=\"window.open('%s','_blank')\"'>%s</td>
													<td>%s грн</td>
													$to_pay
												</tr>"
												,$row_courses['course_desc_link'],$row_courses['course_name_' . $lang],$row_courses['course_price']);
									}
									$course_part = rtrim($course_part, '-|-');
									$new_order_id = $_SESSION['data']['id'] . '_' . Date('YmdHis') . '_' . $course_part;
									$params = array(
													 'version' 	=> '3',
													 'amount' 		=> $full_course_price,
													 'result_url'  => 'http://' . $_SERVER['HTTP_HOST'] . '/cabinet/index.php#tab_4',
													 'server_url'	=> 'http://' . $_SERVER['HTTP_HOST'] . '/courses/serverpart_all.php',
													 'sandbox'		=> 1, //test payment
													 'currency'    => 'UAH',     //Можно менять  'EUR','UAH','USD','RUB','RUR'
													 'description' => "Оплата за Все доступные курсы",  //Или изменить на $desc
													 'language' 	=> $_COOKIE['lang'],
													 'order_id' 	=> $new_order_id,
													 'sender_first_name' => $_SESSION['data']['name'],
													 'sender_last_name' => $_SESSION['data']['surname']
												   );
									if (isset($new_order_id) && $new_order_id != "" && isset($course_part) && $course_part != "") {
										$params = $liqpay->cnb_params($params);
										$data = base64_encode( json_encode($params) );
										$signature = $liqpay->cnb_signature($params);
										$input_data = sprintf("<input type='hidden' name='data' value='%s'>", $data);
										$input_signature = sprintf("<input type='hidden' name='signature' value='%s'>", $signature);
										$payment_all_form = sprintf(" <form action='https://www.liqpay.com/api/checkout' method='post'>
																	$input_data
																	$input_signature
																	<input type='submit' value='$payAll'>
																  </form>");
									}
								}
							?>
							<?php /*
							<tr style='height:28px'>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><?php echo $payment_all_form; ?></td>
								<td></td>
							</tr>
							*/ ?>
						</table>
					</div>
					<div class="table_payment_container course-pay-tab-info payment-buttons">
                        <h3><?php echo $courseBankPayment; ?></h3><br>
						<a class="element" href="http://online-shkola.com.ua/statics/watch.php?id=34"><?php echo $generateBill; ?></a>
						<script>
						    $(function (){
								if($('.chose_file').length) {
									$('.chose_file').click(function(){
										$('.chose_file_input').click();
										return(false);
									});
									$('.chose_file_input').change(function(){
										$('.chose_file_text').html($(this).val());
									}).change(); // .change() в конце для того чтобы событие сработало при обновлении страницы
								}
							});
						</script>
						<a class="chose_file element" href=""><?php echo $attach; ?></a>
						<span class="chose_file_text"></span>
						<input class="chose_file_input" type="file" name="kvitancia1">
						<input type="submit" name="send_kvit" value="<?php echo $sendBill; ?>" class="input-send element">
					<div class="clear"></div>
					</div>
					<div class="table_payment_container_info course-pay-tab-info">
                    	<h3><?php echo $sendCheckInstructions; ?></h3>
					</div>
        </div>
                <? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				<ul class="tabs_lc group">
                    <li><a href="#tab_1">Личная информация</a></li>
                    <li><a href="#tab_2">Управление рассылками</a></li>
                    <li><a href="#tab_3" onclick="redir_rule();">Оплата школы</a></li>
					<li><a href="#tab_4">Оплата курсов</a></li>
                </ul>
				<? else: ?>
				<ul class="tabs_lc group">
                    <li><a href="#tab_1">Персональна інформація</a></li>
                    <li><a href="#tab_2">Керування розсилками</a></li>
                    <li><a href="#tab_3" onclick="redir_rule();">Оплата школи</a></li>
					<li><a href="#tab_4">Оплата курсiв</a></li>
                </ul>
				<? endif; ?>


            </div>
			<div class="clear"></div>
                        <?php endif; ?>
			<!-- ДОСТУП УЧЕНИКА* -->
			<!-- *ДОСТУП УЧИТЕЛЯ -->
		<?php if($_SESSION['data']['level'] == 2): ?>
			<div class="tabbed-area adjacent">

                <div class="no_tabs" id="tab_1">
                   <?php
                   	require_once("req_mods/adata.php");
				?>
				<form method="post" action="<?php print($_SERVER['REQUEST_URI'])?>#tab_1">
						<p>Введите старый пароль</p>
						<input type="text" name="cur_password"></input>
						<p>Введите новый пароль</p>
						<input type="text" name="new_password"></input>
						<p>Подтвердите новый пароль</p>
						<input type="text" name="new_password_accept"></input><br>
						<input type="submit" name="accept_password" value="Сменить пароль"></input>
					</form>
                </div>

                <ul class="tabs_lc group">
                    <li><a href="#tab_1">Личная информация</a></li>
                </ul>

            </div>
		<?php endif; ?>
			<!-- ДОСТУП УЧИТЕЛЯ* -->
			<!-- *ДОСТУП МЕНЕДЖЕРА -->
                        <?php if($_SESSION['data']['level'] == 3): ?>
				  	 <div class="tabbed-area adjacent">

                <div class="no_tabs" id="tab_1">
                   <?php
						//var_dump($_SESSION);
						require_once("req_mods/adata.php");
					?>
					<form method="post" action="<?php print($_SERVER['REQUEST_URI'])?>#tab_1">
						<p>Введите старый пароль</p>
						<input type="text" name="cur_password"></input>
						<p>Введите новый пароль</p>
						<input type="text" name="new_password"></input>
						<p>Подтвердите новый пароль</p>
						<input type="text" name="new_password_accept"></input><br>
						<input type="submit" name="accept_password" value="Сменить пароль"></input>
					</form>
                </div>

                <div class="no_tabs" id="tab_4">
                    <h1>Списки пользователей</h1>
						<?php
							require_once("req_mods/people_list.php");
						?>
                </div>

                <ul class="tabs_lc group">
                    <li><a href="#tab_1">Личная информация</a></li>
                    <li><a href="#tab_4">Списки пользователей</a></li>
                </ul>

            </div>
                        <?php endif; ?>
			<!-- ДОСТУП МЕНЕДЖЕРА* -->
			<!-- *ДОСТУП АДМИНА -->
                        <?php if($_SESSION['data']['level'] == 4): ?>

			<div class="tabbed-area adjacent">

                <div class="no_tabs" id="tab_1">
                   <?php
						//var_dump($_SESSION);
						require_once("req_mods/adata.php");
					?>
					<form method="post" action="<?php print($_SERVER['REQUEST_URI'])?>#tab_1">
						<p>Введите старый пароль</p>
						<input type="text" name="cur_password"></input>
						<p>Введите новый пароль</p>
						<input type="text" name="new_password"></input>
						<p>Подтвердите новый пароль</p>
						<input type="text" name="new_password_accept"></input><br>
						<input type="submit" name="accept_password" value="Сменить пароль"></input>
					</form>
                </div>

                <div class="no_tabs" id="tab_2">
                    <h1>Управление рассылками</h1>
						<h1><a href='../statics/mails.php'>Сделать рассылку группе пользователей</a></h1>
                    <?php
                    	printf("<form method='post' action='index.php#tab_2'>");
							$sql = "SELECT * FROM os_mail_types";
							$res = $mysqli->query($sql);
							print("Вместо символов \"%s\" в рассылке подставляются необходимые значения, - их НЕ менять, НЕ удалять");
							while($row = $res->fetch_assoc()){
								printf("<div class='red_foot'><h4>Рассылка %s</h4>",$row['type']);
								printf("<textarea name='%s_resp'>%s</textarea>
									<script type='text/javascript'>
										CKEDITOR.replace('%s_resp');
									</script>
									<input type='button' name='%_del' value='Удалить'>
										",trim($row['id']),trim($row['template']),trim($row['id']),trim($row['id']));
									if($row['status'] == 0)
										print("<input type='submit' name='".$row['id']."_off' value='Отключить'>");
									else
										print("<input type='submit' name='".$row['id']."_on' value='Включить'>");

								print("</div>");
								}
							print("<div class='clear'></div>");

						print("<input type='submit' name='save_resp'>");
						print("</form>");
						?>
						<div class="clear"></div>


                </div>

                <div class="no_tabs" id="tab_3">
                  <h1> Оплата школы</h1>
						<?php
							require_once("req_mods/pay_manager.php");
						?>
                </div>

                <div class="no_tabs" id="tab_4">
                   <h1>Списки пользователей</h1>
						<?php
							require_once("req_mods/people_list.php");
						?>
                </div>
                <div class="no_tabs" id="tab_5">
                  <h1> Оплата курсов</h1>
						<?php
							require_once("req_mods/course_pay_manager.php");
						?>
                </div>

                <ul class="tabs_lc group">
                    <li><a href="#tab_1">Личная информация</a></li>
                    <li><a href="#tab_2">Управление рассылками</a></li>
                    <li><a href="#tab_3">Оплата школы</a></li>
                    <li><a href="#tab_4">Списки пользователей</a></li>
                    <li><a href="#tab_5">Оплата курсов</a></li>
                </ul>

            </div>
                        <?php endif; ?>
			<!-- ДОСТУП АДМИНА* -->
			</div>
		</div>
	</div>

	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body>
</html>
