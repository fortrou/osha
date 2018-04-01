<?php 
	session_start();
	require_once '../tpl_php/autoload.php';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if (!isset($_GET['id'])) {
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
//var_dump($_POST);
	$sql_l = sprintf("SELECT * FROM os_lessons WHERE id=(SELECT id_lesson FROM os_lesson_homework WHERE id='%s')",$_GET['id']);
	$res_l = $mysqli->query($sql_l);
	$row_l = $res_l->fetch_assoc();
	if (isset($_POST['send'])) {
		if($row_l['same_lang'] == 1){
			$_POST['hw_text_ua'] = $_POST['hw_text_ru'];
		}
		$sql = sprintf("UPDATE os_lesson_homework SET hw_text_ru='%s',hw_text_ua='%s',mark='%s' WHERE id='%s'",$_POST['hw_text_ru'],$_POST['hw_text_ua'],$_POST['mark'],$_GET['id']);
		$res = $mysqli->query($sql);
	}
	$sql_hw = sprintf("SELECT * FROM os_lesson_homework WHERE id='%s'",$_GET['id']);
	$res_hw = $mysqli->query($sql_hw);
	$row_hw = $res_hw->fetch_assoc();

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Создание творческого ДЗ(<? print($_COOKIE['lang']); ?>)</title>

	<?php include '../tpl_blocks/head.php'; ?>

	<!--<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>-->

<script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>
</head>
<body>
	
	<?php include '../tpl_blocks/header.php'; ?>
	<?php
		if (isset($_SESSION['error'])) {
			print("<br>".$_SESSION['error']."<br>");
			unset($_SESSION['error']);
		}
	?>
	<form action="" method="post">

		<p>Творческое домашнее задание</p>

		<p>Русский вариант</p>
		<textarea name="hw_text_ru"  cols="100" rows="10"><?php print($row_hw['hw_text_ru']); ?></textarea>
		<script type="text/javascript">
			CKEDITOR.replace( 'hw_text_ru');
		</script>
		<p>Український варіант</p>
		<textarea name="hw_text_ua"  cols="100" rows="10"><?php print($row_hw['hw_text_ua']); ?></textarea>
		<script type="text/javascript">
			CKEDITOR.replace( 'hw_text_ua');
		</script>
		<input type="text" name="mark" value="<?php print($row_hw['mark']); ?>">

		<input type="submit" value="Завершить" name="send">
		
	</form>

	<?php include '../tpl_blocks/footer.php'; ?>

</body>
</html>