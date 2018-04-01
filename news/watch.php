<?php
	session_start();
	if(!isset($_GET['id'])){
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	if(isset($_GET['categ'])) $cat = $_GET['categ'];
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
	
	$sql = "SELECT * FROM os_news WHERE id='".$_GET['id']."'";
	$res = $mysqli->query($sql);
	$row = $res->fetch_assoc();
?>
<!DOCTYPE html> 
<head>  		
	<title>Новость <?php print($row['title_n_'.$_COOKIE['lang']]); ?> - Онлайн Школа</title>
	<meta name="description" content="<?php print($row["description"]); ?>">
	<meta name="keywords" content="<?php print($row["keywords"]); ?>">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/users.js"></script>
	<!--<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>-->
	<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	
	<div class="content">
		<div class="block0">
			<?php
				if(isset($_COOKIE["lang"])){
					printf("<h3>%s</h3>",$row['title_n_'.$_COOKIE['lang']]);
				printf("<p>%s</p>",$row['text_n_'.$_COOKIE['lang']]);
				} else{
					printf("<h3>%s</h3>",$row['title_n_ru']);
				printf("<p>%s</p>",$row['text_n_ru']);
				}
			
			?>
			<?php
				if($_SESSION['data']['level'] == 4)
					printf("<p><a href='redact.php?id=%s'>Редактировать</a></p>",$_GET['id']);
			?>
		</div> 
	</div> 
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 