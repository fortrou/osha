<div id="captcha-form" class="no-display">
	<input type="hidden" value="" name="form-id">
	<center>
		<p>Тест на робота?</p>
		<div class="g-recaptcha" id='t-recaptcha'></div>
		<p style="color:red; cursor: pointer; font-weight: bold;" onclick="captcha_trigger();">Я робот!</p>
	</center>
</div>
<script type="text/javascript">
	var response;
	var callback = function(response) {
		window.response = response;
		if(response != "" && response != null && response != undefined && response != 0) {
			var current_form_id = $("#captcha-form input[name = form-id]").val();
			$("#" + current_form_id + " input[name = g-recaptcha-response]").val(response);
			$("#" + current_form_id).submit();
		}
	}
	var onloadCallback = function() {
		var captcha1 = grecaptcha.render('t-recaptcha', {
			'sitekey'  : '6Ld3SSUTAAAAAE8ae7sW9P9WOLZfCuBBjXEE-ITV',
        	'callback' : callback
        });
		console.log(response);
    };

</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<div class="footer">
<?php
	if(isset($_COOKIE['lang'])) {
		$currentLang = $_COOKIE['lang'];
	} else {
		$currentLang = 'ru';
	}
	$sql_f1 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=1",$currentLang);
	$res_f1 = $mysqli->query($sql_f1);
	$row_f1 = $res_f1->fetch_assoc();
	$sql_f2 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=2",$currentLang);
	$res_f2 = $mysqli->query($sql_f2);
	$row_f2 = $res_f2->fetch_assoc();
	$sql_f3 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=3",$currentLang);
	$res_f3 = $mysqli->query($sql_f3);
	$row_f3 = $res_f3->fetch_assoc();
	/*$sql_f4 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=4",$currentLang);
	$res_f4 = $mysqli->query($sql_f4);
	$row_f4 = $res_f4->fetch_assoc();
	$sql_f5 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=5",$currentLang);
	$res_f5 = $mysqli->query($sql_f5);
	$row_f5 = $res_f5->fetch_assoc();*/
?>
		<div class="index_body_9_index post">
		<div class="index_body_9">
			<table>
				<tr>
					<td><h3>
						<? if( !isset($currentLang) || $currentLang == "ru"): ?>
				Информация
				<? else: ?>
				Інформація
				<? endif; ?></h3>
						<?php 
							$sql_des = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=7 AND position=1",$currentLang);
							$res_des = $mysqli->query($sql_des);
							$row_des = $res_des->fetch_assoc();
							print($row_des['cont']);
						?>
					<!--<ul>
						<li><a href="#">Формы обучения</a></li>
						<li><a href="#">Как учиться в нашей школе</a></li>
						<li><a href="#">Как оплатить обучение с формой оплаты</a></li>
						<li><a href="#">Нормативные документы</a></li>
						<li><a href="#">ВНО и ГИА (ЗНО и ДПА)</a></li>
						<li><a href="#">Что такое дистанционное обучение</a></li>
					</ul>-->
					</td>
					<td>
						
					<!--<h3>О нас</h3>
					<ul>
						<li><a href="#">Онлайн-школа «Альтернатива</a></li>
						<li><a href="#">Наши учителя</a></li>
						<li><a href="#">Администрация</a></li> 
					</ul>
					<h3>Партнеры</h3>
					<ul>
						<li><a href="#">ООО «РГ ЗиГзаг»</a></li>
						<li><a href="#">Образовательный портал «ВнеШколы»</a></li>
					</ul>-->
					<h3><? if( !isset($currentLang) || $currentLang == "ru"): ?>
				О нас
				<? else: ?>
				Про нас
				<? endif; ?></h3>
					<?php 
							$sql_des = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=7 AND position=2",$currentLang);
							$res_des = $mysqli->query($sql_des);
							$row_des = $res_des->fetch_assoc();
							print($row_des['cont']);
						?>
					<h3><? if( !isset($currentLang) || $currentLang == "ru"): ?>
				Партнеры
				<? else: ?>
				Партнери
				<? endif; ?></h3>
					<?php 
							$sql_des = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=7 AND position=3",$currentLang);
							$res_des = $mysqli->query($sql_des);
							$row_des = $res_des->fetch_assoc();
							print($row_des['cont']);
						?>
					</td>
					<td><a href="http://online-shkola.com.ua/news/watch_all.php"><h3><? if( !isset($currentLang) || $currentLang == "ru"): ?>
				Новости
				<? else: ?>
				Новини
				<? endif; ?></h3></a>
					<ul>
						<?php 
							$sql_news = sprintf("SELECT * FROM os_news ORDER BY id DESC LIMIT 0,5");
							//print("<br>$sql_news<br>");
							$res_news = $mysqli->query($sql_news);
							while($row_news = $res_news->fetch_assoc()){
								printf("<li><a href='http://%s/news/watch.php?id=%s'>%s</a></li>",$_SERVER['HTTP_HOST'],$row_news['id'],$row_news['title_n_'.$currentLang]);
							}
							//print($row_des['cont']);
						?>
					</ul>
					</td>
				</tr>
			</table>
		</div>
		</div> 
		<div class="index_body_10_index post">
		<div class="index_body_10">
						<?php 
							$sql_f1 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=1",$currentLang);
							$res_f1 = $mysqli->query($sql_f1);
							$row_f1 = $res_f1->fetch_assoc();
							$sql_f2 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=2",$currentLang);
							$res_f2 = $mysqli->query($sql_f2);
							$row_f2 = $res_f2->fetch_assoc();
							$sql_f3 = sprintf("SELECT text_%s AS cont FROM os_elements WHERE categ=8 AND position=3",$currentLang);
							$res_f3 = $mysqli->query($sql_f3);
							$row_f3 = $res_f3->fetch_assoc();
						?>
			<table>
				<tr>
					<td>
						<?php 
							print($row_f1['cont']);
						?>

					</td>
					<td>  
						<?php 
							print($row_f2['cont']);
						?>

					</td>
					<td>
						<?php 
							print($row_f3['cont']);
						?>

					</td>
				</tr>
			</table>
				<div class="copyr">
				<a href="/"><? if( !isset($currentLang) || $currentLang == "ru"): ?>ООО Онлайн-центр "Альтернатива" 2016 г.<? else: ?>ТОВ Онлайн-центр "Альтернатива" 2016 г.<? endif; ?></a>
				<!--<span style="float: right;"><a href="http://workshop-it.ru/">Разработка сайта</a> WorkShop-IT.ru</span>-->
				</div>
		</div>
		</div> 
	</div>
	<script type="text/javascript">  
		$(document).ready(function () {
        $('a.tabel_link').click(function (e) {
            $(this).toggleClass('active');
            $('#tabel_link_content').toggle();
                
            e.stopPropagation();
        });
        $('#tabel_link_content').click(function (e) {
            e.stopPropagation();
        });
        $('body').click(function () {
            var link = $('a.tabel_link');
            if (link.hasClass('active')) {
                link.click();
            }
        });
    });
	</script> 
<script type="text/javascript">  
	$(document).ready(function () {
		if(!$("input[name = com_id_chat").val()) {
	        $('a.block').click(function (e) {
	        	getMessages($("input[name = ac_chat_id]").val());
	            $(this).toggleClass('active');
	            $('#contents_tp').toggle();
	                
	            e.stopPropagation();
	        });
	        $('#contents_tp').click(function (e) {
	        	getMessages($("input[name = ac_chat_id]").val());
	            e.stopPropagation();
	        });
	    }
	    $('body').click(function () {
	        var link = $('a.block');
	        if (link.hasClass('active')) {
	            link.click();
	        }
	    });
    });
	</script> 	
		<script src="http://<?php echo $_SERVER['HTTP_HOST'];?>/tpl_js/lightbox-plus-jquery.min.js"></script> 
		
		<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-80599206-1', 'auto');
  ga('send', 'pageview');
</script>