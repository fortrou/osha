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
	
	$sql = "SELECT * FROM os_statics WHERE id='".$_GET['id']."'";
	//print($sql);
	$res = $mysqli->query($sql);
	$row = $res->fetch_assoc();
?>
<!DOCTYPE html> 
<head>  		
	<title><?php
			printf("%s",$row['title_'.$_COOKIE['lang']]); 
		?> - Онлайн Школа</title>
	<meta name="description" content="<?php print($row["description"]); ?>">
	<meta name="keywords" content="<?php print($row["keywords"]); ?>">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/users.js"></script>
	<!--<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>-->
	<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
	<script type="text/javascript">
		window.onload = function(){
			$(".just_print").click(function(){
				window.print()
			});
		}
	</script>
	
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	
<div class="content">
		<div class="block0">
			<!--<h1>Редактировать новость</h1>-->
		<?php
		if(isset($_COOKIE["lang"])){
			printf("<h1>%s</h1>",htmlspecialchars_decode($row['title_'.$_COOKIE['lang']]));
			printf("<p>%s</p>",htmlspecialchars_decode($row['text_'.$_COOKIE['lang']]));
		} else{
			printf("<h1>%s</h1>",htmlspecialchars_decode($row['title_ru']));
			printf("<p>%s</p>",htmlspecialchars_decode($row['text_ru']));
		}
		?>
		<div class="clear"></div>
	</div></div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 



