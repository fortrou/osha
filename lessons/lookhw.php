<?php 
	session_start();
	require_once '../tpl_php/autoload.php';
	
	if ( !isset($_GET['id']) )
		header("Location: ../index.php");
	$id = (int)$_GET['id'];
	$db = Database::getInstance();
		$mysqli = $db->getConnection();
	if(isset($_POST['hw_answer'])){
		//var_dump($_FILES['upload_hw']);
		if(Cfile::isSecure($_FILES['upload_hw'])){
			//print("a");
			$name = Cfile::Load($_FILES['upload_hw']);
			if($name != false)
			{
				$sql_h = "SELECT class, subject FROM os_lessons 
				WHERE id=(SELECT id_lesson FROM os_lesson_homework WHERE id='".$_GET['id']."')";
				$res_h = $mysqli->query($sql_h);
				$row_h = $res_h->fetch_assoc();
				$sql = sprintf("INSERT INTO os_homeworks(date_h,id_hw,`from`,subj,class) VALUES(DATE(now()),'%s','%s','%s','%s')",
					$id,$_SESSION['data']['id'],$row_h['subject'],$row_h['class']);
				$res = $mysqli->query($sql);

				$sql = sprintf("SELECT * FROM os_homeworks WHERE `from`='%s' AND id_hw='%s'",$_SESSION['data']['id'],$id);
				$res = $mysqli->query($sql);
				$row = $res->fetch_assoc();

				$sql = sprintf("INSERT INTO os_homework_docs(id_hw,`from`,file_name,download_status) VALUES('%s','%s','%s',0)",
					$row['id'],'student',$name);
				$res = $mysqli->query($sql);
				header("Location:".$_SERVER['REQUEST_URI']);
			}
		}
	}
	
	$sql = "SELECT * FROM os_lesson_homework WHERE id='$id'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$lesson_name = Lesson::getNameById($row['id_lesson'],$row['lang']);
	//var_dump($lesson_name);
 ?>
<!DOCTYPE html> 
<head>  		
	<title>Творческое домашнее задание - Онлайн Школа</title>
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
		<?php if(!isset($_SESSION['data'])): ?>
			<h1>Этот контент является ознакомительным, для того, чтобы получить полный доступ вам необходимо 
				<a href="http://online-shkola.com.ua/reg.php">зарегистрироваться</a></h1>
		<?php endif; ?>
		
		<div class="looknw">
		<?php
			switch($row['lang']){
				case 'ru':
					print("<h1>Творческое домашнее задание к онлайн-уроку <span>\"$lesson_name\"</span></h1>");
				break;
				case 'ua':
					print("<h1>Творче домашнє завдання до онлайн-уроку <span>\"$lesson_name\"</span></h1>");
				break;
			}
		?>
		<div>
			<?php
				printf("%s",$row['hw_text']);
			?>
		</div>
		<?php 
			$sql = sprintf("SELECT * FROM os_homeworks WHERE id_hw='%s' AND `from`='%s'",$_GET['id'],$_SESSION['data']['id']);
			$res = $mysqli->query($sql);
			$num1 = $res->num_rows;

		?>
		<?php if(isset($_SESSION['data'])): ?> 
		<?php if($_SESSION['data']['level'] == 1 && $num1 == 0): ?>
		<hr>
		<p>Загрузите файл с домашним заданием</p>
		<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" enctype="multipart/form-data">
			<input type="file" name="upload_hw">
			<input type="submit" name="hw_answer">
		</form>
		<?php else: ?>
		<h3>Вы уже отправили файл с домашним заданием, ожидайте проверки преподавателем</h3>
		<?php endif; ?>
		<?php endif; ?>
	
</div> </div> 
	</div> 
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 