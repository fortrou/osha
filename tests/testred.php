<?php
    session_start();
    require_once("../tpl_php/autoload.php");
    $db = Database::getInstance();
    $mysqli = $db->getConnection();
    if (!isset($_SESSION['referer'])) {
        $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
    }
    if (!isset($_GET['tid'])) {
        header("Location:index.php");
    }
    $testId=$_GET['tid'];
    $id_del = $_GET['id'];
	if (isset($_POST['delete'])) {
	    $sql_del = "DELETE FROM os_tests WHERE id='$testId'";
	    $res_del = $mysqli->query($sql_del);
        unset($_SESSION['referer']);
	    header("Location:".$_SERVER["REQUEST_URI"]);
        $sql_t = "SELECT * FROM os_test_quest WHERE id_test='$testId'";
        $res_t = $mysqli->query($sql_t);
        //print($sql_t);
        while($row = $res_t->fetch_assoc()){
            if($row['type']!=5){
                $sql_d = sprintf("DELETE FROM os_test_answs WHERE id_quest='%s'",$row['id_q']);
                $res_d = $mysqli->query($sql_d);
                $sql_d = sprintf("DELETE FROM os_test_matches WHERE id_quest='%s'",$row['id_q']);
                $res_d = $mysqli->query($sql_d);
            }
            else{
                $sql_d = sprintf("DELETE FROM os_test_short_answ WHERE id_quest='%s'",$row['id_q']);
                $res_d = $mysqli->query($sql_d);
            }
            $sql_d = sprintf("DELETE FROM os_test_quest WHERE id_q='%s'",$row['id_q']);
            $res_d = $mysqli->query($sql_d);
            $sql_del = sprintf("DELETE FROM os_lesson_test WHERE id_test='%s'",$testId);
            $res_del = $mysqli->query($sql_del);
        }
	}
    if (isset($_POST['name_level'])) {
        $ref = $_SESSION['referer'];
        unset($_SESSION['referer']);
        header("Location:$ref");
    }
    $_SESSION['test_red']['testId'] = $testId;
    $sql_t = "SELECT * FROM os_test_quest WHERE id_test='$testId'";
    $res_t = $mysqli->query($sql_t);
    //print($sql_t);
    while($row = $res_t->fetch_assoc()){
        if (isset($_POST['del_q'.$row['id_q']])) {
            if($row['type']!=5){
                $sql_d = sprintf("DELETE FROM os_test_answs WHERE id_quest='%s'",$row['id_q']);
                $res_d = $mysqli->query($sql_d);
                $sql_d = sprintf("DELETE FROM os_test_matches WHERE id_quest='%s'",$row['id_q']);
                $res_d = $mysqli->query($sql_d);
            }
            else{
                $sql_d = sprintf("DELETE FROM os_test_short_answ WHERE id_quest='%s'",$row['id_q']);
                $res_d = $mysqli->query($sql_d);
            }
            $sql_d = sprintf("DELETE FROM os_test_quest WHERE id_q='%s'",$row['id_q']);
            $res_d = $mysqli->query($sql_d);
        }
    }

?>
<!DOCTYPE html>
<html>

  <head>
	<title>Главная - ВнеШколы - образовательный портал</title>
   <?php require_once('../tpl_blocks/head.php'); ?>
  </head>

    <body>
        <?php require_once('../tpl_blocks/header.php'); ?>
		<div class="content">
		<div class="block0">
        <div>
            <form method='POST' action='<?=$_SERVER['REQUEST_URI']?>'>
                <ul class='q_list'>
                    <?php
                        $sql_t = "SELECT * FROM os_test_quest WHERE id_test='$testId'";
                        $res_t = $mysqli->query($sql_t);
                        //print($sql_t);
                        while($row = $res_t->fetch_assoc()){
                            switch ($row['type']) {
                                case 1:
                                    printf("<li><a href='quest1Red.php?qid=%s'>%s</a><input type='submit' name='del_q%s' value='Удалить вопрос'></li>",$row['id_q'],$row['name'],$row['id_q']);
                                    break;
                                case 2:
                                    printf("<li><a href='quest2Red.php?qid=%s'>%s</a><input type='submit' name='del_q%s' value='Удалить вопрос'></li>",$row['id_q'],$row['name'],$row['id_q']);
                                    break;
                                case 3:
                                    printf("<li><a href='quest3Red.php?qid=%s'>%s</a><input type='submit' name='del_q%s' value='Удалить вопрос'></li>",$row['id_q'],$row['name'],$row['id_q']);
                                    break;
                                case 4:
                                    printf("<li><a href='quest4Red.php?qid=%s'>%s</a><input type='submit' name='del_q%s' value='Удалить вопрос'></li>",$row['id_q'],$row['name'],$row['id_q']);
                                    break;
                                case 5:
                                    printf("<li><a href='quest5Red.php?qid=%s'>%s</a><input type='submit' name='del_q%s' value='Удалить вопрос'></li>",$row['id_q'],$row['name'],$row['id_q']);
                                    break;

                            }
                        }
                    ?>
                </ul>
            </form>
            <form method='POST' action='<?=$_SERVER['REQUEST_URI']?>' enctype='multipart/form-data'>
                <?php
                        $sqlTest = "SELECT * FROM os_tests WHERE id='$testId'";
                        $resTest = $mysqli->query($sqlTest);
                        $rowTest = $resTest->fetch_assoc();
                
                    printf("<input type='text' name='testName' value='%s'><br>",$rowTest['name']);
                    
                    //printf("<a href='addquest.php?tid=%s'>Добавить вопрос из уже существующих</a><br>",$rowTest['testId']);
                    print("<a href='createq1.php'>Добавить новый вопрос</a><br>");
                ?>
                <br>
                
                <input type="submit" name="name_level" value="Сохранить изменения">
                <?php
                    printf("<a href='/tests/completing.php?id=$testId'>Отменить редактирование</a>");
                ?>
            </form>
            <form method='POST' action='<?=$_SERVER['REQUEST_URI']?>' onsubmit="return confirm('Вы действительно хотите удалить этот тест?')">
                <input type="submit" name="delete" value="Удалить тест!">
            </form>
        </div></div></div>
	<?php require_once('../tpl_blocks/footer.php'); ?>    
</body>
</html>