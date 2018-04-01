<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru') {
		$title = "Домашнее задание - Онлайн Школа";
		$hw = "Домашнее задание";
		$yourMark = "Ваша оценка";
		$testHw = "Тестовое ДЗ";
		$creativeHw = "Творческое ДЗ";
		$toLesson = "К уроку";
		$sentByStud = "Отправленное учеником";
		$sentByTeacher = "Отправленное учителем";
		$comment = "Комментарий к домашнему заданию, от проверявшего учителя";
		$send =  "Отправить";
		$reset = "Отменить";
	} else {
		$title = "Домашнє завдання - Онлайн Школа";
		$hw = "Домашнє завдання";
		$yourMark = "Ваша оцінка";
		$testHw = "Тестове ДЗ";
		$creativeHw = "Творче ДЗ";
		$toLesson = "До уроку";
		$sentByStud = "Відправлено учнем";
		$sentByTeacher = "Відправлено вчителем";
		$comment = "Коментар до домашнього завдання від вчителя";
		$send =  "Відправити";
		$reset = "Відмінити";
	}
?>
<!DOCTYPE html> 
<html>
<head>  		
	<title><?php echo $title; ?></title>
	<meta name="description" content="Как выглядят домашние задания на сайте 'Онлайн-школы 'Альтернатива'">
	<meta name="keywords" content="демо-доступ, домашнее задание, онлайн-школа">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<!--script type="text/javascript" src="../tpl_js/full_hw.js"></script>-->
</head>
<body>
	<?php

		include ("head2.php");

	?>
	
	<div class="content">
	<div class="alt_title_test">
		<div class="block0">
			<?php if(!isset($_SESSION['data'])): ?>
<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
			<h1>Все материалы, которые вы видите, являются демонстрационными. Функции обучения в демонстрационном доступе ограничены.
			 Для получения полного доступа к нашей онлайн-школе зарегистрируйтесь на сайте и оплатите обучение<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Оплатить обучение</a></h1>
			<?php else: ?>
			<h1>Усі матеріали, які ви бачите, є демонстраційними. Функції навчання в демонстраційному доступі
			 обмежені. Для одержання повного доступу до нашої онлайн-школи зареєструйтесь на сайті і оплатіть навчання<br>
			 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Сплатити за навчання</a></h1>
			<?php endif; ?>
		<?php endif; ?> 
		</div>
		</div>
		<div class="block0">
			<input type="hidden" name="language" value="<?=$_COOKIE['lang']?>">
			<h1><?php echo $hw; ?></h1>

			<div class="homework_table">
			<?php
			if(isset($_GET['id'])){
				$sql_hw = sprintf("SELECT id, hw_text_%s, mark,id_lesson FROM os_lesson_homework WHERE id='%s'",$_COOKIE['lang'],$_GET['id']);
				$res_hw = $mysqli->query($sql_hw);
				//print("<br>$sql_hw<br>");

				//print("<br>$sql_hw<br>");
				if($res_hw->num_rows != 0){
					$row_hw = $res_hw->fetch_assoc();
					$sql_test = sprintf("SELECT * FROM os_lesson_test WHERE id_lesson='%s' AND lang='%s'",$row_hw['id_lesson'],$_COOKIE['lang']);
					$res_test = $mysqli->query($sql_test);
					$sql = sprintf("SELECT * FROM os_lessons WHERE id='%s'",$row_hw['id_lesson']);
					//print($sql);
					$res = $mysqli->query($sql);
					if($res->num_rows!=0){
						$row = $res->fetch_assoc();
					}
					if($res_test->num_rows!=0){
						$row_test = $res_test->fetch_assoc();
						$id_test = $row_test['id_test'];
					}
					else{
						$id_test="lila";
					}
					$mark = rand(0,$row_hw['mark']);
					printf("
						<div class='hw_zadanie_norm'>
							<div class='mark_s'>$yourMark: %s</div>
							<h3>Тема: %s</h3>
							<span class='dates'>
								<a class='hw_uc_testdz' href='http://online-shkola.com.ua/tests/completing.php?id=%s'>$testHw</a>
								<a class='hw_kurok' href=\"javascript:onoff2('div%s');\">$creativeHw
								</a><a class='hw_kurok idsd' href='../lessons/watch.php?id=%s'>$toLesson</a>
							</span>
						</div>
						<div id='div%s' class='hw_cooooont' style='display: none;border-radius: 0 0 5px 5px;
    border-right: 1px solid #cacaca;
    border-bottom: 1px solid #cacaca;
    border-left: 1px solid #cacaca;'>
							%s
							<table>
								<tbody>
									<tr>
										<td>
											<table>
												<tbody>
													<tr>
														<td>
															<p>$sentByStud: </p>
														</td>
														<td>
															<p>$sentByTeacher: </p>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<div class='comment'>$comment</div>
							<table>
								<tbody>
									<tr>
										<td>
											<input type='button' class='hw_save_1222' value='$send'><input type='button' class='hw_otmena_12' value='$reset'>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					",$mark,$row['title_'.$_COOKIE['lang']],$row_test['id_test'],$row_hw['id'],$row['id'],$row_hw['id'],$row_hw['hw_text_'.$_COOKIE['lang']]);
				}
				}
				else{
					$sql = "SELECT * FROM os_lessons WHERE  id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=(
										                          SELECT id FROM os_class_manager WHERE is_opened=1))";
					//print("<br>$sql<br>");
					$res = $mysqli->query($sql);
					if ($res->num_rows!=0) {
						while ($row = $res->fetch_assoc()) {
							$sql_hw = sprintf("SELECT id, hw_text_%s, mark FROM os_lesson_homework WHERE id_lesson='%s'",$_COOKIE['lang'],$row['id']);
							$res_hw = $mysqli->query($sql_hw);
							$sql_test = sprintf("SELECT * FROM os_lesson_test WHERE id_lesson='%s' AND lang='%s' AND type=5",$row['id'],$_COOKIE['lang']);
							$res_test = $mysqli->query($sql_test);
							if($res_test->num_rows!=0){
								$row_test = $res_test->fetch_assoc();
								$id_test = $row_test['id_test'];
							}
							else{
								$id_test="lila";
							}
							//print("<br>$sql_hw<br>");
							if($res_hw->num_rows != 0){
								$row_hw = $res_hw->fetch_assoc();
								$mark = rand(0,$row_hw['mark']);
								printf("
									<div class='hw_zadanie_norm'>
										<div class='mark_s'>$yourMark: %s</div>
										<h3>Тема: %s</h3>
										<span class='dates'>
											<a class='hw_uc_testdz' href='http://online-shkola.com.ua/tests/completing.php?id=%s'>$testHw</a>
											<a class='hw_kurok' href=\"javascript:onoff2('div%s');\">$creativeHw
											</a><a class='hw_kurok idsd' href='../lessons/watch.php?id=%s'>$toLesson</a>
										</span>
									</div>
									<div id='div%s' class='hw_cooooont' style='display: none;border-radius: 0 0 5px 5px;
    border-right: 1px solid #cacaca;
    border-bottom: 1px solid #cacaca;
    border-left: 1px solid #cacaca;'>
										%s
										<table>
											<tbody>
												<tr>
													<td>
														<table>
															<tbody>
																<tr>
																	<td>
																		<p>$sentByStud: </p>
																	</td>
																	<td>
																		<p>$sentByTeacher: </p>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<div class='comment'>$comment</div>
										<table>
											<tbody>
												<tr>
													<td>
														<input type='button' class='hw_save_1222' value='$send'><input type='button' class='hw_otmena_12' value='$reset'>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									",$mark,$row['title_'.$_COOKIE['lang']],$row_test['id_test'],$row_hw['id'],$row['id'],$row_hw['id'],$row_hw["hw_text_".$_COOKIE['lang']]);
							}
						}
						}
					}
				?>
			</div>
			
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 