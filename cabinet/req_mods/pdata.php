<?php
	$db = Database::getInstance();
    $mysqli = $db->getConnection();
?> 
	<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
	<h1>Личная информация</h1>
	<? else: ?>
	<h1>Персональна інформація</h1>
	<? endif; ?>
	<form method='post' action="<?=$_SERVER['REQUEST_URI']?>" enctype='multipart/form-data'>
		<table>
			<? if( !isset($_COOKIE['lang']) || $_COOKIE['lang'] == "ru"): ?>
			<tr>
				<td colspan='3'>Класс<font style='color:red;'>*</font> 
					<select size='1' name='class' disabled>
						<?php
							for ($i=1; $i <= 11; $i++) { 
								if($_SESSION['data']['class'] == $i)
									print("<option value='$i' selected>$i</option>");
								else
									print("<option value='$i'>$i</option>");
							}
						?>
					</select>
					<span style='padding-left: 100px;'><font style='color:red;'>*</font> - поля обязательные для заполнения</span><br>
				</td>
			</tr>
			<tr>
				<td>
			<label>Фамилия<font style='color:red;'>*</font> <input type='text' name='surname' value="<?=$_SESSION['data']['surname'];?>"></label>
				</td>
				<td>
			<label>Имя<font style='color:red;'>*</font> <input type='text' name='name' value="<?=$_SESSION['data']['name'];?>"></label>
				</td>
				<td>
			<label>Отчество<font style='color:red;'>*</font> <input type='text' name='patronymic' value="<?=$_SESSION['data']['patronymic'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
			<label>Дата рождения<font style='color:red;'>*</font> <input type='date' name='birth' value="<?=$_SESSION['data']['birth'];?>"></label>
				</td>
				<td rowspan='3'>
				Загрузить аватар<br>
				<input type='file' name='avatar'>
				
					<?php
						if ($_SESSION['data']['avatar'] == "") {
							print("<img src='../upload/avatars/default.jpg' width='250px' height='300px'>");
						}
						else
							printf("<img src='../upload/avatars/%s' width='300px' height='350px'>",$_SESSION['data']['avatar']);
					?>
				
				</td>
			</tr>
			<tr>
				<td colspan='2'>
			<label>Моб. телефон <input type='text' name='phone' pattern='[0-9]{10}' value="<?=$_SESSION['data']['phone'];?>"></label>
				</td>
			</tr>
			
			<tr>
				<td colspan='3'>
					данные родителя
				</td>
			</tr>
			<tr>
				<td colspan='2'>
			<label>Моб. телефон родителя<font style='color:red;'>*</font> <input type='text' name='p_phone' pattern='[0-9]{10}' value="<?=$_SESSION['data']['p_phone'];?>"></label>
				</td>
			</tr>
			<tr>
				<td>
			<label>Фамилия родителя<font style='color:red;'>*</font> <input type='text' name='p_surname' value="<?=$_SESSION['data']['p_surname'];?>"></label>
				</td>
				<td>
			<label>Имя родителя<font style='color:red;'>*</font> <input type='text' name='p_name' value="<?=$_SESSION['data']['p_name'];?>"></label>
				</td>
				<td>
			<label>Отчество родителя<font style='color:red;'>*</font> <input type='text' name='p_patronymic' value="<?=$_SESSION['data']['p_patronymic'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Город<font style='color:red;'>*</font> <input type='text' name='city' value="<?=$_SESSION['data']['city'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Школа<font style='color:red;'>*</font> <input type='text' name='school' value="<?=$_SESSION['data']['school'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Почта<font style='color:red;'>*</font> <input type='text' name='email' value="<?=$_SESSION['data']['email'];?>" pattern='[^@]+@[^@]+\.[a-zA-Z]{2,6}'></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Логин<font style='color:red;'>*</font> <input type='text' name='login' value="<?=$_SESSION['data']['login'];?>" disabled></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<input type='submit' name='send' value='Изменить'>
				</td>
			</tr>
			<? else: ?>
			<tr>
				<td colspan='3'>Клас<font style='color:red;'>*</font> 
					<select size='1' name='class' disabled>
						<?php
							for ($i=1; $i <= 11; $i++) { 
								if($_SESSION['data']['class'] == $i)
									print("<option value='$i' selected>$i</option>");
								else
									print("<option value='$i'>$i</option>");
							}
						?>
					</select>
					<span style='padding-left: 100px;'><font style='color:red;'>*</font> - обов'язкові поля</span><br>
				</td>
			</tr>
			<tr>
				<td>
			<label>Прізвище<font style='color:red;'>*</font> <input type='text' name='surname' value="<?=$_SESSION['data']['surname'];?>"></label>
				</td>
				<td>
			<label>Ім'я<font style='color:red;'>*</font> <input type='text' name='name' value="<?=$_SESSION['data']['name'];?>"></label>
				</td>
				<td>
			<label>По батькові<font style='color:red;'>*</font> <input type='text' name='patronymic' value="<?=$_SESSION['data']['patronymic'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
			<label>Дата народження<font style='color:red;'>*</font> <input type='date' name='birth' value="<?=$_SESSION['data']['birth'];?>"></label>
				</td>
				<td rowspan='3'>
				Завантажити аватар<br>
				<input type='file' name='avatar'>
				
					<?php
						if ($_SESSION['data']['avatar'] == "") {
							print("<img src='../upload/avatars/default.jpg' width='250px' height='300px'>");
						}
						else
							printf("<img src='../upload/avatars/%s' width='300px' height='350px'>",$_SESSION['data']['avatar']);
					?>
				
				</td>
			</tr>
			<tr>
				<td colspan='2'>
			<label>Моб. телефон <input type='text' name='phone' pattern='[0-9]{10}' value="<?=$_SESSION['data']['phone'];?>"></label>
				</td>
			</tr>
			
			<tr>
				<td colspan='3'>
					дані батьків
				</td>
			</tr>
			<tr>
				<td colspan='2'>
			<label>Моб. телефон батьків<font style='color:red;'>*</font> <input type='text' name='p_phone' pattern='[0-9]{10}' value="<?=$_SESSION['data']['p_phone'];?>"></label>
				</td>
			</tr>
			<tr>
				<td>
			<label>Прізвище одного з батьків<font style='color:red;'>*</font> <input type='text' name='p_surname' value="<?=$_SESSION['data']['p_surname'];?>"></label>
				</td>
				<td>
			<label>Ім'я одного з батьків<font style='color:red;'>*</font> <input type='text' name='p_name' value="<?=$_SESSION['data']['p_name'];?>"></label>
				</td>
				<td>
			<label>По батькові одного з батьків<font style='color:red;'>*</font> <input type='text' name='p_patronymic' value="<?=$_SESSION['data']['p_patronymic'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Місто<font style='color:red;'>*</font> <input type='text' name='city' value="<?=$_SESSION['data']['city'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Школа<font style='color:red;'>*</font> <input type='text' name='school' value="<?=$_SESSION['data']['school'];?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Пошта<font style='color:red;'>*</font> <input type='text' name='email' value="<?=$_SESSION['data']['email'];?>" pattern='[^@]+@[^@]+\.[a-zA-Z]{2,6}'></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<label>Логін<font style='color:red;'>*</font> <input type='text' name='login' value="<?=$_SESSION['data']['login'];?>" disabled></label>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
			<input type='submit' name='send' value='Змінити'>
				</td>
			</tr>
			<? endif; ?>
		</table>
	</form> 