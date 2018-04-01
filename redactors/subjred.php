<?php
	session_start();
	require_once('../tpl_php/autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	/*function getVideoLink($link) 
	{
		$video = $link;
	    $pos_1 = strpos($video,'com');
	    $str = substr($video, 0 , $pos_1 + 4 );
	   
	    $str .= 'embed/';
	    
	    $pos_2  = strpos($video , 'v=');
	    $str .= substr($video , $pos_2 + 2 , strlen($video) - $pos_2 - 2 );



	    return $str;
	}*/
	if (isset($_POST["save_changes"])) {
		$sql = "SELECT * FROM os_subjects";
		$res = $mysqli->query($sql);
		while ($row = $res->fetch_assoc()) {
			$sql_up = sprintf("UPDATE os_subjects SET name_ru='%s',name_ua='%s' WHERE id='%s'",
				$_POST['sname_ru_'.$row['id']],$_POST['sname_ua_'.$row['id']],$row['id']);
			$res_up = $mysqli->query($sql_up);
		}
		header("Location: subjred.php");
	}
	if (isset($_POST["add_subject"])) {
		$sql_add = "INSERT INTO os_subjects SELECT MAX(id)+1, '', '' FROM os_subjects";
		//print("<br>$sql_add<br>");
		$res_add = $mysqli->query($sql_add);
		header("Location: subjred.php");
	}
	$sql = "SELECT * FROM os_subjects";
	$res = $mysqli->query($sql);
	while ($row = $res->fetch_assoc()) {
		if (isset($_POST["dels_".$row['id']])) {
			$sql_del = sprintf("DELETE FROM os_subjects WHERE id='%s'",$row['id']);
			$res_del = $mysqli->query($sql_del);
			header("Location: subjred.php");
		}
	}
?>
<!DOCTYPE html> 
<head>  		
	<title>Редактор предметов - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="resps.js"></script>
	<script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	
	<div class="content">
		<div class="block0">
		<h1>Редактор предметов</h1>
	
			<form method="post" action="">
				<?php
					$sql = "SELECT * FROM os_subjects";
					$res = $mysqli->query($sql);
					while ($row = $res->fetch_assoc()) {
						printf("Предмет: <input type='text' value='%s' name='sname_ru_%s'><input type='text' value='%s' name='sname_ua_%s'> <input type='submit' name='dels_%s' value='Удалить предмет'><br>",
							$row['name_ru'],$row['id'],$row['name_ua'],$row['id'],$row['id']);
					}
				?>
				<input type="submit" name="add_subject" value="Добавить предмет"></input>
				<input type="submit" name="save_changes" value="Сохранить изменения"></input>
			</form>
			
		<!--<input type="button" value="Создать класс" name="create_form" onclick="open_modal()"></input>
		<input type="button" value="Удалить класс" name="del_cur_class"></input>-->
	</div> 
</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 