<?php 
session_start();
	require 'tpl_php/autoload.php';
	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if ( isset($_POST['send']) )
	{
		if ($_POST["g-recaptcha-response"]) {
			$response = $reCaptcha->verifyResponse(
		        $_SERVER["REMOTE_ADDR"],
		        $_POST["g-recaptcha-response"]
		    );
		}
		if ($response != null && $response->success) {
	        if ($_POST['password']==$_POST['password1']) {
				try {
					$user = User::createUser_main($_POST,1);

				} catch (Exception $e) {
					print($e->getMessage());
				}
			}
			else{
	            $_SESSION['error'] = "Пароли не совпадают, попробуйте снова";
	        }
	    } else {
	    	$_SESSION['error'] = "Юпс, таки робот";
	    }
		
	}
 ?>

<!DOCTYPE html> 
<head>  		
	<title>Регистрация - Онлайн Школа</title>
	<meta name="description" content="Станица регистрации на сайте 'Онлайн-школы 'Альтернатива''">
	<meta name="keywords" content="форма регистрации, онлайн-школа">
	<?php
		include ("tpl_blocks/head.php");
	?>
</head>
<body id="top">
	<?php
		include ("tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class="block0">
		<div class="reg_pager">
			<?php 
                if (isset($_SESSION['error']) && $_SESSION['error']!="") {
                    printf("<br>%s<br>",$_SESSION['error']);
                    unset($_SESSION['error']);
                }
            ?>
			<form id="form-1" action="" method="post" enctype="multipart/form-data">
			<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				
				
	<table style="width: 50%;     float: left;">
	<tr><td><h2>информация об ученике</h2></td></tr>
		<tr>
			<td> 
			<p>Класс<font style="color:red;">*</font></p>
				<select name="class">
					<?php 
						$sql = sprintf("SELECT * FROM os_class_manager WHERE is_opened=0");
						$res = $mysqli->query($sql);
						while ($row = $res->fetch_assoc()) {
							printf("<option value='%s'>%s класс</option>",$row["id"],$row["class_name"]);
						}

					?>
				</select>
				<p>Имя<font style="color:red;">*</font></p>
				<input name="name" required ></input>

				<p>Фамилия<font style="color:red;">*</font></p>
				<input name="surname" required ></input>
				
				<p>Отчество<font style="color:red;">*</font></p>
				<input name="patronymic" required ></input>

				<p>Дата рождения<font style="color:red;">*</font></p>
				<input type="date" name="birth" required></input>
<p>Школа<font style="color:red;">*</font></p>
				<input name="school"></input>
				
				
			</td>
		</tr>
	</table>
			
<table style="width: 50%;     float: right;">
<tr><td><h2>информация о родителях</h2></td></tr>
		<tr>
			<td> 
			 <p>Имя родителя<font style="color:red;">*</font></p>
				<input name="p_name" required=""></input>

				<p>Фамилия родителя<font style="color:red;">*</font></p>
				<input name="p_surname"  required=""></input>
				
				<p>Отчество родителя<font style="color:red;">*</font></p>
				<input name="p_patronymic" required="" ></input>

				<p>Телефоны родителей<font style="color:red;">*</font></p>
				<input name="p_phone" required ></input>

				<p>Город проживания<font style="color:red;">*</font></p>
				<input name="city" required="" ></input>
				
				<p>Телефон<font style="color:red;">*</font></p>
				<input type="number" name="phone" maxlength="12"></input>
			</td>
		</tr>
	</table>			
<table style="width: 100%;    ">
<tr><td><h2>дополнительная информация</h2></td></tr>
		<tr>
			<td style="width: 50%;"> 
				<p>E-mail<font style="color:red;">*</font></p>
				<input name="email" required="" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" ></input>

				<p>Логин<font style="color:red;">*</font></p>
				<input name="login" maxlength="12" minlength="4" required=""></input>
			</td>
			<td style="width: 50%;">
				<p>Пароль<font style="color:red;">*</font></p>
				<input name="password" minlength="4" max="12" type="password" required ></input>
				<p>Подтвердите пароль<font style="color:red;">*</font></p>
				<input type='password' name='password1'  minlength="4" max="12" required></input><br>
			</td>
				</tr> 
				<tr>
				<td>
				 <p>Фото ученика<font style="color:red;">*</font></p>
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
						}).change(); 
					}
				});
			</script>
			<a style="width: 200px;" id="chose_file" href="">Загрузите фото</a>
			<span id="chose_file_text"></span> 
			<input id="chose_file_input" type="file" name="avatar">
				
				</td>
				<td>
				
				
				
				
				
				<p>Откуда узнали об Онлайн-школе "Альтернатива"?<font style="color:red;">*</font></p>
				<textarea name="appendix" maxlength="200" required=""></textarea>
				</td>
		</tr>
		<!--<tr>
			<td colspan="2">
		<center><div class="g-recaptcha" data-sitekey="6Ld3SSUTAAAAAE8ae7sW9P9WOLZfCuBBjXEE-ITV"></div></center>
			</td>
		</tr>-->
	</table>
				<br>
				<center><!--<input style="    height: 50px;
    line-height: 50px;
    background: #1e9cb7;
    color: #fff;" type="submit" value="Зарегистрироваться!" name="send"></input>-->
    	<input type="hidden" name="g-recaptcha-response" value="">
		<input type="hidden" value="1" name="send">
		<input style="height: 50px; text-align: center;
    line-height: 50px;
    background: #1e9cb7;
    color: #fff; type="button" value="Зарегистрироваться!" onclick="captcha_trigger('form-1')">
    </center>
				
				<div class="clear"></div>
<p>Поля, помеченные звездочками,<font style="color:red;">*</font> обязательны к заполнению.</p>
				<? else: //UKR VERSION?>

				<table style="width: 50%;     float: left;">
	<tr><td><h2>Інформація про учня</h2></td></tr>
		<tr>
			<td> 
			<p>Клас<font style="color:red;">*</font></p>
				<select name="class">
					<?php 
						$sql = sprintf("SELECT * FROM os_class_manager WHERE is_opened=0");
						$res = $mysqli->query($sql);
						while ($row = $res->fetch_assoc()) {
							printf("<option value='%s'>%s клас</option>",$row["id"],$row["class_name"]);
						}

					?>
				</select>
				<p>Ім'я<font style="color:red;">*</font></p>
				<input name="name" required ></input>

				<p>Прізвище<font style="color:red;">*</font></p>
				<input name="surname" required ></input>
				
				<p>По батькові<font style="color:red;">*</font></p>
				<input name="patronymic" required ></input>

				<p>Дата народження<font style="color:red;">*</font></p>
				<input type="date" name="birth" required></input>
<p>Школа<font style="color:red;">*</font></p>
				<input name="school"></input>
				
				
			</td>
		</tr>
	</table>
			
<table style="width: 50%;     float: right;">
<tr><td><h2>Інформація про батьків</h2></td></tr>
		<tr>
			<td> 
			 <p>Ім'я одного з батьків<font style="color:red;">*</font></p>
				<input name="p_name" required=""></input>

				<p>Прізвище одного з батьків<font style="color:red;">*</font></p>
				<input name="p_surname"  required=""></input>
				
				<p>По батькові одного з батьків<font style="color:red;">*</font></p>
				<input name="p_patronymic" required="" ></input>

				<p>Телефони батьків<font style="color:red;">*</font></p>
				<input name="p_phone" required ></input>

				<p>Місто<font style="color:red;">*</font></p>
				<input name="city" required="" ></input>
				
				<p>Телефон<font style="color:red;">*</font></p>
				<input type="number" name="phone" maxlength="12"></input>
			</td>
		</tr>
	</table>			
<table style="width: 100%;    ">
<tr><td><h2>Додаткова інформація</h2></td></tr>
		<tr>
			<td style="width: 50%;"> 
				<p>E-mail<font style="color:red;">*</font></p>
				<input name="email" required="" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" ></input>

				<p>Логін<font style="color:red;">*</font></p>
				<input name="login" maxlength="12" minlength="4" required=""></input>
			</td>
			<td style="width: 50%;">
				<p>Пароль<font style="color:red;">*</font></p>
				<input name="password" minlength="4" max="12" type="password" required ></input>
				<p>Підтвердіть пароль<font style="color:red;">*</font></p>
				<input type='password' name='password1'  minlength="4" max="12" required></input><br>
			</td>
				</tr> 
				<tr>
				<td>
				 <p>Фото учня<font style="color:red;">*</font></p>
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
						}).change(); 
					}
				});
			</script>
			<a style="width: 200px;" id="chose_file" href="">Завантажте фото</a>
			<span id="chose_file_text"></span> 
			<input id="chose_file_input" type="file" name="avatar">
				
				</td>
				<td>
				
				
				
				
				
				<p>Звідки дізналися про Онлайн-школу "Альтернатива"?<font style="color:red;">*</font></p>
				<textarea name="appendix" maxlength="200" required=""></textarea>
				</td>
		</tr>
		<!--<tr>
			<td colspan="2">
		<center><div class="g-recaptcha" data-sitekey="6Ld3SSUTAAAAAE8ae7sW9P9WOLZfCuBBjXEE-ITV"></div></center>
			</td>
		</tr>-->
	</table>
				<br>
				<center><!--<input style="    height: 50px;
    line-height: 50px;
    background: #1e9cb7;
    color: #fff;" type="submit" value="Зареєструватися!" name="send"></input>-->
    	<input type="hidden" name="g-recaptcha-response" value="">
		<input type="hidden" value="1" name="send">
		<input style="height: 50px; text-align: center;
    line-height: 50px;
    background: #1e9cb7;
    color: #fff; type="button" value="Зареєструватися!" onclick="captcha_trigger('form-1')">
    </center>
				
				<div class="clear"></div>
<p>Поля, що відмічені зірочками,<font style="color:red;">*</font> заповнюються обов'язково.</p>
				
				<? endif; ?>
			</form>
</div> 
	</div> 
	</div>
	
	<?php
		include ("tpl_blocks/footer.php");
	?>
</body> 
</html> 