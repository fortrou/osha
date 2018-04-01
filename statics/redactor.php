<?php
	session_start();
	if(!isset($_GET['id'])){
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	require_once('../tpl_php/autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	function getVideoLink($link) 
	{
		$video = $link;
	    $pos_1 = strpos($video,'com');
	    $str = substr($video, 0 , $pos_1 + 4 );
	   
	    $str .= 'embed/';
	    
	    $pos_2  = strpos($video , 'v=');
	    $str .= substr($video , $pos_2 + 2 , strlen($video) - $pos_2 - 2 );

	    /*if ( date('Y-m-d H:i:s') < strftime("%Y-%m-%d %H:%M:%S" , strtotime($this->data['date'])) )
	    	return;*/

	    return $str;
	}
	if(isset($_POST['update'])){
		$sql_upd = sprintf("UPDATE os_statics SET keywords='%s', description='%s', title_ru='%s', title_ua='%s', text_ru='%s', text_ua='%s' WHERE id=%s",
			$_POST['keywords'],$_POST['description'],$_POST['title_ru'],$_POST['title_ua'],$_POST['text_ru'],$_POST['text_ua'],$_GET['id']);

		$res_upd = $mysqli->query($sql_upd);
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	$sql = "SELECT * FROM os_statics WHERE id='".$_GET['id']."'";
	//print($sql);
	$res = $mysqli->query($sql);
	$row = $res->fetch_assoc();
?>
<!DOCTYPE html> 
<head>  		
	<title>Создать статичную страницу - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/users.js"></script>
	<!--<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>-->
	<script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class="block0">
			<h1>Создание статической страницы</h1>
	 
		<?php
			
		?>
		<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" enctype="multipart/form-data">
			<span>Введите ключевые слова через ","</span><br>
			<textarea name="keywords" style="width:200px; height:60px;resize:none;"><?php print($row['keywords']); ?></textarea><br>
			<span>Введите краткое описание страницы, желательно, не более 100 слов</span><br>
			<textarea name="description" style="width:400px; height:200px;resize:none;"><?php print($row['description']); ?></textarea>
			<p>Русское название</p>
			<input type="text" name="title_ru" value="<?=$row['title_ru']?>">
			<p>Українська назва</p>
			<input type="text" name="title_ua" value="<?=$row['title_ua']?>">
			<p>Русский вариант</p>
			<textarea name="text_ru"><?=$row['text_ru']?></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('text_ru');
			</script>
			<p>Український варіант</p>
			<textarea name="text_ua"><?=$row['text_ua']?></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('text_ua');
			</script>
			<input type="submit" name="update" value="Редактировать">
			<a href="manager.php?id=13">Отменить</a>
		</form> 
	<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
		<label>Введите ссылку<input type="text" name="link"></label>
		<input type="button" name="get_link" value="Сформировать ссылку">
		<label>Ссылка для установки в iframe<input type="text" name="new_link"></label>
	</form>
	</div></div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 