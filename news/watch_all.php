<?php
	session_start();
	
	$cat = $_GET['categ'];
	require_once('../tpl_php/autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	
	
	$sql = "SELECT * FROM os_news ORDER BY id DESC";
	$res = $mysqli->query($sql);
?>
<!DOCTYPE html> 
<head>  		
	<title>Блог новостей - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	
	<div class="content">
	<div class="block0">
		<ul>
			<?php
				if($res->num_rows != 0){
					while ($row = $res->fetch_assoc()) {
						printf("<li><h3>%s</h3><p>%s...</p><br><a href='watch.php?id=%s'>Читать полностью</a></li>",
							$row['title_n_'.$_COOKIE['lang']],rtrim(substr(strip_tags($row['text_n_'.$_COOKIE['lang']]),0,200)),$row['id']);
					}
				}
			?>
		</ul>
	</div> 
	</div> 
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 