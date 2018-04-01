<?php
	session_start();
	if(!isset($_GET['categ'])){
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	$cat = $_GET['categ'];
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
	
	if (isset($_POST['save'])) {
		//var_dump($_POST);
		$sql = "SELECT * FROM os_elements WHERE categ='$cat'";
			$res = $mysqli->query($sql);
			
			switch ($cat) {
				case 6:
					while ($row = $res->fetch_assoc()) {
						$sql_u = sprintf("UPDATE os_elements SET text_ua='%s', text_ru='%s', price='%s' WHERE id='%s'",
							trim($_POST[$row['id'].'_ua']),trim($_POST[$row['id'].'_ru']),trim($_POST[$row['id'].'_price']),trim($row['id']));

						$res_u = $mysqli->query($sql_u);
						//print("<br>$sql_u<br>");
						header("Location:".$_SERVER['REQUEST_URI']);
					}
					break;
				case 4:
					while ($row = $res->fetch_assoc()) {
						if(trim($_POST[$row['id'].'_video']) != $row['video'])
							$link = getVideoLink(trim($_POST[$row['id'].'_video']));
						else
							$link = trim($_POST[$row['id'].'_video']);
						$sql_u = sprintf("UPDATE os_elements SET text_ua='%s', text_ru='%s', video='%s' WHERE id='%s'",
							trim($_POST[$row['id'].'_ua']),trim($_POST[$row['id'].'_ru']),$link,trim($row['id']));

						$res_u = $mysqli->query($sql_u);
						//print("<br>$sql_u<br>");
						header("Location:".$_SERVER['REQUEST_URI']);
					}
					break;
				case 3||5||7||8||15:
					while ($row = $res->fetch_assoc()) {
						$sql_u = sprintf("UPDATE os_elements SET text_ua='%s', text_ru='%s' WHERE id='%s'",
							trim($_POST[$row['id'].'_ua']),trim($_POST[$row['id'].'_ru']),trim($row['id']));

						$res_u = $mysqli->query($sql_u);
						//print("<br>$sql_u<br>");
						header("Location:".$_SERVER['REQUEST_URI']);
					}
					break;
				case 9||10:
					while ($row = $res->fetch_assoc()) {
						$sql_u = sprintf("UPDATE os_elements SET text_ua='%s', text_ru='%s' WHERE id='%s'",
							trim($_POST[$row['id'].'_ua']),trim($_POST[$row['id'].'_ru']),trim($row['id']));

						$res_u = $mysqli->query($sql_u);
						//print("<br>$sql_u<br>");
						header("Location:".$_SERVER['REQUEST_URI']);
					}
					break;

			}
	}
	
?>
<!DOCTYPE html> 
<head>  		
	<title>Редактор - Онлайн Школа</title>
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
		
	<div class="hat_menu">
		 
			<?php if($_GET['categ']==3): ?>
			<div><a href="index.php?categ=3" class="active">Редактор блока  </a></div>
			<? else: ?>
			<div><a href="index.php?categ=3">Редактор блока  </a></div>
			<? endif; ?>
			<?php if($_GET['categ']==4): ?>
			<div><a href="index.php?categ=4" class="active">Редактор блока с видео</a></div>
			<? else: ?>
			<div><a href="index.php?categ=4">Редактор блока с видео</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==5): ?>
			<div><a href="index.php?categ=5" class="active">Редактор переключаемого блока</a></div>
			<? else: ?>
			<div><a href="index.php?categ=5">Редактор переключаемого блока</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==6): ?>
			<div><a href="index.php?categ=6" class="active">Редактор диаграммы возможностей</a></div>
			<? else: ?>
			<div><a href="index.php?categ=6">Редактор диаграммы возможностей</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==7): ?>
			<div><a href="index.php?categ=7" class="active">Редактор футера 1</a></div>
			<? else: ?>
			<div><a href="index.php?categ=7">Редактор футера 1</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==8): ?>
			<div><a href="index.php?categ=8" class="active">Редактор футера 2</a></div>
			<? else: ?>
			<div><a href="index.php?categ=8">Редактор футера 2</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==9): ?>
			<div><a href="index.php?categ=9" class="active">Редактор "Наши преимущества"</a></div>
			<? else: ?>
			<div><a href="index.php?categ=9">Редактор "Наши преимущества"</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==10): ?>
			<div><a href="index.php?categ=10" class="active">Редактор "Альтернатива для вас, если"</a></div>
			<? else: ?>
			<div><a href="index.php?categ=10">Редактор "Альтернатива для вас, если"</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==13): ?>
			<div><a href="../statics/manager.php?id=13" class="active">Менеджер статических страниц</a></div>
			<? else: ?>
			<div><a href="../statics/manager.php?id=13">Менеджер статических страниц</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==14): ?>
			<div><a href="frames.php?id=14" class="active">Менеджер Фреймов</a></div>
			<? else: ?>
			<div><a href="frames.php?id=14">Менеджер Фреймов</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==15): ?>
			<div><a href="index.php?categ=15" class="active">Страница Контакты</a></div>
			<? else: ?>
			<div><a href="index.php?categ=15">Страница Контакты</a></div>
			<? endif; ?>
		 
	</div>
	<div class="clear">
	</div>
	<hr>
		<?php
			$sql = "SELECT * FROM os_elements WHERE categ='$cat'";
			$res = $mysqli->query($sql);
			switch ($cat) {
				case 3:
					print("<h1>Редактор блока \"Поступить\" </h1>");
					break;
				case 4:
					print("<h1>Редактор блока с видео</h1>");
					break;
				case 5:
					print("<h1>Редактор поля с описанием доступа</h1>");
					break;
				case 6:
					print("<h1>Редактор прайслиста</h1>");
					break;
				case 7:
					print("<h1>Редактор поля с описанием школы</h1>");
					break;
				case 8:
					print("<h1>Редактор футера</h1>");
					break;
				break;
				case 9:
					print("<h1>Редактор рассылок</h1>");
					break;
				break;
				case 15:
					print("<h1>Редактор контактов</h1>");
					break;
				break;
			}
			
				printf("<form method='post' action='%s'>",$_SERVER['REQUEST_URI']);
				print("<input type='hidden' name='categ' value='$cat'>");
				while ($row = $res->fetch_assoc()) {
					printf("<div class='red_foot'><h4>Позиция %s(русский вариант)</h4>",$row['position']);
					printf("<textarea name='%s_ru'>%s</textarea>
						<script type='text/javascript'>
							CKEDITOR.replace('%s_ru');
						</script></div>",trim($row['id']),trim($row['text_ru']),trim($row['id']));
					printf("<div class='red_foot'><h4>Позиция %s(український варіант)</h4>",$row['position']);
					printf("<textarea name='%s_ua'>%s</textarea>
						<script type='text/javascript'>
							CKEDITOR.replace('%s_ua');
						</script></div>",trim($row['id']),trim($row['text_ua']),trim($row['id']));
					if($row['categ']=='8'){
						printf("<div class='red_foot2'><a href='../statics' target='_blank'>Создать статичную страницу</a></div>");
					}
					if($row['categ']=='6'){
						printf("<div class='red_foot2'><span>Цена услуги:</span> <input type='text' name='%s_price' value='%s' placeholder='цена'> <span>грн.</span></div>",
							trim($row['id']),$row['price']);
					}
					if($row['categ']=='4'){
						printf("<div class='red_foot2'><span>Ссылка на видео:</span> <input style='width: 500px;' type='text' name='%s_video' value='%s' placeholder='ссылка на видео'></div>",
							trim($row['id']),$row['video']);
						printf("<div class='current_v'><span>Текущее видео</span><br><iframe src='%s' width='800px' height='600px'></iframe></div>",
							$row['video']);
					}
				}
			
			print("<input type='submit' name='save'>");
			print("</form>");
		?>
		<div class="clear"></div>
	</div> </div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 