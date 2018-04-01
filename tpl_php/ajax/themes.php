<?php
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($_POST){
		if($_POST['flag'] == '1') {
			$sql = "SELECT * FROM os_themes ORDER BY theme_start_date, id";
			$res = $mysqli->query($sql);
			
			$result = "";
			if($res->num_rows!=0) {
				while($row = $res->fetch_assoc()) {
					$result .= sprintf("<li onclick='load_theme(%s)'>
										<table>
											<tr>
												<td rowspan='1'>Тема %s</td>
												<td>%s</td>
											</tr>
											<tr>
												<td></td><td>%s</td>
											</tr>
										</table></li>",$row['id'],$row['id'],$row['theme_name_ru'],$row['theme_name_ua']);
				}
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '2') {
			$sql = sprintf("INSERT INTO os_themes(theme_start_date) VALUES('%s')",Date('Y-m-d'));
			$res = $mysqli->query($sql);
			$sql = "SELECT * FROM os_themes WHERE id = (SELECT MAX(id) FROM os_themes)";
			$res = $mysqli->query($sql);
			
			$result = "";
			if($res->num_rows!=0) {
				$row = $res->fetch_assoc();
				$result .= sprintf("<li onclick='load_theme(%s)'>
									<table>
										<tr>
											<td rowspan='1'>Тема %s</td>
											<td>%s</td>
										</tr>
										<tr>
											<td></td><td>%s</td>
										</tr>
									</table></li>",$row['id'],$row['id'],$row['course_name_ru'],$row['course_name_ua']);
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '3') {
			$err = array();
			if($_POST['value'] != "") {
				$value = $_POST['value'];
			} else {
				$err[] = "incorrect value";
			}
			if($_POST['field'] != "") {
				$field = $_POST['field'];
			} else {
				$err[] = "incorrect field";
			}
			if($_POST['course_id'] != "") {
				$course_id = $_POST['course_id'];
			} else {
				$err[] = "incorrect course_id";
			}
			if(count($err) == 0) {
				$sql = "UPDATE os_themes SET $field='$value' WHERE id=$course_id";
				$res = $mysqli->query($sql);
			}
			print_r(json_encode(array(
						'success' => count($err)?false:true,
						'error'	  => $err
					)
				)
			);
		}
		if($_POST['flag'] == '4') {
			//получение темы
			$err = array();
			if(!in_array($_POST['theme_id'], array("",0))) {
				$theme_id = $_POST['theme_id'];
			} else {
				$err[] = "incorrect theme_id";
			}
			$result = array();
			/**
			 * $courses_list  - готовый html курсов
 			 * $classes_list  -  -----=------ классов
 			 * $subjects_list -  -----=------ предметов
			 */
			$courses_list  = "";
			$classes_list  = "";
			$subjects_list = "";
			$courses_array = array(
								    0 => array(
								    			0 => 0,
								    			1 => "Основная школа | Основна школа",
								    			2 => ""
								    		  )
								  );
			if(count($err) == 0) {
				$sql_courses = "SELECT * FROM os_courses_meta";
				$res_courses = $mysqli->query($sql_courses);
				if($res_courses->num_rows != 0) {
					while($row_courses = $res_courses->fetch_assoc()) {
						$courses_array[$row_courses['id']] = array(
												  0 => $row_courses['id'],
												  1 => $row_courses['course_name_ru'] . " | " . $row_courses['course_name_ua'],
												  2 => ""
												);
					}
				}
				$sql = "SELECT * FROM os_themes WHERE id=$theme_id";
				$res = $mysqli->query($sql);
				$sql_theme_classes = "SELECT * FROM os_theme_classes WHERE id_theme=$theme_id";
				$res_theme_classes = $mysqli->query($sql_theme_classes);
				$theme_classes = array();
				if($res_theme_classes->num_rows!=0) {
					while($row_theme_classes = $res_theme_classes->fetch_assoc()) {
						$theme_classes[] = $row_theme_classes['id_class'];
					}
				}
				if($res->num_rows != 0) {
					$row = $res->fetch_assoc();
					foreach ($row as $key => $value) {
						if(in_array($key, array("theme_course","theme_class","theme_subject"))) continue;
						$result[$key] = $value;
					}
					$courses_array[$row['theme_course']][2] = 'selected';
					foreach ($courses_array as $value) {
						if($value[2] == 'selected') $selected = " selected";
						else $selected = "";
						$courses_list .= sprintf("<option value='%s'$selected>%s</option>", $value[0], $value[1]);
					}
					$sql_classes = "SELECT * FROM os_class_manager WHERE is_opened=0";
					$res_classes = $mysqli->query($sql_classes);
					if($res_classes->num_rows != 0) {
						/*if($row['theme_class'] == 0) $selected = " selected";
						else $selected = "";
						$classes_list  .= "<option value='0'$selected>Класс не выбран</option>";*/
						while($row_classes = $res_classes->fetch_assoc()) {
							if(in_array($row_classes['id'], $theme_classes)/*$row['theme_class'] == $row_classes['id']*/) $selected = " selected";
							else $selected = "";
							$classes_list  .= sprintf("<option value='%s'$selected>Класс: %s</option>",
							$row_classes['id'],$row_classes['class_name']);
						}
					}
					$sql_subjects = "SELECT * FROM os_subjects WHERE name_ru <> '' AND name_ua <> ''";
					$res_subjects = $mysqli->query($sql_subjects);
					if($res_classes->num_rows != 0) {
						if($row['theme_subject'] == 0) $selected = " selected";
						else $selected = "";
						$subjects_list  .= "<option value='0'$selected>Предмет не выбран</option>";
						if($row['theme_subject'] != 0) {
							while($row_subjects = $res_subjects->fetch_assoc()) {
								if($row['theme_subject'] == $row_subjects['id']) $selected = " selected";
								else $selected = "";
								$subjects_list  .= sprintf("<option value='%s'$selected>Предмет: %s</option>",
									$row_subjects['id'],$row_subjects['name_ru'],$row_subjects['name_ua']);
							}
						}
					}
					$result['theme_course']  = $courses_list;
					$result['theme_class']   = $classes_list;
					$result['theme_subject'] = $subjects_list;

				} else {
					$err[] = "There are no themes with such id";
				}
			}
			print_r(json_encode(array(
						'course'  => $result,
						'success' => count($err)?false:true,
						'error'	  => $err
					)
				)
			);
		}
		if($_POST['flag'] == '5') {
			$sql = "SELECT * FROM os_courses_meta ORDER BY create_date, id";
			$res = $mysqli->query($sql);
			
			$result = "";
			if($res->num_rows!=0) {
				$result .= "<option value='0' selected>Основная школа</option>";
				while($row = $res->fetch_assoc()) {
					$result .= sprintf("<option value='%s'>%s | %s</li>",
						$row['id'],$row['course_name_ru'],$row['course_name_ua']);
				}
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '6') {
			if($_POST['course'] == 0) {
				$sql = "SELECT * FROM os_class_manager WHERE is_opened=0";
			}
			$res = $mysqli->query($sql);

			$result = "";
			if($res->num_rows!=0) {
				$result .= "<option value='0' selected>Все классы</option>";
				while($row = $res->fetch_assoc()) {
					$result .= sprintf("<option value='%s'>Класс: %s</li>",
						$row['id'],$row['class_name']);
				}
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '7') {
			$add_str = '';
			if($_POST['class'] != 0) {
				$add_str .= " AND class = " . $_POST['class'];
			}
			$sql = sprintf("SELECT DISTINCT * FROM os_subjects WHERE id IN(SELECT id_s FROM os_class_subj WHERE course='%s' $add_str )",
					$_POST['course']);
			$res = $mysqli->query($sql);

			$result = "";
			if($res->num_rows!=0) {
				$result .= "<option value='0' selected>Все предметы</option>";
				while($row = $res->fetch_assoc()) {
					$result .= sprintf("<option value='%s'>%s | %s</li>",
						$row['id'],$row['name_ru'],$row['name_ua']);
				}
			}
			print_r(json_encode($result));
		}
		if($_POST['flag'] == '8') {
			if($_POST['column_name'] == 'theme_class') {
				$id_list = "";
				if(is_array($_POST['value'])) {
					$sql = sprintf("DELETE FROM os_theme_classes WHERE id_theme = %s", $_POST['theme_id']);
					$res = $mysqli->query($sql);
					foreach ($_POST['value'] as $value) {
						$sql = sprintf("INSERT INTO os_theme_classes(id_theme,id_class) VALUES(%s,%s)", $_POST['theme_id'], $value);
						$res = $mysqli->query($sql);
						$id_list .= $value . ", ";
					}
					$id_list = rtrim($id_list, ", ");
				}
			} else {
				$sql = sprintf("UPDATE os_themes SET %s='%s' WHERE id=%s",$_POST['column_name'],$_POST['value'],$_POST['theme_id']);
				$res = $mysqli->query($sql);
			}
			$priority = array(
							   1 => 'theme_subject',
							   2 => 'theme_class',
							   3 => 'theme_course'
							 );
			if($_POST['column_name'] == 'theme_course') {
				$sql = "UPDATE os_themes SET theme_class=0, theme_subject=0 WHERE id=" . $_POST['theme_id'];
			} else if($_POST['column_name'] == 'theme_class') {
				$sql = "UPDATE os_themes SET theme_subject=0 WHERE id=" . $_POST['theme_id'];
			}
			$res = $mysqli->query($sql);
			if($_POST['column_name'] == 'theme_class') {
				$sql = sprintf("SELECT * FROM os_subjects WHERE id IN(
								SELECT id_s FROM os_class_subj WHERE class IN($id_list) AND course=(
								SELECT theme_course FROM os_themes WHERE id='%s'))",
					$_POST['theme_id']);
				$res = $mysqli->query($sql);
				if($res->num_rows!=0) {
					$temp_str = "";
					$temp_str .= "<option value='0' selected>Предмет не выбран</option>";
					while($row=$res->fetch_assoc()) {
						$temp_str .= sprintf("<option value='%s'>%s | %s</option>", $row['id'], $row['name_ru'], $row['name_ua']);
					}
				}
			}
			$result = array();
			if($temp_str != '') {
				$result['subjects'] = $temp_str;
			}
			print(json_encode($result));
		}
		if($_POST['flag'] == '9') {
			$add_str = '';
			if($_POST['subject'] != 0) {
				$add_str .= ' AND theme_subject = ' . $_POST['subject'];
			}
			$sql = sprintf("SELECT * FROM os_themes WHERE theme_course = %s $add_str", $_POST['course']);
			if($_POST['class_id'] != 0) {
				$sql .= sprintf(' AND id IN (SELECT id_theme FROM os_theme_classes WHERE id_class = %s )', $_POST['class_id']);
			}
			//print("<br>$sql<br>");
			$res = $mysqli->query($sql);
			if($res->num_rows!=0) {
			$result = array();
			$result['text'] = '';
				while($row = $res->fetch_assoc()) {
					$result['text'] .= "<li onclick='load_theme({$row['id']})'>
										<table>
											<tr>
												<td rowspan='1'>Тема {$row['id']}</td>
												<td>{$row['theme_name_ru']}</td>
											</tr>
											<tr>
												<td></td><td>{$row['theme_name_ua']}</td>
											</tr>
										</table></li>";
				}
			}
			print(json_encode($result));
		}
	}
?>