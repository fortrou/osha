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
		$sql = sprintf("INSERT INTO os_news(keywords, `description`, title_n_ru,title_n_ua,text_n_ru,text_n_ua)
		 VALUES('%s','%s','%s','%s','%s','%s')",
		 $_POST['keywords'],$_POST['description'],$_POST['new_title_ru'],$_POST['new_title_ua'],$_POST['new_text_ru'],$_POST['new_text_ua']);
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		$sql_new = "SELECT MAX(id) AS num FROM os_news";
		$res_new = $mysqli->query($sql_new);
		$row_new = $res_new->fetch_assoc();
		$sql_st = "SELECT * FROM os_users WHERE level = 1";
		//print($sql_st);
		$res_st = $mysqli->query($sql_st);

		$sql_mail = "SELECT * FROM os_mail_types WHERE id=3";
			$res_mail = $mysqli->query($sql_mail);
			$row_mail = $res_mail->fetch_assoc();
		
		while($row_st = $res_st->fetch_assoc()){
			$sql = sprintf("INSERT INTO os_events(text_ua,text_ru,link,id_user,date_e,type,read_status) 
					VALUES('На сайті з\'явилася свіжа новина',
					'На сайте появилась новость','%s',%s,now(),3,0)",
					"http://online-shkola.com.ua/news/watch.php?id=".$row_new['num'],$row_st['id']);
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			$sql_mails = sprintf("SELECT * FROM os_user_mails WHERE id_user='%s' AND id_mail=3 AND yep='1'",$row_st['id']);
				//print("<br>$sql_mail<br>");
				$res_mails = $mysqli->query($sql_mails);
				//var_dump($res_mail);
				//print("<br>");
				if ($res_mails->num_rows!=0) {
					//var_dump($row_mail);
					//print("<br>");
					if ($row_mail["status"]!=1) {
						$headers = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=utf-8\r\n";
						$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n".
				    	'X-Mailer: PHP/' . phpversion();
						
						$text = sprintf($row_mail["template"],$fio);
						//var_dump($row_mail["template"]);
						//print("<br>$text<br>");
						mail($row_st['email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$text,$headers);
						//print("<br>".$row['email']);
						//print("<br>");
						if($row_st['p_email']!=""){
							//print("<br>$text<br>");
							mail($row['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua <shkola.alt@gmail.com>",$text,$headers);
							//print("<br>".$row['p_email']);
							//print("<br>");
						}
					}
					//$fio;
				}
		}
		header("Location:index.php");
	}
	
?>
<!DOCTYPE html> 
<head>  		
	<title>Создать новость - Онлайн Школа</title>
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
		<form method="post" action="index.php" enctype="multipart/form-data">
			<span>Введите ключевые слова через ","</span><br>
			<textarea name="keywords" style="width:200px; height:60px;resize:none;"></textarea><br>
			<span>Введите краткое описание страницы, желательно, не более 100 слов</span><br>
			<textarea name="description" style="width:400px; height:200px;resize:none;"></textarea>
			<p>Название новости</p>
			<input type="text" name="new_title_ru">
			<p>Назва новини</p>
			<input type="text" name="new_title_ua">
			<p>Текст новости</p>
			<textarea name="new_text_ru"></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('new_text_ru');
			</script>
			<p>Текст новини</p>
			<textarea name="new_text_ua"></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('new_text_ua');
			</script>
			<input type="submit" name="create" value="Создать">
		</form>
	 
	<form method="post" action="">
		<label>Введите ссылку<input type="text" name="link"></label>
		<input type="button" name="get_link" value="Сформировать ссылку">
		<label>Ссылка для установки в iframe<input type="text" name="new_link"></label>
	</form>
	</div> 
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 