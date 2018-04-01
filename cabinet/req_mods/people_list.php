<form action="<?php $_SERVER['REQUEST_URI'];?>" method="post">

<table style="width:100%;">
<tr>
	<td>
		<ul>
			<li>
				Классы
			</li>
			<li>
			<select size='1' name='class_id_us'>
				<option value="1,2,3,4,5,6,7,8,9,10,11,12" selected>Все классы</option>
				<?php
					for($i = 1; $i <= 11; $i++){
						if(isset($_SESSION['filter_data']['class']) && $_SESSION['filter_data']['class']==$i)
							printf("<option value='%s' selected>%s-й класс</option>",$i,$i);
						else
							printf("<option value='%s'>%s-й класс</option>",$i,$i);
					}
				?>
			</select>
			</li>
		</ul>
	</td>
	<td>
		<ul>
			<li>
				Предметы
			</li>
			<li>
			<?php
				$db = Database::getInstance();
				$mysqli = $db->getConnection();
				$sql = "SELECT * FROM os_subjects";
				$result = $mysqli->query($sql);
				$subjects_all = "";
				while($row = $result->fetch_assoc()){
					$subjects_all .= $row['id'].",";
				}
				$subjects_all = rtrim($subjects_all,",");
			?>
			<select size='1' name='subject_id_us'>
				<option value="<?=$subjects_all?>">Все предметы</option>
				<?php
					while($row = $result->fetch_assoc()){
						printf("<option value='%s'>%s</option>",$row['id'],$row['name']);
					}
				?>
			</select>
			</li>
		</ul>
	</td>
	<td>
		<ul>
			<li>
				Статус
			</li>
			<li>
			
			<select size='1' name='status'>
				<option value='0,1' selected>Все</option>
				<option value='0'>Разблокированные</option>
				<option value='1'>Заблокированные</option>
				<!--<?php
					if($_SESSION['filter_data']['status']==1)
						printf("<option value='1' selected>Разблокированные</option>");
					else
						printf("<option value='1'>Разблокированные</option>");
					if($_SESSION['filter_data']['status']==0)
						printf("<option value='0' selected>Заблокированные</option>");
					else
						printf("<option value='0'>Заблокированные</option>");
					
				?>-->
			</select>
			</li>
		</ul>
	</td>
	<td>
		<ul>
			<li>
				Тип доступа
			</li>
			<li>
			
			<select size='1' name='filter_edu_type'>
				<option value='0,1,2,3' selected>Все</option>
				<option value='0'>Не установлен</option>
				<option value='1'>Общее образование</option>
				<option value='2'>Дополнительное образование</option>
				<option value='3'>Частичное образование</option>
			</select>
			</li>
		</ul>
	</td>
	<td>
		<ul>
			<li>
				Уровень доступа
			</li>
			<li>
			<!--<select size='1' name='level'>
				<option value="1,2,3,4" selected>Все</option>
				<option value="1">Ученик</option>
				<option value="2">Учитель</option>
				<option value="3">Менеджер</option>
				<option value="4">Супер Админ</option>
			</select>-->
			<?php if($_SESSION['data']['level'] == 4):?>
			<select size='1' name='level'>
				<option value="1,2,3,4" selected>Все</option>
				<option value="1">Ученик</option>
				<option value="2">Учитель</option>
				<option value="3">Менеджер</option>
				<option value="4">Супер Админ</option>
			</select>
			<?php endif; ?>
			<?php if($_SESSION['data']['level'] == 3):?>
			<select size='1' name='level'>
				<option value="1,2" selected>Все</option>
				<option value="1">Ученик</option>
				<option value="2">Учитель</option>

			</select>
			<?php endif; ?>
			</li>
		</ul>
	</td>
	</tr>
	<tr >
	<td colspan="5">
	
		<form action="<?php $_SERVER['REQUEST_URI'];?>" method="post">
			<span>Введите имя или фамилию для поиска</span><br>
			<input style="width: 48%;" type="text" name="sText">
			<!--<input type="submit" name="search" value="Поиск">-->
		</form>
	</td>
	</tr>
</tr>
</table>
						
						
						
						
						
                                                <div class="crt_peaple"> 
												 
													<div class="crt_peaple_list">
														
														<ul style="margin-bottom: 10px;" id="nav7"> 
														  <li><img src="/tpl_img/arrow_m.png"><a>Создать пользователя</a>
															  <ul style="margin-top: 1px;">
																<li><a href="createuser.php?type=1">Создать ученика</a></li>
																<li><a href="createuser.php?type=2">Создать учителя</a></li>
																<li><a href="createuser.php?type=3">Создать менеджера</a></li>
																<li><a href="createuser.php?type=4">Создать администратора</a></li>
															  </ul></li>
														  </ul>
													 </div>
												</div>
						<form action="<?php $_SERVER['REQUEST_URI'];?>" method="post">
						<div class="users_lk_adm"><table class='users'>
							<thead>
								<tr>
									<td>
										ФИО
									</td>
									<td>
										Email
									</td>
									<td>
										Вид доступа
									</td>
									<td>
										Разбл./Забл.
									</td>
								</tr>
							</thead>
							<tbody>
							

							</tbody>
						</table></div>
						</form>