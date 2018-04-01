<?php
	session_start();
	if(!isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) header("Location: ../index.php");
	require_once("../tpl_php/autoload.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Менеджер курсов - Онлайн Школа</title>
	<?php
		include ("../tpl_blocks/head.php");
	?>
	<script type="text/javascript" src="../tpl_js/courses.js"></script>
</head>
<body>
	<?php
		include ("../tpl_blocks/header.php");
	?>
    <div class="content">
    	<?php /*meta-block*/ ?>
    	<input type='hidden' name='course_id' value="0">
    	<?php /*meta-block*/ ?>
    	<div class='bottom-right-scroll' onclick="create_course()"><p>Создать пустой курс</p></div>
        	<div class="message">
		 		<h1>Выберите курс</h1>
		   	</div>
		<div class="course-list left-position">
	        <div class="no-courses-text">
	            <p>Нет курсов</p>
	        </div>
	        <ul class="real-course-list no-display">
	        </ul>
	    </div>
	    <div class="course-manager right-position">
		   	<?php /*При загрузке страницы Должна высвеиваться заглушка "Выберите курс, мейн-блок должен быть скрыт"*/ ?>
		    <div class='main_block no-display'>
	        <div class="course-manager-hat">
	        	<div class="course_meta">
	        		<label>Идентификатор курса
	        		<input type="text" disabled name="id" value=""></label>
	        		<label>Дата создания
	        		<input type="date" disabled name="create_date" value=""></label><br />
	        		<label>Дата начала курса
	        		<input type="date" name="date_from" onchange="save_course_text(this.value,'date_from')" value=""></label>
	        		<label>Дата окончания курса
	        		<input type="date" name="date_till" onchange="save_course_text(this.value,'date_till')" value=""></label><br />
	        	</div>
	        	<div class="course_meta">
	        		<label><input type="checkbox" name="is_active" onchange="save_course_checkbox(this.checked,this.name)"> Активировать/деактивировать</label>
	        		<label><input type="checkbox" name="is_onMain" onchange="save_course_checkbox(this.checked,this.name)"> На главную?</label>
	        	</div>
	            <div class="course-price">
	                <label>Цена: <input type="text" name="course_price" id="price" oninput="save_course_text(this.value,'course_price')" data-rel="" value=""></label>
	                <label>Срок оплаты: <input type="text" name="payment_period" id="price" oninput="save_course_text(this.value,'payment_period')" data-rel="" value=""></label>
	            </div>
				<br>
	            <div class="course-name">
	            	<h3>Название курса</h3><br>
	                <label>Русское: <input type="text" name="course_name_ru" id="name" oninput="save_course_text(this.value,'course_name_ru')" data-rel="" value=""></label>
	                <label>Украинское: <input type="text" name="course_name_ua" id="name" oninput="save_course_text(this.value,'course_name_ua')" data-rel="" value=""></label>
	            </div>
	            <div class="course-name">
	            	<h3>Описание курса</h3><br>
	                <label>Ссылка на курс: <input type="text" name="course_desc_link" id="name" oninput="save_course_text(this.value,'course_desc_link')" data-rel="" value=""></label>
	            </div>
				<br>
	        </div>
	        <div class="course-manager-body">
	           
	            </div>
	            <div class='bottom-left-scroll' onclick="delete_course()"><p>Удалить курс</p></div>
		    </div>
		</div>
		<div class="clear"></div>
	</div>
<?php
 	include ("../tpl_blocks/footer.php");
?>
</body>
</html>