<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
?>
<!DOCTYPE html> 
<head>  	
	<?php if($_COOKIE['lang'] == 'ru'): ?>	
		<title>Диалоги - Онлайн Школа</title>
	<?php endif; ?>
	<?php if($_COOKIE['lang'] == 'ua'): ?>	
		<title>Діалоги - Онлайн Школа</title>
	<?php endif; ?>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<script type="text/javascript" src="../tpl_js/journal.js"></script>

	<?php
		include ("../tpl_blocks/head.php");
	?>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
		<div class='block_l'>
			<?php
				if($_SESSION['data']['level']!=4){
					$sql = "SELECT DISTINCT id_chat FROM os_chat_users WHERE id_user='".$_SESSION['data']['id']."'";
					print("<br>$sql<br>");
					$res = $mysqli->query($sql);
					while($row = $res->fetch_assoc()){
						$sql_pre = sprintf("SELECT id_user 
							FROM os_chat_users 
							WHERE id_user IS NOT '%s' AND id_chat='%s'",$_SESSION['data']['id'],$row['id_chat']);
						$res_pre = $mysqli->query($sql_pre);
						while($row_pre = $res_pre->fetch_assoc()){

						}


						$sql_d = sprintf("SELECT concat(surname,' ',name) AS fi 
							FROM os_users 
							WHERE id IN";
						print("<br>$sql_d<br>");
						$res_d = $mysqli->query($sql_d);
						$row_d = $res_d->fetch_assoc();
						printf("<a href='index.php?cid=%s'>
							<div class='dialog'>
								%s
							</div>
						</a>",$row['id_chat'],$row_d['fi']);
					}
				}
			?>
		</div>
		<div class='block_r'>

		</div>
		<div class="clear"></div>

	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 