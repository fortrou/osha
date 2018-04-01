<?php 
session_start();
header('Content-Type: text/html; charset=utf-8', true);
	require 'tpl_php/classDatabase.php';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
 ?>

<!DOCTYPE html> 
<head>  		
	<title>Регистрация - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("tpl_blocks/head.php");
	?>
</head>
<body id="top">
	<?php
		include ("tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class="block0">
			<h1>Ваш аккаунт временно заблокирован, обратитесь к администрации, используя кнопку техподдержки.</h1>	
		</div> 
	</div> 
	
	<?php
		include ("tpl_blocks/footer.php");
	?>
</body> 
</html> 