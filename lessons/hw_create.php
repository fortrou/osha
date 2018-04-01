<?php 
	session_start();
	require '../tpl_php/autoload.php';

//var_dump($_POST);
	if (isset($_POST['send'])) {
		$res = Lesson::create_hw($_SESSION['lesson']['lesson_id'], $_POST['hw_text_ru'], $_POST['hw_text_ua'],$_POST['mark']);
		if ($res == false) {
			$_SESSION['error'] = "Неверное срабатывание";
			//header("Location:hw_create.php");
		}
		else{
			header("Location:stage2.php?id=".$_SESSION['lesson']['lesson_id']);
		}
	}
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
	<form action="hw_create.php" method="post">

		<p>Творческое домашнее задание</p>

		<p>Русский вариант</p>
		<textarea name="hw_text_ru"  cols="100" rows="10"></textarea>
		<script type="text/javascript">
			CKEDITOR.replace( 'hw_text_ru');
		</script>
		<p>Український варіант</p>
		<textarea name="hw_text_ua"  cols="100" rows="10"></textarea>
		<script type="text/javascript">
			CKEDITOR.replace( 'hw_text_ua');
		</script>
		<input type="text" name="mark" value="0">

		<input type="submit" value="Завершить" name="send">
		
	</form>

	<?php include '../tpl_blocks/footer.php'; ?>

</body>
</html>