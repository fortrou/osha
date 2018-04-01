<?php
	session_start();
	if(!isset($_GET['id']) || $_GET['id'] != 13){
		header("Location:manager.php?id=13");
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
	$sql = "SELECT * FROM os_statics";
	//print($sql);
	$res = $mysqli->query($sql);
	while ($row = $res->fetch_assoc()) {
		$tstr = sprintf("del%s",$row['id']);
		if (isset($_POST[$tstr])) {
			$sql_del = sprintf("DELETE FROM os_statics WHERE id=%s",$row['id']);
			//print("<br>$sql_del<br>");
			$res_del = $mysqli->query($sql_del);
			header("Location:".$_SERVER["REQUEST_URI"]);
		}
	}
	$sql = "SELECT * FROM os_news";
	//print($sql);
	$res = $mysqli->query($sql);
	while ($row = $res->fetch_assoc()) {
		$tstr = sprintf("deln%s",$row['id']);
		if (isset($_POST[$tstr])) {
			$sql_del = sprintf("DELETE FROM os_news WHERE id=%s",$row['id']);
			//print("<br>$sql_del<br>");
			$res_del = $mysqli->query($sql_del);
			header("Location:".$_SERVER["REQUEST_URI"]);
		}
	}
	$sql = "SELECT * FROM os_statics";
	//print($sql);
	$res = $mysqli->query($sql);
	$sql_n = "SELECT * FROM os_news";
	//print($sql);
	$res_n = $mysqli->query($sql_n);
	
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
	<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	
<div class="content">
		<div class="block0">
				<div class="hat_menu">
			<?php if($_GET['categ']==3): ?>
			<div><a href="../redactors/index.php?categ=3" class="active">Редактор блока  </a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=3">Редактор блока  </a></div>
			<? endif; ?>
			<?php if($_GET['categ']==4): ?>
			<div><a href="../redactors/index.php?categ=4" class="active">Редактор блока с видео</a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=4">Редактор блока с видео</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==5): ?>
			<div><a href="../redactors/index.php?categ=5" class="active">Редактор переключаемого блока</a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=5">Редактор переключаемого блока</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==6): ?>
			<div><a href="../redactors/index.php?categ=6" class="active">Редактор диаграммы возможностей</a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=6">Редактор диаграммы возможностей</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==7): ?>
			<div><a href="../redactors/index.php?categ=7" class="active">Редактор футера 1</a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=7">Редактор футера 1</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==8): ?>
			<div><a href="../redactors/index.php?categ=8" class="active">Редактор футера 2</a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=8">Редактор футера 2</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==9): ?>
			<div><a href="../redactors/index.php?categ=9" class="active">Редактор "Наши преимущества"</a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=9">Редактор "Наши преимущества"</a></div>
			<? endif; ?>
			<?php if($_GET['categ']==10): ?>
			<div><a href="../redactors/index.php?categ=10" class="active">Редактор "Альтернатива для вас, если"</a></div>
			<? else: ?>
			<div><a href="../redactors/index.php?categ=10">Редактор "Альтернатива для вас, если"</a></div>
			<? endif; ?>
			<?php if($_GET['id']==13): ?>
			<div><a href="../statics/manager.php?id=13" class="active">Менеджер статических страниц</a></div>
			<? else: ?>
			<div><a href="../statics/manager.php?id=13">Менеджер статических страниц</a></div>
			<? endif; ?>
			<?php if($_GET['id']==14): ?>
			<div><a href="../redactors/frames.php?id=14" class="active">Менеджер Фреймов</a></div>
			<? else: ?>
			<div><a href="../redactors/frames.php?id=14">Менеджер Фреймов</a></div>
			<? endif; ?>
				</div>
		<div class="clear">
		</div>
		<div class='red_foot2'><a href='index.php' target='_blank'>Создать статичную страницу</a></div>
			<!--<h1>Редактировать новость</h1>-->
			<form method="post" action="manager.php" onsubmit="return confirm('Вы действительно хотите удалить эту статическую страницу')">
			<h2>Статические страницы</h2>
			<table>
				<thead>
					<tr>
						<td>
							Название страницы
						</td>
						<td>
							Назва сторінки
						</td>
						<td>
							Удалить
						</td>
						<td>
							Редактировать
						</td>
					</tr>
				</thead>
				<tbody>
		<?php
		while ($row = $res->fetch_assoc()) {
			printf("<tr><td><a href='watch.php?id=%s'>%s</a></td><td><a href='watch.php?id=%s'>%s</a></td>
				<td><input type='submit' name='del%s' value='Удалить'></td><td><a href='redactor.php?id=%s'>Редактировать</a></td></tr>",
				$row['id'],$row['title_ru'],$row['id'],$row['title_ua'],$row['id'],$row['id']);
		}
			/*printf("<h1>%s</h1>",$row['title_'.$_COOKIE['lang']]);
			printf("<p>%s</p>",$row['text_'.$_COOKIE['lang']]);*/
		?>
				</tbody>
			</table>
			<h2>Новости</h2>
			<table>
				<thead>
					<tr>
						<td>
							Название новости
						</td>
						<td>
							Назва новини
						</td>
						<td>
							Удалить
						</td>
						<td>
							Редактировать
						</td>
					</tr>
				</thead>
				<tbody>
		<?php
		while ($row_n = $res_n->fetch_assoc()) {
			printf("<tr><td><a href='../news/watch.php?id=%s'>%s</a></td><td><a href='../news/watch.php?id=%s'>%s</a></td>
				<td><input type='submit' name='deln%s' value='Удалить'></td><td><a href='../news/redact.php?id=%s'>Редактировать</a></td></tr>",
				$row_n['id'],$row_n['title_n_ru'],$row_n['id'],$row_n['title_n_ua'],$row_n['id'],$row_n['id']);
		}
			/*printf("<h1>%s</h1>",$row['title_'.$_COOKIE['lang']]);
			printf("<p>%s</p>",$row['text_'.$_COOKIE['lang']]);*/
		?>
				</tbody>
			</table>
			</form>
		<div class="clear"></div>
	</div></div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 