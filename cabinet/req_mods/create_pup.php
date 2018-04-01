<form method='post' action="<?=$_SERVER['REQUEST_URI']?>" enctype="multipart/form-data">
	<select name="class" required>
		<?php 
			$sql = sprintf("SELECT * FROM os_class_manager WHERE is_opened=0");
			$res = $mysqli->query($sql);
			while ($row = $res->fetch_assoc()) {
				printf("<option value='%s'>%s класс</option>",$row["id"],$row["class_name"]);
			}

		?>
		
	</select>
	<label>Фамилия<input type='text' name='surname' required></input></label>
	<label>Имя<input type='text' name='name' required></input></label>
	<label>Отчество<input type='text' name='patronymic' required></input></label><br>
	<input type="date" name="birth" required></input>
	<label>Телефон<input name="phone" maxlength="12" required></input></label>
	
	<p>Имя родителя</p>
	<input name="p_name" required></input>

	<p>Фамилия родителя</p>
	<input name="p_surname"  required></input>
				
	<p>Отчество родителя</p>
	<input name="p_patronymic" required></input>

	<p>Телефоны родителей</p>
	<input name="p_phone" required ></input>

	<p>Город проживания</p>
	<input name="city" required></input>

	<p>Школа</p>
	<input name="school" required></input>
	
	<p>E-mail</p>
	<input name="email" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" ></input>

	<p>Login</p>
	<input type='text' name='login' required></input><br>
	
	<p>Пароль</p>
	<input type='text' name='password' required></input><br>

	<p>Подтвердите пароль</p>
	<input type='text' name='password1' required></input><br>

	<span>Загрузите аватар</span>
	<input type='file' name='avatar' required></input>

	<input type='hidden' name='level' value="<?=$_SESSION['create']['type']?>"></input><br>
	<input type='submit' name='create' value='Создать'>
</form>