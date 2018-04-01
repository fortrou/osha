 <?php 

session_start();

	require 'tpl_php/autoload.php';

$db = Database::getInstance();

	$mysqli = $db->getConnection();

	if ( isset($_POST['send']) )

	{

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

	}

 ?>



<!DOCTYPE html> 

<head>  	

	<title><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>Авторизация<? else: ?>Авторизація<? endif; ?>	 - Онлайн Школа</title>

	<meta name="description" content="Станица авторизации на сайте 'Онлайн-школы 'Альтернатива''">

	<meta name="keywords" content="форма авторизации, онлайн-школа">

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

			<div class="tech_admin_block">

	  <?php if(isset($_GET['type'])){ ?>

	  <? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3 style="text-align: center;">Для начала занятий и оплаты сначала необходимо авторизоваться или <a href="reg.php">зарегистрироваться</a></h3><? else: ?><h3 style="text-align: center;">Для початку занять та оплати спочатку треба авторизуватися або <a href="reg.php">зареєструватися</a></h3><? endif; ?>	

	 <?php } ?>	 

	

	 

		 <div class="auth_log_form">



				

				<?php if(!isset($_SESSION['data'])): ?> 

				

				

				



				 

				<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>

						 



						 <form action="cabinet/auth.php" method="post"> 



								<input name="name" type="text" placeholder="Логин" required=""><br> 



								<input name="ocenka" type="text" placeholder="Пароль" required=""><br> 



								<a href="forgot.php" class="index_head_login_link">Забыли пароль?</a><br> 



								<input type="submit" value="Войти">	  	 			

								

								

						</form>

				<? else: ?>

						 



						 <form action="cabinet/auth.php" method="post"> 



								<input name="name" type="text" placeholder="Логін" required=""><br> 



								<input name="ocenka" type="text" placeholder="Пароль" required=""><br> 



								<a href="forgot.php" class="index_head_login_link">Забули пароль?</a><br> 



								<input type="submit" value="Увійти">	  			

								

								

						</form>

				<? endif; ?>

				

				

				<?php endif; ?>

				<?php if(isset($_SESSION['data'])): ?> 

				<div class="head_reg_user">



					



					

					<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>

					<a href="/schedule/diary.php">Мои уроки</a><br><br><br><br>



					<a style="color: #e53a24; padding: 10px 44px;" href="/cabinet/goout.php">Выход</a>

					<? else: ?>

					<a href="/schedule/diary.php">Мої уроки</a><br><br><br><br>



					<a style="color: #e53a24; padding: 10px 44px;" href="/cabinet/goout.php">Вихід</a>

					<? endif; ?>

				</div>

				<?php endif; ?>

				

				

				

				



			</div>

</div> 

		 

</div> 

	</div> 

	

	<?php

		include ("tpl_blocks/footer.php");

	?>

</body> 

</html> 