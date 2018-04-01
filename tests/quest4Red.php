<?php
session_start();
if (!isset($_GET['qid'])) {
    header("Location:".$_SERVER['HTTP_REFERER']);
}
require_once("../tpl_php/autoload.php");
$db = Database::getInstance();
$mysqli = $db->getConnection();

$qId = $_GET['qid'];

if(isset($_POST['add_more'])){
    $tsql = "INSERT INTO os_test_answs(answer,correct,id_quest) VALUES('',0,'$qId')";
    $res = $mysqli->query($tsql);
    header("Location:".$_SERVER['REQUEST_URI']);
}
if(isset($_POST['add_match'])){
    $sql_cnt = "SELECT COUNT(*) FROM os_test_matches WHERE id_quest='$qId'";
    $res_cnt = $mysqli->query($sql_cnt);
    $row_cnt = $res_cnt->fetch_assoc();
    $cnt = $row_cnt['COUNT(*)'];
    $cnt++;
    $tsql = "INSERT INTO os_test_matches(match_text,num,id_quest) VALUES('','$cnt','$qId')";
    $res = $mysqli->query($tsql);
    header("Location:".$_SERVER['REQUEST_URI']);
}
    //var_dump($_POST);
    $sql_answs = "SELECT * FROM os_test_answs WHERE id_quest='$qId'";
    $res_answs = $mysqli->query($sql_answs);

    $sql_matches = "SELECT * FROM os_test_matches WHERE id_quest='$qId'";
    $res_matches = $mysqli->query($sql_matches);
    //print("<br>$sql_answs<br>");
    
    $num = $res_answs->num_rows;
    $num_m = $res_matches->num_rows;

    $it_an = 0;
    $cntr = 0;
    $it_an_1 = 0;
    $cntr_1 = 0;
if (isset($_POST['sbm'])) {
    $id_ar = array();
    $tsql = "SELECT id_a FROM os_test_answs WHERE id_quest='$qId'";
    $tres = $mysqli->query($tsql);
    
    /** Массив идентификаторов **/
    while ($trow = $tres->fetch_assoc()) {
        $id_ar[] = $trow['id_a'];
    }
    /** Массив идентификаторов **/

    /** Массив правильных ответов **/
        $right_ar = array();
    for ($i=0; $i < $num; $i++) { 
        $tstr = sprintf("id%s",$i+1);
        if (!isset($_POST[$tstr]) || $_POST[$tstr] == 0) {
            $right_ar[] = 0;
        }
        else{
            $right_ar[] = $_POST[$tstr];
        }
    }
    /** Массив правильных ответов **/

    /** Массив вариантов ответов **/
        $arr_an = array();
    for ($i=0; $i < $num; $i++) { 
        $tval = sprintf("answ%s",$i);
        //print("<br>$tval<br>".$_POST[$tval]."<br>");
        $arr_an[$i] = $_POST[$tval]; 
    }
    /** Массив вариантов ответов **/

    /** Массив вариантов соотвтетствий **/
        $arr_m = array();
    for ($i=0; $i <= $num_m-1; $i++) { 
        $tval = sprintf("match%s",$i);
        //print("<br>$tval<br>".$_POST[$tval]."<br>");
        $arr_m[$i] = $_POST[$tval]; 
    }
    /** Массив вариантов соотвтетствий **/
    
    /** Массив нумерации соответствий **/
        $arr_num = array();
    $sql_num = "SELECT * FROM os_test_matches WHERE id_quest='$qId'";
    $res_num = $mysqli->query($sql_num);
    while ($row_num = $res_num->fetch_assoc()) {
        $arr_num[] = $row_num['num'];
    }
    /** Массив нумерации соответствий **/
   
    /*var_dump($arr_m);
    print("<br>");
    var_dump($arr_num);
    print("<br>");
    var_dump($id_ar);
    print("<br>");
    var_dump($right_ar);
    print("<br>");
    var_dump($arr_an);
    print("<br>");*/
    //var_dump($_POST);
    Quest::update_q($_POST['quest'],(int)$_POST['rangecost'],trim($_POST['descAnswer']),trim(htmlspecialchars($qId)),$link);
    Quest::update_2($arr_an,$id_ar,$right_ar,$link);
    Quest::update_m($qId,$arr_m,$arr_num,$link);
    header("Location:".$_SERVER['REQUEST_URI']);
}

while($row = $res_answs->fetch_assoc()){
    //var_dump($row);
    $answ = sprintf("del%s",$it_an+1);
    if (isset($_POST[$answ])) {
        $tsql = sprintf("DELETE FROM os_test_answs WHERE id_a=%s",$row['id_a']);
        $tres = $mysqli->query($tsql);
        header("Location:".$_SERVER['REQUEST_URI']);
    }
    //var_dump($row);
    if ($row['correct'] == 1) {
        $sel[] = $cntr;
    }
    $tval = sprintf("answ%s",$cntr);
    $tanswv = sprintf("id%s",$cntr+1);
    $_POST[$tval] = $row['answer'];
    $_POST[$tanswv] = $row['correct'];
    $it_an++;
    $cntr++;
}
while($row = $res_matches->fetch_assoc()){
    
    $answ = sprintf("delete%s",$it_an_1+1);
    if (isset($_POST[$answ])) {
        $tsql = sprintf("DELETE FROM os_test_matches WHERE id_ma=%s",$row['id_ma']);
        $tres = $mysqli->query($tsql);
        header("Location:".$_SERVER['REQUEST_URI']);
    }
    
    $tval = sprintf("match%s",$cntr_1);
    //$matchv = sprintf("id%s",$cntr_1+1);
    $_POST[$tval] = $row['match_text'];
    //$_POST[$matchv] = $row['num'];
    $it_an_1++;
    $cntr_1++;
}
//var_dump($_POST);
?>
<!DOCTYPE html>
<html>
    <html lang="en">

  <head>
    <title>Главная - ВнеШколы - образовательный портал</title>
    <?php require_once('../tpl_blocks/head.php'); ?>
    <style>
        .matchRadio{
            list-style:none;
        }
        
        .matchRadio li{
            float:left;
            width:15px;
            margin-left:10px;
            border:1px solid transparent;
            text-align:center;
        }
   </style>
  </head>

  <body>
    <?php require_once('../tpl_blocks/header.php'); ?>
    <script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>
 

                <?php
                    $sql_quest = "SELECT * FROM os_test_quest WHERE id_q='$qId'";
                    $res_quest = $mysqli->query($sql_quest);
                    $row_quest = $res_quest->fetch_assoc();
                    $question = $row_quest['name'];
                    $cost = $row_quest['cost'];
                    $full_desc = $row_quest['full_desc'];
                    //print("<br>$cost<br>");
                ?>
                <?php
                    $alphabet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К");
                ?>
                <div class='createTest' style='margin-top:20px;'>
                    <div id="quest3" class="collapsed">
                        <form method='post' action='<?=$_SERVER['REQUEST_URI']?>' enctype="multipart/form-data">
                            <?php
                            require('r_c.php');
                            ?>
                            <br> <span class='testText'>Введите вопрос</span>
                            <textarea type='text' name='quest' class='answForm' style='width:960px; min-height: 200px;'> <? print($question); ?></textarea>
                            <script type='text/javascript'>
                                        CKEDITOR.replace('quest');
                                    </script>
                           
                                <br>
                                <ul style='float:left;list-style:none;'>
                                    <li>
                                        <h2>Варианты ответов</h2>
                                    </li>
                                    <?
                                        for($i = 0; $i < $num; $i++){
                                            $tval = sprintf("answ%s",$i);
                                            //print("<br>$tval<br>");
                                            $value = $_POST[$tval];
                                            //print("<br>$value<br>");
                                            printf("<li><span class='testText'>ответ №%s </span>
                                                <textarea type='text' name='answ%s' style='width:470px; min-height: 200px;'>%s</textarea>
                                                <script type='text/javascript'>
                                                    CKEDITOR.replace('answ%s');
                                                </script>
                                                <input type='submit' name='del%s' value='удалить ответ'>
                                                <br>
                                            </li>",$i+1,$i,$value,$i,$i+1);
                                        }
                                    ?>
                                </ul>

                                <ul style='float:right;margin-right:20px;list-style:none;'>
                                    <li>
                                        <h2>Варианты соответствий</h2>
                                    </li>
                                    <?php
                                    
                                        for($i = 0; $i < $num_m; $i++){
                                            $tval = sprintf("match%s",$i);
                                            $value = $_POST[$tval];
                                            printf("<li><span class='testText'>соответствие №%s </span>
                                                <textarea type='text' name='match%s' style='width:470px; min-height: 200px;'>%s</textarea>
                                                <script type='text/javascript'>
                                                    CKEDITOR.replace('match%s');
                                                </script>
                                                <input type='submit' name='delete%s' value='удалить ответ'>
                                                <br>
                                                
                                            </li>",$i+1,$i,$value,$i,$i+1);
                                        }
                                    ?>
                                </ul>
                                <div style='clear:both;'></div>
                                <ul style='width:450px;margin-top:25px; list-style:none;'>
                                    <?php
                                        print("<li>
                                        <ul class='matchRadio'>");
                                        printf("<li style='width:80px;'></li>");
                                            
                                        for($it = 0; $it < $num_m; $it++){
                                            printf("<li>%s</li>",$alphabet[$it]);
                                        }
                                        print("</ul>
                                            <div style='clear:both;'></div>
                                        </li>");
                                        for($i = 1; $i <= $num; $i++){
                                            $matchv = sprintf("id%s",$i);
                                            //print($tanswv);
                                            $value = $_POST[$matchv];
                                            
                                            print("<li>
                                            <ul class='matchRadio'>");
                                            printf("<li style='width:80px;'><span>Ответ №%s</span></li>",$i);
                                            for($it = 1; $it <= $num_m; $it++){
                                                if($_POST[$matchv] == $it)
                                                    printf("<li><input type='radio' name='id%s' value='%s' checked></li>",$i,$it);
                                                else
                                                    printf("<li><input type='radio' name='id%s' value='%s'></li>",$i,$it);
                                            }
                                            print("</ul>
                                            <div style='clear:both;'></div>
                                            </li>");
                                        }
                                    ?>
                                </ul>
                                <div class="clear"></div>
                            <table>
                                <tr>
                                    <td>
                                        <input type='submit' name='add_more' value='Добаить вариант ответа'><br>
                                    </td>
                                    <td>
                                        <input type='submit' name='add_match' value='Добавить новый вариант соответствия'>
                                    </td>
                                </tr>
                            </table>
                            <?php
                            //var_dump($_POST);
                            require('def_red.php');
                            ?>
                        </form>
                    </div>
                </div>

   
    <?php require_once('../tpl_blocks/footer.php'); ?>



    </body>
</html>
