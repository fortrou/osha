<?php
    $alphabet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К");
    $abc = array("a","b","c","d","e","f","g","h","i","j");
    session_start();
    require_once('../tpl_php/autoload.php');
    require_once('../tpl_php/functions.php');
    

$flag_contr = 0;
$locked = 0;
// relocate if test opened in new tab
$test_away = 0;
$year_num = get_currentYearNum();
$options = new Options();
$date_end = $options->get_option('semester_end_date');
$semester = $options->get_option('semester_current_number');
//print($date_end);
$end_timestamp	   = strtotime($date_end);
$current_timestamp = time();
$result_timestump  = $end_timestamp - $current_timestamp;
$date_plus_two	   = $end_timestamp+60*60*24*2;

if(!isset($_GET['id']))
	header("Location:index.php");
	
	$sql_user_data = sprintf("SELECT * FROM os_users WHERE id = %s", $_SESSION['data']['id']);
    /*echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";*/
    //if(!isset($_SESSION['testGet'])) {
	    $testId = $_GET['id'];
	/*} else {
		$test_away = 1;
	}*/

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
		/*else{
			print("Данного теста пока нет на другом языке");
		}*/
	}
    $sql_subj = sprintf("SELECT * FROM os_subjects WHERE id IN (SELECT DISTINCT subject FROM os_lessons WHERE id IN(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='%s'))",
    	$_GET['id']);
    //print("$sql_subj<br>");
    $res_subj = $mysqli->query($sql_subj);
    $row_subj = $res_subj->fetch_assoc();

    $keywords = sprintf("тест, школа, онлайн, пройти, предмет, урок, контроль, %s",$row_subj['name_ru']); 
    $sql = "SELECT * FROM os_tests WHERE id='".$_GET['id']."'";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();
    if ($row['type'] == 5) {
    	$sql = sprintf("SELECT * FROM os_journal WHERE id_s='%s' 
    		AND id_l IN(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='%s' AND id_lesson IN (
    		SELECT id FROM os_lessons WHERE lesson_year = $year_num))",$_SESSION['data']['id'],$testId);
    	
		$res = $mysqli->query($sql);
		if($res->num_rows != 0){
			$row = $res->fetch_assoc();
			if($row['is_completed'] != 0){
				//header("Location:".$_SERVER['HTTP_REFERER']);
				$flag_contr = 1;
			}
			else{
				$flag_contr = 0;
			}
			if($row['is_first'] == 1){
				//header("Location:".$_SERVER['HTTP_REFERER']);
				$flag_first = 1;
			}
			else{
				$flag_first = 0;
			}
			$j_id = $row["id"];
		}
    }
    else{
    	$flag_test = 4;
    }
    //var_dump($flag_contr);
    $sql_test = sprintf("SELECT * FROM os_lesson_test WHERE id_test='%s'",
    	$_GET['id']);
    $res_test = $mysqli->query($sql_test);
    $row_test = $res_test->fetch_assoc();
    $id_lesson = $row_test["id_lesson"];
    $type_test = $row_test["type"];

    $sql_class = sprintf("SELECT * FROM os_class_manager WHERE id IN(SELECT DISTINCT class FROM os_lessons WHERE id IN(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='%s'))",$_GET['id']);
    $res_class = $mysqli->query($sql_class);
    $row_class = $res_class->fetch_assoc();
    $desc = "";
		if ($row_test['type'] == 5) {
			$type = "Контрольное";
		}
		if ($row_test['type'] == 4) {
			$type = "Тренировочное";
		}
		$desc = sprintf("$type тестирование по предмету %s. Создано для класса %s на сайте http://online-shkola.com.ua",$row_subj['name_ru'],$row_class['class_name']);
		if ($row_test['type'] == 5) {
			$type = "Контрольне";
		}
		if ($row_test['type'] == 4) {
			$type = "Тренувальне";
		}
		$desc .= sprintf("$type тестування з предмету %s. Створено для класу %s на сайті http://online-shkola.com.ua",$row_subj['name_ru'],$row_class['class_name']);
//print($desc);
	$sql_test = "SELECT * FROM os_tests WHERE id='$testId'";
    $result = $mysqli->query($sql_test);
	$row_test = $result->fetch_assoc();
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru") {
		$array_translates = array( "try_later_test" => "Если у вас открыт еще один тест в другой вкладке, то нажмите Ок и закройте его. Если нет, то нажмите Ок, подождите 15 секунд и попробуйте снова"
								 );
	} else {
		$array_translates = array( "try_later_test" => "Якщо у вас відкрито ще один тест в іншій вкладці, натисніть кнопку Ок і закрийте його. Якщо ні, то натисніть Ок, зачекайте 15 секунд і спробуйте знову"
								 );
	}
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta name="keywords" content="<?php print($keywords); ?>" />
		<meta name="description" content="<?php print($desc); ?>" />
	<title>Прохождение теста: <?php print($row_test['tName']) ?> - Онлайн школа Альтернатива</title>
	<?php require_once('../tpl_blocks/head.php'); ?>
	</head>

	<body>
		<?php
			if(isset($_SESSION['data'])){
				include ("../tpl_blocks/header.php");
			}
			else{
				include ("../test_access/head2.php");
			}
		?>
		<div class="content"> 
				<?php if(!isset($_SESSION['data'])): ?>
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
		<?php endif; ?>
		<?php if(isset($flag_first) && isset($type_test) && $flag_first == 1 && $type_test == 5): ?>
			<input type="hidden" name="is_first" value="1">
		<?php endif; ?>

		<?php
			/*if($_SESSION["data"]["level"] == 1){
		        $sql = sprintf("SELECT * FROM os_lessons WHERE class=(SELECT class FROM os_users WHERE id='%s') AND subject IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",
		            $_SESSION["data"]["id"],$_SESSION["data"]["id"]);
		        $res = $mysqli->query($sql);
		        //print("<br>$sql<br>");
		        while ($row = $res->fetch_assoc()) {
		            $sql_journal = sprintf("SELECT * FROM os_journal WHERE id_s='%s' AND id_l='%s'",$_SESSION["data"]["id"],$row["id"]);
		            $res_journal = $mysqli->query($sql_journal);
		            //print($sql_journal);
		            if ($res_journal->num_rows == 0) {
		                //print("<br>incorrect<br>");
		                if ($row["is_control"] == 0) {
		                    $status = 1;
		                }
		                else{
		                    $status = 3;
		                }
		                $date_ru = explode(" ",$row["date_ru"]);
		                $date_ua = explode(" ",$row["date_ua"]);
		                $sql_create = sprintf("INSERT INTO os_journal(id_s,id_l,date_ru,date_ua,status,id_subj,title_t_ru,title_t_ua,test_contr) VALUES(%s,%s,'%s','%s',$status,%s,'','','')",
		                    $_SESSION["data"]["id"],$row["id"],$date_ru[0],$date_ua[0],$row["subject"]);
		                $res_create = $mysqli->query($sql_create);
		                //print("<be>$sql_create<br>");
		            }
		        }
		    }*/
		?>
		<div class="block0">
			<div class="testes">
				<!--<h1>Тренировочный тест</h1>-->
				<?php if($flag_test == 4): ?>
				<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
					<h2>Этот тест является тренировочным, 
						его можно проходить любое количество раз. 
						Оценка за данный тест не учитывается при 
						расчете тематических оценок и не влияет 
						на оценки в табеле и на аттестат</h2>
				<?php else: ?>
					<h2>Це тренувальний тест, 
						його можна проходити будь-яку 
						кількість разів. Оцінка за даний 
						тест не враховується при розрахунку 
						тематичних оцінок і не впливає на 
						оцінки в табелі та на атестат</h2>
				<?php endif; ?>
				<?php endif; ?>
				<div class="names">
					<?
						
						$sql_names1 = sprintf("SELECT * FROM os_lessons 
							WHERE id IN(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='$testId') AND lesson_year = $year_num",$_COOKIE['lang']);
						$sql_names2 = sprintf("SELECT name_".$_COOKIE['lang']." AS name FROM os_subjects WHERE id IN (SELECT subject FROM os_lessons 
							WHERE id IN(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='$testId') AND lesson_year = $year_num)");
						//print("<br>$sql_names1<br>");
						//print("<br>$sql_names2<br>");
						$res_names1 = $mysqli->query($sql_names1);
						$res_names2 = $mysqli->query($sql_names2);
						$row_names1 = $res_names1->fetch_assoc();
						$row_names2 = $res_names2->fetch_assoc();

						if($_SESSION['data']['class'] != 11 && $_SESSION['data']['edu_type'] == 1) {
							$lock_result = control_semester($row_names1['date_ua']);
							if(!$lock_result)
								$locked = 1;
						}
						//print("<br>$locked<br>");
					?>
					<span><?php print($row_names2['name']); ?></span><br>
					<span><?php print($row_names1['title_' . $_COOKIE['lang']]); ?></span>
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
				<table>
					<?php
					if($_GET['id'] != 0 && $test_away != 1){
						$iteration = 1;
						$sql_quest = "SELECT * FROM os_test_quest WHERE id_test='$testId'";
						$res_quest = $mysqli->query($sql_quest);
						while($row_quest = $res_quest->fetch_assoc()){
							if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"){
			                	printf("<tr><td colspan='3'>Вопрос №%s ( %s балл(-а/-ов)): %s </td></tr>",$iteration,$row_quest['cost'],$row_quest['name']);
			                } else {
			                	printf("<tr><td colspan='3'>Питання №%s ( %s бал(-и/-ів)): %s </td></tr>",$iteration,$row_quest['cost'],$row_quest['name']);
			                }
							
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
			                    	/*$temp_val = rtrim($arr_mix['data'][$i-1],"</p>");
			                    	print("<br>$temp_val<br>"); */
			                    	
			                        printf("<li class='bukva_%s'><input type='radio' class='radio' name='%s' value='%s'> <label>%s</label></li>",
			                        	$abc[$i-1],$qid,$arr_mix['id'][$i-1],Quest::strip_conors($arr_mix['data'][$i-1]));
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
			                        printf("<li class='bukva_%s'><input type='checkbox' class='checkbox' name='%s%s'> <label>%s</label></li>",
			                            $abc[$i-1],$qid,$arr_mix['id'][$i-1],Quest::strip_conors($arr_mix['data'][$i-1]));
			                    }
			                    print("</ul></td>");
			                    print("<td></td>");
							}
							if($row_quest['type']==3){
								
			                    $qid = $row_quest['id_q'];
			                    $arr_mix = Quest::mix_1_data($qid);

			                    $sql = "SELECT * FROM os_test_answs WHERE id_quest='$qid'";
								$res = $mysqli->query($sql);
								$num = $res->num_rows;
			                    //print("<br>$num<br>");
			                    print("<table><tr><td style='vertical-align: top;
    min-width: 136px;
    padding: 30px 10px 0;'><ul style='list-style:none;' class='test_ul_sootv'>");
			                    /*
			                    for($i = 1; $i <= count($arr_mix['data']); $i++) {
			                        printf("<li  class='bukva_%s'><span>%s</span> %s</li>",$alphabet[$i-1],$arr_mix['data'][$i-1]);
			                    }
			                    */
			                    for($i = 1; $i <= count($arr_mix['data']); $i++) {
			                        printf("<li  class='bukva_%s'>%s</li>",$abc[$i-1],$arr_mix['data'][$i-1]);
			                    }

			                    print("</ul></td>");
			                    /*print("<td><ul style='list-style:none;'>");
			                    while($row = $res->fetch_assoc()){
			                        printf("<li>%s</li>",$row['answer']);
			                    }
			                    print("</ul></td>");*/
			                    print("<td style='vertical-align: top;
    min-width: 200px;
    padding: 0 10px;'>
			                    <ul style='list-style:none;'>");
			                        
			                    print("<li>
			                        <ul class='matchRadio'>");
			                        printf("<li style='width:36px;'></li>");
			                        
			                        for($it = 0; $it < $num; $it++){
			                            printf("<li>%s</li>",$alphabet[$it]);
			                        }
			                        print("</ul>
			                        <div style='clear:both;'></div>
			                    </li>");
			                        for($i = 1; $i <= $num; $i++){
			                            print("<li>
			                            <ul class='matchRadio'>");
			                            printf("<li style='width:30px;'><span>%s</span></li>",$i);
			                            for($it = 1; $it <= $num; $it++){
											$val = $arr_mix['id'][$it-1];
											$let = $abc[$it-1];
			                                printf("<li><input type='radio' class='radio' name='%s%s' value='%s'><label></label></li>",$qid,$arr_mix['id'][$it-1],$i);
			                            }
			                            print("</ul>
			                            <div style='clear:both;'></div>
			                            </li>");
			                        }
			                    
			                    print("</ul></td></tr></table>");
							}
							if($row_quest['type']==4){
			                    print("<td><table><tr><td style='vertical-align: top;min-width: 200px;
    padding: 0 10px;'><ul style='list-style:none;' class='test_ul_sootv'>");
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
			                        printf("<li class='bukva_a$num_el' >%s</li>",$row['answer']);
			                        $num_el++;
			                    }
			                                    
			                                print("</ul></td>");
							print("<td style='vertical-align: top;min-width: 200px;
    padding: 0 10px;'><ul style='list-style:none;' class='test_ul_sootv'>");
			                    
			                    for($i = 1; $i <= count($arr_mix['data']); $i++) {
			                        printf("<li class='bukva_%s'>%s</li>",$abc[$i-1],$arr_mix['data'][$i-1]);
			                    }

			                    print("</ul></td></tr></table>");
								
								
			                                print("<ul style='list-style:none;'>");
			                                    
			                                print("<li>
			                                    <ul class='matchRadio'>");
			                                    printf("<li style='width:6px;'></li>");
			                                    
			                                    for($it = 0; $it < $num_horiz; $it++){
			                                        printf("<li>%s</li>",$alphabet[$it]);
			                                    }
			                                    print("</ul>
			                                    <div style='clear:both;'></div>
			                                </li>");
			                                    for($i = 1; $i <= $num; $i++){
			                                        print("<li>
			                                        <ul class='matchRadio class_sootvetst'>");
			                                        printf("<li style='width:17px;margin-right: 14px;'><span>%s</span></li>",$i);
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
							}
							if($row_quest['type']==5){
								printf("<td><input type='text' name='%s'></td><td></td>",$row_quest['id_q']);
							}
							print("</tr>");
							$iteration++;
						}
					}
					else{
						if($test_away == 1) {
							print('<h1>Вы уже проходите другой тест, завершите его попробуйте пройти снова. 
								<br> <a href="../schedule/calendar.php">Перейти в календарь</a></h1>');
						} else {
							print("данного теста не существует");
						}
					}
					?>
				</table>
				
				<?php if($flag_contr == 0 && $locked == 0): ?>
				<div class="end">
					<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
						<input type="submit" name="sbm" value="Завершить тест">
					<?php else: ?>
						<input type="submit" name="sbm" value="Завершити тест">
					<?php endif; ?>
				</div>
				<?php else: ?>
					<?php if($type_test == 5): ?>
						<?php
							if($flag_contr != 0) {
								if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
									print("<h2>Вы уже проходили тестовое ДЗ по данному уроку в одной из языковых версий</h2>");
								}
								else {
									print("<h2>Ви вже склали тестове ДЗ до цього уроку в одній з мовних версій</h2>");
								}
							}
							if($locked == 1) {
								if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
									print("<h2>Период времени для прохождения теста окончен</h2>");
								} else {
									print("<h2>Період часу для проходження тесту закінчено</h2>");
								}
							}
						?>
					<?php else: ?>
					<div class="end">
						<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
							<input type="submit" name="sbm" value="Завершить тест">
						<?php else: ?>
							<input type="submit" name="sbm" value="Завершити тест">
						<?php endif; ?>
					</div>
					<?php endif; ?>
				<?php endif; ?>
				</form>
				
				<script type="text/javascript">
					window.onload = function(){
						function timer_up(){
							$("input[name = timer]").val((1*$("input[name = timer]").val()+1));
						}
						function set_nf(j_id){
							$.ajax({
								url : '../tpl_php/ajax/tests_scripts.php' ,
								method : 'POST' , 
								dataType : 'json' ,
								data : { j_id : j_id,
									flag : '1' } ,
								success : function ( data ) {
									
								}
							});
						}
						setInterval(timer_up,1000);
						
						var test_id = get_urlParam('id');
						var test_cookie = get_cookie('test_completing');
						if(test_cookie && test_cookie != null && test_cookie != test_id) {
							if(confirm("<?php echo $array_translates['try_later_test']; ?>")) {
								window.location.assign("http://online-shkola.com.ua/lessons/watch.php?id="+<?php print($id_lesson); ?>);
							} else {
								window.location.assign("http://online-shkola.com.ua/lessons/watch.php?id="+<?php print($id_lesson); ?>);
							}
						} else {
							$.ajax({
								url : '../tpl_php/ajax/tests_scripts.php' ,
								method : 'POST' , 
								dataType : 'json' ,
								data : { 
										 test_id: test_id,
								 		 flag : '2' } ,
								success : function ( data ) {
									
								}
							});
						}
						if($("input[name = is_first]").val() == 1){
							if (confirm("Этот тест засчитывается, как часть вашего домашнего задания.\
								Пройти его можно только один раз и результат сразу же будет автоматически перенесен в журнал.\
								Пересдать эту оценку будет нельзя. Вы готовы начать тестирование сейчас?")) {
									set_nf(<?php print($j_id); ?>);
							}
							else{
								set_nf(<?php print($j_id); ?>);
								window.location.assign("http://online-shkola.com.ua/lessons/watch.php?id="+<?php print($id_lesson); ?>);
							}
						}
							
							
						}
						$("input[name = sbm]").click(function(){
								$("input[name = sbm]").css("display","none");
								$(".end").append("<img width='50px' height='50px' src='../tpl_img/loading-01.gif'>");
							})
				</script>
				
			</div>
		</div>
	</div>
	<?php
		/*unset($_SESSION['data_collection']);
		unset($_SESSION['string_answs']);*/
		include ("../tpl_blocks/footer.php");
	?>
</body>
</html>