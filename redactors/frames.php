<?php
	session_start();
	if(!isset($_GET['id'])){
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	$cat = $_GET['id'];
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
	
	if (isset($_POST['save'])) {
		//var_dump($_POST);
		$sql = "SELECT * FROM os_frames";
		$res = $mysqli->query($sql);
		while ($row = $res->fetch_assoc()) {
			if (isset($_POST['is_displayed'.$row['type']])) {
				$disp = 1;
			}
			else{
				$disp = 0;
			}
			$n_sql = sprintf("UPDATE os_frames SET frame_content_ru='%s',frame_content_ua='%s',is_displayed=$disp WHERE type='%s'",
				$_POST[$row['type'].'_ru'],$_POST[$row['type'].'_ua'],$row['type']);
//print("<br>$n_sql<br>");
			$n_res = $mysqli->query($n_sql);
		}
				
		
	}
	
?>
<!DOCTYPE html> 
<head>  		
	<title>Редактор фреймов - Онлайн Школа</title>
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
			<?php if($_GET['id']==13): ?>
			<div><a href="../statics/manager.php?id=13" class="active">Менеджер статических страниц</a></div>
			<? else: ?>
			<div><a href="../statics/manager.php?id=13">Менеджер статических страниц</a></div>
			<? endif; ?>
			<?php if($_GET['id']==14): ?>
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
			
				$sql = "SELECT * FROM os_frames";
				//print("<br>$sql<br>");
				$res = $mysqli->query($sql);
				printf("<form method='post' action='%s'>",$_SERVER['REQUEST_URI']);
				while ($row = $res->fetch_assoc()) {
					printf("<div class='red_foot'><h4>Позиция %s(русский вариант)</h4>",$row['position']);
					printf("<h4>%s</h4>",$row['type_name_ru']);
					printf("<textarea name='%s_ru'>%s</textarea>
						<script type='text/javascript'>
							CKEDITOR.replace('%s_ru');
						</script></div>",trim($row['type']),trim($row['frame_content_ru']),trim($row['type']));
					printf("<div class='red_foot'><h4>Позиция %s(український варіант)</h4>",$row['position']);
					printf("<h4>%s</h4>",$row['type_name_ua']);
					printf("<textarea name='%s_ua'>%s</textarea>
						<script type='text/javascript'>
							CKEDITOR.replace('%s_ua');
						</script></div>",trim($row['type']),trim($row['frame_content_ua']),trim($row['type']));
					if($row['is_displayed'] == 1){
						printf("<div><input type='checkbox' name='is_displayed%s' checked value='1'> Отображать?</div>",$row['type']);
					}
					if($row['is_displayed'] == 0){
						printf("<div><input type='checkbox' name='is_displayed%s' value='0'> Отображать?</div>",$row['type']);
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