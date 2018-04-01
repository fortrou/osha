<?php
    session_start();
    require_once("../tpl_php/autoload.php");
    if (!isset($_SESSION['test']['cnt'])) {
    	$_SESSION['test']['cnt'] = 0;
    }
    else{
    	$cnt = $_SESSION['test']['cnt'];
    }
    $db = Database::getInstance();
	$mysqli = $db->getConnection();
    if (isset($_POST['sbm'])) {
        $strTemp = "";

        $testId = $_SESSION['test']['id'];
        for ($i = 1; $i <= $cnt; $i++) {
            if (isset($_POST[$i])) {
                $strTemp .= $i."=".$_POST[$i].";";
            }
        }
        //var_dump($strTemp);
        $sql = "UPDATE os_tests SET mark_table='$strTemp' WHERE id='$testId'";
        $res = $mysqli->query($sql);    
        if($res != false){
        	header("Location:../lessons/stage2.php?id=".$_SESSION['lesson']['lesson_id']);
        }
    }
    //var_dump($sql);
    $cnt = 0;
?>
<!DOCTYPE html>
<html lang="en">

  <head>
	<title>Главная - ВнеШколы - образовательный портал</title>
   <?php require_once('../tpl_blocks/head.php'); ?>
  </head>
	<body>
		<?php require_once('../tpl_blocks/header.php'); ?>
		<div class="content">
		<div class="block0">
			<div class="testes">
			<?php
			//var_dump($_SESSION);
				$id_test = $_SESSION['test']['id'];
				//var_dump($_SESSION['test']);
				$sql = "SELECT * FROM os_test_quest WHERE id_test='$id_test'";
				$res = $mysqli->query($sql);
				//var_dump($res);
				while($row = $res->fetch_assoc()){
					switch ((int)$row['type']) {
						case 1:
							$cnt += $row['cost'];
							//print("<br>$cnt<br>");
							break;
						case 2:
							$sql = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s AND correct=1",$row['id_q']);
							$result = $mysqli->query($sql);
							$row_loc = $result->fetch_assoc();
							$cnt += (int)$row_loc['count(id_a)']*(int)$row['cost'];
							//print("<br>$cnt<br>");
							break;
						case 3:
							$sql = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row['id_q']);
							$result = $mysqli->query($sql);
							$row_loc = $result->fetch_assoc();
							$cnt += (int)$row_loc['count(id_a)']*(int)$row['cost'];
							//print("<br>$cnt<br>");
							break;
						case 4:
							$sql = sprintf("SELECT count(id_a) FROM os_test_answs WHERE id_quest=%s",$row['id_q']);
							$result = $mysqli->query($sql);
							$row_loc = $result->fetch_assoc();
							$cnt += (int)$row_loc['count(id_a)']*(int)$row['cost'];
							//print("<br>$cnt<br>");
							break;
						
						default:
							break;
					}
				}
				print($cnt);
			?>
			<form method='post' action='cancel.php' class='createTest'>
                    <?php
                        for ($i = 1; $i <= $cnt; $i++) {
                            printf("%d балл(-а) = <input type='text' name='%d' placeholder='балл'><br>", $i, $i);
                        }
                    ?>
                    <input type='submit' name='sbm' class='sbm' value='Создать'>
            </form>
            <?php
            	$_SESSION['test']['cnt'] = $cnt;
            ?>
			</div>
		</div>
	</div>
		<?php require_once('../tpl_blocks/footer.php'); ?>
    </body>
</html>