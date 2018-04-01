			<h1>Личная информация</h1>
			<form method='post' action="<?=$_SERVER['REQUEST_URI']?>#tab_1" enctype='multipart/form-data'>
				<?php if($_SESSION['data']['level'] == 2): ?>
					<ul class="classes_subjects">
						<li>Ваши классы:</li>
						<?php
							$sql_classes = sprintf("SELECT * FROM os_teacher_class WHERE id_teacher='%s'",$_SESSION['data']['id']);
							$res_classes = $mysqli->query($sql_classes);
							while($row_classes = $res_classes->fetch_assoc()){
								printf("<li>%s-й класс</li>",$row_classes['id_c']);
							}
						?>
					</ul>
					<ul class="classes_subjects">
						<li>Ваши предметы:</li>
						<?php
							$sql_classes = sprintf("SELECT * FROM os_subjects WHERE id IN 
								(SELECT id_s FROM os_teacher_subj WHERE id_teacher='%s')",$_SESSION['data']['id']);
							$res_classes = $mysqli->query($sql_classes);
							while($row_classes = $res_classes->fetch_assoc()){
								printf("<li>Предмет: %s</li>",$row_classes['name_' . $_COOKIE['lang']]);
							}
						?>
					</ul>
				<?php endif; ?>
				<table>
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
					<label>Дата рождения <input type='date' name='birth' value="<?=$_SESSION['data']['birth'];?>"></label>
						</td>
						<td rowspan='3'>
						Загрузить аватар<br>
						<input type='file' name='avatar'><br>
				
						<?php
							if ($_SESSION['data']['avatar'] == "" || $_SESSION['data']['avatar'] == "Array") {
								print("<img src='../upload/avatars/default.jpg' width='250px' height='300px'>");
							}
							else
								printf("<img src='../upload/avatars/%s' width='250px' height='300px'>",$_SESSION['data']['avatar']);
						?>
						</td>
					</tr>
					
					
					
					<tr>
						<td colspan='2'>
					<label>Почта <input type='text' name='email' value="<?=$_SESSION['data']['email'];?>" pattern='[^@]+@[^@]+\.[a-zA-Z]{2,6}'></label>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
					<label>Логин <input type='text' name='login' value="<?=$_SESSION['data']['login'];?>" disabled></label>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
					<input type='submit' name='send' value='Изменить'>
						</td>
					</tr>
				</table>
			</form>
			<!--<?php 
				var_dump($_SESSION['data']);
			?>-->
	