<?php
	session_start();
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
	if (isset($_POST['send_mail'])) {
		//var_dump($_POST['users']);
		if($_POST['level'] == '2'){
			$sql_u = "SELECT * FROM os_users WHERE level=2";
			$res_u = $mysqli->query($sql_u);
			if ($res_u->num_rows != 0) {
				while ($row_u = $res_u->fetch_assoc()) {
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=utf-8\r\n";
					$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n";
					mail($row_u['email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					if($row_u['p_email']!=""){
						mail($row_u['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					}
				}
			}
		}
		if($_POST['level'] == '3'){
			$sql_u = "SELECT * FROM os_users WHERE level=3";
			$res_u = $mysqli->query($sql_u);
			if ($res_u->num_rows != 0) {
				while ($row_u = $res_u->fetch_assoc()) {
					$headers= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=utf-8\r\n";
					$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n";
					mail($row_u['email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					if($row_u['p_email']!=""){
						mail($row_u['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					}
				}
			}
		}
		if($_POST['level'] == '1,2,3'){
			$sql_u = "SELECT * FROM os_users WHERE level IN(1,2,3)";
			$res_u = $mysqli->query($sql_u);
			if ($res_u->num_rows != 0) {
				while ($row_u = $res_u->fetch_assoc()) {
					$headers= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=utf-8\r\n";
					$headers .= "From: <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n";
					mail($row_u['email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					if($row_u['p_email']!=""){
						mail($row_u['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					}
				}
			}
		}
		if($_POST['level'] == '1'){
			$sql_u = "SELECT * FROM os_users WHERE level=1";
			if($_POST['class'] != "all") {
				$sql_u .= " AND class='".$_POST['class']."'";
			}
			$res_u = $mysqli->query($sql_u);
			if ($res_u->num_rows != 0) {
				while ($row_u = $res_u->fetch_assoc()) {
					$headers= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=utf-8\r\n";
					$headers .= "From: Письмо с сайта <http://online-shkola.com.ua> <shkola.alt@gmail.com>\r\n Reply-To: shkola.alt@gmail.com" . "\r\n";
					mail($row_u['email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					if($row_u['p_email']!=""){
						mail($row_u['p_email'],"Рассылка от ONLINE-SHKOLA.com.ua",trim($_POST['new_text']),$headers);
					}
				}
			}
		}	
		
		header("Location:".$_SERVER['REQUEST_URI']);
	}
	
?>
<!DOCTYPE html> 
<head>  		
	<title>Сделать рассылку группе пользователей - Онлайн Школа</title>
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
			<h1>Создание рассылки</h1>
	 
		<?php
			/*if(isset($_SESSION['link']) && $_SESSION['link']!=""){
				printf("Ссылка на только что созданную вами страницу:<br>
					%s <br> Скопируйте ее и вставьте в редактор текста, сформировав как ссылку.<br>
					чтобы создать новую статичную страницу достаточно просто заново заполнить все поля и нажать 'Создать'",$_SESSION['link']);
				unset($_SESSION['link']);
			}*/
		?>
		<form method="post" action="mails.php" enctype="multipart/form-data">
			<textarea name="new_text"></textarea>
			<script type='text/javascript'>
				CKEDITOR.replace('new_text');
			</script>
			<p>Теперь выберите, кому хотите отправить письмо</p>
			
			<select name='level'>
				<option value="1,2,3">Всем</option>
				<option value="1">Ученикам</option>
				<option value="2">Учителям</option>
				<option value="3">Менеджерам</option>
			</select><br>
			<select name='class'>
				<option value="all">Все классы</option>
				<?php
					$sql = "SELECT * FROM os_class_manager WHERE is_opened=0";
					$res = $mysqli->query($sql);
					$res = $mysqli->query($sql);
					if ($res->num_rows != 0) {

						while ($row = $res->fetch_assoc()) {
							printf("<option value='%s'>Класс: %s</option>",$row['id'],$row['class_name']);
						}
					}
				?>
			</select><br>
			<input type="submit" name="send_mail" value="Создать">
		</form> 
	<!--<form method="post" action="">
		<label>Введите ссылку<input type="text" name="link"></label>
		<input type="button" name="get_link" value="Сформировать ссылку">
		<label>Ссылка для установки в iframe<input type="text" name="new_link"></label>
	</form>-->
	</div></div>
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 