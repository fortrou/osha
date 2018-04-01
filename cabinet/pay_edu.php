<?php
	ini_set('display_errors','Off');
	if (!isset($_COOKIE['lang'])) {
		setcookie("lang","ru",time()+1000*60*60*24*7);
		//print("<br>a<br>");
	}
	if(isset($_POST['ua'])){
		setcookie("lang");
		setcookie("lang","ua",time()+1000*60*60*24*7);
		//print("<br>b<br>");
		header("Location:".$_SERVER['REQUEST_URI']);
	}
  	if(isset($_POST['ru'])){
		setcookie("lang");
		setcookie("lang","ru",time()+1000*60*60*24*7);
		//print("<br>c<br>");
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	session_start();
	header('Content-Type: text/html; charset=utf-8', true);
	//require_once("../tpl_php/autoload.php");
	require_once("../tpl_php/classDatabase.php");
	require ('../tpl_php/classLiqpay.php');
	require ('../tpl_php/classFile.php');
	$cmp_date = date("Y-m-d");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$merchant_id_1='i10672147601'; //Вписывайте сюда свой мерчант
	$signature_1="gedRsHaal5YlgcnXcIcONXE3eIfliWa6pC40l5vZ"; //Сюда вносите public_key
	$merchant_id_2='i97603769660'; //Вписывайте сюда свой мерчант
	$signature_2="bZAUhVOWNAycyQsKJQi3fgOJI3W0czDlEnLj4DIb"; //Сюда вносите public_key
	$liqpay_1 = new Liqpay($merchant_id_1, $signature_1);
	$liqpay_2 = new Liqpay($merchant_id_2, $signature_2);

	$sql = "SELECT * FROM os_payment_data WHERE student_id ='".$_SESSION['data']['id']."' AND pay_status = 0";
	$res = $mysqli->query($sql);
	$cur_sum = 0;
	//var_dump($_SESSION['date_end']);
	//print($sql);
	if($res->num_rows != 0){
		while($row = $res->fetch_assoc()){
			$liqpay_res = $liqpay_1->api("payment/data",array(
				'version' => '3',
				'public_key' => $merchant_id_1,
				'order_id' => $row['order_id'],
				'info' => 'a'
			));

			if($liqpay_res->status != "error"){
				$cur_sum += $row['payment'];
			}

			$liqpay_res = $liqpay_2->api("payment/data",array(
				'version' => '3',
				'public_key' => $merchant_id_2,
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
		if($cur_sum!=0){
		$day = ceil($cur_sum/$day);
		//print($day);
		if($_SESSION['data']['date_end']!=NULL && $_SESSION['data']['date_end']!="" && $_SESSION['data']['date_end']!="0000-00-00"){
			$date_of_end = strtotime($_SESSION['data']['date_end'])+$day*24*3600*30;
		}
		else{
			$date_of_end = strtotime($cmp_date)+$day*24*3600*30;
		}
		//print("<br>$date_of_end<br>");
		//if(strtotime($date_of_end) != strtotime(date("Y-m-d"))){
			$date_of_end = date("Y-m-d",$date_of_end);
			$sql_upd = sprintf("UPDATE os_users SET date_end='%s', current_money='%s' WHERE id='%s'",$date_of_end,$cur_sum,$_SESSION['data']['id']);
			$res_upd = $mysqli->query($sql_upd);
			$_SESSION['data']['date_end'] = $date_of_end;
		//}
		}
		//print("<br>$date_of_end<br>");
	}
	if(isset($_POST['pay2'])){
		$cost=(int)$_POST['sum_text'];
		//var_dump($_POST);
		$sql = "DELETE FROM os_student_subjects WHERE id_student='".$_SESSION['data']['id']."'";
		$res = $mysqli->query($sql);
		if($_POST['tid'] == 1 || $_POST['tid'] == 2){
			$sql = sprintf("INSERT INTO os_student_subjects(id_student,id_subject) SELECT %s,id FROM os_subjects WHERE id 
				IN(SELECT id_s FROM os_class_subj WHERE class='%s')",$_SESSION['data']['id'],$_SESSION['data']['class']);
			$res = $mysqli->query($sql);
		}
		if($_POST['tid'] == 3){
			foreach ($_POST['subjects'] as $value) {
				$sql = sprintf("INSERT INTO os_student_subjects(id_student,id_subject) VALUES(%s,%s)",$_SESSION['data']['id'],$value);
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
			}
		}
		header("Location:makeform.php?price=$cost&e_type=".$_POST['tid']."&type=1");
	}
	if(isset($_POST['pay3'])){
		$cost=(int)$_POST['sum_text'];
		header("Location:makeform.php?price=$cost");
	}
	if(isset($_POST['pay'])){
		/*var_dump($_POST);
		print("<br>");
		var_dump($_FILES['kvit']);*/
		if(File::isValidImg($_FILES['kvitancia'])){
		//print("alala");
			$kvit_name = File::LoadUpdImg($_FILES['kvitancia'],$_SESSION['data']['login']);
			if($kvit_name != false){
				$sql = sprintf("INSERT INTO os_bills(image_bill,id_student, `date`) VALUES('%s','%s','%s')",
					$kvit_name,$_SESSION['data']['id'],$cmp_date);
				//print($sql);
				$res = $mysqli->query($sql);
				//header("Location:".$_SERVER['REQUEST_URI']);
			}
			else{
				print("Квитанция не отправлена");
			}
		}
	}
	$sql_sum = sprintf("SELECT * FROM os_edu_types WHERE id=%s",$_SESSION['data']['edu_type']);
		$res_sum = $mysqli->query($sql_sum);
		$row_sum = $res_sum->fetch_assoc();
?>
<!DOCTYPE html> 
<head>  		
	<title>Оплата - Просмотр профиля - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">



	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/users.js"></script>
</head>
<body>
	<input type="hidden" name="lang" value="<?php echo $_COOKIE['lang'] ?>"?>
	<?php
		include ("../tpl_blocks/header.php");
	?>
<div class="content">
		<div class="block0">
		<div class="no_tabs" id="tab_3">
					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<h1>Управление оплатами</h1>
					<? else: ?>
						<h1>Керування оплатами</h1>
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
						
					<!--<div class="infos_oplata">
						<p>Вид доступа: 
							<?php
								if($_SESSION['data']['edu_type']!=0){
									switch ($_SESSION['data']['edu_type']) {
										case 1:
											print("Полное образование");
											break;
										case 2:
											print("Дополнительное образование");
											break;
										case 3:
											print("Частичное образование");
											break;										
										default:
											print("Тип образования не установлен");
											break;
									}
								}
							?>
						</p>
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
					</div>-->
<?php
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru') {
		$pay_for = "Оплатить выбранные услуги";
		$t3subjs = "Вы выбрали более 3х предметов, так нельзя";
		$payThrowBank = "Оплата через банк (нажмите на кнопку «Сгенерировать платежку», <br> чтобы получить реквизиты для оплаты  в банке)";
		$pay = "Оплатить";
		$pay_card = "Сгенерировать платежку";
		$attach = "Прикрепить файл";
		$pay_send = "Отправить квитанцию";
		$message = "Чтобы отправить копию чека об уплате квитанции, сфотографируйте ее или отсканируйте, нажмите на кнопку “Прикрепить файл”, выберите файл и, когда он загрузится, нажмите на кнопку “Отправить квитанцию” и отправьте ее нам. Загружать можно файлы формата: .png, .jpg, .jpeg";
		$array_vals = array( 'select_type' => 'Выберите вид доступа:',
							 'full_type'   => 'Общее образование',
							 'added_type'  => 'Дополнительное образование',
							 'part_type'   => 'Частичное образование',
							 'pay_inf_txt' => '<p>1) Онлайн-оплата обучения</p>
											   <p>Осуществляется с помошью интернет-сервиса электронных платежей <a href="http://www.liqpay.com" target="_blank">www.liqpay.com</a>.
											   Средства перечисляются автоматически на счёт Онлайн-школы за вычетом комиссии в течение 1 банковского дня.</p>
											   <p>2) Оплата обучения по квитанции.</p>
											   <p>Вы можете сгенерировать квитанцию для оплаты, распечатать ее, оплатить обучение в любом банке 
											   и загрузить нам обратно оплаченный чек, чтобы мы продлили вам обучение.</p>',
							 'pay_info'    => 'Информация об оплате',
							 'autopay_text'  => 'Автоматическая оплата (при помощи систем электронной коммерции)',
							 'select_3_subj' => 'Выберите 3 предмета:'
						   );
	} else {
		$pay_for = "Сплатити за обрані послуги";
		$t3subjs = "Ви обрали більше 3х предметів, так не можна";
		$payThrowBank = "Оплата в банку (натисніть на кнопку «Створити квитанцію», <br>	щоб отримати реквізити для оплати в банку)";
		$pay = "Сплатити";
		$pay_card = "Створити квитанцію";
		$attach = "Прикріпити файл";
		$pay_send = "Відправити квитанцію";
		$message = "Щоб надіслати копію чека про оплату квитанції, сфотографуйте її або відскануйте, натисніть на кнопку “Прикріпити файл”, виберіть файл і, коли він завантажиться, натисніть на кнопку “Відправити квитанцію” і надішліть її нам. Завантажувати можна файли формату: .png .jpg, .jpeg";
		$array_vals = array( 'select_type' => 'Оберіть вид доступу:',
							 'full_type'   => 'Загальна освіта',
							 'added_type'  => 'Додаткова освіта',
							 'part_type'   => 'Часткова освіта',
							 'pay_inf_txt' => '<p>1) Онлайн-оплата навчання</p>
											   <p>Здійснюється за допомогою інтернет-сервісу електронних платежів <a href="http://www.liqpay.com" target="_blank">www.liqpay.com</a>.
											   Кошти автоматично перераховуються на рахунок Онлайн-школи з врахуванням комісії протягом 1 банківського дня.</p>
											   <p>2) Оплата навчання по квитанції.</p>
											   <p>Ви можете створити квитанцію для оплати, роздрукувати її, оплатити навчання в будь-якому 
											   банку і завантажити назад нам оплачений чек, щоб ми продовжили вам доступ до навчання.</p>',
							 'pay_info'    => 'Інформація про оплату',
							 'autopay_text'  => 'Автоматична оплата (за допомогою систем електронної комерції)',
							 'select_3_subj' => 'Оберіть 3 предмети:'
						   );
	}
?>
				<a class="oplata_od"><h6 class="pay_head"><?php print($array_vals['pay_info']); ?></h6></a> 
				<div id="oplata_od_content" style="display: none;">
					<div id="oplata_od_content_text">
						<?php print($array_vals['pay_inf_txt']); ?>
					</div>
				</div>
				<a class="oplata_od2">	<h6 class="pay_head"><?php echo $pay_for; ?></h6></a> 
					<div id="oplata_od_content2"  style="display: block;">
						<div id="oplata_od_content_text3">
								<script>
									var expanded = false;
									function showCheckboxes() {
										var checkboxes = document.getElementById("checkboxes");
										if (!expanded) {
											checkboxes.style.display = "block";
											expanded = true;
										} else {
											checkboxes.style.display = "none";
											expanded = false;
										}
									}
								</script> 

								<input type="hidden" name="user_id" value="<?=$_SESSION['data']['id']?>">
								<input type="hidden" name="userlevel" value="<?=$_SESSION['data']['level']?>">
								<input type="hidden" name="class_id" value="<?=$_SESSION['data']['class']?>">
								<h4><?php print($array_vals['autopay_text']); ?></h4>
								
								<form action='https://www.liqpay.com/api/checkout' method='post'>
								<center> 
									<table>
										<tr>
											<td>
												<p><?php print($array_vals['select_type']); ?></p>
											</td>
											<td style="padding-top: 16px;vertical-align: top;"> 
												 <select name="tid" onchange="select_cost()">
													<option value="1" selected><?php print($array_vals['full_type']); ?></option>
													<option value="2"><?php print($array_vals['added_type']); ?></option>
													<option value="3"><?php print($array_vals['part_type']); ?></option> 
												</select>
											</td>
										</tr> 
										<tr> 
											<td>
												<p class="pre_ms"><?php print($array_vals['select_3_subj']); ?></p>
											</td>
											<td> 
	<script type="text/javascript">
		$("select[name = tid]").change(function(){
			if ($(this).val() == 3 || $(this).val() == 2) {
				$("#oplata_ssilkas").attr("href","http://online-shkola.com.ua/statics/watch.php?id=34");
				if ($(this).val() == 2) {
					$("#oplata_ssilkas").attr("href",$("#oplata_ssilkas").attr("href")+"&type=2");
				}
				if ($(this).val() == 3) {
					$("#oplata_ssilkas").attr("href",$("#oplata_ssilkas").attr("href")+"&type=3");
				}
			}
			else{
				$("#oplata_ssilkas").attr("href","http://online-shkola.com.ua/statics/watch.php?id=32");
			}
		})
		$(function() {
		    $("#real").css({
		        "top": $("#fake").offset().top + $("#fake").outerHeight(),
		        "left": $("#fake").offset.left
		    });
		    $("#fake").click(function() {
		        $("#real").show().focus();
		    });
		    $("#real").mouseleave(function() {
		        $("#real").hide();
		    });
		    $("#real").change(function() {
		    	//select_cost();
		    	if($("#real").val().length > 3){
					$("#pay2").css("display","none");
					$("#failed_update").empty();
					$("#failed_update").append("<?php echo $payThrowBank; ?>");
				}
				if($("#real").val().length <= 3){
					$("#failed_update").empty();
					$("#pay2").css("display","");
				}
		        if ($("#fake option").length != 0) {
		            $("#fake option").html(""+$("#real").val());
		        } else {
		            $("#fake").append("<option>" + $("#real").val() + "</option>");
		        }
		    });
		});
	</script>
			<select id="fake"></select>
			<select id="real" size="5" multiple="multiple" name='subjects[]' id="subjects" style="height:150px; margin-top: -6px;" onclick="addItem(this)"></select>
			<p id="failed_update"></p>
											</td>
										</tr>
									</table>
									<table>
										<tr> 
											<td>
												<input type="text" name="sum_text" required hidden><div id="sum_text">0 грн.</div>
											</td>
											<td> 
												<div id="signature_items"></div>
												<input type="submit" name="pay2" value="Оплатить">
												<!--<a href="" id="pay2" target="_blank"><?php echo $pay; ?></a>-->
											</td>
										</tr>
									</table>
								</form></center>
							<h4><?php echo $payThrowBank; ?></h4>

							<center>
								<table>
									<tr>
										<td style="vertical-align: top;"><a class="oplata_ssilkas" id="oplata_ssilkas" href="http://online-shkola.com.ua/statics/watch.php?id=32"><?php echo $pay_card; ?></a></td>
										<td style="vertical-align: top;">
											<form method="post" enctype="multipart/form-data">
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
												<a id="chose_file" href=""><?php echo $attach;?></a>
												<span id="chose_file_text"></span> 
												<input id="chose_file_input" type="file" name="kvitancia"><br>
												<input type="submit" name="pay" value="<?php echo $pay_send; ?>"> 
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
						
	</div>
</div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 