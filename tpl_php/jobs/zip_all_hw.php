<?php
	set_time_limit(7200);
	session_start();
	if($_SESSION['data']['level'] != 4) header("Location: http://online-shkola.com.ua");
	$base_url = "http://online-shkola.com.ua/";
	$old_file_base_path = "../../upload/hworks/";
	$new_file_base_path = "../../temp_catalog/";
	require_once("../autoload_light.php");
	if(!isset($_SESSION['data']) || $_SESSION['data']['level'] != 4) header("Location: ../../");
		
require_once("../functions.php");
if(isset($_POST['delete_archieve'])) {
	if(unlink($new_file_base_path . $_POST['zip_name'])) {
		$sql_del = sprintf("DELETE FROM os_zip_names WHERE id=%s", $_POST['id']);
		$res_del = $mysqli->query($sql_del);
	}
	usleep(10000);
	header("Location:" . $_SERVER['REQUEST_URI']);
}
if(isset($_POST['delete_on_dates']) || isset($_POST['delete_on_user'])) {
	$class 	 = $_POST['classes'];
	/*$subject = $_POST['subjects'];*/
	$date_from = $_POST['date_from'];
	$date_till = $_POST['date_till'];
	$base_url = "http://online-shkola.com.ua/";
	$old_file_base_path = "../../upload/hworks/";
	$new_file_base_path = "../../temp_catalog/";
	$sql = "SELECT * FROM os_lessons WHERE (date_ru >= '$date_from' OR date_ua >= '$date_from') AND (date_ru <= '$date_till' OR date_ua <= '$date_till') 
									   AND id IN (SELECT id_lesson FROM os_lesson_classes WHERE id_class=$class)";
	//print($sql . "<br>");
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$subject_folder_name = "";
			$sql_subject = sprintf("SELECT * FROM os_subjects WHERE id=%s",$row['subject']);
			$res_subject = $mysqli->query($sql_subject);
			if($res_subject->num_rows != 0) {
				$row_subject = $res_subject->fetch_assoc();
			}
			// lesson homework string
			$sql_lesson_hw = "SELECT * FROM os_lesson_homework WHERE id_lesson = " . $row['id'];
			//print("lessons hw" . $sql_lesson_hw . "<br>");
			$res_lesson_hw = $mysqli->query($sql_lesson_hw);
			if($res_lesson_hw->num_rows != 0) {
				$row_lesson_hw = $res_lesson_hw->fetch_assoc();
				$user_q_add = "";
				if(isset($_POST['archive_on_user']) || isset($_POST['delete_on_user'])) {
					$user_q_add = "WHERE id = " . $_POST['user_id'];
				}
				$sql_user_data = "SELECT * FROM os_users $user_q_add";
				$res_user_data = $mysqli->query($sql_user_data);
				while($row_user_data = $res_user_data->fetch_assoc()){
					$sql_homeworks = sprintf("SELECT * FROM os_homeworks WHERE `from` = %s AND id_hw = %s",
						$row_user_data['id'],$row_lesson_hw['id']);
					$res_homeworks = $mysqli->query($sql_homeworks);
					if($res_homeworks->num_rows != 0) {
						while($row_homeworks = $res_homeworks->fetch_assoc()) {
							$sql_hw_student = sprintf("SELECT * FROM os_homework_docs WHERE id_hw = %s AND `from` = 'student'",
								$row_homeworks['id']);
							$res_hw_student = $mysqli->query($sql_hw_student);
							$sql_hw_teacher = sprintf("SELECT * FROM os_homework_docs WHERE id_hw = %s AND `from` = 'teacher'",
								$row_homeworks['id']);
							$res_hw_teacher = $mysqli->query($sql_hw_teacher);
							if($res_hw_student->num_rows != 0) {
								while($row_hw_student = $res_hw_student->fetch_assoc()) {
									if(!file_exists($old_file_base_path . $row_hw_student['file_name'])) continue;
									unlink($old_file_base_path . $row_hw_student['file_name']);
										usleep(500);
								}
							}
							if($res_hw_teacher->num_rows != 0) {
								while($row_hw_teacher = $res_hw_teacher->fetch_assoc()) {
									if(!file_exists($old_file_base_path . $row_hw_teacher['file_name'])) continue;
									unlink($old_file_base_path . $row_hw_teacher['file_name']);
										usleep(500);
								}
							}

						}

					} else {
						usleep(500);
						continue;
					}
				}
			} else {
				usleep(500);
				continue;
			}
			usleep(1000);
		}
		header("Location:" . $_SERVER['REQUEST_URI']);
	}
}
if(isset($_POST['archive_on_dates']) || isset($_POST['archive_on_user'])) {
	$class 	 = $_POST['classes'];
	/*$subject = $_POST['subjects'];*/
	$date_from = isset($_POST['date_from'])?$_POST['date_from']:'0000-00-00';
	$date_till = isset($_POST['date_till'])?$_POST['date_till']:'0000-00-00';
	
	$user_part = '';

	$zip_name = 'class_-_' . $class . '__dateFROM_-_' . $date_from . '__dateTILL_-_' . $date_till;
	if(isset($_POST['archive_on_user'])) {
		$user_part = '__user_id_-_' . (int)$_POST['user_id'];
		$zip_name .= '__user_id_-_' . (int)$_POST['user_id'];
	}
	$zip_name .= '___' . time() . '.zip';

	$zip = new ZipArchive();
	$archieve_base_name = $new_file_base_path . $zip_name;
	$sql_add_name = "INSERT INTO os_zip_names(zip_name) VALUES('$zip_name')";
	$res_add_name = $mysqli->query($sql_add_name);
	$handle = $zip -> open($archieve_base_name, ZipArchive::CREATE);
	$sql = "SELECT * FROM os_lessons WHERE (date_ru >= '$date_from' OR date_ua >= '$date_from') AND (date_ru <= '$date_till' OR date_ua <= '$date_till') 
									   AND id IN (SELECT id_lesson FROM os_lesson_classes WHERE id_class=$class)";
	//print($sql . "<br>");
	$res = $mysqli->query($sql);
	if($res->num_rows != 0) {
		while($row = $res->fetch_assoc()) {
			$subject_folder_name = "";
			$sql_subject = sprintf("SELECT * FROM os_subjects WHERE id=%s",$row['subject']);
			$res_subject = $mysqli->query($sql_subject);
			if($res_subject->num_rows != 0) {
				$row_subject = $res_subject->fetch_assoc();
				$subject_folder_name = transliteration($row_subject['name_ru']) . "_{$date_from}_-_{$date_till}__Class__{$class}" . '/';
				$zip->addEmptyDir($subject_folder_name);
			}
			// lesson homework string
			$sql_lesson_hw = "SELECT * FROM os_lesson_homework WHERE id_lesson = " . $row['id'];
			//print("lessons hw" . $sql_lesson_hw . "<br>");
			$res_lesson_hw = $mysqli->query($sql_lesson_hw);
			if($res_lesson_hw->num_rows != 0) {
				$row_lesson_hw = $res_lesson_hw->fetch_assoc();
				$user_q_add = "";
				if(isset($_POST['archive_on_user']) || isset($_POST['delete_on_user'])) {
					$user_q_add = "WHERE id = " . $_POST['user_id'];
				}
				$sql_user_data = "SELECT * FROM os_users $user_q_add";
				$res_user_data = $mysqli->query($sql_user_data);
				while($row_user_data = $res_user_data->fetch_assoc()){
					$sql_homeworks = sprintf("SELECT * FROM os_homeworks WHERE `from` = %s AND id_hw = %s",
						$row_user_data['id'],$row_lesson_hw['id']);
						//print("homeworks" . $sql_homeworks . "<br>");
					$folder_name = transliteration($row_user_data['surname'] . '_' . $row_user_data['name']);
					$res_homeworks = $mysqli->query($sql_homeworks);
					if($res_homeworks->num_rows != 0) {
						$lesson_dir = 'id_' .$row['id'] . '_NAME__' . transliteration(substr($row['title_ru'],0,30));
						$zip->addEmptyDir($subject_folder_name . $lesson_dir);
						while($row_homeworks = $res_homeworks->fetch_assoc()) {
							$sql_hw_student = sprintf("SELECT * FROM os_homework_docs WHERE id_hw = %s AND `from` = 'student'",
								$row_homeworks['id']);
							$res_hw_student = $mysqli->query($sql_hw_student);
							$sql_hw_teacher = sprintf("SELECT * FROM os_homework_docs WHERE id_hw = %s AND `from` = 'teacher'",
								$row_homeworks['id']);
							$res_hw_teacher = $mysqli->query($sql_hw_teacher);
							if($res_hw_student->num_rows != 0) {
								//print("<br>СТУДЕНТ - $sql_hw_student<br>");
								$zip->addEmptyDir($subject_folder_name . $lesson_dir . '/' . $folder_name);
								$iter = 1;
								while($row_hw_student = $res_hw_student->fetch_assoc()) {
									if(!file_exists($old_file_base_path . $row_hw_student['file_name'])) continue;
									$file_name_arr = explode('.',$row_hw_student['file_name'] );
									$file_name_ext = $file_name_arr[count($file_name_arr)-1];
									$new_file_name = "file_number_" . $iter . "_from_uchenik." . $file_name_ext;
										if(!$zip->addFile($old_file_base_path . $row_hw_student['file_name'], $subject_folder_name 
																			  . $lesson_dir . '/' . $folder_name . '/' . $new_file_name)) continue;
										usleep(500);
									$iter++;
								}
							}
							if($res_hw_teacher->num_rows != 0) {
								//print("<br>УЧИТЕЛЬ - $sql_hw_teacher<br>");
								$zip->addEmptyDir($subject_folder_name . $lesson_dir . '/' . $folder_name);
								$iter = 1;
								while($row_hw_teacher = $res_hw_teacher->fetch_assoc()) {
									if(!file_exists($old_file_base_path . $row_hw_teacher['file_name'])) continue;
									$file_name_arr = explode('.',$row_hw_teacher['file_name'] );
									$file_name_ext = $file_name_arr[count($file_name_arr)-1];
									$new_file_name = "file_number_" . $iter . "_from_uchitel." . $file_name_ext;
										if(!$zip->addFile($old_file_base_path . $row_hw_teacher['file_name'], $subject_folder_name 
																			  . $lesson_dir . '/' . $folder_name . '/' . $new_file_name)) continue;
										usleep(500);
									$iter++;
								}
							}

						}

					} else {
						usleep(500);
						continue;
					}
				}
			} else {
				usleep(500);
				continue;
			}
			usleep(1000);
		}
		$zip->close();
		header("Location:" . $_SERVER['REQUEST_URI']);
	}
	echo "<pre>";
	print_r($zip);
	echo "</pre>";
	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Zip all HW for admin</title>
	<?php require_once("../../tpl_blocks/head.php"); ?>
</head>
<?php require_once("../../tpl_blocks/header.php"); ?>

	<form method="post" action="">
		<table>
			<thead>
				<tr>
					<td title="Выберите КЛАСС Уроков">
						<select name="classes" id="">
							<?php 
								$sql = "SELECT * FROM os_class_manager";
								$res = $mysqli->query($sql);
								if($res->num_rows != 0) {
									while($row = $res->fetch_assoc()) {
										$selected = "";
										if($row['class_name'] == '8') $selected = "selected"; 
										printf("<option value='%s' $selected>Класс %s</option>",
												$row['id'],$row['class_name']);
									}
								}
							?>
						</select>
					</td>
					<td title="Дата уроков 'С'">
						<input type="date" name="date_from" />
					</td>
					<td title="Дата уроков 'ДО'">
						<input type="date" name="date_till" />
						<!--<select name="subjects" id="">
							<option value="0">Все предметы</option>
							<?php
								$sql = "SELECT * FROM os_subjects";
								$res = $mysqli->query($sql);
								if($res->num_rows != 0) {
									while($row = $res->fetch_assoc()) {
										printf("<option value='%s'>%s</option>",
												$row['id'],$row['name_ru']);
									}
								}
							?>
						</select>-->
					</td>
					<td title="Введите ID пользователя(не обязательно)"><input type="text" name="user_id" /></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td title="Архивировать ДЗ по классу и дате, по всем пользователям">
						<input type="submit" name="archive_on_dates" value="Архивация! (Только используя даты и класс)">
					</td>
					<td></td>
					<td title="Архивировать ДЗ по классу и дате, по выбранному пользователю">
						<input type="submit" name="archive_on_user" value="Архивация! (С использованием идентификатора пользователя)">
					</td>
				</tr>
				<!--<tr>
					<td></td>
					<td title="Удалить ДЗ по классу и дате, по всем пользователям">
						<input type="submit" name="delete_on_dates" value="Удаление! (Только используя даты и класс)">
					</td>
					<td></td>
					<td title="Удалить ДЗ по классу и дате, по выбранному пользователю">
						<input type="submit" name="delete_on_user" value="Удаление! (С использованием идентификатора пользователя)">
					</td>
				</tr>-->
			</tbody>
		</table>
	</form>
	<div class="container">
		<div class="block0">
			<ul>
				<?php
					$sql = "SELECT * FROM os_zip_names ORDER BY id DESC";
					$res = $mysqli->query($sql);
					if($res->num_rows != 0) {
						while ($row = $res->fetch_assoc()) {
							if(!file_exists($new_file_base_path . $row['zip_name'])) {
								$sql_del = sprintf("DELETE FROM os_zip_names WHERE id=%s", $row['id']);
								$res_del = $mysqli->query($sql_del);
							} else {
								printf("<h1 color='red'><a href='%s'>СКАЧАТЬ ФАЙЛ АРХИВА #%s <br> 
														%s</a></h1>",
										"http://" . $_SERVER['HTTP_HOST'] . "/temp_catalog/" . $row['zip_name'], $row['id'], $row['zip_name']);
								printf("<form method='post' action=''>
											<input type='hidden' name='id' value='%s'>
											<input type='hidden' name='zip_name' value='%s'>
											<input type='submit' name='delete_archieve' value='Удалить архив'>
										</form>",$row['id'],$row['zip_name']);
							}
						}
					}
				?>
			</ul>
		</div>
	</div>
</body>
</html>
