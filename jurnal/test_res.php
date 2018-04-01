<?php
session_start();
require_once("../tpl_php/autoload.php");
$db = Database::getInstance();
$mysqli = $db->getConnection();
$test_answs = "";
if(!isset($_GET['type']) || !isset($_GET['id'])){
	header("Location:".$_SERVER['HTTP_REFERER']);
}
else{
	if ($_GET['type'] == 5) {
		$sql = sprintf("SELECT test_contr FROM os_journal WHERE id='%s'",$_GET['id']);
		$res = $mysqli->query($sql);
		if ($res->num_rows!=0) {
			$row = $res->fetch_assoc();
			if ($row['test_contr'] != "") {
				$test_answs = $row['test_contr'];
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>
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

    <?php 
        if(isset($_SESSION['data'])){
            include ("../tpl_blocks/header.php");
        }
        else{
            include ("../test_access/head2.php");
        }
    ?>
        

	<div class="content">
        <div class="block0">
        	<?php
	            /*if ($_SESSION['data']['level'] == 1) {
	                printf("<h3 class='fio_print' style='display:none;'>%s %s %s</h3>",$_SESSION['data']['surname'],$_SESSION['data']['name'],$_SESSION['data']['patronymic']);
	            }*/
	            $sql = sprintf("SELECT * FROM os_users WHERE id = (SELECT DISTINCT id_s FROM os_journal WHERE id='%s')",$_GET["id"]);
	            $res = $mysqli->query($sql);
	            $row = $res->fetch_assoc();
	            printf("<h3 class='fio_print' style='display:none;'>%s %s %s</h3>
	            	<h3 class='fio_print' style='display:none;'>Класс: %s</h3>",$row['surname'],$row['name'],$row['patronymic'],$row["class"]);
	            $sql = sprintf("SELECT * FROM os_lessons WHERE id = (SELECT DISTINCT id_l FROM os_journal WHERE id='%s')",$_GET["id"]);
	            //print("<br>$sql<br>");
	            $res = $mysqli->query($sql);
	            $row = $res->fetch_assoc();
	            $sql_test = sprintf("SELECT DISTINCT * FROM os_journal WHERE id='%s'",$_GET["id"]);
	            $res_test = $mysqli->query($sql_test);
	            $row_test = $res_test->fetch_assoc();
	            $sql_tinfo = sprintf("SELECT * FROM os_lesson_test WHERE type='5' AND id_lesson IN 
	            	(SELECT DISTINCT id FROM os_lessons WHERE id = (SELECT DISTINCT id_l FROM os_journal WHERE id='%s'))",$_GET["id"]);
	            $res_tinfo = $mysqli->query($sql_tinfo);
	            $row_tinfo = $res_tinfo->fetch_assoc();
	            $max_convert = 0;

		        $sql_fc = sprintf("SELECT * FROM os_test_quest WHERE id_test='%s'",$row_tinfo["id_test"]);
		        $res_fc = $mysqli->query($sql_fc);
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
	            printf("
	            	<h3 class='fio_print' style='display:none;'>Контрольный тест</h3>
	            		<br />
	            	<table class='on_print'>
	            	<tr>
	            		<td>
	            			Тема: 
	            		<td>
	            		<td>
	            			%s
	            		<td>
	            	</tr>
	            	<tr>
	            		<td>
	            			Оценка:  
	            		<td>
	            		<td>
	            			%s
	            		<td>
	            	</tr>
	            	<tr>
	            		<td>
	            			Максимальный балл:  
	            		<td>
	            		<td>
	            			%s
	            		<td>
	            	</tr>
	            	</table>",$row['title_'.$_COOKIE["lang"]],$row_test["mark_contr"],$max_convert);
	        ?>
        	<a href="#" onclick="window.print()">Печатать результаты</a>
        	<?php
        	//print("<br>$test_answs<br>");
	        if($test_answs != ""){
	            print("<table class='tests_table'>");
	            //print($_SESSION['string_answs']);
	            $arr_quests = explode(";;",$test_answs);
	            
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
	        ?>
        </div> 
	</div> 
  
    <?php require_once('../tpl_blocks/footer.php'); ?>


    </body>
</html>