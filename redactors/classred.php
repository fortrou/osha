<?php
	session_start();
	require_once('../tpl_php/autoload.php');
	if(!isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) header("Location: ../");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	/*function getVideoLink($link) 
	{
		$video = $link;
	    $pos_1 = strpos($video,'com');
	    $str = substr($video, 0 , $pos_1 + 4 );
	   
	    $str .= 'embed/';
	    
	    $pos_2  = strpos($video , 'v=');
	    $str .= substr($video , $pos_2 + 2 , strlen($video) - $pos_2 - 2 );



	    return $str;
	}*/

	
?>
<!DOCTYPE html> 
<head>  		
	<title>Редактор классов - Онлайн Школа</title>
	<meta name="description" content=" ">
	<meta name="keywords" content=" ">
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="resps.js"></script>
	<!--<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
	<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>-->
	<script type="text/javascript" src="../editors/ckeditor/ckeditor.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
	
	
	<div class="content">
		<div id="pop_create" >
			<div class="close" onclick="close_modal()"></div>

			<p>Введите имя класса</p>
			<input type="text" name="create_name"></input>
			<p>Укажите предметы</p>
			<?php
				$sql_create = sprintf("SELECT * FROM os_subjects");
				$res_create = $mysqli->query($sql_create);

			?>
			<select name="create_subj" style="height:200px;" id="create_subj" multiple>
				<?php
					while ($row_create = $res_create->fetch_assoc()) {
						printf("<option value='%s'>%s</option>",$row_create['id'],$row_create['name']);
					}
				?>
			</select><br>
			<?php
				$sql_create = sprintf("SELECT id, CONCAT(surname,' ',name) AS fi FROM os_users WHERE level=3");
				$res_create = $mysqli->query($sql_create);

			?>
			<select name="create_manager">
				<option value="0" selected>Не выбран</option>
				<?php
					while ($row_create = $res_create->fetch_assoc()) {
						printf("<option value='%s'>%s</option>",$row_create['id'],$row_create['fi']);
					}
				?>
			</select><br>
			<input type="button" name="create_ok" value="Создать"></input>
			<input type="button" name="create_close" value="Отменить" onclick="close_modal()"></input>
		</div>
		<div class="block0">
			<a href='subjred.php'>Редактор предметов</a>
		<h1>Редактор классов</h1>
	<div class="hat_class"></div>
	<div class="form_red_name">
		<input type="text" name="class_name"></input>
		<input type="button" name="confirm_name" value="Подтвердить имя"></input>
	</div>

	<div class="opened_class">
		<p>Открытый класс: </p>
		<?php
			$sql = "SELECT * FROM os_class_manager";
			$res = $mysqli->query($sql);
		?>
		<select name="opened_class">
			<?php
				$sql_1 = "SELECT * FROM os_class_manager WHERE is_opened='1'";
				$res_1 = $mysqli->query($sql_1);
				if ($res_1->num_rows == 0) {
					print("<option value='0'>Не выбрано</option>");
				}
				while ($row = $res->fetch_assoc()) {
					if($row['is_opened'] == 1)
						printf("<option value='%s' selected>%s</option>",$row['id'],$row['class_name']);
					else
						printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
				}
			?>
		</select>

	</div>
	<hr>
		<?php
			$sql = "SELECT * FROM os_class_manager";
			$res = $mysqli->query($sql);
		?>
		Выберите класс
		<select size='1' name="red_class">
			<option value="0">Не выбран</option>
			<?php
				while ($row = $res->fetch_assoc()) {
					printf("<option value='%s'>%s</option>",$row['id'],$row['class_name']);
				}
			?>
		</select><br>
		<select size='5' name="red_subject" multiple style="height:200px;display:none;" id="red_subject"></select><br>
		<!-- Вывод студентов -->
		<div class="stud_list" id="red_stud_list">
		</div>
		<!-- Вывод студентов -->
		<input type="button" value="Создать класс" name="create_form" onclick="open_modal()"></input>
		<input type="button" value="Удалить класс" name="del_cur_class"></input>
	</div> 
</div> 
	
	<?php
		include ("../tpl_blocks/footer.php");
	?>
</body> 
</html> 