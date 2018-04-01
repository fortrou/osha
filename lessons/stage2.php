<?php 
	session_start();
	require '../tpl_php/autoload.php';

	if ( !isset($_GET['id']) )
		header("Location: " . $_SERVER['HTTP_REFERER']);

	$id = $_GET['id'];
	//var_dump($_GET);
	/**
	 * @type 4 - Обычный тест
	 * @type 5 - Тестовое дз
	 * 
	**/
	//var_dump($_POST);
	$db = Database::getInstance();
    $mysqli = $db->getConnection();
    $sql_linfo = "SELECT * FROM os_lessons WHERE id='$id'";
	$res_linfo = $mysqli->query($sql_linfo);
	$row_linfo = $res_linfo->fetch_assoc();
	//var_dump($row_linfo);
	if ( $_POST['simpt'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ru';
		$_SESSION['lesson']['test_type'] = 4;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];

		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		//var_dump($_SESSION['test']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,4,'%s')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id'],$_SESSION['lesson']['test_lang']);
		//print($sql);
		//print("<br>");
		$result = $mysqli->query($sql);
		//var_dump($result);
		if ($row_linfo['same_lang'] == 1) {
			
			$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s, %s, 4, 'ua')",$id,$_SESSION['test']['id']);
			//print($sql);
			//print("<br>");
			$res = $mysqli->query($sql);
			//var_dump($res);
		}
		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['hardt'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ru';
		$_SESSION['lesson']['test_type'] = 5;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,5,'ru')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id']);
		//print($sql);
		$result = $mysqli->query($sql);
		if ($row_linfo['same_lang'] == 1) {

			$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s, %s, 5, 'ua')",$id,$_SESSION['test']['id']);
			$res = $mysqli->query($sql);
		}
		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['simptu'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ua';
		$_SESSION['lesson']['test_type'] = 4;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,%s,'%s')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		//print($sql);
		$result = $mysqli->query($sql);

		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['hardtu'] )
	{
		$_SESSION['lesson']['test_lang'] = 'ua';
		$_SESSION['lesson']['test_type'] = 5;
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		//var_dump($_SESSION['lesson']);
		Test::createTest($_SESSION['lesson']['lesson_id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		$sql = sprintf("INSERT INTO os_lesson_test(id_lesson,id_test,type,lang) VALUES(%s,%s,%s,'%s')",
			$_SESSION['lesson']['lesson_id'],$_SESSION['test']['id'],$_SESSION['lesson']['test_type'],$_SESSION['lesson']['test_lang']);
		//print($sql);
		$result = $mysqli->query($sql);

		header("Location:../tests/createquestion.php");
		//header("Location:../tests/create.php");
	}
	if ( $_POST['hwtext'] )
	{
		$_SESSION['lesson']['lesson_id'] = $_GET['id'];
		header("Location:hw_create.php");
	}
	
	if ( $_POST['skip'] )
	{
		header("Location:stage3.php?id=".$_GET['id']);
	}
	
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Создание онлайн-урока</title>

	<?php include '../tpl_blocks/head.php'; ?>
	
	


</head>
<body>
	
	<?php include '../tpl_blocks/header.php'; ?>
	<div class="content">
		<div class="block0">
	
<?php
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "SELECT subject FROM os_lessons WHERE id='$id'";
		//print("<br>$sql<br>");
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$subject = $row['subject'];
		$sql = "SELECT id, name 
				FROM os_tests 
				WHERE subj_id=$subject";
		//print("<br>$sql<br>");
		$result = $mysqli->query($sql);
		$flags = array(0,0,0,0,0,0);
		$sql = "SELECT * FROM os_lesson_test WHERE id_lesson='$id'";
		//print("<br>$sql<br>");
		$result = $mysqli->query($sql);
		while($row = $result->fetch_assoc()){
			if($row['type'] == 4 && $row['lang'] == 'ru'){
				$flags[0] = 1;
			}
			if($row['type'] == 5 && $row['lang'] == 'ru'){
				$flags[1] = 1;
			}
			if($row['type'] == 4 && $row['lang'] == 'ua'){
				$flags[2] = 1;
			}
			if($row['type'] == 5 && $row['lang'] == 'ua'){
				$flags[3] = 1;
			}
		}
		$sql = "SELECT * FROM os_lesson_homework WHERE id_lesson='$id'";
		$result = $mysqli->query($sql);
		if ($result->num_rows != 0) {
			$flags[4] = 1;
		}
?>

	<form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
		<?php if($flags[0] != 1): ?>
			<input type='submit' name='simpt' value='Создать тренировочный тест[rus]'><br>
		<?php endif; ?>
		<?php if($flags[2] != 1 && $row_linfo['same_lang']!=1): ?>
			<input type='submit' name='simptu' value='Создать тренировочный тест[ukr]'><br>
		<?php else: ?>
			<input type='submit' name='simptu' value='Создать тренировочный тест[ukr]' style="display:none;" disabled><br>
		<?php endif; ?>
		<?php if($flags[1] != 1): ?>
			<input type='submit' name='hardt' value='Создать тестовое ДЗ[rus]'><br>
		<?php endif; ?>
		<?php if($flags[3] != 1 && $row_linfo['same_lang']!=1): ?>
			<input type='submit' name='hardtu' value='Создать тестовое ДЗ[ukr]'><br>
		<?php else: ?>
			<input type='submit' name='hardtu' value='Создать тестовое ДЗ[ukr]' style="display:none;" disabled><br>
		<?php endif; ?>
		<?php if($flags[4] != 1): ?>
		<input type='submit' name='hwtext' value='Создать творческое ДЗ'><br>
		<?php endif; ?>
		
		<input type='submit' name='skip' value='Пропустить'>
	</form>
			</div>			
		</div> 
	<?php include '../tpl_blocks/footer.php'; ?>

</body>
</html>