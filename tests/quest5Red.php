<?php
session_start();
	if (!isset($_GET['qid'])) {
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	require_once("../tpl_php/autoload.php");
$db = Database::getInstance();
$mysqli = $db->getConnection();
	$qId = $_GET['qid'];
	if (isset($_POST["sbm"])) {
		Quest::update_q($_POST['quest'],(int)$_POST['rangecost'],trim($_POST['descAnswer']),trim(htmlspecialchars($qId)));
		$sql = sprintf("UPDATE os_test_short_answ SET answer='%s' WHERE id_quest='$qId'",$_POST["answer"]);
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
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
			    	//print("<br>$sql_quest<br>");
			    	$res_quest = $mysqli->query($sql_quest);
			    	//var_dump($res_quest);
			    	$row_quest = $res_quest->fetch_assoc();
			    	$question = $row_quest['name'];
			    	$cost = $row_quest['cost'];
			    	$full_desc = $row_quest['full_desc'];
			    	//print("<br>$cost<br>");
			    	
			    ?>
			    <div class='createTest' >
				    <div id="quest1" class="collapsed">
				        <form method='post' action='<?=$_SERVER['REQUEST_URI']?>' enctype="multipart/form-data" >
				            <?php
				                require_once('r_c.php');
				                //print("<br>$cnt<br>");
				                //print("<br>");
				                //var_dump($_SESSION['correct']);
				                print("<br>");
				                $sql = "SELECT * FROM os_test_quest WHERE id_q=$qId";
				                $res = $mysqli->query($sql);
				                $row = $res->fetch_assoc();
				                $sql_answer = "SELECT * FROM os_test_short_answ WHERE id_quest=$qId";
				                $res_answer = $mysqli->query($sql_answer);
				                $row_answer = $res_answer->fetch_assoc();
				            ?>
				            
				            <p> <span class='testText'>Редактировать вопрос</span></p>
							<textarea type='text' name='quest' class='quest' style='width:960px; min-height: 200px;'><?php print($row["name"]); ?></textarea>
							<script type='text/javascript'>
								CKEDITOR.replace('quest');
							</script><br>
				 
							<p> <span class='testText'>Редактировать ответ</span></p>
							<input style="width: 900px;" type="text" name="answer" placeholder="Введите ответ" value="<?php print($row_answer["answer"]); ?>"></input>
				            <?php
				            	require_once('def_red.php');
				            ?>
				        	
				        </form>
				    </div>
				</div>		
	<?php require_once('../tpl_blocks/footer.php'); ?>	



	</body>
</html>