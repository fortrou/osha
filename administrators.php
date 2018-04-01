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

	<title><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>Наша администрация<? else: ?>Наша адміністрація<? endif; ?>	 - Онлайн Школа</title>

	<meta name="description" content="Администрация и руководство 'Онлайн-школы 'Альтернатива''. Адміністрація та керівництво 'Онлайн-школи 'Альтернатива''">

	<meta name="keywords" content="Онлайн-школа, Альтернатива, директор, учредитель, руководитель, администрация, засновник, керівник, адміністрація">

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

			<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Наша администрация</h3><? else: ?><h3>Наша адміністрація</h3><? endif; ?>	



			<center><table class="teach_table" style="width: 100%; text-align:center;">			

<tr>

<td>

<a class="example-image-link" href="/tpl_img/administrators/3.jpg" data-lightbox="example-3" >

<img class="example-image" src="/tpl_img/administrators/3_2.jpg" alt="Постный Алексей Витальевич"/></a>

<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Зарицкий Александр Николаевич<br>Учредитель и директор<br>ООО «Онлайн-центр «Альтернатива»<br>Учредитель «Онлайн-школы «Альтернатива»<br>Учитель физики и математики</h3><? else: ?><h3>Зарицький Олександр Миколайович<br>Засновник та директор<br>ТОВ «Онлайн-центр «Альтернатива»<br>Засновник «Онлайн-школи «Альтернатива»<br>Вчитель фізики та математики</h3><? endif; ?>

</td> 

<td>

<a class="example-image-link" href="/tpl_img/teachers/shpak_2.jpg" data-lightbox="example-2" >

<img class="example-image" src="/tpl_img/teachers/shpak.jpg" alt="Зарицкий Александр Николаевич"  width="320" height="417"/></a>

<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Шпак Андрей Петрович<br>Учредитель ООО «Онлайн-центр «Альтернатива»<br>Учредитель и директор Онлайн-школы «Альтернатива»<br>Учитель математики и информатики<br>Кандидат юридических наук<br>Награды: органов государственной власти<br>и местного самоуправления, Министерства<br> образования и науки Украины</h3><? else: ?><h3>Шпак Андрій Петрович<br>Засновник ТОВ «Онлайн-центр «Альтернатива»<br>Засновник та директор Онлайн-школи «Альтернатива»<br>Вчитель математики та інформатики<br>Кандидат юридичних наук<br>Нагороди: органів державної влади<br>та місцевого самоврядування, Міністерства<br> освіти і науки України</h3><? endif; ?>

</td>

<td>

<a class="example-image-link" href="/tpl_img/administrators/suprun_600x400.jpg" data-lightbox="example-1" >

<img class="example-image" src="/tpl_img/administrators/suprun_600x400.jpg" alt="Гусак Евгения Александровна" width="320" height="417"/></a>

<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Супрун Татьяна Игоревна<br>Учредитель ООО «Онлайн-центр «Альтернатива»<br>Учредитель «Онлайн-школы «Альтернатива»<br>Учитель математики и информатики</h3><? else: ?><h3>Супрун Тетяна Ігорівна<br>Засновник ТОВ «Онлайн-центр «Альтернатива»<br>Засновник «Онлайн-школи «Альтернатива»<br>Вчитель математики та інформатики</h3><? endif; ?>

</td>

</tr>

 </table></center>

 

</div> 

		 

</div> 

	</div> 

	

	<?php

		include ("tpl_blocks/footer.php");

	?>

</body> 

</html> 