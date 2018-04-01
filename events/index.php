<?php
	session_start();
	if(isset($_SESSION['data']) && (!isset($_SESSION['data']['currentCourse']) || $_SESSION['data']['currentCourse'] == 0)) 
	    require_once '../tpl_php/autoload.php';
	else
	    require_once '../tpl_php/autoload_light.php';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

?>
<!DOCTYPE html> 
<html>
<head>  		
	<title>События - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<style>
		.unread span{
			cursor: pointer;
		}
	</style>
	<script type="text/javascript" src="../tpl_js/events.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?> 
	<div class="content">
		<?php
			if($_SESSION['data']['level'] == 1){
				$sql_frame = "SELECT * FROM os_frames WHERE type=6";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=6",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(6, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div class="block0">
		<div class="doing_filter">
			 
			<form action="#" method="post"> 
			    <table>
					<tbody><tr> 
						<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru") {
								$select_type 	 = "Выбрать тип события";
								$all_events  	 = "Все события";
								$lessons 		 = "Уроки";
								$news 			 = "Новости";
								$checked_hworks  = "Проверка ДЗ";
								$new_tabel_marks = "Новые оценки в табеле";
								$payments  		 = "Оплаты";
							} else {
								$select_type 	 = "Вибрати тип події";
								$all_events  	 = "Усі події";
								$lessons 		 = "Уроки";
								$news 			 = "Новини";
								$checked_hworks  = "Перевірка ДЗ";
								$new_tabel_marks = "Нові оцінки в табелі";
								$payments  		 = "Оплати";
							}
							if($_SESSION['data']['currentCourse'] == 0) {
								$all_events_val = "'2','3','4','5','6'";
							} else {
								$all_events_val = "'2','3','4'";
							}
						?>
						<td><span><?php echo $select_type; ?></span><br>
							<select name="ev_type">		
									<option selected value="<?php echo $all_events_val; ?>"><?php echo $all_events; ?></option>
									<!--<option value="'2'"><?php echo $lessons; ?></option>-->
									<option value="'3'"><?php echo $news; ?></option>
									<option value="'4'"><?php echo $checked_hworks; ?></option>
									<?php if($_SESSION['data']['currentCourse'] == 0): ?>
										<option value="'5'"><?php echo $new_tabel_marks; ?></option>
										<option value="'6'"><?php echo $payments; ?></option>
									<?php endif; ?>
								
							</select>
						</td>						 
					</tr>
				</tbody></table>
			</form>
			</div>
			<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>	
			<h1>События</h1>
			<?php else: ?>
			<h1>Події</h1>
			<?php endif; ?>
			<div class="clear"></div>
		<div class="doing_table">
		<input type="hidden" name="id" value="<?=$_SESSION['data']['id']?>">
		<input type="hidden" name="lang" value="<?=$_COOKIE['lang']?>">
		<input type="hidden" name="count" value="50">
		<input type="hidden" name="count_all">
		<input type="hidden" name="cur_page" value="1">
		<input type="hidden" name="cur_bot_lim" value="0">
		<input type="hidden" name="cur_top_lim" value="50">

		<div class="events">
		
		</div>
		<div class="paginate">
		</div>
	</div> 
	</div> 
	</div> 
	
	<?php
	
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 