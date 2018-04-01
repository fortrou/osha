<?php
	session_start();
	require_once("../tpl_php/autoload.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
?>
<!DOCTYPE html> 
<head>  		
	<title>Табель - Журнал - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<script type="text/javascript" src="../tpl_js/tabel.js"></script>
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
				$sql_frame = "SELECT * FROM os_frames WHERE type=4";
				$res_frame = $mysqli->query($sql_frame);
				$row_frame = $res_frame->fetch_assoc();
				$sql_uf = sprintf("SELECT * FROM os_user_frames WHERE id_user='%s' AND id_frame=4",$_SESSION['data']['id']);
				$res_uf = $mysqli->query($sql_uf);
				$row_uf = $res_uf->fetch_assoc();
				if ($row_uf['is_displayed'] == 1) {
					printf("<div class='frame'>
							<span class='frame_close_ss' onclick=\"close_once(4, %s)\">x</span>
							<span class='frame_close_none'><input type='checkbox' name='no_more'>$dontShow</span>
							<p>%s</p>
						</div>",$_SESSION['data']['id'],$row_frame['frame_content_'.$_COOKIE['lang']]);
				}
			}
		?>
		<div class="block0">
			<h1>Табель</h1>
			<?php
	            if ($_SESSION['data']['level'] == 1) {
	            	$sql_class = sprintf("SELECT * FROM os_class_manager WHERE id='%s'",$_SESSION['data']['class']);
	            	$res_class = $mysqli->query($sql_class);
	            	$row_class = $res_class->fetch_assoc();

	                printf("<h3 class='fio_print' style='display:none;'>%s %s %s</h3>
	                	<h2 class='fio_print' style='display:none;'>%s</h2>",
	                	$_SESSION['data']['surname'],$_SESSION['data']['name'],$_SESSION['data']['patronymic'],$row_class['class_name']);
	            }
	        ?>
			<a style="    float: right;
    margin-left: 50px;" class="tables_adm_link" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/jurnal/jurnal.php">Ваш журнал</a> 
	        <?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru'): ?>
			<div class="print_btn"><a href="#" onclick="window.print()">Печатать табель</a>
			<?php else: ?>
			<div class="print_btn"><a href="#" onclick="window.print()">Друкувати табель</a>
			<?php endif; ?>
			</div>
			<!-- ВИД ТАБЕЛЯ ДЛЯ УЧИТЕЛЕЙ -->
			<div class="tables_adm_filter">  
				<table class="fulle">
					<tr>
						<td>
							<form action="#" method="post"> 
								<table>
									<?php if($_SESSION['data']['level']!=1): ?> 
									<tr> 						 
										<td><span>Класс</span><br>
											
											<select name="class" id="class">
												<?php
												if($_SESSION['data']['level'] == 4 || $_SESSION['data']['level'] == 3){
													$sql = "SELECT * FROM os_class_manager";
												}
												if($_SESSION['data']['level'] == 2){
													$sql = sprintf("SELECT * FROM os_class_manager WHERE id IN(SELECT id_c FROM os_teacher_class WHERE id_teacher='%s')",
														$_SESSION['data']['id']);
												}
												$res = $mysqli->query($sql);
												if($res->num_rows != 0){
													while($row = $res->fetch_assoc()){
														printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
													}
												}
												?>
											</select>
										</td>
													
									</tr>
									<? endif; ?>
								</table>
							</form>  
						</td>
						<td><!-- <a class="btn_b" href="#">Добавить оценку</a> -->
			<?php if($_SESSION['data']['level']!=1 && $_SESSION['data']['level']!=3): ?> 			
			 <a class="tabel_link1" href="#" onclick="easy_open()">Добавить оценку</a> 
			<div id="tabel_link_content_1" style="display: none;margin-left: 100px;" >
				<form>
					<select name='type' id="type">
						<option value="first_s">За первый семестр</option>
						<option value="second_s">За второй семестр</option>
						<option value="year">За год</option>
						<option value="gia">За гиа</option>
						<option value="final">Итоговая</option>
					</select>
					<select name="subjects" id="subject">
	
					</select>
					<p>Введите оценку от 1 до 12</p>
					<input type="text" name="mark" id="mark" placeholder="Введите оценку от 1 до 12">
					<input type="button" name="update_tabel" value="Обновить">
					<input type="button" name="undo_tabel" value="Отменить" onclick="easy_close()">
				</form>
			</div> 
			<? endif; ?>
			
						</td>
						<td></td>
					</tr><tr>
					<?php if($_SESSION['data']['level']!=1): ?> 
					
						<td colspan="2">							
							<form action="#" method="post"> 
								<input type="search" name="search" placeholder="ученик"> <input type="submit" value="Поиск"> 
							</form> 							
						</td>
						
				<? endif; ?> 
					</tr>
				</table>			
				
				
			</div>
			
			<div class="tables_adm_table">
			<?php if($_SESSION['data']['level']!=1): ?> 
				<div class='left'>
					<select name="students" size="5" id="students">
					
					</select>
				</div>
			<? endif; ?>
			<input type="hidden" name="level" value="<?=$_SESSION['data']['level']?>">
			<input type="hidden" name="id" value="<?=$_SESSION['data']['id']?>">
			<input type="hidden" name="lang" value="<?=$_COOKIE['lang']?>">
				<div <?php if($_SESSION['data']['level']==1) {echo ("style=\"width:100%;\"");}?>  class="right">
				<table class="tabel">	
					<thead>
						<?php if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] == 'ru'): ?>
						<tr>
							<th>Предмет</th>
							<th>За<br>1 семестр</th>
							<th>За<br>2 семестр</th>
							<th>За год</th>
							<th>За ГИА</th>
							<th>Итоговая</th>
						</tr>
						<?php else: ?>
						<tr>
							<th>Предмет</th>
							<th>За<br>1 семестр</th>
							<th>За<br>2 семестр</th>
							<th>За рік</th>
							<th>За ДПА</th>
							<th>Підсумкова</th>
						</tr>
						<?php endif; ?>
					</thead>
					<tbody>

					</tbody>
				</table>

				</div>
				<div class="clear"></div>



			</div>
			<!-- ВИД ТАБЕЛЯ ДЛЯ УЧИТЕЛЕЙ -->
			
			
			
		</div> 
	</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 