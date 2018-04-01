<?php
$db = Database::getInstance();
$mysqli = $db->getConnection();
$sql_cost_1 = "SELECT * FROM os_edu_types WHERE id=1";
$res_cost_1 = $mysqli->query($sql_cost_1);
$row_cost_1 = $res_cost_1->fetch_assoc();
$sql_cost_2 = "SELECT * FROM os_edu_types WHERE id=2";
$res_cost_2 = $mysqli->query($sql_cost_2);
$row_cost_2 = $res_cost_2->fetch_assoc();
$sql_cost_3 = "SELECT * FROM os_edu_types WHERE id=3";
$res_cost_3 = $mysqli->query($sql_cost_3);
$row_cost_3 = $res_cost_3->fetch_assoc();
$sql_cost_4 = "SELECT * FROM os_edu_types WHERE id=4";
$res_cost_4 = $mysqli->query($sql_cost_4);
$row_cost_4 = $res_cost_4->fetch_assoc();
?>
<? if($_SESSION['data']['level'] == 4): ?>

<form action="<?=$_SERVER['PHP_SELF'];?>#tab2" method="post">
<table class="cab_usr_list_css">
	<tr>
		<td>
			Класс<br>
			<select name="class_pm">
				<?php
					$sql_classes_pays = sprintf("SELECT * FROM os_class_manager WHERE is_opened=0");
					$res_classes_pays = $mysqli->query($sql_classes_pays);
					$classes_all = "";
					while ($row_classes_pays = $res_classes_pays->fetch_assoc()) {
						$classes_all .= $row_classes_pays['id'].',';
					}
					$classes_all = rtrim($classes_all,',');
					print("<option value='$classes_all'>Все классы</option>");
					$res_classes_pays = $mysqli->query($sql_classes_pays);
					while ($row_classes_pays = $res_classes_pays->fetch_assoc()) {
						printf("<option value='%s'>Класс %s</option>",$row_classes_pays['id'],$row_classes_pays['class_name']);
					}
					/*for($i = 1; $i <= 11; $i++){
						printf("<option value='%s'>%s-класс</option>",$i,$i);
					}*/
				?>
			</select>
		</td>
		<td>
			<?php
				$sql = "SELECT * FROM os_edu_types";
				$result = $mysqli->query($sql);
			?>
			Вид доступа<br>
			<select name="edu_type_pm">
				<option value="0">Тип: не установлен</option>
				<option value="0,1,2,3">Тип: Все типы</option>
				<?php
					while($row = $result->fetch_assoc()){
						printf("<option value='%s'>Тип: %s</option>",$row['id'],$row['name']);
					}
				?>
			</select>
		</td>
		<td>
			Статус оплаты<br>
			<select name="edu_status_pm">
				<option value="0,1" selected>Статус: Все</option>
				<option value="0">Статус: Не оплачено</option>
				<option value="1">Статус: Оплачено</option>
				
			</select>
		</td>
		<td>
			<form action="#" method="post"> 
				<input type="search" name="search" placeholder=""> <input type="button" value="Поиск" onclick="get_people_pm(this.form.search.value)"> 
				
			</form>
		</td>
		<td>
			
			<a style="margin: 0;" class="tabel_link ">Изменить сумму оплат</a>
			<div class="oplata_orderrs" id="tabel_link_content" style="display: none; margin-left: 0;">

								<form method="post" action="index.php#tab_3">
									<table>
										<tr>
											<td><p>Общее образование</p></td>
											<td><p><input type="text" name="cost1" pattern="[0-9]{1,6}" value="<?=$row_cost_1['cost']?>"></p></td>
											<td><p>за месяц</p></td>		
										</tr>
										<tr>
											<td><p>Дополнительное<br>образование</p></td>
											<td><p><input type="text" name="cost2" pattern="[0-9]{1,6}" value="<?=$row_cost_2['cost']?>"></p></td>
											<td><p>за месяц</p></td>		
										</tr>
										<tr>
											<td><p>Частичное<br>образование</p></td>
											<td><p><input type="text" name="cost3" pattern="[0-9]{1,6}" value="<?=$row_cost_3['cost']?>"></p></td>
											<td><p>за месяц</p></td>		
										</tr>
										<tr>
											<td><p>Индивидуальные<br>занятия</p></td>
											<td><p><input type="text" name="cost4" pattern="[0-9]{1,6}" value="<?=$row_cost_4['cost']?>"></p></td>
											<td><p>за месяц</p></td>		
										</tr>
									</table> 
								<input type="submit" name="change_cost" value="Сохранить">	
<a style="width: 110px;
    background: #1e9cb7;
    color: #fff;
    float: left;
    margin: 25px 0 0 180px;    line-height: 35px;
    padding: 5px;" class="tabel_link ">Отмена</a>
								</form>
								
							</div>
			
		</td> 
	</tr>
</table>
</form>
<table id="pay_manager_users">
	<thead>
		<tr>
			<td>
				ФИО
			</td>
			<td>
				Сумма
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

