<? if($_SESSION['data']['level'] == 4): ?>

<form action="<?=$_SERVER['PHP_SELF'];?>#tab2" method="post">
<table class="cab_usr_list_css">
	<tr>
		<td>
			Класс<br>
			<select name="course_class_pm" data-type="course_payment_select">
				<?php
					$sql_classes_pays = sprintf("SELECT * FROM os_class_manager WHERE is_opened=0");
					$res_classes_pays = $mysqli->query($sql_classes_pays);
					print("<option value='0'>Все классы</option>");
					while ($row_classes_pays = $res_classes_pays->fetch_assoc()) {
						printf("<option value='%s'>Класс %s</option>",$row_classes_pays['id'],$row_classes_pays['class_name']);
					}
				?>
			</select>
		</td>
		<td>
			Статус оплаты<br>
			<select name="course_status_pm" data-type="course_payment_select">
				<option value="0" selected>Статус: Все</option>
				<option value="1">Статус: Не оплачено</option>
				<option value="2">Статус: Оплачено</option>
				
			</select>
		</td>
		<td>
			Курс<br>
			<select name="course_select_pm" data-type="course_payment_select">
				<?php
					$sql_courses_meta = sprintf("SELECT * FROM os_courses_meta WHERE is_active=1");
					$res_courses_meta = $mysqli->query($sql_courses_meta);
					print("<option value='0'>Все курсы</option>");
					while ($row_courses_meta = $res_courses_meta->fetch_assoc()) {
						printf("<option value='%s'>Курс: %s</option>",$row_courses_meta['id'],$row_courses_meta['course_name_ru']);
					}
				?>
			</select>
		</td>
		<td>
			<form action="#" method="post"> 
				<input type="search" name="course_search" placeholder=""> <input type="button" value="Поиск" 
				onclick="get_people_course_pm(this.form.course_search.value,'course_pay_manager_users')">
			</form>
		</td>
	</tr>
</table>
</form>
<table id="course_pay_manager_users">
	<thead>
		<tr>
			<td>
				ФИО
			</td>
			<td>
				Тип образования
			</td>
			<td>
				Дата окончания оплаты
			</td>
		</tr>
	</thead>
	<tbody></tbody>
</table>
<? endif; ?> 