<?php 
	session_start();
	require '../tpl_php/autoload.php';

	if ( !isset($_GET['id']) )
		header("Location: " . $_SERVER['HTTP_REFERER']);
	$id = $_GET['id'];

	$db = Database::getInstance();
    $mysqli = $db->getConnection();
    $sql_linfo = "SELECT * FROM os_lessons WHERE id='$id'";
	$res_linfo = $mysqli->query($sql_linfo);
	$rus_vers = "RUS";
	$ukr_vers = "UKR";
	if($res_linfo->num_rows != 0) {
		$row_linfo = $res_linfo->fetch_assoc();
		$rus_vers = htmlspecialchars_decode($row_linfo['summary_ru']);
		$ukr_vers = htmlspecialchars_decode($row_linfo['summary_ua']);
	}
	if ( $_POST['send'] )
	{
		try 
		{
			if ($row_linfo['same_lang']==1) {
				$_POST['summary_ua'] = $_POST['summary_ru'];
			}
			$result = Lesson::finalStage( $_GET['id'] , htmlspecialchars($_POST['summary_ua']) , htmlspecialchars($_POST['summary_ru']));
			if ( $result ) 
				//print('a');
				header("Location: ../schedule/calendar.php");
			else
				print("Ошибка!");
		}
		catch (Exception $e) 
		{
			print($e->getMessage());
		}
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Создание онлайн-урока</title>

	<?php include '../tpl_blocks/head.php'; ?>

	<script src="../tpl_js/lessons.js"></script>	
	<!--<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>-->
	<script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>

</head>
<body>
	
	<?php include '../tpl_blocks/header.php'; ?>

	<div class="content">
		<div class="block0">
	<form action="" method="post">
		
		<p>Конспект</p>
		<p>Русский</p>
		<textarea name="summary_ru"  cols="100" rows="10"><?php print($rus_vers); ?></textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('summary_ru');
			</script>

		<p>Український</p>
		<textarea name="summary_ua"  cols="100" rows="10"><?php print($ukr_vers); ?></textarea>
		<script type='text/javascript'>
				CKEDITOR.replace('summary_ua');
			</script>
		
		<input type="submit" value="Завершить" name="send">
	</form>

			</div>			
		</div> 
	<?php include '../tpl_blocks/footer.php'; ?>

</body>
</html>