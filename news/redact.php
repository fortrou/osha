<?php
	session_start();
	if(!isset($_GET['id'])){
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	$cat = $_GET['categ'];
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
	if (isset($_POST['create'])) {
		$sql = sprintf("UPDATE os_news SET keywords='%s', description='%s', title_n_ru='%s',title_n_ua='%s',text_n_ru='%s',text_n_ua='%s' WHERE id='%s'",
			$_POST['keywords'],$_POST['description'],$_POST['new_title_ru'],$_POST['new_title_ua'],$_POST['new_text_ru'],$_POST['new_text_ua'],$_GET['id']);
		$res = $mysqli->query($sql);
	}
	$sql = "SELECT * FROM os_news WHERE id='".$_GET['id']."'";
	$res = $mysqli->query($sql);
	$row = $res->fetch_assoc();
?>
<!DOCTYPE html> 
<head>  		
	<title>Редактировать новость - Онлайн Школа</title>
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
		<form method="post" action="index.php" enctype="multipart/form-data">
			<span>Введите ключевые слова через ","</span><br>
			<textarea name="keywords" style="width:200px; height:60px;resize:none;"><?php print($row['keywords']); ?></textarea><br>
			<span>Введите краткое описание страницы, желательно, не более 100 слов</span><br>
			<textarea name="description" style="width:400px; height:200px;resize:none;"><?php print($row['description']); ?></textarea>
			<p>Название новости</p>
			<input type="text" name="new_title_ru" value="<?php print($row['title_n_ru']); ?>">
			<p>Назва новини</p>
			<input type="text" name="new_title_ua" value="<?php print($row['title_n_ua']); ?>">
			<p>Текст новости</p>
			<textarea name="new_text_ru"><?php print($row['text_n_ru']); ?></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('new_text_ru');
			</script>
			<p>Текст новини</p>
			<textarea name="new_text_ua"><?php print($row['text_n_ua']); ?></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('new_text_ua');
			</script>
			<input type="submit" name="create" value="Создать">
		</form>
		<form method="post" action="">
		<label>Введите ссылку<input type="text" name="link"></label>
		<input type="button" name="get_link" value="Сформировать ссылку">
		<label>Ссылка для установки в iframe<input type="text" name="new_link"></label>
		</form>
	</div> 
	</div> 
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 