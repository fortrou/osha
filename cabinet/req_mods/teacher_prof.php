<div class="profile">
<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
	<?php if($_SESSION['data']['level'] == 4): ?>
	<p>Классы</p>
	<?php
		$classes_t = $user->getTclasses();
			//var_dump($classes_t);

	?>
	<input type="hidden" name="id_pr" value="<?=$user->uniGet('id') ?>">
	<input type="hidden" name="prev_status" value="2">
	
	<select size='4' id="classes" class="select-width-200" name="classes[]" multiple style="height:150px;">
		<?php
			
			for($i = 1; $i <= 11; $i++){
				if(in_array($i, $classes_t))
					print("<option value='$i' selected>класс $i</option>");
				else
					print("<option value='$i'>класс $i</option>");
			}
		?>
	</select><br>
	<p>Предметы</p>
	<select size='4' id="subjects" name="subjects[]" multiple style="height:200px;">
		<option disabled>Выберите класс</option>
	</select><br>
	<?php endif; ?>
	<?php if($_SESSION['data']['level'] != 4): ?>
	<div class='head_profile'>
		<h1>Данные уителя</h1>
		<ul>
			<input type="hidden" name="id_pr" value="<?=$user->uniGet('id') ?>">
			<li>Login: <b><?php print($user->uniGet('login')) ?></b></li>
			<li>Фамилия: <b><?php print($user->uniGet('surname')) ?></b></li>
			<li>Имя: <b><?php print($user->uniGet('name')) ?></b></li>
			<li>Отчество: <b><?php print($user->uniGet('patronymic')) ?></b></li>
		</ul><br>
		<ul>
			<li>E-mail: <b><?php print($user->uniGet('email')) ?></b></li>

		</ul><br>
		<?php else: ?>
			<ul>
				<input type="hidden" name="id_pr" value="<?=$user->uniGet('id') ?>">
				<li>login: <input type="text" name="login" disabled value="<?=$user->uniGet('login')?>"></li>
				<li>Фамилия: <input type="text" name="surname" value="<?=$user->uniGet('surname')?>"></li>
				<li>Имя: <input type="text" name="name" value="<?=$user->uniGet('name')?>"></li>
				<li>Отчество: <input type="text" name="patronymic" value="<?=$user->uniGet('patronymic')?>"></li>
			</ul><br>
			<ul>
				<li>E-mail: <input type="text" name="email" value="<?=$user->uniGet('email')?>"></li>
			</ul>
		<?php endif; ?>
	</div>
	<?php
			if($user->uniGet('avatar') != "" && $user->uniGet('avatar') != "Array"){
				print("<img width='300px' height='300px' src='../upload/avatars/".$user->uniGet('avatar')."'>");
			}
			else{
				print("<img width='300px' height='300px' src='../upload/avatars/default.jpg'>");
			}
		?>
	<div class="footer_profile">
			<?php if($_SESSION['data']['level'] == 3 || $_SESSION['data']['level'] == 4 ): ?>
				<input type="submit" name="redact_target" value="Редактировать данные">
			<?php endif; ?>
			<input type="hidden" name="id_target" value="<?=$user->uniGet('id')?>">
			<input type="hidden" name="lock_status_target" value="<?=$user->uniGet('lock_status')?>">
			<?php if($user->uniGet('lock_status') == 0 && $_SESSION['data']['level'] == 4): ?>
				<input type="submit" name="lock_target" value="Заблокировать">
			<?php endif; ?>
			<?php if($user->uniGet('lock_status') == 1 && $_SESSION['data']['level'] == 4): ?>
				<input type="submit" name="unlock_target" value="Разблокировать">
			<?php endif; ?>
		
	</div>
</form>
<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" onsubmit="return confirm('Вы действительно хотите удалить данного пользователя?');">
		<input type="hidden" name="id_target" value="<?=$user->uniGet('id')?>">
		<input type="submit" name="delete_target" value="Удалить">
	</form>

	<div class="courses-container">
		<?php
			$sql_courses = "SELECT * FROM os_courses_meta WHERE is_active=1";
			$res_courses = $mysqli->query($sql_courses);
			if($res_courses->num_rows != 0) {
				while($row_courses = $res_courses->fetch_assoc()) {
					$sql_teacher_payment = sprintf("SELECT * FROM os_courses_teachers WHERE id_course=%s AND id_teacher=%s",$row_courses['id'],$user->uniGet('id'));
					$res_teacher_payment = $mysqli->query($sql_teacher_payment);
					if($res_teacher_payment->num_rows != 0) {
						$row_teacher_payment = $res_teacher_payment->fetch_assoc();
						printf("<form method='post' action=''>
									<label>Курс: %s <input type='submit' name='delete_from_course' value='Удалить из курса'></label>
									<input type='hidden' name='id_user' value='%s'>
									<input type='hidden' name='id_course' value='%s'>
									<input type='hidden' name='is_student' value='0'>
								</form>
								<div onclick='open_course_redact(%s,%s)' style='cursor:pointer;'><p>Открыть информацию о курсе по этому учителю</p></div>",
								$row_courses['course_name_ru'],$user->uniGet('id'),$row_courses['id'],$user->uniGet('id'),$row_courses['id']);
					} else {
						printf("<form method='post' action=''>
									<label>Курс: %s <input type='submit' name='add_to_course' value='Добавить к курсу'></label>
									<input type='hidden' name='id_user' value='%s'>
									<input type='hidden' name='id_course' value='%s'>
									<input type='hidden' name='is_student' value='0'>
								</form>",$row_courses['course_name_ru'],$user->uniGet('id'),$row_courses['id']);
					}
				}
			}
		?>
	</div>
	<div class="courses-settings">
		<p onclick="close_course_redact()"  style="cursor:pointer;">Закрыть управление предметами</p>
		<input type="hidden" name="id_user" value="">
		<input type="hidden" name="id_course" value="">
		<select name="course-subjects" id="course-subjects" class="course-subjects select-width-200" multiple onchange="save_subjectsOnTeacher()">
			<option value="0">Нет предметов</option>
		</select>
	</div>
	<div class="clear"></div>
</div>