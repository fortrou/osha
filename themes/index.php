<?php
	session_start();
	if(!isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) header("Location: ../index.php");
	require_once("../tpl_php/autoload.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Менеджер тем - Онлайн Школа</title>
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/themes.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
    <div class="content">
    	<?php /*meta-block*/ ?>
    	<input type='hidden' name='theme_id' value="0">
    	<?php /*meta-block*/ ?>
    	<select name='course_list' class="hat-filter" onchange="get_classes();"></select>
    	<select name='classes_list' class="hat-filter" onchange="get_subjects();"></select>
    	<select name='subjects_list' class="hat-filter"></select>
    	<div class='bottom-right-scroll' onclick="create_theme()"><p>Создать пустую тему</p></div>
        	<div class="message">
		 		<h1>Выберите тему</h1>
		   	</div>
		<div class="course-list left-position">
	        <div class="no-courses-text">
	            <p>Нет тем</p>
	        </div>
	        <ul class="real-course-list no-display">
	        </ul>
	    </div>
	    <div class="course-manager right-position">
		   	<?php /*При загрузке страницы Должна высвеиваться заглушка "Выберите курс, мейн-блок должен быть скрыт"*/ ?>
		    <div class='main_block no-display'>
	        <div class="course-manager-hat">
	        	<div class="course_meta">
	        		<label>Идентификатор темы
	        		<input type="text" disabled name="id" value=""></label>
	        		<label>Дата создания
	        		<input type="date" disabled name="theme_start_date" value=""></label><br />
	        	</div>
				<br>
	            <div class="course-name">
	            	<h3>Название курса</h3><br>
	                <label>Русское: <input type="text" name="theme_name_ru" id="name" oninput="save_course_text(this.value,'theme_name_ru')" data-rel="" value=""></label>
	                <label>Украинское: <input type="text" name="theme_name_ua" id="name" oninput="save_course_text(this.value,'theme_name_ua')" data-rel="" value=""></label>
	            </div>
				<br>
	        </div>
	        <div class="course-manager-body">
	        	<select name="courses"  class="theme-select" onchange="save_theme_filter('courses', 'theme_course', 3)"></select>
	            <select name="subjects" class="theme-select" onchange="save_theme_filter('subjects', 'theme_subject', 1)"></select><br>
	        	<select name="classes" id="classes" class="theme-select" onchange="save_theme_filter('classes', 'theme_class', 2)" multiple></select>
	            </div>
		    </div>
		</div>
		<div class="clear"></div>
	</div>
<?php
 	include ("../tpl_blocks/footer.php");
?>
</body>
</html>