<?php
	class Strings{
		const stringForStudCab = "
		<div>
			<h1>Личная информация</h1>
			<form method='post' action='%s' enctype='multipart/form-data'>
				<table>
					<tr>
						<td colspan='3'>Класс<font style='color:red;'>*</font> 
							<select size='1' name='class'>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
								<option value='5'>5</option>
								<option value='6'>6</option>
								<option value='7'>7</option>
								<option value='8'>8</option>
								<option value='9'>9</option>
								<option value='10'>10</option>
								<option value='11'>11</option>
							</select>
							<span style='padding-left: 100px;'><font style='color:red;'>*</font> - поля обязательные для заполнения</span><br>
						</td>
					</tr>
					<tr>
						<td>
					<label>Фамилия<font style='color:red;'>*</font> <input type='text' name='surname' value='%s'></label>
						</td>
						<td>
					<label>Имя<font style='color:red;'>*</font> <input type='text' name='name' value='%s'></label>
						</td>
						<td>
					<label>Отчество<font style='color:red;'>*</font> <input type='text' name='patronymic' value='%s'></label>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
					<label>Дата рождения<font style='color:red;'>*</font> <input type='date' name='birth' value='%s'></label>
						</td>
						<td rowspan='3'>
						Загрузить аватар
					<div  class='photo_lc'>
					</div>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
					<label>Моб. телефон <input type='text' name='phone' pattern='[0-9]{10}' value='%s'></label>
						</td>
					</tr>
					
					<tr>
						<td colspan='3'>
							данные родителя
						</td>
					</tr>
					<tr>
						<td colspan='2'>
					<label>Моб. телефон родителя<font style='color:red;'>*</font> <input type='text' name='p_phone' pattern='[0-9]{10}' value='%s'></label>
						</td>
					</tr>
					<tr>
						<td>
					<label>Фамилия родителя<font style='color:red;'>*</font> <input type='text' name='p_surname' value='%s'></label>
						</td>
						<td>
					<label>Имя родителя<font style='color:red;'>*</font> <input type='text' name='p_name' value='%s'></label>
						</td>
						<td>
					<label>Отчество родителя<font style='color:red;'>*</font> <input type='text' name='p_patronymic' value='%s'></label>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
					<label>Город<font style='color:red;'>*</font> <input type='text' name='city' value='%s'></label>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
					<label>Школа<font style='color:red;'>*</font> <input type='text' name='school' value='%s'></label>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
					<label>Почта<font style='color:red;'>*</font> <input type='text' name='email' value='%s' pattern='[^@]+@[^@]+\.[a-zA-Z]{2,6}'></label>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
					<label>Логин<font style='color:red;'>*</font> <input type='text' name='login' value='%s' disabled></label>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
					<input type='submit' name='send' value='Изменить'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		";
		const stringForSuperCab = "
		<div>
			<h1>Личная информация</h1>
			<form method='post' action='%s' enctype='multipart/form-data'>
				<table>
					
					<tr>
						<td>
					<label>Фамилия <input type='text' name='surname' value='%s'></label> 
						</td>
						<td>
					<label>Имя <input type='text' name='name' value='%s'></label>
						</td>
						<td>
					<label>Отчество <input type='text' name='patronymic' value='%s'></label>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
					<label>Дата рождения <input type='date' name='birth' value='%s'></label>
						</td>
						<td rowspan='3'>
						Загрузить аватар
					<div class='photo_lc'>
					</div>
						</td>
					</tr>
					
					
					
					<tr>
						<td colspan='2'>
					<label>Почта <input type='text' name='email' value='%s' pattern='[^@]+@[^@]+\.[a-zA-Z]{2,6}'></label>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
					<label>Логин <input type='text' name='login' value='%s' disabled></label>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
					<input type='submit' name='send' value='Изменить'>
						</td>
					</tr>
				</table>
			</form>
		</div>
		";
		
	}
	
?>