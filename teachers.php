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
	<title><? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>Наши учителя<? else: ?>Наші вчителі<? endif; ?>	 - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
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
			<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Наши учителя</h3><? else: ?><h3>Наші вчителі</h3><? endif; ?>

<center><table class="teach_table" style="width: 100%; text-align:center;">	
		
<tr>
<td>
<a class="example-image-link" href="/tpl_img/teachers/belozerova.jpg" data-lightbox="example-1" >
<img class="example-image" src="/tpl_img/teachers/belozerova_2.jpg" alt="Белозерова Людмила Анатольевна" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Белозерова Людмила Анатольевна<br>Учитель физики</h3>
<? else: ?><h3>Бєлозьорова Людмила Анатоліївна<br>Вчитель фізики</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/gusak.jpg" data-lightbox="example-1" >
<img class="example-image" src="/tpl_img/teachers/gusak_2.jpg" alt="Гусак Евгения Александровна" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Гусак Евгения Александровна<br>Учитель биологии</h3><? else: ?><h3>Гусак Євгенія Олександрівна<br>Вчитель біології</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/zarickiy.jpg" data-lightbox="example-2" >
<img class="example-image" src="/tpl_img/teachers/zarickiy_2.jpg" alt="Зарицкий Александр Николаевич"/></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Зарицкий Александр Николаевич<br>Учитель физики и математики</h3><? else: ?><h3>Зарицький Олександр Миколайович<br>Вчитель фізики та математики</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/ivarovskaya.jpg" data-lightbox="example-1" >
<img class="example-image" src="/tpl_img/teachers/ivarovskaya_2.jpg" alt="Иваровская Татьяна Васильевна" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Иваровская Татьяна Васильевна<br>Учитель зарубежной литературы</h3>
<? else: ?><h3>Іваровська Тетяна Василіївна<br>Вчитель зарубіжної літератури</h3><? endif; ?>
</td>
</tr>

<tr>
<td>
<a class="example-image-link" href="/tpl_img/teachers/polniy.jpg" data-lightbox="example-3" >
<img class="example-image" src="/tpl_img/teachers/polniy_2.jpg" alt="Постный Алексей Витальевич"/></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Постный Алексей Витальевич<br>Учитель физики</h3><? else: ?><h3>Постний Олексій Віталійович<br>Вчитель фізики</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/rastorgueve.jpg" data-lightbox="example-4" >
<img class="example-image" src="/tpl_img/teachers/rastorgueve_2.jpg" alt="Расторгуева Наталья Валерьевна"/></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Расторгуева Наталья Валерьевна<br>Учитель английского языка</h3><? else: ?><h3>Расторгуєва Наталія Валеріївна<br>Вчитель англійської мови</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/selevko.jpg" data-lightbox="example-1" >
<img class="example-image" src="/tpl_img/teachers/selevko_2.jpg" alt="Иваровская Татьяна Васильевна" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Селевко Оксана Викторовна<br>Учитель географии</h3>
<? else: ?><h3>Селевко Оксана Вікторівна<br>Вчитель географії</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/skidan.jpg" data-lightbox="example-5" >
<img class="example-image" src="/tpl_img/teachers/skidan_2.jpg" alt="Скидан Ярослав Анатолиевич" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Скидан Ярослав Анатольевич<br>Учитель украинского языка и литературы</h3><? else: ?><h3>Скидан Ярослав Анатолійович<br>Вчитель української мови та літератури</h3><? endif; ?>
</td>
</tr>

<tr>
<td>
<a class="example-image-link" href="/tpl_img/teachers/strelxtyrj.jpg" data-lightbox="example-6" >
<img class="example-image" src="/tpl_img/teachers/strelxtyrj_2.jpg" alt="Стрельченко Ирина Олеговна"/></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Стрельченко Ирина Олеговна<br>Учитель химии</h3><? else: ?><h3>Стрельченко Ірина Олегівна<br>Вчитель хімії</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/suprun.jpg" data-lightbox="example-7" >
<img class="example-image" src="/tpl_img/teachers/suprun_2.jpg" alt="Супрун Татьяна Игоревна"/></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Супрун Татьяна Игоревна<br>Учитель математики и информатики</h3><? else: ?><h3>Супрун Тетяна Ігорівна<br>Вчитель математики та інформатики</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/tvach.jpg" data-lightbox="example-8" >
<img class="example-image" src="/tpl_img/teachers/tvach_2.jpg" alt="Трач Ирина Романовна"/></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Трач Ирина Романовна<br>Учитель украинского языка и литературы</h3><? else: ?><h3>Трач Ірина Романівна<br>Вчитель української мови та літератури</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/trush.jpg" data-lightbox="example-1" >
<img class="example-image" src="/tpl_img/teachers/trush_2.jpg" alt="Труш Светлана Николаевна" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Труш Светлана Николаевна<br>Учитель истории</h3>
<? else: ?><h3>Труш Світлана Миколаївна<br>Вчитель історії</h3><? endif; ?>
</td>
</tr>

<tr>
<td>
<a class="example-image-link" href="/tpl_img/teachers/hit.jpg" data-lightbox="example-1" >
<img class="example-image" src="/tpl_img/teachers/hit_2.jpg" alt="Хить Ирина Ивановна" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Хить Ирина Ивановна<br>Учитель биологии</h3>
<? else: ?><h3>Хить Ірина Іванівна<br>Вчитель біології</h3><? endif; ?>
</td> 
<td>
<a class="example-image-link" href="/tpl_img/teachers/carolina-2.jpg" data-lightbox="example-7" >
<img class="example-image" src="/tpl_img/teachers/carolina.jpg" alt="Хрипливец Каролина Александровна"/></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Хрипливец Каролина Александровна<br>Учитель начальных классов</h3><? else: ?><h3>Хрипливець Кароліна Олександрівна<br>Вчитель початкових класів</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/teachers/chernaya.jpg" data-lightbox="example-1" >
<img class="example-image" src="/tpl_img/teachers/chernaya_2.jpg" alt="Черная Елена Сергеевна" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Черная Елена Сергеевна<br>Учитель информатики</h3>
<? else: ?><h3>Чорна Олена Сергіївна<br>Вчитель інформатики</h3><? endif; ?>
</td>
<td>
<a class="example-image-link" href="/tpl_img/administrators/2.jpg" data-lightbox="example-9" >
<img class="example-image" src="/tpl_img/administrators/2_2.jpg" alt="Шпак Андрей Петрович" width="220" height="287" /></a>
<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?><h3>Шпак Андрей Петрович<br>Учитель математики и информатики</h3><? else: ?><h3>Шпак Андрій Петрович<br>Вчитель математики та інформатики</h3><? endif; ?>
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