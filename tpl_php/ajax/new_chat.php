<?php
//print("arg");
	require_once('../autoload.php');
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	//print("arg");
	if($_POST){
		if($_POST['flag'] == '1'){
			
			$sql = "INSERT INTO os_chat_messages(id_chat,id_user,message,`date`) 
			VALUES('".$_POST['chat_id']."','".$_POST['from']."','".$_POST['message']."',now())";
			print($sql);
			$res = $mysqli->query($sql);

			/*$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$row['id_chat']."' AND id_user='".$_POST['from']."' ORDER BY `date` DESC";
			$res = $mysqli->query($sql);
			$result = array();
			while ($row = $res->fetch_assoc()) {
				$result[$row['id']] = array(
					"message" => $row['message'],
					"date" => $row['date'],
					"id" => $_POST['from']
				);
				$sql_ud = "SELECT * FROM os_users WHERE id='".$_POST['from']."'";
				$res_ud = $mysqli->query($sql_ud);
				$row_ud = $res_ud->fetch_assoc();
				$result[$row['id']]['fio'] = $row_ud['surname'].' '.$row_ud['name'];
			}
			print_r(json_encode($result));*/
		}
		if($_POST['flag'] == '2'){
			
		}
		if($_POST['flag'] == '3'){
			$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['chat_id']."' ORDER BY `date` DESC LIMIT 1";
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$query_date = $_POST['date'];
			$row_date = $row['date'];
			$i = 0;
			while ($query_date > $row_date) {
				usleep("1000000");
				$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['chat_id']."' ORDER BY `date` DESC LIMIT 1";
				$res = $mysqli->query($sql);
				$row = $res->fetch_assoc();
				++$i;
			}
			$sql = "SELECT * FROM os_chat_messages WHERE id_chat='".$_POST['chat_id']."' AND `date` < $row_date ORDER BY `date` DESC";
			$res = $mysqli->query($sql);
			$result = "";
			while($row = $res->fetch_assoc()){
				$result .= "<li>";
				$sql_n = "SELECT CONCAT(surname,' ',name) AS fi, level WHERE id='".$row['id_user']."'";
				$res_n = $mysqli->query($sql_n);
				$row_n = $res_n->fetch_assoc()
				if($row_n['level'] == 4){
					$result .= "<span>administration</span><br><span>".$row['message']."</span></li>";
				}
				else{
					$result .= "<span>".$row_n['fi']."</span><br><span>".$row['message']."</span></li>";	
				}
			}
			$data = array(
				"data" => $result,
				"date" => $row_date,
				"cid" => $_POST['chat_id']
			);
			print(json_encode($date));
		}
	}
?>