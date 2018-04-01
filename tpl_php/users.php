<?php
require_once('autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
function getVideoLink($link) 
	{
		$video = $link;
	    $pos_1 = strpos($video,'com');
	    $str = substr($video, 0 , $pos_1 + 4 );
	   
	    $str .= 'embed/';
	    
	    $pos_2  = strpos($video , 'v=');
	    $str .= substr($video , $pos_2 + 2 , strlen($video) - $pos_2 - 2 );

	    /*if ( date('Y-m-d H:i:s') < strftime("%Y-%m-%d %H:%M:%S" , strtotime($this->data['date'])) )
	    	return;*/

	    return $str;
	}
if($_POST['flag'] == '1'){
	if ( $_POST['level'] == '1')
	{


		$query = "SELECT id, name, surname, patronymic, email, lock_status, edu_type
				  FROM os_users
				  WHERE class IN (";

		$query .= $_POST['id'] . ") AND level IN (".$_POST['level'].") AND lock_status IN(".$_POST['status']
		.") AND edu_type IN(".$_POST['type'].")";
		
		if ($_POST['subjects'] != "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33") {
			$query .= sprintf(" AND ( id IN (SELECT id_student FROM os_student_subjects WHERE id_subject='%s') OR 
				id IN (SELECT id_teacher FROM os_teacher_subj WHERE id_s='%s'))",
			$_POST['subjects'],$_POST['subjects']);
		}
		$result = $mysqli->query($query);
		//print($query);
		$pupils = array();

		while ( $row = $result->fetch_assoc() ) 
		{
			$pupils[$row['id']] = array ( 
				'second' => $row['surname'] ,
				'third' => $row['name'],
				'forth' => $row['email'],
				'sixth' => $row['id']
				);
			switch ($row['lock_status']) {
				case '0':
					$pupils[$row['id']]['fifth'] = sprintf("<input type='button' class='lock_adm_surlist' onclick=\"change_lock1(%s)\" value='Заблокировать'>",$row['id']);
					break;
				case '1':
					$pupils[$row['id']]['fifth'] = sprintf("<input type='button' class='unlock_adm_surlist' onclick=\"change_lock1(%s)\" value='Разблокировать'>",$row['id']);
					break;
			}
			switch ($row['edu_type']) {
				case '0':
					$pupils[$row['id']]['seventh'] = "Не установлен";
					break;
				case '1':
					$pupils[$row['id']]['seventh'] = "Общее образование";
					break;
				case '2':
					$pupils[$row['id']]['seventh'] = "Дополнительное образование";
					break;
				case '3':
					$pupils[$row['id']]['seventh'] = "Частичное образование";
					break;
			}
		}

		print(json_encode($pupils));
	}
	if ( $_POST['level'] != '1')
	{
		$query = "SELECT id, name, surname, patronymic, email, lock_status, level, edu_type
				  FROM os_users
				  WHERE level IN(".$_POST['level'].") AND lock_status IN(".$_POST['status'].")";

		if ($_POST['subjects'] != "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33") {
			$query .= sprintf(" AND ( id IN (SELECT id_student FROM os_student_subjects WHERE id_subject='%s') OR 
				id IN (SELECT id_teacher FROM os_teacher_subj WHERE id_s='%s'))",$_POST['subjects'],$_POST['subjects']);
		}
		$query .= " ORDER BY level DESC, surname ASC";
		//print($query);
		$result = $mysqli->query($query);

		$pupils = array();
$iter = 0;
if($result->num_rows==0) exit();
		while ( $row = $result->fetch_assoc() ) 
		{
			$pupils[$iter] = array ( 
				'second' => $row['surname'] ,
				'third' => $row['name'],
				'forth' => $row['email'],
				'sixth' => $row['id']
				);
			switch ($row['lock_status']) {
				case '0':
					$pupils[$iter]['fifth'] = sprintf("<input type='button' class='lock_adm_surlist' onclick=\"change_lock1(%s)\" value='Заблокировать'>",$row['id']);
					break;
				case '1':
					$pupils[$iter]['fifth'] = sprintf("<input type='button' class='unlock_adm_surlist' onclick=\"change_lock1(%s)\" value='Разблокировать'>",$row['id']);
					break;
			}
			if($row['level'] == 1){
				switch ($row['edu_type']) {
					case '0':
						$pupils[$iter]['seventh'] = "Не установлен";
						break;
					case '1':
						$pupils[$iter]['seventh'] = "Общее образование";
						break;
					case '2':
						$pupils[$iter]['seventh'] = "Дополнительное образование";
						break;
					case '3':
						$pupils[$iter]['seventh'] = "Частичное образование";
						break;
				}
			}
			else{
				$pupils[$iter]['seventh'] = "";
			}
			$iter++;
		}

		print(json_encode($pupils));
	}
}
if($_POST['flag'] == '2'){
	$id = $_POST['id'];
	$sql = "SELECT lock_status FROM os_users WHERE id='$id'";
	$res = $mysqli->query($sql);
	$row = $res->fetch_assoc();
	if($row['lock_status'] == 0)
		$sql = "UPDATE os_users SET lock_status=1 WHERE id='$id'";
	else
		$sql = "UPDATE os_users SET lock_status=0 WHERE id='$id'";
	$res = $mysqli->query($sql);
}
if($_POST['flag'] == '3'){
	$str = getVideoLink($_POST['link']);
	print(json_encode($str));
}
if($_POST['flag'] == '4'){
	$id = $_POST['id'];
	$sql = "UPDATE os_users SET class='".$_POST['class_id']."' WHERE id='$id'";
	$res = $mysqli->query($sql);

}
?>