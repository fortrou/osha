<div class="profile">

	<form method="post" action="<?=$_SERVER['REQUEST_URI']?>">
<table>
<tr>
<td><div class='head_profile'>
		<h1>Данные ученика</h1>
		<?php if($_SESSION['data']['level']<4): ?>
		<ul>
			<input type="hidden" name="id_pr" value="<?php $user->uniGet('id') ?>">
			<li>Login: <b><?php print($user->uniGet('login')) ?></b></li>
			<li>Фамилия: <b><?php print($user->uniGet('surname')) ?></b></li>
			<li>Имя: <b><?php print($user->uniGet('name')) ?></b></li>
			<li>Отчество: <b><?php print($user->uniGet('patronymic')) ?></b></li>
		</ul><br>
		<ul>
			<li>E-mail: <b><?php print($user->uniGet('email')) ?></b></li>
			<li>Телефон: <b><?php print($user->uniGet('phone')) ?></b></li>
			<li>Город: <b><?php print($user->uniGet('city')) ?></b></li>
			<li>Откуда узнал: <br><?php print($user->uniGet('appendix'));?></li>
		</ul><br>
		<select name="class_profile" id="classes">
			<?php
				for($i = 1; $i <= 11; $i++){
					if($user->uniGet('class') == $i)
						print("<option value='$i' selected disabled>$i</option>");
					else
						print("<option value='$i' disabled>$i</option>");
				}
			?>
		</select>
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
			<li>Телефон: <input type="text" name="phone" value="<?=$user->uniGet('phone')?>"></li>
			<li>Город: <input type="text" name="city" value="<?=$user->uniGet('city')?>"></li>
			<li>Откуда узнал: <br><textarea name="appendix" maxlength="50"><?php print($user->uniGet('appendix'));?></textarea></li>
		</ul><br>
		<table>
			<tr>
				<td>Класс ученика:</td>
				<td>Доступ к архивам: </td>
				<td>В школе с: </td>
			</tr>
			<tr>
				<td>
				<select name="class">
					<?php
						for($i = 1; $i <= 11; $i++){
							if($user->uniGet('class') == $i)
								print("<option value='$i' selected>$i</option>");
							else
								print("<option value='$i'>$i</option>");
						}
					?>
				</select>
				</td>
				<td>
					<?php
						User::get_yearNums(3, $user->uniGet('id'));
					?>
					<select size="6" name="archieve_access[]" multiple>
						<option value="0">Нет архива</option>
						<?php
							print(User::get_yearNums(3, $user->uniGet('id')));
						?>
					</select>
				</td>
				<td>
					<input type="date" name="date_start_learning" value="<?=$user->uniGet('date_start_learning')?>">
				</td>
			</tr>
		</table>
		<br><br>
		<?php endif; ?>
		
	</div>
		<?php
			if($user->uniGet('avatar') != ""){
				print("<img width='300px' height='350px' src='../upload/avatars/".$user->uniGet('avatar')."'>");
			}
			else{
				print("<img width='250px' height='300px' src='../upload/avatars/default.jpg'>");
			}
		?></td>
<td><div class='body_profile'>
		<h1>Данные родителя ученика</h1>
		<ul>
			<li>Фамилия: <input type="text" name="p_surname" value="<?=$user->uniGet('p_surname')?>"></li>
			<li>Имя: <input type="text" name="p_name" value="<?=$user->uniGet('p_name')?>"></li>
			<li>Отчество: <input type="text" name="p_patronymic" value="<?=$user->uniGet('p_patronymic')?>"></li>
			<li>Телефон: <input type="text" name="p_phone" value="<?=$user->uniGet('p_phone')?>"></li>
		</ul>
	</div>
	
	</td>
</tr>
</table>
<div class="footer_profile">
	<?php if($_SESSION['data']['level'] == 3 || $_SESSION['data']['level'] == 4 ): ?>
		<input type="submit" name="redact_target" value="Редактировать данные">
	<?php endif; ?>
	<?php if($_SESSION['data']['level'] == 4): ?>
	
			<input type="hidden" name="id_target" value="<?=$user->uniGet('id')?>">
			<input type="hidden" name="login_target" value="<?=$user->uniGet('login')?>">
			<input type="hidden" name="lock_status_target" value="<?=$user->uniGet('lock_status')?>">
			
			<?php if($user->uniGet('lock_status') == 0 && $_SESSION['data']['level'] == 4): ?>
				<input type="submit" name="lock_target" value="Заблокировать">
			<?php endif; ?>
			<?php if($user->uniGet('lock_status') == 1 && $_SESSION['data']['level'] == 4): ?>
				<input type="submit" name="unlock_target" value="Разблокировать">
			<?php endif; ?>
	
	<?php endif; ?>
	</div>
	</form>
	<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" onsubmit="return confirm('Вы действительно хотите удалить данного пользователя?');">
		<input type="hidden" name="id_target" value="<?=$user->uniGet('id')?>">
		<input type="submit" name="delete_target" value="Удалить">
	</form>
	<div class="courses-container">
		<h1>Привязка курсов</h1>
		<div class="list" style="height: 400px;overflow-y: auto;">
		<?php
			$sql_courses = "SELECT * FROM os_courses_meta WHERE is_active=1";
			$res_courses = $mysqli->query($sql_courses);
			if($res_courses->num_rows != 0) {
				while($row_courses = $res_courses->fetch_assoc()) {
					$sql_student_payment = sprintf("SELECT * FROM os_courses_students WHERE id_course=%s AND id_user=%s",$row_courses['id'],$user->uniGet('id'));
					$res_student_payment = $mysqli->query($sql_student_payment);
					if($res_student_payment->num_rows != 0) {
						$row_student_payment = $res_student_payment->fetch_assoc();
						printf("<form method='post' action=''>
									<label>Курс: %s <input type='submit' name='delete_from_course' value='Удалить из курса'></label>
									<input type='hidden' name='id_user' value='%s'>
									<input type='hidden' name='id_course' value='%s'>
									<input type='hidden' name='is_student' value='1'>
								</form>",$row_courses['course_name_ru'],$user->uniGet('id'),$row_courses['id']);
					} else {
						printf("<form method='post' action=''>
									<label>Курс: %s <input type='submit' name='add_to_course' value='Добавить к курсу'></label>
									<input type='hidden' name='id_user' value='%s'>
									<input type='hidden' name='id_course' value='%s'>
									<input type='hidden' name='start_date' value='%s'>
									<input type='hidden' name='is_student' value='1'>
								</form>",$row_courses['course_name_ru'],$user->uniGet('id'),$row_courses['id'],$row_courses['date_from']);
					}
				}
			}
		?>
		</div>
	</div>
	<div class="clear"></div>
</div>
