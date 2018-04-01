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
    $sql_answs = "SELECT * FROM os_test_answs WHERE id_quest='$qId'";
	$res_answs = $mysqli->query($sql_answs);
	//print("<br>$sql_answs<br>");

	$it_an = 1;
	$num = $res_answs->num_rows;
	//var_dump($num);
	$cntr = 0;
	
	if (isset($_POST['sbm'])) {
		$id_ar = array();
		$tsql = "SELECT id_a FROM os_test_answs WHERE id_quest='$qId'";
		$tres = $mysqli->query($tsql);
		while ($trow = $tres->fetch_assoc()) {
			$id_ar[] = $trow['id_a'];
		}

		//var_dump($id_ar);

		$arr_an = array();
		for ($i=0; $i < $num; $i++) { 
			$tval = sprintf("answ%s",$i);
			//print("<br>$tval<br>");
			$arr_an[$i] = $_POST[$tval];
			
		}
		//var_dump($arr_an);
		//var_dump($_POST);
		Quest::update_q($_POST['quest'],(int)$_POST['rangecost'],trim($_POST['descAnswer']),trim(htmlspecialchars($qId)));
		Quest::update_1($arr_an,$id_ar,(int)$_POST['id1']);
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	while($row = $res_answs->fetch_assoc()){
		$answ = sprintf("del%s",$it_an);
		if (isset($_POST[$answ])) {
			$tsql = sprintf("DELETE FROM os_test_answs WHERE id_a=%s",$row['id_a']);
			$res = $mysqli->query($tsql);
			header("Location:".$_SERVER['REQUEST_URI']);
		}
		//var_dump($row);
		if ($row['correct'] == 1) {
			$sel = $cntr;
		}
		$tval = sprintf("answ%s",$cntr);
		$_POST[$tval] = $row['answer'];

		$cntr++;
		$it_an++;
	}
	//var_dump($_POST);
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
			    	/*$num = mysqli_num_rows($res_answs);
			    	$cntr = 0;
			    	while($row_an = mysqli_fetch_assoc($res_answs)){
			    		var_dump($row_an);
			    		if ($row_an['correct'] == 1) {
		    				$sel = $cntr;
			    		}
			    		$tval = sprintf("answ%s",$cntr);
			    		$_POST[$tval] = $row_an['answer'];

			    		$cntr++;
			    	}*/
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
				                //var_dump($_POST);
				            ?>
				            
				                     <span class='testText'>Введите вопрос</span>
				                    <textarea type='text' name='quest' class='answForm' style='width: 960px; min-height: 200px;'> <? print($question); ?></textarea>
				                    <script type='text/javascript'>
										CKEDITOR.replace('quest');
									</script>
				                   <br>
				                    
				                    <ul style='float:left;width:650px;'>
				                        <?
				                        for($i = 0; $i < $num; $i++){
				                        	$tval = sprintf("answ%s",$i);
				                        	//print($tval);
				                            $value = $_POST[$tval];
				                            //print("<br>$value<br>");
				                            printf("<li><span class='testText'>%sй ответ</span>
				                                <textarea type='text' name='answ%s' style='width: 960px; min-height: 200px;'>%s</textarea>
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
				            <p> <span class='testText'>Выберите правельный ответ</span></p>
				    <table style='width:700px; padding: 10px ;border: 1px solid #1e9cb7;'>
					<tr>
                            <?
                                for($i = 0; $i < $cnt; $i++)
                                printf("<td class='chk_yes_qes'><label>Ответ №%s:<input type='radio' name='id1' value='%s'></label></td>",$i+1,$i+1);
                                ?>
                           </tr> 
                                    
				                            <?
				                            //var_dump($_POST);
				                                for($i = 0; $i < $num; $i++)
				                                	if ($i != $sel) {
				                                		printf("<td class='chk_yes_qes'><label>              
				                                        	%s:<input type='radio' name='id1' value='%s'><br>
				                                    	</label></td>",$i+1,$i+1);
				                                	}
				                                	else{
				                                		printf("<td class='chk_yes_qes'><label>            
				                                        	%s:<input type='radio' checked name='id1' value='%s'><br>
				                                    	</label></td>",$i+1,$i+1);
				                                	}
				                                
				                                ?>
				                            
				                                             
				                               
                    </table><br>
				            <?php
				            	require_once('def_red.php');
				            

							
				            ?>
				        	
				        </form>
				    </div>
				</div>		
	<?php require_once('../tpl_blocks/footer.php'); ?>	



	</body>
</html>