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
    //var_dump($_POST);
    $sql_answs = "SELECT * FROM os_test_answs WHERE id_quest='$qId'";
    $res_answs = $mysqli->query($sql_answs);
    //print("<br>$sql_answs<br>");

    $it_an = 1;
    $num = $res_answs->num_rows;
    $cntr = 0;

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
    /*var_dump($id_ar);
    print("<br>");
    var_dump($right_ar);
    print("<br>");
    var_dump($arr_an);
    print("<br>");*/
    //var_dump($_POST);
    Quest::update_q($_POST['quest'],(int)$_POST['rangecost'],trim($_POST['descAnswer']),trim(htmlspecialchars($qId)),$link);
    Quest::update_2($arr_an,$id_ar,$right_ar,$link);
    header("Location:".$_SERVER['REQUEST_URI']);
}

while($row = $res_answs->fetch_assoc()){
    //var_dump($row);
    $answ = sprintf("del%s",$it_an);
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
?>
<!DOCTYPE html>
<html>
    <html lang="en">

  <head>
    <title>Главная - ВнеШколы - образовательный портал</title>
   <?php require_once('../tpl_blocks/head.php'); ?>
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
                    $alphabet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И");
                ?>
                <div class='createTest' style='margin-top:20px;'>
                    <div id="quest3" class="collapsed">
                        <form method='post' action='<?=$_SERVER['REQUEST_URI']?>' enctype="multipart/form-data">
                            <?php
                            require('r_c.php');
                            ?>
                                <br>
                                <ul style='float:left;'>
                                    <li>
                                        <textarea type='text' name='quest' class='answForm' style='width: 960px; min-height: 200px;'> <? print($question); ?></textarea>
                                        <script type='text/javascript'>
                                        CKEDITOR.replace('quest');
                                    </script>
                                        <span class='testText'>Введите вопрос</span><br>
                                    </li>
                                    
                                    <?
                                        for($i = 0; $i < $num; $i++){
                                            $tval = sprintf("answ%s",$i);
                                            $value = $_POST[$tval];
                                            printf("<li><span class='testText'>%sй ответ</span>
                                                <textarea type='text' name='answ%s'  style='width: 960px; min-height: 200px;'>%s</textarea>
                                                <script type='text/javascript'>
                                                    CKEDITOR.replace('answ%s');
                                                </script>
                                                <input type='submit' name='del%s' value='удалить ответ'>
                                                <br>
                                            </li>",$i+1,$i,$value,$i,$i+1);
                                        }
                                    ?>
                                </ul>
                                
                            <div class="clear"></div>
                            <input type='submit' name='add_more' value='Добаить вариант ответа'><br>
							
                                <ul >
                                    <?php
                                        for($i = 1; $i <= $num; $i++){
                                            $tanswv = sprintf("id%s",$i);
                                            //print($tanswv);
                                            $value = $_POST[$tanswv];
                                            printf("<li>    
                                            %s:<input type='text' name='id%s' value='%s'>
                                            </li>",$i,$i,$value);
                                        }
                                    ?>
                                </ul>
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
