<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8', true);
	//require_once("../tpl_php/autoload.php");
	require_once("../tpl_php/classDatabase.php");

	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	

?>
<!DOCTYPE html> 
<head>  		
	<title>Квитанции учеников - Просмотр профиля - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">


	<script type="text/javascript" src="../tpl_js/paginate.js"></script>
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
		<ul class="all_bills">
			<?php 
				$sql = sprintf("SELECT * FROM os_bills");
				$res = $mysqli->query($sql);
				$iter = 0;
				while ($row = $res->fetch_assoc()) {
					$sql_user = sprintf("SELECT CONCAT(surname,' ',name,' ',patronymic) AS fio, login FROM os_users WHERE id='%s'",$row['id_student']);
					$res_user = $mysqli->query($sql_user);
					$row_user = $res_user->fetch_assoc();
					printf("<div class='bill bill_$iter'>Здравствуйте, Админ, вам квитанция за <b>%s</b> от пользователя<br>
						%s ( login: %s ) :<br>
						<img src='../upload/avatars/%s' width='150px' height='100px'></div>",$row['date'],$row_user['fio'],$row_user['login'],$row['image_bill']);
					$iter++;
				}
			?>
		</ul>
		<div class="pagination_cmon">	
			<ul class="pagination pagination1">
			</ul>
		</div>
		<!--<input type="hidden" name="id" value="<?=$_SESSION['data']['id']?>">
		<input type="hidden" name="lang" value="<?=$_COOKIE['lang']?>">
		<input type="hidden" name="count" value="50">
		<input type="hidden" name="count_all">
		<input type="hidden" name="cur_page" value="1">
		<input type="hidden" name="cur_bot_lim" value="0">
		<input type="hidden" name="cur_top_lim" value="50">-->
	</div>
</div>
<input type="hidden" name="pagination1" value="1">
<script type="text/javascript">
		window.onload = function(){
			create_pages(1,"pagination1","bill");
			show_positions(1,"bill");
		}
	</script>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 