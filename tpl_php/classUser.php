<?php 

/**
* User class
*/
class User
{
	
	private $data = array();
	private $level;

	static public $user_levels = array(
		'0' => "ученик ( не подтвержденный ) ",
		'1' => "ученик",
		'2' => "учитель",
		'3' => "менеджер",
		'4' => "администратор" 
	);
	
	static public function auth($log = "",$passw = "")
	{
		if($log == "" || $passw == "") return false;
		$alpha = setcookie("udata",$log."212-212".$passw,time()+60*60*24*30);
		var_dump($alpha);
		$passw = md5($passw."girls");
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$login = $db->clear($log);
		
		$sql = "SELECT * FROM os_users WHERE login='$login'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();

		if( $row['password'] == $passw )
		{
			foreach( $row as $key => $value )
			{
				//if ( $key == 'level' ) continue;
				$_SESSION['data'][$key] = $value;
			}

			return new User($_SESSION['data'] , $row['level']);
		}
		else 
			return false;
	}

	static public function update_avatar($file,$login){
		//var_dump($file);
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		if (File::isValidImg($file))
		{
			//print("<br>AAA<br>");
			$file = File::LoadUpdImg($file, $login);
			//var_dump($file);
				$file = $file;
				$sql = "UPDATE os_users SET avatar='$file' WHERE login='$login'";
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		}
		
		
	}

    static public function redactPD($data_arr,$user_id,$user_login)
	{
		
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "UPDATE os_users SET ";
		foreach($data_arr AS $key => $value){
			if($key == "send") continue;
			if($key == "send_pem") continue;
			if($key == "id_target") continue;
			if($key == "level_target") continue;
			if($key == "lock_status_target") continue;
			if($key == "id_pr") continue;
			$sql .= "$key = '$value', ";
		}
		$sql = rtrim($sql,", ");
		$sql .= " WHERE id='$user_id'";
		print($sql);
		$result = $mysqli->query($sql);
                //var_dump($result);
                $login = htmlspecialchars($user_login);
                $sql = "SELECT * FROM os_users WHERE login='$login'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
			foreach( $row as $key => $value )
			{
				$_SESSION['data'][$key] = $value;
			}
    }
	
	static public function redactPD_1($data_arr,$user_id,$user_login)
	{
		
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "UPDATE os_users SET ";
		foreach($data_arr AS $key => $value){
			if($key == "send") continue;
			if($key == "id_target") continue;
			if($key == "level_target") continue;
			if($key == "lock_status_target") continue;
			if($key == "redact_target") continue;
			if($key == "login_target") continue;
			if($key == "classes") continue;
			if($key == "subjects") continue;
			if($key == "prev_status") continue;
			if($key == "id_pr") continue;
			if($key == "archieve_access") {
				$sql .= sprintf("archieve_access = '%s', ", serialize($_POST['archieve_access']));
				continue;
			}
			$sql .= "$key = '$value', ";
		}
		$sql = rtrim($sql,", ");
		$sql .= " WHERE id='$user_id'";
		print($sql);
		$result = $mysqli->query($sql);
                //var_dump($result);
                $login = htmlspecialchars($user_login);
                $sql = "SELECT * FROM os_users WHERE login='$login'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
			
    }

	static public function createUser($data = array(), $level )
	{
		//var_dump($data);

		if ( $_FILES['avatar']['error'] != 4  )
		{
			if (File::isValidImg($_FILES['avatar']))
			{
				if($_SESSION['data']['level'] == 4)
					$file = File::LoadUpdImg($_FILES['avatar'], $data['login']);
				else
					$file = File::LoadImg($_FILES['avatar'], $data['login']);
				if ( !$file ) throw new Exception("Error of moving your img to server!");
				else
					$data['avatar'] = $file;
				 
			}
			else
			{
				throw new Exception("Image that you're loading is invalid!");
			}
		}

		if ( !array_key_exists( $level, User::$user_levels ) )
			throw new Exception("Such user level doesn't exist");
			

		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$login = $db->clear($data['login']);

		$query = "SELECT login FROM os_users 
				  WHERE login = '$login' ";

		$result = $mysqli->query($query);

		if ( $mysqli->affected_rows > 0 )
			throw new Exception("Such login is already exists!");
		
		$part_1 = "INSERT INTO os_users ( ";
		$part_2 = " VALUES ( ";

		$data['activation'] = md5("silence".$data['login']);
		$data['level'] = $level;

		foreach ($data as $key => $value ) 
		{
			if ( $key == 'send' ) continue;
			if ( $key == 'create' ) continue;
			if ( $key == 'password1' ) continue;
			if ( $key == 'classes' || $key == 'subjects' ) continue;
			if ( $key == 'password' ) $value = md5($value."girls");
			if ( $key == 'g-recaptcha-response' ) continue;
			$part_1 .= " $key, ";
			$part_2 .= "'$value', ";
		}

		$part_1 = rtrim($part_1,", ");
		$part_2 = rtrim($part_2,", ");

		$query = $part_1 . ' ) ' . $part_2 . ')';
		

		//var_dump($query);
		$result = $mysqli->query($query) or die($mysqli->error);
		$sql = "SELECT * FROM os_users WHERE login='".$data['login']."'";
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		$sql_frames = sprintf("INSERT INTO os_user_frames(id_user, id_frame,is_displayed) SELECT %s,id,1 FROM os_frames",$row['id']);
		$res_frames = $mysqli->query($sql_frames);
		$sql_mails = sprintf("INSERT INTO os_user_mails(id_user, id_mail, yep) SELECT %s, id, 1 FROM os_mail_types",$row['id']);
		$res_mails = $mysqli->query($sql_mails);
		$sql = "INSERT INTO os_chat(id) SELECT MAX(id)+1 FROM os_chat";
		$res = $mysqli->query($sql);
		$sql_ch = "SELECT MAX(id) FROM os_chat";
		$res_ch = $mysqli->query($sql_ch);
		$row_ch = $res_ch->fetch_assoc();
		$sql = "UPDATE os_users SET chat_id='".$row_ch['MAX(id)']."' WHERE id='".$row['id']."'";
		$res = $mysqli->query($sql);
		$sql = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), 'admin' FROM os_chat";
		$res = $mysqli->query($sql);
		$sql = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), '".$row['id']."' FROM os_chat";
		$res = $mysqli->query($sql);
		if ( $mysqli->affected_rows <= 0 )
			throw new Exception("Ошибка регистрации!");
		else
			return new User($data,$level);
	}
	/**
	 * get_yearNums($answer_type = 1, $for_user = 0) - получаем список год обучения/номер
	 * @answer_type - тип ответа, отданный сервером, 1 - массив с ответами, 2 - список, 3 - селект
	 * @for_user    - определяет, будет ли отдан ответ под конкретного пользователя или общий случай
	 *
	 **/
	public static function get_yearNums($answer_type = 1, $for_user = 0) {
		$db 		   	  = Database::getInstance();
		$mysqli		   	  = $db->getConnection();
		$where_array   	  = array();
		$result_string 	  = "";
		$years_array      = array();
		$student_archieve = array();

		$where_array[] = sprintf("year_end <= %s", Date("Y"));
		if($for_user != 0) {
			$sql = "SELECT * FROM os_users WHERE id = $for_user";
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$row = $res->fetch_assoc();
				if(!empty($row['archieve_access']) && $row['archieve_access'] != '') {
					$archieve_years = unserialize($row['archieve_access']);
					if(count($archieve_years) > 0) {
						$where_string  = implode(', ', $archieve_years);
						$where_array[] = 'id IN (' . $where_string . ')';
					}
				}
			}
		}
		if(count($where_array) > 0) {
			foreach ( $where_array as $value) {
				if($value != "") {
					$result_string .= $value . ' AND ';
				}
			}
			$result_string = rtrim($result_string, ' AND ');
			if($result_string != "") {
				$result_string = ' WHERE ' . $result_string;
			}
		}
		$sql = "SELECT * FROM os_year_date WHERE year_end <= " . Date("Y");
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			while($row = $res->fetch_assoc()) {
				$years_array[$row['id']] = array( "id" 			  => $row['id'],
												  "years" 		  => $row['year_start'] . ' => ' . $row['year_end'],
												  "year_num" 	  => $row['year_number'],
												  "select_status" => "unselected"
									  			);
			}
		}

		if($for_user != 0 && !empty($result_string) && $result_string != "") {

			//$sql = "SELECT * FROM os_year_date" . $result_string;
			//$res = $mysqli->query($sql);
			$sql = sprintf("SELECT * FROM os_users WHERE id = %s", 
				$for_user);
			$res = $mysqli->query($sql);
			if($res->num_rows != 0) {
				$row = $res->fetch_assoc();
				$arcieve_user = unserialize($row['archieve_access']);
				foreach ($arcieve_user as $value) {
					$years_array[$value]['select_status'] = "selected";
				}
			}
		}
		switch($answer_type) {
			case 1:
				return $years_array;
				break;
			case 2:
				$answer_string = "";
				foreach ($years_array as $value) {
					$answer_string .= sprintf( "<li data-handler='%s' class='arcieve_%s'>%s  | year-num: %s |</li>",
											    	$value['id'], $value['select_status'], $value['years'], $value['year_num'] );
				}
				return $answer_string;
				break;
			case 3:
				$answer_string = "";
				foreach ($years_array as $value) {
					if($value['select_status'] == 'selected') $selected = "selected";
					else $selected = "";
					$answer_string .= sprintf( "<option value='%s' $selected class='arcieve_%s'>%s  | year-num: %s |</option>",
											    	$value['id'], $value['select_status'], $value['years'], $value['year_num'] );
				}
				return $answer_string;
				break;
			default:
				return $years_array;
				break;
		}



		return false;
	}
	static public function createUser_main($data = array(), $level ) {
		//var_dump($data);

		if ( $_FILES['avatar']['error'] != 4  )
		{
			if (File::isValidImg($_FILES['avatar']))
			{
				if($_SESSION['data']['level'] == 4)
					$file = File::LoadUpdImg($_FILES['avatar'], $data['login']);
				else
					$file = File::LoadImg($_FILES['avatar'], $data['login']);
				if ( !$file ) throw new Exception("Error of moving your img to server!");
				else
					$data['avatar'] = $file;
				 
			}
			else
			{
				throw new Exception("Image that you're loading is invalid!");
			}
		}

		if ( !array_key_exists( $level, User::$user_levels ) )
			throw new Exception("Such user level doesn't exist");
			

		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$login = $db->clear($data['login']);

		$query = "SELECT login FROM os_users 
				  WHERE login = '$login' ";

		$result = $mysqli->query($query);

		if ( $mysqli->affected_rows > 0 )
			throw new Exception("Such login is already exists!");
		
		$part_1 = "INSERT INTO os_users ( ";
		$part_2 = " VALUES ( ";

		$data['activation'] = md5("silence".$data['login']);
		$data['level'] = $level;

		foreach ($data as $key => $value ) 
		{
			if ( $key == 'send' ) continue;
			if ( $key == 'create' ) continue;
			if ( $key == 'password1' ) continue;
			if ( $key == 'classes' || $key == 'subjects' ) continue;
			if ( $key == 'password' ) $value = md5($value."girls");
			if ( $key == 'g-recaptcha-response' ) continue;
			$part_1 .= " $key, ";
			$part_2 .= "'$value', ";
		}

		$part_1 = rtrim($part_1,", ");
		$part_2 = rtrim($part_2,", ");

		$query = $part_1 . ' ) ' . $part_2 . ')';
		

		//var_dump($query);
		$result = $mysqli->query($query) or die($mysqli->error);
		$sql = "SELECT * FROM os_users WHERE login='".$data['login']."'";
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		$sql_frames = sprintf("INSERT INTO os_user_frames(id_user, id_frame,is_displayed) SELECT %s,id,1 FROM os_frames",$row['id']);
		$res_frames = $mysqli->query($sql_frames);
		$sql_mails = sprintf("INSERT INTO os_user_mails(id_user, id_mail, yep) SELECT %s, id, 1 FROM os_mail_types",$row['id']);
		$res_mails = $mysqli->query($sql_mails);
		$sql = "INSERT INTO os_chat(id) SELECT MAX(id)+1 FROM os_chat";
		$res = $mysqli->query($sql);
		$sql_ch = "SELECT MAX(id) FROM os_chat";
		$res_ch = $mysqli->query($sql_ch);
		$row_ch = $res_ch->fetch_assoc();
		$sql = "UPDATE os_users SET chat_id='".$row_ch['MAX(id)']."' WHERE id='".$row['id']."'";
		$res = $mysqli->query($sql);
		$sql = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), 'admin' FROM os_chat";
		$res = $mysqli->query($sql);
		$sql = "INSERT INTO os_chat_users(id_chat,id_user) SELECT MAX(id), '".$row['id']."' FROM os_chat";
		$res = $mysqli->query($sql);
		if ( $mysqli->affected_rows <= 0 )
			throw new Exception("Ошибка регистрации!");
		else{
			$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
			$headers .= "From: online-shkola.com.ua\r\n"; 
			$headers .= "Bcc: no-reply@online-shkola.com.ua\r\n"; 
			$mail_text = sprintf("<div style=''><span style=''>Здравствуйте, уважаемый(-ая) %s %s %s</span><br>
				<span style=''>Ваш код(введите его в форму активации) <b>%s</b></span></div>",
				$_POST['surname'],$_POST['name'],$_POST['patronymic'],md5("silence".$_POST['login']));
			mail($_POST['email'],"Подтверждение регистрации на сайте online-shkola.com.ua",$mail_text,$headers);
			$user = User::auth( $_POST['login'] , $_POST['password'] );
			//print_r($_SESSION);
			if($user == false){
				header('Location:reg.php');
			}
			else{
				header('Location:cabinet/accept_profile.php');
			}
			return new User($data,$level);
		}
	}

	public function loadUser($id){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "SELECT * FROM os_users WHERE id='$id'";
		$res = $mysqli->query($sql);
		$row = $res->fetch_assoc();
		//var_dump($row);
		return $user = new User($row,$row['level']);
	}

	public function uniGet($type){
		return $this->data[$type];
	}

	public function getTclasses(){
		if($this->data['level'] == 2){
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql = sprintf("SELECT * FROM os_teacher_class WHERE id_teacher='%s'",$this->data['id']);
			//print($sql);
			$res = $mysqli->query($sql);
			$result = array();
			while ($row = $res->fetch_assoc()) {
				$result[] = $row['id_c'];
			}
			return $result;
		}
		else return false;
	}

	public function getTsubjects(){
		if($this->data['level'] == 2){
			$sql = sprintf("SELECT * FROM os_teacher_subj WHERE id_teacher='%s'",$this->data['id']);
			$res = $mysqli->query($sql);
			$result = array();
			while ($row = $res->fetch_assoc()) {
				$result[] = $row['id_s'];
			}
			return $result;
		}
		else return false;
	}

	private function __construct($data,$level)
	{
			$this->data = $data;
			$this->level = $level;		
	}
};

 ?>