<form method='post' action="<?=$_SERVER['REQUEST_URI']?>" enctype="multipart/form-data">
	<?php if($_GET['type'] == 2): ?>
		<p>Классы</p>
		<select size='4' id="classes" name="classes[]" multiple style="height:100px;">
			<?php
				for($i = 1; $i <= 11; $i++){
					print("<option value='$i'>$i</option>");
				}
			?>
		</select><br>
		<p>Предметы</p>
		<select size='4' id="subjects" name="subjects[]" multiple style="height:200px;">
			<option disabled>Выберите класс</option>
		</select><br>
	<?php endif; ?>
	<label>Фамилия<input type='text' name='surname' required></input></label>
	<label>Имя<input type='text' name='name' required></input></label>
	<label>Отчество<input type='text' name='patronymic' required></input></label><br>
	<label>Login<input type='text' name='login' required></input></label><br>
	<label>Пароль<input type='text' name='password' id="passw" required></input></label><br>
	<label>Подтвердите пароль<input type='text' name='password1' required></input></label><br>
	<!--<label>Подтвердите пароль<input type='text' name='check_password' id="check_passw" required></input></label><br>-->
	<label>E-mail<input type='text' name='email' required></input></label><br>

	<span>Загрузите аватар</span>
	<input type='file' name='avatar'>

	<input type='hidden' name='level' value="<?=$_SESSION['create']['type']?>"></input><br>
	<input type='submit' name='create' value='Создать'></input>
</form>