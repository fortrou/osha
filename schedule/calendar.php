<?php
	session_start();
	if (!isset($_SESSION['data'])) {
		header("Location:calendar_test.php");
	}
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	//var_dump($_SESSION['data']);
	

?>
<!DOCTYPE html> 
<head>  		
	<title>Календарь - Расписание - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<script src="https://code.jquery.com/jquery-2.2.1.min.js"   integrity="sha256-gvQgAFzTH6trSrAWoH1iPo9Xc96QxSZ3feW6kem+O00="   crossorigin="anonymous"></script>
	<script src="../tpl_js/calendar_new.js"></script>

	<?php
		include ("../tpl_blocks/head.php");
	?>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
		<?php
			if($_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=1";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=1",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(1, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div class="block0">
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'): ?>
			<h1>Календарь</h1>
			<div class="calendar_filter">
			<a class="calendar_link" href="diary.php">Перейти к расписанию в дневнике</a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						 
						<td><span>Месяц</span><br>
			<?php else: ?>
			<h1>Календар</h1>
			<div class="calendar_filter">
			<a class="calendar_link" href="diary.php">Перейти до розкладу у щоденнику</a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						 
						<td><span>Місяць</span><br>
			<?php endif; ?>
							<select name="pokaz">
							</select>
						</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО АДМИНАМ -->
						<?php if ( $_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3 ) : ?>
						<?php 
							$sql = "SELECT DISTINCT * FROM os_class_manager";
							
							$res = $mysqli->query($sql);		

							$all_classes = "";
							while ($row = $res->fetch_assoc()) {
								$all_classes .= $row['id'].',';
							}
							$all_classes = rtrim($all_classes,",");
							//print("<br>$sql<br>");

						?>
							<td><span>Класс</span><br>
								<select name="class" >
									<option value="0" selected>Все классы</option>
									<?php
									$sql = "SELECT DISTINCT * FROM os_class_manager";
									$res = $mysqli->query($sql);
										while ($row = $res->fetch_assoc()) {
											printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
										}
									?>
								</select>
							</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО АДМИНАМ -->
						<?php endif;  ?>
						
						<!-- КЛАСС ВИДЕН ТОЛЬКО Учителям -->
						<?php if ( $_SESSION['data']['level'] == 2 ) : ?>
						<?php 
							$sql = sprintf("SELECT DISTINCT * FROM os_teacher_class WHERE id_teacher='%s'",$_SESSION['data']['id']);
							//print($sql);
							$res = $mysqli->query($sql);		

							$all_classes = "";
							while ($row = $res->fetch_assoc()) {
								$all_classes .= $row['id_c'].',';
							}
							$all_classes = rtrim($all_classes,",");
							//print("<br>$sql<br>");
							$sql = sprintf("SELECT * FROM os_class_manager WHERE id IN(SELECT DISTINCT id_c FROM os_teacher_class WHERE id_teacher='%s')",$_SESSION['data']['id']);
									//print("<br>$sql<br>");

						?>
							<td><span>Класс</span><br>
								<select name="class" >
									<option value="0" selected>Все классы</option>
									<?php
									
									$res = $mysqli->query($sql);
										while ($row = $res->fetch_assoc()) {
											printf("<option value='%s'>Класс %s</option>",$row['id'],$row['class_name']);
										}
									?>
								</select>
							</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО Учителям -->
						<?php endif;  ?>
						<?php if ( $_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3 ) : ?>
						<?php
								$sql = "SELECT * FROM os_subjects";
								$result = $mysqli->query($sql);
								$subjects_all = "";
								while($row = $result->fetch_assoc()){
									$subjects_all .= $row['id'].",";
								}
								$subjects_all = rtrim($subjects_all,",");
							?>
							<td><span>Предмет</span><br>
								<select name="subject" >
									<option value="0" selected>Все предметы</option>
								</select>
							</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО АДМИНАМ -->
						<?php endif;  ?>
						<?php if ( $_SESSION['data']['level'] == 2 ) : ?>
							<td><span>Предмет</span><br>
								<select name="subject" >

								</select>
							</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО АДМИНАМ -->
						<?php endif;  ?>
						<?php if ( $_SESSION['data']['level'] == 1 ) : ?>
						<?php
							$sql = sprintf("SELECT * FROM os_subjects WHERE id 
								IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_SESSION['data']['id']);
							$result = $mysqli->query($sql);
							$subjects_all = "";
							//print("<br>$sql<br>");
							while($row = $result->fetch_assoc()){
								$subjects_all .= $row['id'].",";
							}
							$subjects_all = rtrim($subjects_all,",");
						?>
							<td><span>Предмет</span><br>
								<select name="subject" >
									<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru'): ?>
									<option value="0" selected>Все предметы</option>
									<?php else: ?>
									<option value="0" selected>Усі предмети</option>
									<?php endif; ?>
									<?php
									$sql = sprintf("SELECT * FROM os_subjects WHERE id 
										IN(SELECT id_subject FROM os_student_subjects WHERE id_student='%s')",$_SESSION['data']['id']);
									//print($sql);
									$res = $mysqli->query($sql);
										while ($row = $res->fetch_assoc()) {
											printf("<option value='%s'>%s</option>",$row['id'],$row['name_'.$_COOKIE['lang']]);
										}
									?>
								</select>
							</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО Ученикам -->
						<?php endif;  ?>
						<input type="text" value="<?=$_COOKIE['lang']?>" hidden name="language">
						<input type="text" hidden="" name="level" value="<?=$_SESSION['data']['level']?>">
						<input type="text" name="class" hidden value="<?=$_SESSION['data']['class']?>">
						<input type="text" name="user" hidden value="<?=$_SESSION['data']['id']?>">
						<input type="hidden" name="req_year">

					</tr>
				</table>
			</form>
			<span class="cyear"></span>
			</div>
			<div class="calendar_table">
				<div class="left_cal"></div>
				<div class="right_cal"></div>
				<table id="calendar"></table>
			</div>
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 