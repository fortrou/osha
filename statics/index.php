<?php
	session_start();
	require_once('../tpl_php/autoload.php');
	if(!isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) header("Location: ../");
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
		$sql = "INSERT INTO os_statics(keywords, description, title_ru,title_ua,text_ru,text_ua) 
		VALUES('".htmlspecialchars($_POST['keywords'])."','".htmlspecialchars($_POST['description'])."','".htmlspecialchars($_POST['title_ru'])
			."','".htmlspecialchars($_POST['title_ua'])."','".htmlspecialchars($_POST['text_ru'])."','"
			.htmlspecialchars($_POST['text_ua'])."')";
		$res = $mysqli->query($sql);
		$sql = "SELECT * FROM os_statics ORDER BY id DESC LIMIT 1";
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		$_SESSION['link'] = "http://online-shkola.com.ua/statics/watch.php?id=".$row['id'];
	}
	
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
			if(isset($_SESSION['link']) && $_SESSION['link']!=""){
				printf("Ссылка на только что созданную вами страницу:<br>
					%s <br> Скопируйте ее и вставьте в редактор текста, сформировав как ссылку.<br>
					чтобы создать новую статичную страницу достаточно просто заново заполнить все поля и нажать 'Создать'",$_SESSION['link']);
				unset($_SESSION['link']);
			}
		?>
		<form method="post" action="index.php" enctype="multipart/form-data">
			<span>Введите ключевые слова через ","</span><br>
			<textarea name="keywords" style="width:200px; height:60px;resize:none;"></textarea><br>
			<span>Введите краткое описание страницы, желательно, не более 100 слов</span><br>
			<textarea name="description" style="width:400px; height:200px;resize:none;"></textarea>
			<p>Русское название</p>
			<input type="text" name="title_ru">
			<p>Українська назва</p>
			<input type="text" name="title_ua">
			<p>Русский вариант</p>
			<textarea name="text_ru"></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('text_ru');
			</script>
			<p>Український варіант</p>
			<textarea name="text_ua"></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('text_ua');
			</script>
			<input type="submit" name="create" value="Создать">
		</form> 
	<form method="post" action="">
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