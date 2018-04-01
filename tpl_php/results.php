<?php
require_once('autoload.php');
$db = Database::getInstance();
$mysqli = $db->getConnection();
$year_num = get_currentYearNum();
$sub_query_add = "";
if($year_num) {
	$sub_query_add = " AND lesson_year = " . $year_num;
}
if ( $_POST['id'])
{
	$sql_user_data = sprintf("SELECT * FROM os_users WHERE id = %s", $_POST['id']);
	$res_user_data = $mysqli->query($sql_user_data);
	$row_user_data = $res_user_data->fetch_assoc();
	$lang = $_POST['lang'];
	$journal = array();
	$journal['journal']   = "";
	$journal['dates_ru']  = "";
	$journal['dates_ua']  = "";
	$journal['date_reg']  = $row_user_data['date_start_learning'];
	$journal['user_data'] = $row_user_data['surname'] . " " . 
							$row_user_data['name'] . " " . 
							$row_user_data['patronymic'] . "  |  Класс: " .
							$row_user_data['class'] . " | ID: " . 
							$row_user_data['id'];
	$print_td = "";
	
	$query_pre = sprintf("SELECT * FROM os_journal WHERE id_s='%s' AND id_subj='%s' AND (id_l IN(SELECT id FROM os_lessons 
		WHERE id IN(SELECT id_lesson FROM os_lesson_classes WHERE 1 = 1  $sub_query_add AND id_class =( SELECT class FROM os_users WHERE id='%s')))
		 OR (id_l=0 AND year_num = $year_num))
	 ORDER BY date_$lang ASC, status ASC",
		$_POST['id'],$_POST['subj'],$_POST['id']);
	//print("<br>$query_pre<br>");
	$result_pre = $mysqli->query($query_pre);
	while ( $row_pre = $result_pre->fetch_assoc() ) {
		$redact_control = $row_pre['mark_contr'];
		if($_POST['level'] == 1){
			$hw = sprintf("<a href='http://online-shkola.com.ua/homework/index.php?id=%s' target='_blank'>%s</a>",$row_pre['id'],$row_pre['mark_hw']);
		}
		if($_POST['level'] > 1){
			$hw = sprintf("<a href='http://online-shkola.com.ua/homework/index.php?id=%s' target='_blank'>%s</a>",$row_pre['id_l'],$row_pre['mark_hw']);
		}
		$set_uncomplete = '';
		if($_POST['level'] == 4) {
			$set_uncomplete = sprintf("<td><span class='download' onclick='set_null_test(%s, %s)'>Обнулить</span></td>", $_POST["id"], $row_pre["id_l"]);
			$redact_control = sprintf('<input type="text" value="%s" oninput="update_test(%s, this.value)" class="journal-text-input">', 
										$row_pre['mark_contr'], $row_pre['id']);
		}
		$id_lesson = $row_pre['id_l'];
		if($_POST['level'] != 3){
			if(!isset($lang) || $lang == "ru"){
				if($row_pre['status'] == 1){
					$print_td = "<td><span class='download' onclick=\"open_print_modal(".$row_pre['id'].")\">Печать</span></td>";
				}
				/*if($row_pre['status'] == 2){
					$print_td = "<td class='theme_mark'><span class='download' onclick=\"open_print_modal(".$row_pre['id'].")\">Печать</span></td>";
				}*/
				if($row_pre['status'] == 3){
					$print_td = "<td class='contr_mark'><span class='download' onclick=\"open_print_modal(".$row_pre['id'].")\">Печать</span></td>";
				}
			} else {
				if($row_pre['status'] == 1){
					$print_td = "<td><span class='download' onclick=\"open_print_modal(".$row_pre['id'].")\">Друк</span></td>";
				}
				/*if($row_pre['status'] == 2){
					$print_td = "<td class='theme_mark'><span class='download' onclick=\"open_print_modal(".$row_pre['id'].")\">Друк</span></td>";
				}*/
				if($row_pre['status'] == 3){
					$print_td = "<td class='contr_mark'><span class='download' onclick=\"open_print_modal(".$row_pre['id'].")\">Друк</span></td>";
				}
			}
		}

		$mark_common = $row_pre['mark_hw']+$row_pre['mark_contr'];
		$sql = sprintf("SELECT title_$lang, DATE(date_$lang) as dte FROM os_lessons WHERE id='%s'",$row_pre['id_l']);
		//print($sql);
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		if($row_pre['status'] == 1){
			$str = sprintf("<tr>
							<td><a href='../lessons/watch.php?id=$id_lesson'>%s</a></td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							$print_td 
							$set_uncomplete</tr>",
			$row['title_'.$lang],$row_pre['date_'.$lang],$row_pre['mark_tr'],$redact_control,$hw,$mark_common);
		}
		if($row_pre['status'] == 3){
			$str = sprintf("<tr><td class='contr_mark'><a href='../lessons/watch.php?id=$id_lesson'>%s</a></td>
				<td class='contr_mark'>%s</td><td class='contr_mark'>%s</td>
				<td class='contr_mark'>%s</td><td class='contr_mark'>%s</td><td class='contr_mark'>%s</td> $print_td $set_uncomplete</tr>",
			$row['title_'.$lang],$row_pre['date_'.$lang],$row_pre['mark_tr'],$redact_control,$hw,$mark_common);
		}
		/*if($row_pre['status'] == 2){
			$name = $row_pre['title_t_'.$lang];
			$str = "<tr><td class='theme_mark'>$name";
			if($_POST['level'] == 4)
				$str .= "<div class='delete' onclick=\"del_theme('".$row_pre['id']."')\">удалить</div>";
			$str .= "</td><td class='theme_mark'>".$row_pre['date_'.$lang]."</td>";
			if($row_pre['mark_tr'] != 0){
				$str .= "<td colspan='4' class='theme_mark'>".$row_pre['mark_tr']."</td>$print_td</tr>";
			}
			if($row_pre['mark_contr'] != 0){
				$str .= "<td class='theme_mark'></td><td colspan='3' class='theme_mark'>".$row_pre['mark_contr']."</td>$print_td</tr>";
			}
			if($row_pre['mark_hw'] != 0){
				$str .= "<td class='theme_mark'></td><td class='theme_mark'></td><td colspan='2' class='theme_mark'>".$row_pre['mark_hw']."</td>$print_td</tr>";
			}
			if($row_pre['mark_com'] != 0){
				$str .= "<td class='theme_mark'></td><td class='theme_mark'></td><td class='theme_mark'></td><td class='theme_mark'>".$row_pre['mark_com']."</td>$print_td</tr>";
			}
		}*/
		$journal['journal'] .= $str;
	}


	
	print_r(json_encode($journal));
}


?>