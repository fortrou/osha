<?php
	session_start();
	if (!isset($_SESSION['data'])) {
		header("Location:diary_test.php");
	}
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
?>
<!DOCTYPE html> 
<head>  		
	<title>Дневник - Расписание - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">

	<?php
		include ("../tpl_blocks/head.php");
	?>

	<script src="../tpl_js/diary.js"></script>

</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	<div class="content">
		<?php
			if($_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=2";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=2",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(2, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div class="block0">
						<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang']=='ru'): ?>
			<h1>Дневник</h1>
			<div class="diary_filter">
			<a class="calendar_link" href="calendar.php">Перейти к расписанию в календаре</a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						<td><span>Дата с</span><br>
							<input type="date" name="date_s">
						</td>
						<td><span>Дата до</span><br>
							<input type="date" name="date_do">
						</td>
						<td><span>Показывать</span><br>
							<select name="pokaz">
								<option value="7">1 неделя</option>
								<option value="14">2 недели</option>
								<option value="21">3 недели</option>
								<option value="28">4 недели</option>
								<option value="35">5 недель</option>
								<option value="42">6 недель</option>
								<option value="49">7 недель</option>
								</select>
						</td>
						<?php else: ?>
			<h1>Щоденник</h1>
			<div class="diary_filter">
			<a class="calendar_link" href="calendar.php">Перейти до розкладу в календар</a>
			<form action="#" method="post"> 
			    <table>
					<tr>
						<td><span>Дата від</span><br>
							<input type="date" name="date_s">
						</td>
						<td><span>Дата до</span><br>
							<input type="date" name="date_do">
						</td>
						<td><span>Показувати</span><br>
							<select name="pokaz">
								<option value="7">1 тиждень</option>
								<option value="14">2 тижні</option>
								<option value="21">3 тижні</option>
								<option value="28">4 тижні</option>
								<option value="35">5 тижнів</option>
								<option value="42">6 тижнів</option>
								<option value="49">7 тижнів</option>
								</select>
						</td>
						<?php endif; ?>
						<!-- КЛАСС ВИДЕНЬ ТОЛЬКО АДМИНАМ -->
						<?php if ( $_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3) : ?>
						<td><span>Класс</span><br>
							<select name="class">
								<?php
									$sql_classes = "SELECT * FROM os_class_manager";
									$res_classes = $mysqli->query($sql_classes);
									while ($row_classes = $res_classes->fetch_assoc()) {
										printf("<option value='%s'>Класс %s</option>",$row_classes['id'],$row_classes['class_name']);
									}
								?>
								<!--<option value="1">1 класс</option>
								<option value="2">2 класс</option>
								<option value="3">3 класс</option>
								<option value="4">4 класс</option>
								<option value="5">5 класс</option>
								<option value="6">6 класс</option>
								<option value="7">7 класс</option>
								<option value="8">8 класс</option>
								<option value="9">9 класс</option>
								<option value="10">10 класс</option>
								<option value="11">11 класс</option>
								<option value="12">12 класс</option>-->
							</select>
						</td>
						<!-- КЛАСС ВИДЕНЬ ТОЛЬКО АДМИНАМ -->
						<?php endif; ?>
						<!-- КЛАСС ВИДЕН ТОЛЬКО Учителям -->
						<?php if ( $_SESSION['data']['level'] == 2 ) : ?>
						<?php 
							$sql = sprintf("SELECT * FROM os_teacher_class WHERE id_teacher='%s'",$_SESSION['data']['id']);
							//print($sql);
							$res = $mysqli->query($sql);
							//print("<br>$sql<br>");

						?>
							<td><span>Класс</span><br>
								<select name="class" >
									
									<?php
									$sql = sprintf("SELECT * FROM os_teacher_class WHERE id_teacher='%s'",$_SESSION['data']['id']);
									//print($sql);
									$res = $mysqli->query($sql);
										while ($row = $res->fetch_assoc()) {
											printf("<option value='%s'>%s-й класс</option>",$row['id_c'],$row['id_c']);
										}
									?>
								</select>
							</td>
						<!-- КЛАСС ВИДЕН ТОЛЬКО Учителям -->
						<?php endif;  ?>
						<?php if ( $_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3 ): ?>
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
						<!-- КЛАСС ВИДЕН ТОЛЬКО АДМИНАМ -->
						<?php endif;  ?>
					</tr>

					<input type="text" value="<?=$_COOKIE['lang']?>" hidden name="language">
					<input type="text" hidden="" name="level" value="<?=$_SESSION['data']['level']?>">
					<input type="text" hidden="" name="class" value="<?=$_SESSION['data']['class']?>">
					<input type="text" name="user" hidden value="<?=$_SESSION['data']['id']?>">

				</table>
			</form>
			</div>
			<div class="diary_table">
				<table></table>
			</div>

		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 