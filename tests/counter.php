<?php
session_start();
require_once("../tpl_php/autoload.php");
$db = Database::getInstance();
$mysqli = $db->getConnection();
$year_num = get_currentYearNum();
//$date = date("Y-m-d");
    $test_truth = 0;
/*if(!isset($_POST['sbm'])){
    header("Location:index.php");
} */   
    /**
     * $full_cost --- переменная, определяющая максимальную стоимость всего теста, состоящую из суммы баллов, указанных в цене вопросов
     * $_val_after_trans --- переменная, определяющая результат теста, состоящий из суммы баллов, указанных в цене вопросов, выведенный по пропорции
     * $max_truth --- максимальное количество правильных ответов в тесте
     * $collect_truth --- набранное количество правильных ответов в тесте
     * $complete_percent --- процент правильности выполнения теста
     * $school_mark --- оценка по 12-бальной шкале
     * $string_answs --- Строка с набором данных пользователем ответов
     * 
     **/
    if(isset($_SESSION['testGet']) && !empty($_SESSION['testGet'])) {
        $_SESSION['data_collection']['current_completed'] = $_SESSION['testGet'];
        /*unset($_SESSION['testGet']);
        unset($_SESSION['data_collection']);
        unset($_SESSION['string_answs']);*/
    }
    if(!isset($_SESSION['data_collection'])){
        $_SESSION['data_collection'] = array();
    }
    $full_cost = $_SESSION['data_collection']['full_cost'];
    $_val_after_trans = $_SESSION['data_collection']['_val_after_trans'];
    $max_truth = $_SESSION['data_collection']['max_truth'];
    $collect_truth = $_SESSION['data_collection']['collect_truth'];
    $complete_percent = $_SESSION['data_collection']['complete_percent'];
    $school_mark = $_SESSION['data_collection']['school_mark'];
    $string_answs = "";
    $test_truth = $_SESSION['data_collection']['test_truth'];
    if($_SESSION['string_answs']!=""){
        $string_answs = $_SESSION['string_answs'];
        //print($_SESSION['string_answs']);
    }
    /*$full_cost = "";
    $_val_after_trans = "";
    $max_truth = "";
    $collect_truth = "";
    $complete_percent = "";
    $school_mark = "";
    $string_answs = "";*/
    $testId = $_SESSION['data_collection']['current_completed'];
    //print_r($_SESSION['testGet']);
    
    
    if (isset($_POST['sbm']) && !isset($_GET['tab'])) {
        $_SESSION['data_collection']['timer'] = $_POST['timer']/60;
        $sql_quest = "SELECT DISTINCT * FROM os_test_quest WHERE id_test='$testId'";
        $res_quest = $mysqli->query($sql_quest);
        //var_dump($res_quest);
        while($row_quest = $res_quest->fetch_assoc()){
            /** 1 вариант ответа **/
            if($row_quest['type'] == 1){
                
                $tstr = sprintf("%s",$row_quest['id_q']);
                
                $qid = $row_quest['id_q'];
                
                if(!isset($_POST[$tstr])){
                    $answ = 0;
                }
                else{
                    $answ = $_POST[$tstr];
                }
                
                $sql = "SELECT * FROM `os_test_answs` WHERE correct=1 AND id_quest='$qid'";
                $res = $mysqli->query($sql);
                $row = $res->fetch_assoc();
                
                //var_dump($qid);
                
                $id_right = 999;
                $id_cnt = 1;
                /*while($row = $mysqli->fetch_assoc($res)){
                    if((int)$row['correct'] === 1){
                        $id_right = $id_cnt;
                    }
                    $id_cnt++;
                }*/
                //print("<br>$id_cnt<br>$answ<br>$id_right<br>");
                $full_cost += $row_quest['cost'];
                $max_truth++;
                
                /*var_dump($answ);
                print("<br>1 ");
                var_dump($row['id_a']);
                print("<br>2 ");*/

                if($answ == $row['id_a']){
                    $collect_truth++;
                    $test_truth+=$row_quest['cost'];
                    $_val_after_trans += $row_quest['cost'];
                }
                /*if($answ == $id_right){
                    $collect_truth++;
                    $test_truth+=(int)$row_quest['cost'];
                    $_val_after_trans += $row_quest['cost'];
                    //var_dump($test_truth);
                }*/


                $_SESSION['data_collection']['test_truth'] = $test_truth;
                $_SESSION['data_collection']['_val_after_trans'] = $_val_after_trans; 
                $_SESSION['data_collection']['full_cost'] = $full_cost;
                $_SESSION['data_collection']['max_truth'] = $max_truth;
                $_SESSION['data_collection']['collect_truth'] = $collect_truth;
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
                 /*** Проверка количества правильных ***/
                    //printf("<br>%s<br>",$_SESSION['data_collection']['test_truth']);

                 /*** Проверка количества правильных ***/
                $str = sprintf("%s*-=+=-*1*-=+=-*$answ",$qid);
                //print("<br>$str<br>");
                //var_dump($str);
                $string_answs .= $str.";;";
                $str = "";
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
            }
            /** 1 вариант ответа **/
            
            /** мультивыбор **/
            if($row_quest['type'] == 2){
                $qid = $row_quest['id_q'];
                $sql = "SELECT * FROM `os_test_answs` WHERE id_quest='$qid'";
                $res = $mysqli->query($sql);
                $sql1 = "SELECT COUNT(*) FROM `os_test_answs` WHERE correct=1 AND id_quest='$qid'";
                $res1 = $mysqli->query($sql1);
                $row1 = $res1->fetch_assoc();
                
                $ra_cnt = 0;
                
                $num_rows = $res->num_rows;
                $arr_right = array();
                $arr_select = array();
                
                $id_right = 999;
                $id_cnt = 1;
                while($row = $res->fetch_assoc()){
                    if((int)$row['correct'] === 1){
                        $arr_right[] = $qid.''.$row['id_a'];
                    }
                    $tstr = sprintf("%s%s",$qid,$row['id_a']);
                    if(isset($_POST[$tstr])){
                        $arr_select[] = $tstr;
                    }
                    $id_cnt++;
                }
                
                    
                

                for($i = 0; $i < count($arr_select);$i++){
                    if(in_array($arr_select[$i],$arr_right)){
                        $ra_cnt++;
                        $collect_truth++;
                        $test_truth+=$row_quest['cost'];
                        //var_dump($test_truth);
                    }
                }
                
                $full_cost += $row_quest['cost'];
                $_val_after_trans += $row_quest['cost']/$row1['COUNT(*)']*$ra_cnt;
                $max_truth += $row1['COUNT(*)'];
                
                $_SESSION['data_collection']['test_truth'] = $test_truth;
                $_SESSION['data_collection']['_val_after_trans'] = $_val_after_trans;
                $_SESSION['data_collection']['full_cost'] = $full_cost;
                $_SESSION['data_collection']['max_truth'] = $max_truth;
                $_SESSION['data_collection']['collect_truth'] = $collect_truth;
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
                 /*** Проверка количества правильных ***/
                    //printf("<br>%s<br>",$_SESSION['data_collection']['test_truth']);

                 /*** Проверка количества правильных ***/
                $str_sel = "";
                for($i = 0; $i < count($arr_select);$i++){
                    if(isset($arr_select[$i+1]))
                        $str_sel .= $arr_select[$i]."$-$";
                    else
                        $str_sel .= $arr_select[$i];
                }
                //print("<br>$str_sel<br>");
                $str = sprintf("%s*-=+=-*2*-=+=-*$str_sel",$qid);
                $string_answs .= $str.";;";
                $str = "";
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
            }
            /** мультивыбор **/
            
            /** Последовательность **/
            if($row_quest['type'] == 3){
                $qid = $row_quest['id_q'];
                $sql = "SELECT * FROM `os_test_answs` WHERE id_quest='$qid'";
                $res = $mysqli->query($sql);
                
                $num_answs = $res->num_rows;
                $num_el = 1;
                
                $ra_cnt = 0;
                $el = 9999;
                $arr_select = array();
                
                while($row = $res->fetch_assoc()){
                    $tstr = sprintf("%s%s",$qid,$row['id_a']);
                    if(!isset($_POST[$tstr])){
                        $el = 0;
                        $arr_select[] = 0;
                    }
                    else{
                        $el = $_POST[$tstr];
                        $arr_select[] = $_POST[$tstr];
                    }
                    if($el == (int)$row['correct']){
                        $ra_cnt++;
                        $collect_truth++;
                        $test_truth+=$row_quest['cost'];
                        //var_dump($test_truth);
                        //var_dump($row_quest['cost']);
                    }
                    //printf("<br> $num_el --- $el --- %s <br>",$row['correct']);
                    $num_el++;
                }
                $max_truth += $num_answs;
                $full_cost += $row_quest['cost'];
                $_val_after_trans += $row_quest['cost']/$num_answs*$ra_cnt;
                
                $_SESSION['data_collection']['test_truth'] = $test_truth;
                $_SESSION['data_collection']['_val_after_trans'] = $_val_after_trans;
                $_SESSION['data_collection']['full_cost'] = $full_cost;
                $_SESSION['data_collection']['max_truth'] = $max_truth;
                $_SESSION['data_collection']['collect_truth'] = $collect_truth;
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
                 /*** Проверка количества правильных ***/
                    //printf("<br>%s<br>",$_SESSION['data_collection']['test_truth']);

                 /*** Проверка количества правильных ***/
                $str_sel = "";
                for($i = 0; $i < count($arr_select);$i++){
                    if(isset($arr_select[$i+1]))
                        $str_sel .= $arr_select[$i]."$-$";
                    else
                        $str_sel .= $arr_select[$i];
                }
                //print("<br>$str_sel<br>");
                $str = sprintf("%s*-=+=-*3*-=+=-*$str_sel",$qid);
                $string_answs .= $str.";;";
                $str = "";
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
            }        
            /** Последовательность **/
            
            /** Соответствия **/
            if($row_quest['type'] == 4){

                $qid = $row_quest['id_q'];
                
                
                
                
                $sqlm = "SELECT * FROM os_test_matches WHERE id_quest='$qid' AND num IN
                (SELECT correct FROM `os_test_answs` WHERE id_quest='$qid')";
                $resm = $mysqli->query($sqlm);
                
                $num_matches = $resm->num_rows;
                
                $array_m = array();
                while ($rowm = $resm->fetch_assoc()) {
                    $sql = "SELECT * FROM `os_test_answs` WHERE id_quest='$qid' AND correct='".$rowm['num']."'";
                    $res = $mysqli->query($sql);
                    $row = $res->fetch_assoc();
                    $array_m[(int)$row['id_a']] = $rowm['id_ma'];
                }

                //var_dump($array_m);
                $num_el = 1;
                $ra_cnt = 0;
                $el = 9999;
                $arr_select = array();
                
                $sql = "SELECT * FROM `os_test_answs` WHERE id_quest='$qid'";
                $res = $mysqli->query($sql);
                $num_answs = $res->num_rows;
                while ($row = $res->fetch_assoc()) { 

                    $tstr = sprintf("%s%s",$qid,$num_el);
                    
                    if(!isset($_POST[$tstr])){
                        $el = 0;
                        $arr_select[] = 0;
                    }
                    else{
                        $el = $_POST[$tstr];
                        $arr_select[] = $_POST[$tstr];
                    }
                    if($array_m[(int)$row['id_a']] == $el){
                        $ra_cnt++;
                        $collect_truth++;
                        $test_truth+=$row_quest['cost'];
                        //var_dump($test_truth);
                        //var_dump($row_quest['cost']);
                    }
                    $num_el++;
                }
                
                $max_truth += $num_answs;
                $full_cost += $row_quest['cost'];
                $_val_after_trans += $row_quest['cost']/$num_answs*$ra_cnt;
                
                $_SESSION['data_collection']['test_truth'] = $test_truth;
                $_SESSION['data_collection']['_val_after_trans'] = $_val_after_trans;
                $_SESSION['data_collection']['full_cost'] = $full_cost;
                $_SESSION['data_collection']['max_truth'] = $max_truth;
                $_SESSION['data_collection']['collect_truth'] = $collect_truth;
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
                 /*** Проверка количества правильных ***/
                    //printf("<br>%s<br>",$_SESSION['data_collection']['test_truth']);

                 /*** Проверка количества правильных ***/
                $str_sel = "";
                for($i = 0; $i < count($arr_select);$i++){
                    if(isset($arr_select[$i+1]))
                        $str_sel .= $arr_select[$i]."$-$";
                    else
                        $str_sel .= $arr_select[$i];
                }
                //print("<br>$str_sel<br>");
                $str = sprintf("%s*-=+=-*4*-=+=-*$str_sel",$qid);
                $string_answs .= $str.";;";
                $str = "";
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
                
            }
            /** Соответствия **/
            /*** Краткий ответ ***/
            if($row_quest['type'] == 5){
                
                $tstr = sprintf("%s",$row_quest['id_q']);
                
                $qid = $row_quest['id_q'];
                
                if(!isset($_POST[$tstr])){
                    $answ = 0;
                }
                else{
                    $answ = $_POST[$tstr];
                }
                
                $sql = "SELECT * FROM `os_test_short_answ` WHERE id_quest='$qid'";
                //print("<br>$sql<br>");
                $res = $mysqli->query($sql);
                $row = $res->fetch_assoc();
                
                //var_dump($qid);
                
                $id_right = 999;
                $id_cnt = 1;
                /*while($row = $mysqli->fetch_assoc($res)){
                    if((int)$row['correct'] === 1){
                        $id_right = $id_cnt;
                    }
                    $id_cnt++;
                }*/
                //print("<br>$id_cnt<br>$answ<br>$id_right<br>");
                $full_cost += $row_quest['cost'];
                $max_truth++;
                
                /*var_dump($answ);
                print("<br>1 ");
                var_dump($row['id_a']);
                print("<br>2 ");*/

                if($answ == $row['answer']){
                    $collect_truth++;
                    $test_truth+=$row_quest['cost'];
                    $_val_after_trans += $row_quest['cost'];
                }
                /*if($answ == $id_right){
                    $collect_truth++;
                    $test_truth+=(int)$row_quest['cost'];
                    $_val_after_trans += $row_quest['cost'];
                    //var_dump($test_truth);
                }*/


                $_SESSION['data_collection']['test_truth'] = $test_truth;
                $_SESSION['data_collection']['_val_after_trans'] = $_val_after_trans; 
                $_SESSION['data_collection']['full_cost'] = $full_cost;
                $_SESSION['data_collection']['max_truth'] = $max_truth;
                $_SESSION['data_collection']['collect_truth'] = $collect_truth;
                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
                 /*** Проверка количества правильных ***/
                    //printf("<br>%s<br>",$_SESSION['data_collection']['test_truth']);

                 /*** Проверка количества правильных ***/
                $str = sprintf("%s*-=+=-*5*-=+=-*$answ",$qid);
                //print("<br>$str<br>");
                //var_dump($str);
                $string_answs .= $str.";;";
                $str = "";

                /*** Формируем строки для того, чтобы показать юзверу правильные и неправильные решения ***/
            }
            /*** Краткий ответ ***/
        }
        //print("<br>$string_answs<br>");
        $_SESSION['string_answs'] = $string_answs;


    //print($_SESSION['string_answs']);
if(isset($_SESSION['data'])){
    if ($_SESSION['data']['level'] == 1) {
        //var_dump($_SESSION['data_collection']['current_completed']);
        $sql = "SELECT DISTINCT * FROM os_lessons WHERE id IN (SELECT id_lesson FROM os_lesson_test WHERE id_test='" . 
        $_SESSION['data_collection']['current_completed'] . "') AND lesson_year = $year_num";
        //print("<br>$sql<br>");
            $res = $mysqli->query($sql);
            //var_dump($res);
            //$row = $res->fetch_assoc();
            //print("<br>");
            //var_dump($row);
            //print("<br>");
            $arr = explode(' ',$row['date']);

            $date = $arr[0];
            //var_dump($arr);
            //print("<br>");
            //var_dump($date);
        }
    if($_SESSION["data"]["level"] == 1){

        $sql = sprintf("SELECT * FROM os_lessons 
                                WHERE id 
                                   IN (
                                      SELECT id_lesson 
                                        FROM os_lesson_classes 
                                       WHERE id_class 
                                          IN (
                                             SELECT class 
                                               FROM os_users 
                                              WHERE id='%s')) 
            AND subject IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s') AND lesson_year = $year_num",
            $_SESSION["data"]["id"],$_SESSION["data"]["id"]);
        $res = $mysqli->query($sql);
        //print("<br>$sql<br>");
        while ($row = $res->fetch_assoc()) {
            $sql_journal = sprintf("SELECT * FROM os_journal WHERE id_s='%s' AND id_l='%s'",$_SESSION["data"]["id"],$row["id"]);
            $res_journal = $mysqli->query($sql_journal);
            //print("<br>$sql_journal<br>");
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
                //print("<br>$sql_create<br>");
            }
        }
        $sql_lesson_num = "SELECT id_lesson FROM os_lesson_test WHERE id_test=$testId AND id_lesson IN (SELECT id FROM os_lessons WHERE lesson_year = $year_num)";
        $res_lesson_num = $mysqli->query($sql_lesson_num);
        $row_lesson_num = $res_lesson_num->fetch_assoc();
        $sql = sprintf("SELECT * FROM os_journal WHERE id_s='%s' AND id_l='%s'",$_SESSION['data']['id'],$row_lesson_num["id_lesson"]);
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        //print("<br>$sql");
        if (!isset($_SESSION['data_collection']['test_truth']) || $_SESSION['data_collection']['test_truth'] == "") {
            $_SESSION['data_collection']['test_truth'] = 0;
        }
        if ($res->num_rows == 0) {
            $new_sql = "SELECT * FROM os_tests WHERE id='$testId'";
            $new_res = $mysqli->query($new_sql);
            $new_row = $new_res->fetch_assoc();
            $sql_les = sprintf("SELECT subject FROM os_lessons WHERE id='%s'",$row_lesson_num["id_lesson"]);
            $res_les = $mysqli->query($sql_les);
            $row_les = $res_les->fetch_assoc();
            switch ($new_row['type']) {
                case 4:
                    $sql_in = sprintf("INSERT INTO os_journal(id_s,id_l,mark_tr,id_subj) VALUES(%s,%s,%s,'%s')",
                    $_SESSION['data']['id'],$row_lesson_num["id_lesson"],$_SESSION['data_collection']['test_truth'],$row_les['subject']);
                    $res_in = $mysqli->query($sql_in);
                break;

                case 5:
                    $sql_in = sprintf("INSERT INTO os_journal(id_s,id_l,mark_contr,id_subj,test_contr,is_first,is_completed) VALUES(%s,%s,%s,'%s','%s',0,1)",
                    $_SESSION['data']['id'],$row_lesson_num["id_lesson"],$_SESSION['data_collection']['test_truth'],$row_les['subject'],$string_answs);
                    $res_in = $mysqli->query($sql_in);
                break;

            }

        }
        else{
            $new_sql = "SELECT * FROM os_tests WHERE id='$testId'";
            $new_res = $mysqli->query($new_sql);
            $new_row = $new_res->fetch_assoc();
            switch ($new_row['type']) {
                case 4:
                    $sql_in = sprintf("UPDATE os_journal SET mark_tr=%s WHERE id_s='%s' AND id_l='%s'",
                    $_SESSION['data_collection']['test_truth'],$_SESSION['data']['id'],$row_lesson_num["id_lesson"]);
                    $res_in = $mysqli->query($sql_in);
                break;

                case 5:
                if ($row["is_completed"] == 0) {
                    $sql_in = sprintf("UPDATE os_journal SET mark_contr=%s, test_contr='%s',is_first=0,is_completed=1 WHERE id_s='%s' AND id_l='%s'",
                    $_SESSION['data_collection']['test_truth'],$string_answs,$_SESSION['data']['id'],$row_lesson_num["id_lesson"]);
                    //print($sql_in);
                    $res_in = $mysqli->query($sql_in);
                }
                break;

            }
        }
    }
    /*print("<br><br>");
        print($sql_in);*/

    }
    //header("Location:counter.php");
}
        /*if(isset($_SESSION['user_data'])){
            $sql_j = sprintf("INSERT INTO journal(id_s,id_test,mark,maxMark,convB,dtime,tfa) VALUES(%s,%s,%s,%s,%s,now(),'%s')",
                $_SESSION['user_data']['id'],$testId,$collect_truth,$max_truth,$_SESSION['data_collection']['test_truth'],
                $_SESSION['string_answs']);
            //print("<br>$sql_j<br>");
            $res_j = $mysqli->query($sql_j);
        }*/
        //header("Location:".$_SERVER['REQUEST_URI']);    
    //print($_SESSION['data_collection']['test_truth']);
    //var_dump($collect_truth);
        $sql_subj = sprintf("SELECT * FROM os_subjects WHERE id = (SELECT DISTINCT subject FROM os_lessons WHERE id IN(SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='%s'))",
        $_GET['id']);
    $res_subj = $mysqli->query($sql_subj);
    $row_subj = $res_subj->fetch_assoc();

    $keywords = sprintf("тест, школа, онлайн, пройти, предмет, урок, контроль, %s",$row_subj['name_ru']); 
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta name="keywords" content="<?php print($keywords); ?>" />
    <title>Результат теста - ВнеШколы - образовательный портал</title>
   <?php require_once('../tpl_blocks/head.php'); ?>
   <style>
        #subj_list{
            list-style:none;
        }
        #subj_list li{
            float:left;
            width:95px;
        } 
    </style>
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
                <?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"): ?>
                <h1>Все материалы, которые вы видите, являются демонстрационными. Функции обучения в демонстрационном доступе ограничены.
                 Для получения полного доступа к нашей онлайн-школе зарегистрируйтесь на сайте и оплатите обучение<br>
                 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Оплатить обучение</a></h1>
                <?php else: ?>
                <h1>Усі матеріали, які ви бачите, є демонстраційними. Функції навчання в демонстраційному доступі
                 обмежені. Для одержання повного доступу до нашої онлайн-школи зареєструйтесь на сайті і оплатіть навчання<br>
                 <a href="http://online-shkola.com.ua/auth_log.php?type=1">Сплатити за навчання</a></h1>
                <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="block0">
		<?php
            if ($_SESSION['data']['level'] == 1) {
                    $sql_class = sprintf("SELECT * FROM os_class_manager WHERE id='%s'",$_SESSION['data']['class']);
                    $res_class = $mysqli->query($sql_class);
                    $row_class = $res_class->fetch_assoc();

                    $sql_test_data = "SELECT * FROM os_tests WHERE id=$testId";
                    //print($sql_test_data);
                    $res_test_data = $mysqli->query($sql_test_data);
                    $row_test_data = $res_test_data->fetch_assoc();
                    printf("<h3 class='fio_print' style='display:none;'>%s %s %s</h3>
                        <h2 class='fio_print' style='display:none;'>%s</h2>
                        <h2 class='fio_print' style='display:none;'>Тип теста: %s</h2>",
                        $_SESSION['data']['surname'],$_SESSION['data']['name'],$_SESSION['data']['patronymic'],$row_class['class_name'], $row_test_data['name']);
                }
        ?>
		<div class="rez_teste">
    <?php

        $complete_percent = round($collect_truth/$max_truth*100,1);
        $school_mark = round($collect_truth/$max_truth*12,1);
        $_val_after_trans = round($_val_after_trans,2);
    ?>
    <?
        $mta = explode("/",$_SERVER['DOCUMENT_ROOT']);
        $mtel = $mta[count($mta) - 2];

        $sql_mb = "SELECT * FROM os_tests WHERE id='$testId'";
        //var_dump($sql_mb);
        $res_mb = $mysqli->query($sql_mb);
        $row_mb = $res_mb->fetch_assoc();
        
        $arr1 = explode($row_mb['markBy'],";");
        foreach ($arr1 as $value) {
            $arr2 = explode($value,"=");
            if ($arr2[0] == $_SESSION['data_collection']['test_truth'] && $arr2[1]!="") {
                $mark = $arr2[1];
                break;
            }
            else{
                $mark = "";
            }
        }
        if ($row_mb['markBy'] == "") {
            $mark = "";
        }
        
        $max_convert = 0;

        $sql_fc = "SELECT * FROM os_test_quest WHERE id_test='$testId'";
        $res_fc = $mysqli->query($sql_fc);
        //var_dump($res_fc);
                while($row_fc = $res_fc->fetch_assoc()){
                    switch ((int)$row_fc['type']) {
                        case 1:
                            $max_convert += $row_fc['cost'];
                            //var_dump($row_fc['cost']);
                            //print("<br>$max_convert<br>");
                            break;
                        case 2:
                            $sql = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s AND correct=1",$row_fc['id_q']);
                            $result = $mysqli->query($sql);
                            $row_loc = $result->fetch_assoc();
                            //var_dump($row_fc['cost']);
                            //var_dump($row_loc['count(id_a)']);
                            $max_convert += (int)$row_loc['count(id_a)']*(int)$row_fc['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 3:
                            $sql = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row_fc['id_q']);
                            $result = $mysqli->query($sql);
                            $row_loc = $result->fetch_assoc();
                            $max_convert += (int)$row_loc['count(id_a)']*(int)$row_fc['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 4:
                            $sql = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row_fc['id_q']);
                            $result = $mysqli->query($sql);
                            $row_loc = $result->fetch_assoc();
                            $max_convert += (int)$row_loc['count(id_a)']*(int)$row_fc['cost'];
                            //print("<br>$max_convert<br>");
                            break;
                        case 5:
                            $max_convert += $row_fc['cost'];
                            //print("<br>$max_convert<br>");
                            break;

                    }
                }
        //print($max_convert);


    ?>  
        
        <?php
        $date = date("Y-m-d H:i");
        $sql_ti = "SELECT * FROM os_lesson_test WHERE id_test='" . $_SESSION['data_collection']['current_completed'] . "'";
        //print("<br>$sql_ti<br>");
        $res_ti = $mysqli->query($sql_ti);
        $row_ti = $res_ti->fetch_assoc();
        
            $sql = "SELECT DISTINCT * FROM os_lessons WHERE id IN (SELECT DISTINCT id_lesson FROM os_lesson_test WHERE id_test='" .
             $_SESSION['data_collection']['current_completed'] . "')  
            AND lesson_year = $year_num";
            //print("<br>$sql<br>");
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();
            $sql_subject = "SELECT * FROM os_subjects WHERE id = '".$row['subject']."'";
            $res_subject = $mysqli->query($sql_subject);
            $row_subject = $res_subject->fetch_assoc();
            if($row_ti['type']==4){
                if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"){
                    print("<h3>Этот тест является тренировочным, его можно проходить любое количество
                     раз. Оценка за данный тест не учитывается при расчете тематических оценок и 
                     не влияет на оценки в табеле и на аттестат</h3>");
                } else {
                    print("<h3>Це тренувальний тест, його можна проходити будь-яку кількість 
                        разів. Оцінка за даний тест не враховується при розрахунку тематичних оцінок і 
                        не впливає на оцінки в табелі та на атестат</h3>");
                }
            }
            if($row_ti['type']==5){
                if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] =="ru"){
                    print("<h3>Если к этому уроку прикреплено еще творческое ДЗ, то оценка за эту
                     тестовую часть ДЗ является не окончательной. Когда учитель проверит творческое 
                     ДЗ, то вы сможете увидеть в своем журнале суммарную оценку</h3>");
                } else {
                    print("<h3>Якщо до цього уроку прикріплено ще творче ДЗ, то оцінка за цю 
                        тестову частину ДЗ є не остаточною. Коли вчитель перевірить творче ДЗ, 
                        то ви зможете побачити в своєму журналі сумарну оцінку</h3>");
                }
            }
            if($_COOKIE['lang'] == 'ru') {
                printf("<h2 class='test_res_zaf'>Предмет: %s</h2>",$row_subject['name_ru']);
                printf("<h2 class='test_res_zaf'>Тест к уроку: %s</h2>",$row['title_'.$_COOKIE['lang']]);
            } else {
                printf("<h2 class='test_res_zaf'>Предмет: %s</h2>",$row_subject['name_ua']);
                printf("<h2 class='test_res_zaf'>Тест до уроку: %s</h2>",$row['title_'.$_COOKIE['lang']]);
            }
            
            if(!isset($_SESSION['data_collection']['test_truth']) || $_SESSION['data_collection']['test_truth'] == 0 || $_SESSION['data_collection']['test_truth'] == "" ){
                $_SESSION['data_collection']['test_truth'] = 0;
            }
        ?>
    <table id="test_res" class="test_res">
        <!--<tr>
            <td>Правильно решено</td>
            <td><?print($collect_truth." ".$complete_percent."% из ".$max_truth);?></td>
        </tr>-->  
        
        <?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
            <tr>
                <td>Дата прохождения</td>
                <td><?print($date);?></td>
            </tr>
            
            <tr>
                <td>Время, затраченное на прохождение:</td>
                <td> <?php print(round($_SESSION['data_collection']['timer'],1)." min"); ?></td>
            </tr>
            
            <tr>
                <td>Набранный балл:</td>
                 <td><?printf("%s",$_SESSION['data_collection']['test_truth']);?></td>
            </tr>
            
            <tr>
                <td>Максимум баллов:</td>
                <td><?printf("%s",$max_convert);?></td>
            </tr>
        <?php else: ?>
            <tr>
                <td>Дата виконання</td>
                <td><?print($date);?></td>
            </tr>
            
            <tr>
                <td>Час, який витрачено на виконання:</td>
                <td> <?php print(round($_SESSION['data_collection']['timer'],1)." min"); ?></td>
            </tr>
            
            <tr>
                <td>Набраний бал:</td>
                 <td><?printf("%s",$_SESSION['data_collection']['test_truth']);?></td>
            </tr>
            
            <tr>
                <td>Максимальний бал:</td>
                <td><?printf("%s",$max_convert);?></td>
            </tr>
        <?php endif; ?>
        
		
		
		 

    </table>
    <div class="clear"></div> 
    <div class="test_res_btn">
            <?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
                <?php
                    if($row_ti['type'] == 4)
                        printf("<a href='completing.php?id=%s'>Пройти еще раз</a>", $_SESSION['data_collection']['current_completed']);
                ?>
                <a href="counter.php?tab=1">Правильные ответы</a>
                
                <a href="counter.php?tab=2">Показать подсказки </a>
                <a href="#" onclick="window.print()">Печатать результаты</a>
            <?php else: ?>
                <?php
                    if($row_ti['type'] == 4)
                        printf("<a href='completing.php?id=%s'>Скласти ще раз</a>", $_SESSION['data_collection']['current_completed']);
                ?>
                <a href="counter.php?tab=1">Правильні відповіді</a>
                
                <a href="counter.php?tab=2">Показати підказки</a>
                <a href="#" onclick="window.print()">Друк результатів</a>
            <?php endif; ?>
            
            
</div>
                    <?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
                        <div class="test_res_btn" style="    margin: 30px auto 0; width: 240px; text-align: center;"><a href="../lessons/watch.php?id=<?=$row['id']?>">Вернуться к уроку</a> 
                        </div>
                    <?php else: ?>
                        <div class="test_res_btn" style="    margin: 30px 380px 0; width: 260px; text-align: center;"><a href="../lessons/watch.php?id=<?=$row['id']?>">Повернутися до уроку</a> 
                        </div>
                    <?php endif; ?>
    
            
    <?php

        if($_GET['tab'] == 1){
            print("<table class='tests_table'>");
            //print($_SESSION['string_answs']);
            $arr_quests = explode(";;",$_SESSION['string_answs']);
            
            /**
             * Теперь разбиваем каждый вопрос на 3 составляющие:
             * Вопрос(строка)
             * Тип вопроса, от 1 до 5
             * Строка, в которой хранятся выбранные пользователем ответы
             * *-=+=-* - разделитель
             *
             * разбиваются вопросы в цикле, чтобы пройти сразу по всему массиву вопросов
            **/
            
            foreach($arr_quests as $value){
                //print("<br>$value<br>");
                $arr_data = explode("*-=+=-*",$value);
                //var_dump($arr_data);
                if($arr_data[1]!=1){
                    $multip_answs_arr = explode("$-$",$arr_data[2]);

                }
                if($arr_data[1] == 1){
                    QuestOver::first_type($arr_data[0], $arr_data[2]);
                }
                if($arr_data[1] == 2){
                    QuestOver::second_type($arr_data[0], $multip_answs_arr);
                }
                if($arr_data[1] == 3){
                    //  var_dump($multip_answs_arr);
                    QuestOver::third_type($arr_data[0], $multip_answs_arr);
                }
                if($arr_data[1] == 4){
                    QuestOver::forth_type($arr_data[0], $multip_answs_arr);
                }
                if($arr_data[1] == 5){
                    QuestOver::fifth_type($arr_data[0], $multip_answs_arr);
                }
                
            }
            print("</table>");
        }
        if($_GET['tab'] == 2){
            print("<table class='tests_table'>");
            //print($_SESSION['string_answs']);
            $arr_quests = explode(";;",$_SESSION['string_answs']);
            
            /**
             * Теперь разбиваем каждый вопрос на 3 составляющие:
             * Вопрос(строка)
             * Тип вопроса, от 1 до 5
             * Строка, в которой хранятся выбранные пользователем ответы
             * *-=+=-* - разделитель
             *
             * разбиваются вопросы в цикле, чтобы пройти сразу по всему массиву вопросов
            **/
            
            foreach($arr_quests as $value){
                //print("<br>$value<br>");
                $arr_data = explode("*-=+=-*",$value);
                //var_dump($arr_data);
                $sql_comment = sprintf("SELECT full_desc FROM os_test_quest WHERE id_q='%s'",$arr_data[0]);
                $res_comment = $mysqli->query($sql_comment);
                $row_comment = $res_comment->fetch_assoc();
                if($arr_data[1]!=1){
                    $multip_answs_arr = explode("$-$",$arr_data[2]);
                }
                if($arr_data[1] == 1){
                    QuestOver::first_type($arr_data[0], $arr_data[2]);
                    printf("<br>%s<br>",$row_comment['full_desc']);
                    if(trim($row_comment['full_desc']) != ""){
                        printf("<tr class='comment'>
                            <td colspan='3'>
                                <span>%s</span>
                            </td>
                        </tr>",$row_comment['full_desc']);
                    }
                    else{
                        print("<tr class='comment'>
                            <td colspan='3'>
                                <span>Нет подсказок</span>
                            </td>
                        </tr>");
                    }
                }
                if($arr_data[1] == 2){
                    QuestOver::second_type($arr_data[0], $multip_answs_arr);
                    if(trim($row_comment['full_desc']) != ""){
                        printf("<tr class='comment'>
                            <td colspan='3'>
                                %s
                            </td>
                        </tr>",$row_comment['full_desc']);
                    }
                    else{
                        print("<tr class='comment'>
                            <td colspan='3'>
                                Нет подсказок
                            </td>
                        </tr>");
                    }
                }
                if($arr_data[1] == 3){
                    //  var_dump($multip_answs_arr);
                    QuestOver::third_type($arr_data[0], $multip_answs_arr);
                    if(trim($row_comment['full_desc']) != ""){
                        printf("<tr class='comment'>
                            <td colspan='3'>
                                %s
                            </td>
                        </tr>",$row_comment['full_desc']);
                    }
                    else{
                        print("<tr class='comment'>
                            <td colspan='3'>
                                Нет подсказок
                            </td>
                        </tr>");
                    }
                }
                if($arr_data[1] == 4){
                    QuestOver::forth_type($arr_data[0], $multip_answs_arr);
                    if(trim($row_comment['full_desc']) != ""){
                        printf("<tr class='comment'>
                            <td colspan='3'>
                                %s
                            </td>
                        </tr>",$row_comment['full_desc']);
                    }
                    else{
                        print("<tr class='comment'>
                            <td colspan='3'>
                                Нет подсказок
                            </td>
                        </tr>");
                    }
                }
                if($arr_data[1] == 5){
                    QuestOver::fifth_type($arr_data[0], $multip_answs_arr);
                    if(trim($row_comment['full_desc']) != ""){
                        printf("<tr class='comment'>
                            <td colspan='3'>
                                %s
                            </td>
                        </tr>",$row_comment['full_desc']);
                    }
                    else{
                        print("<tr class='comment'>
                            <td colspan='3'>
                                Нет подсказок
                            </td>
                        </tr>");
                    }
                }
            }

            print("</table>");
            /*$sql_quest = "SELECT * FROM os_test_quest WHERE id_test='$testId'";
            $res_quest = $mysqli->query($sql_quest);
            //var_dump($res_quest);
            while($row_quest = $res_quest->fetch_assoc()){
                printf("<div><ul style='list-style:none;'>
                <li>%s</li>",$row_quest['name']);
                if(trim($row_quest['full_desc']) != ""){
                    printf("<li><a href='testansw.php?id=%s'>Перейти на страницу с разбором вопроса</a></li>",$row_quest['id_q']);
                }
                else{
                    printf("<li>Страницы с разбором вопроса не существует</li>");
                }
                print("</ul></div>");
            }*/
        }
    ?>

</div> 
	</div> 
	</div> 
  
    <?php require_once('../tpl_blocks/footer.php'); ?>


    </body>
</html>