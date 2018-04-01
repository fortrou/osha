<?php
    $alphabet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К");
    $abc = array("a","b","c","d","e","f","g","h","i","j");
    session_start();
    require_once('../tpl_php/autoload.php');
    

$flag_contr = 0;

	if(!isset($_GET['id']))
		header("Location:index.php");
    unset($_SESSION['data_collection']);
    unset($_SESSION['string_answs']);
    $_SESSION['testGet'] = $_GET['id']; 
    $testId = $_SESSION['testGet'];
	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$sql = "SELECT * FROM os_tests WHERE id='".$_GET['id']."'";
	$res = $mysqli->query($sql);
	$row = $res->fetch_assoc();
	if ($row['lang']!=$_COOKIE['lang']) {
		$sql_red = sprintf("SELECT id FROM os_tests WHERE lang='%s' AND type='%s' AND less_id = (SELECT less_id FROM os_tests WHERE id='%s')",
			$_COOKIE['lang'],$row['type'],$row['id']);
		//print($sql_red);
		$res_red = $mysqli->query($sql_red);
		
		if ($res_red->num_rows!=0) {
			$row_red = $res_red->fetch_assoc();
			header("Location:completing.php?id=".$row_red['id']);
		}
		else{
			print("Данного теста пока нет на другом языке");
		}
	}
    
    $sql = "SELECT * FROM os_tests WHERE id='".$_GET['id']."'";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    if ($row['type'] == 5) {
    	$sql = "SELECT * FROM os_journal WHERE id_s='".$_SESSION['data']['id']."' 
		AND id_l=(SELECT id_lesson FROM os_lesson_test WHERE id_test='".$testId."')";
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		if($row['mark_contr'] != 0){
			//header("Location:".$_SERVER['HTTP_REFERER']);
			$flag_contr = 1;
		}
    }
    else{
    	$flag_test = 4;
    }
	


	$sql_test = "SELECT * FROM os_tests WHERE id='$testId'";
    $result = $mysqli->query($sql_test);
	$row_test = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

	<head>
	<title>Прохождение теста: <?php print($row_test['tName']) ?> - Онлайн школа Альтернатива</title>
	<?php require_once('../tpl_blocks/head.php'); ?>
	</head>

	<body>
		<?php
			include ("header.php");
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
		
			<div class="testes">
				<!--<h1>Тренировочный тест</h1>-->
				<?php if($flag_test == 4): ?>
					<h2>Этот тест является тренировочным, 
						его можно проходить любое количество раз.
						Оценка за данный тест не учитывается при расчете
						тематических оценок и не влияет на оценки 
						в табеле и на аттестат</h2>
				<?php endif; ?>
				<div class="names">
					<?
						$sql_names1 = sprintf("SELECT title_%s AS tit FROM os_lessons 
							WHERE id=(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='$testId')",$_COOKIE['lang']);
						$sql_names2 = sprintf("SELECT name FROM os_subjects WHERE id = (SELECT subject FROM os_lessons 
							WHERE id=(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='$testId'))");
						//print("<br>$sql_names1<br>");
						//print("<br>$sql_names2<br>");
						$res_names1 = $mysqli->query($sql_names1);
						$res_names2 = $mysqli->query($sql_names2);
						$row_names1 = $res_names1->fetch_assoc();
						$row_names2 = $res_names2->fetch_assoc();
					?>
					<span><?php print($row_names1['tit']); ?></span><br>
					<span><?php print($row_names2['name']); ?></span>
				</div>
				<form method="post" action="counter.php">
					<input type="hidden" value="0" name="timer">
					<script type="text/javascript">
					window.onload=function(){
						
						function upd(){
							1*$("input[name = timer]").val(1*$("input[name = timer]").val()+1);
						}
						setInterval(upd,1000);
						//setInterval(alert,10,"a");
					}
					</script>
				<table class="tests_table">
					<?php
					$iteration = 1;
						$sql_quest = "SELECT * FROM os_test_quest WHERE id_test='$testId'";
						$res_quest = $mysqli->query($sql_quest);
						while($row_quest = $res_quest->fetch_assoc()){
							
							printf("<tr><td colspan='3'  width='128px'>Вопрос №%s: %s</td></tr>",$iteration,$row_quest['name']);
							print("<tr>");
							if($row_quest['type']==1){
								print("<td><ul style='list-style:none;'>");
			                    $qid = $row_quest['id_q'];
			                    //var_dump($qid);
			                        $arr_mix = Quest::mix_1_data($qid);
			                        //var_dump($arr_mix);
			                        
			                    $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
			                    $res = $mysqli->query($sql);
			                    $num = $res->num_rows;
			                    //print("<br>$num<br>");
			                    
			                    for($i = 1; $i <= count($arr_mix['data']); $i++) {
			                        printf("<li><input type='radio' class='radio' name='%s' value='%s'> <label>%s</label></li>",
			                        	$qid,$arr_mix['id'][$i-1],$arr_mix['data'][$i-1]);
			                    }
			                  
			                    print("</ul></td>");
			                    print("<td></td>");
							}
							if($row_quest['type']==2){
								
			                    print("<td><ul style='list-style:none;'>");
			                    $qid = $row_quest['id_q'];
			                    $arr_mix = Quest::mix_1_data($qid);
			                    $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
			                    $res = $mysqli->query($sql);
			                    $num = $res->num_rows;
			                    //print("<br>$num<br>");
			                    
			                    for($i = 1; $i <= count($arr_mix['data']); $i++) {
			                        printf("<li><input type='checkbox' class='checkbox' name='%s%s'  class='nomer_%s'> <label>%s</label></li>",
			                            $qid,$arr_mix['id'][$i-1],$i,$arr_mix['data'][$i-1]);
			                    }
			                    print("</ul></td>");
			                    print("<td></td>");
							}
							if($row_quest['type']==3){
								print("<td><ul style='list-style:none;'>");
			                    $qid = $row_quest['id_q'];
			                    $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
								$res = $mysqli->query($sql);
								$num = $res->num_rows;
			                    //print("<br>$num<br>");
			                    
			                    while($row = $res->fetch_assoc()){
			                        printf("<li>%s</li>",$row['answer']);
			                    }
			                    print("</ul></td>");
			                    print("<td></td>");
			                    print("<td>
			                    <ul style='width:250px;margin-top:25px; list-style:none;'>");
			                        
			                    print("<li>
			                        <ul class='matchRadio'>");
			                        printf("<li style='width:30px;'></li>");
			                        
			                        for($it = 0; $it < $num; $it++){
			                            printf("<li>%s</li>",$it+1);
			                        }
			                        print("</ul>
			                        <div style='clear:both;'></div>
			                    </li>");
			                        for($i = 1; $i <= $num; $i++){
			                            print("<li>
			                            <ul class='matchRadio'>");
			                            printf("<li style='width:30px;'><span>%s:</span></li>",$alphabet[$i-1]);
			                            for($it = 1; $it <= $num; $it++){
			                                printf("<li><input type='radio' class='radio' name='%s%s' value='%s'><label></label></li>",$qid,$i,$it);
			                            }
			                            print("</ul>
			                            <div style='clear:both;'></div>
			                            </li>");
			                        }
			                    
			                    print("</ul></td>");
							}
							if($row_quest['type']==4){
			                    print("<td><ul style='list-style:none;'>");
			                    $qid = $row_quest['id_q'];
			                    $arr_mix = Quest::mix_m_data($qid);
			                    //var_dump($arr_mix);

			                    $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
			                    $res = $mysqli->query($sql);
			                    $num = $res->num_rows;
			                    //print("<br>$num<br>");
			                    
			                    $sql1 = "SELECT * FROM os_test_matches WHERE id_quest='$qid'";
			                    $res1 = $mysqli->query($sql1);
			                    $num_horiz = $res1->num_rows;

			                    $num_el = 1;

			                    while($row = $res->fetch_assoc()){
			                        printf("<li><span>$num_el </span>%s</li>",$row['answer']);
			                        $num_el++;
			                    }
			                                    
			                                print("</ul>");
			                                print("<ul style='width:450px;margin-top:25px; list-style:none;'>");
			                                    
			                                print("<li>
			                                    <ul class='matchRadio'>");
			                                    printf("<li style='width:80px;'></li>");
			                                    
			                                    for($it = 0; $it < $num_horiz; $it++){
			                                        printf("<li>%s</li>",$alphabet[$it]);
			                                    }
			                                    print("</ul>
			                                    <div style='clear:both;'></div>
			                                </li>");
			                                    for($i = 1; $i <= $num; $i++){
			                                        print("<li>
			                                        <ul class='matchRadio'>");
			                                        printf("<li style='width:80px;'><span>%s</span></li>",$i);
			                                        for($it = 1; $it <= $num_horiz; $it++){
			                                            $val = $arr_mix['id'][$it-1];
			                                            $let = $abc[$it-1];
			                                            print("<li><input type='radio' class='radio' name='$qid$i' value='$val' class='nomer_$i bukva_$let'><label></label></li>");
			                                        }
			                                        print("</ul>
			                                        <div style='clear:both;'></div>
			                                    </li>");
			                                }
			                                
			                            print("</ul>");
			                        print("</td>");

			                    
			                    print("<td><ul style='list-style:none;'>");
			                    
			                    for($i = 1; $i <= count($arr_mix['data']); $i++) {
			                        printf("<li><span>%s</span> %s</li>",$alphabet[$i-1],$arr_mix['data'][$i-1]);
			                    }

			                    print("</ul></td>");
							}
							if($row_quest['type']==5){
								printf("<td><input type='text' name='%s'></td><td></td>",$row_quest['id_q']);
							}
							print("</tr>");
							$iteration++;
						}
					?>
				</table>
				<?php if($flag_contr == 0): ?>
				<input type="submit" name="sbm" value="Завершить тест">
				<?php else: ?>
				<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
				<h2>Вы уже проходили тестовое ДЗ по данному уроку в одной из языковых версий</h2>
				<?php else: ?>
				<h2>Ви вже склали тестове ДЗ до цього уроку в одній з мовних версій</h2>
				<?php endif; ?>
				<input type="submit" name="sbm" value="Завершить тест" disabled>
				<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body>
</html>