<?php 

/**
 * Online-lesson class.
 * dev by @fortrou
 *
 **/
class Lesson
{
	private $data = array();

	static public function Create($ar = array())
	{
		//var_dump($ar);
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$sql = "INSERT INTO os_lessons ( ";

		foreach ($ar as $f => $v) 
		{
			if ( $f == 'send') continue;
			if ( $f == 'control'){ 
				$f_q .= "is_control, ";
				$l_q .= "'$v', "; 
				continue;
			}
			if ( $f == 'class' ) continue;

			$f_q .= $f . ', ';

			if ( $f != 'links_ua' && $f != 'links_ru' ){
				//print("a");
				$temp = $db->clear($v);
			}
			else{
				$temp = $v;	
			}

			$l_q .= "'$temp', ";
		}

		$f_q = rtrim($f_q, ', ');
		$l_q = rtrim($l_q, ', ');

		$sql .= $f_q . " ) VALUES ( " . $l_q . ' )';

		
		//print($sql);
		$mysqli->query($sql) or die($mysqli->error);
		if ($mysqli->affected_rows > 0) {
			$insertPart = "";
			$lessonId = $mysqli->insert_id;
			foreach ($ar['class'] as $value) {
				$insertPart .= sprintf("(%s,%s),",$lessonId,$value);
			}
			$insertPart   = rtrim($insertPart,",");
			$insertQuery  = "INSERT INTO os_lesson_classes(id_lesson,id_class) VALUES" . $insertPart;
			$insertResult = $mysqli->query($insertQuery);
			return $lessonId;
		} else {
			return false;
		}
		return $mysqli->affected_rows > 0 ? $mysqli->insert_id : false;
	}

	public static function Update ( $id = NULL , $data = array() )
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$query = "UPDATE os_lessons SET ";

		foreach ( $data as $f => $v ) 
		{
			if ( $f == 'send') continue;
			if ($f == 'title_ua' || $f == 'title_ru' || $f == 'video_ru' || $f == 'video_ua') {
				if(preg_match("/'/", $v)){
					for ($i=0; $i < strlen($v); $i++) {
						$str_old="";
						$str_new="";
						if ($v[$i] == "'") {
							for ($j=0; $j < $i; $j++) { 
								$str_old .= $v[$j];
							}
							for ($k=$i+1; $k < strlen($v); $k++) { 
								$str_new .= $v[$k];
							}
							$str_old .= "&#39;".$str_new;
							$v = $str_old;
						}
					}
				}
			}

			$v = htmlspecialchars($v);
			//echo preg_replace("/'/", "&#39;", $v);

			//print(htmlspecialchars(" Обов'язки в сім'ї. Повторення Present Perfect ")."<br>");
				//print("<br> $f - - - $v <br>");
			$query .= $f . ' = ' . "'$v', ";
		}

		$query = rtrim($query , ' ,' );

		$query .= " WHERE id = '$id'";
		//print("<br>$query<br>");
		$result = $mysqli->query($query);
		//var_dump($result);
		/*if ( $mysqli->affected_rows <= 0 )
			throw new Exception("Ошибка!");*/
			
	}

	private function __construct($ar = array())
	{
		foreach ($ar as $f => $v) 
		{
			$this->data[$f] = $v;
		}
	}

	public static function secondStage($id , $testarr )
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "SELECT subject 
		FROM os_lessons 
		WHERE id='$id'";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		
		if(empty($testarr)){
			
			return false;
		}
		if($id==0){
			return false;
		}
		foreach($testarr as $value){
			$sql = "INSERT INTO os_lesson_test(id_lesson,id_test) VALUES('$id','$value')";
			$result = $mysqli->query($sql);
		}
		return 1;
	}
	
	public static function create_hw($id_less, $text_ru,$text_ua, $mark=''){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql_olesson = "SELECT * FROM os_lessons WHERE id='$id_less'";
		$res_olesson = $mysqli->query($sql_olesson);
		$row_olesson = $res_olesson->fetch_assoc();
		$new_date = Date("Y-m-d", strtotime($row_olesson['date_ua']) + 3600 * 24 * 7);
		if($row_olesson['same_lang'] != 1)
		$sql = "INSERT INTO os_lesson_homework(id_lesson,hw_text_ru,hw_text_ua,mark) VALUES($id_less,'$text_ru','$text_ua',$mark)";
		else
		$sql = "INSERT INTO os_lesson_homework(id_lesson,hw_text_ru,hw_text_ua,mark) VALUES($id_less,'$text_ru','$text_ru',$mark)";
		//print("<br>$sql<br>");
		$result = $mysqli->query($sql);
		$sql = sprintf("SELECT * FROM os_homeworks WHERE id_hw IN(SELECT id FROM os_lesson_homework WHERE id_lesson='%s')",$id_less);
		//print("<br>$sql<br>");
		$res = $mysqli->query($sql);
		if($res->num_rows == 0){
			$sql_lesson = sprintf("SELECT * FROM os_lessons WHERE id='%s'",$id_less);
			$res_lesson = $mysqli->query($sql_lesson);
			$row_lesson = $res_lesson->fetch_assoc();
			//print("<br>$sql_lesson<br>");
			if($row_lesson['course'] != 0) {
				$where_array = array();
				$where_array[] = "id_course=" . $row_lesson['course'];
				$where_array[] = "payment_end_date>=" . Date("Y-m-d");
				if(count($where_array)) {
					$where_string = "";
					foreach ($where_array as $value) {
						$where_string .= $value . " AND ";
					}
					$where_string = rtrim($where_string, " AND ");
				}
				$where_course = " AND id IN(SELECT DISTINCT id_user FROM os_courses_students WHERE $where_string GROUP BY id_course, id_user HAVING MAX(id))";
			}
			$sql_users = sprintf("SELECT * FROM os_users WHERE level = 1 AND class IN (SELECT id_class FROM os_lesson_classes WHERE id_lesson='%s') $where_course",$id_less);
			//print("<br>$sql_users<br>");
			$res_users = $mysqli->query($sql_users);
			while($row_users = $res_users->fetch_assoc()){
				$date = explode(' ',$row_lesson['date_ua']);
				//var_dump($date);
				$sql_id = sprintf("SELECT id FROM os_lesson_homework WHERE id_lesson='%s'",$id_less);
				$res_id = $mysqli->query($sql_id);
				$row_id = $res_id->fetch_assoc();
				if ($row_lesson["is_control"] == 1) {
					$status = "'3'";
				}
				else{
					$status = "'1'";
				}
				$sql_new = sprintf("INSERT INTO os_homeworks(date_h, `from`, subj, class, id_hw, status, last_hw_date) 
										 VALUES ('%s', %s, %s, %s, %s, $status, '$new_date')",
					$date[0],$row_users['id'],$row_lesson['subject'],$row_users['class'],$row_id['id']);
				//print("<br>$sql_new<br>");
				$res_new = $mysqli->query($sql_new);
			}
			
		}
		//var_dump($result);
		return $result;
	}

	public static function finalStage($id , $ua, $ru )
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$sql = "SELECT summary_ua, summary_ru 
				FROM os_lessons
				WHERE id = '$id'";

		$result = $mysqli->query($sql);

		$row = $result->fetch_assoc();

		//if ( !empty($row['summary_ru']) && !empty($row['summary_ua']) ) return false;


		$sql = "UPDATE os_lessons SET ";

		$sql .= " summary_ru = " . "'$ru'";
		$sql .= ", summary_ua = " . "'$ua'";

		$sql .= " WHERE id = '$id' ";

		print($sql);
		$result = $mysqli->query($sql);

		return $mysqli->affected_rows > 0 ? true : false;
	}

	/**
	 * function check_users_access
	 * dev by @fortrou
	 * check_users access to the lesson
	 **/
	public function check_users_access($lesson){
		if(empty($lesson)) header("Location:../");
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$class  = $this->getClass();
		$false_token = true;
		if(isset($_SESSION['data'])) {
			if($_SESSION['data']['level'] > 1) return true;
			else if($_SESSION['data']['level'] == 1) {
				if(strpos($class, $_SESSION['data']['class']) === false) {
					$false_token = false;
				}
				if($this->data['course'] == 0) {
					$sql = sprintf("SELECT * FROM os_student_subjects WHERE id_student = %s AND id_subject = %s", 
						$_SESSION['data']['id'], $this->data['subject']);
				} else {
					$sql = sprintf("SELECT * FROM os_courses_students WHERE id_course=%s AND id_user=%s AND id = (
									SELECT MAX(id) FROM os_courses_students WHERE id_course=%s AND id_user=%s AND payment_verified=1)"
									, $this->data['course'],$_SESSION['data']['id'], $this->data['course'],$_SESSION['data']['id']);
				}
				$res = $mysqli->query($sql);
				if($res->num_rows == 0) {
					$false_token = false;
				}
			} else {
				$false_token = false;
			}
		} else {
			if(strpos($class, 'Открытый') === false) {
				$false_token = false;
			}
		}
		if($false_token == false)  header("Location:../");
		return true;
		
	}

	public static function Load( $id = NULL)
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$sql = "SELECT a.id, class, date_ua, date_ru, b.name_ru, b.name_ua, 
				title_ua,title_ru, video_ua, video_ru,
				links_ru, links_ua, summary_ua, summary_ru, b.id AS subject, course, theme 
				FROM os_lessons as a
				JOIN os_subjects as b
				ON a.subject = b.id 
				WHERE a.id = '$id' ";

		$result  = $mysqli->query($sql) or die($mysqli->error);
		//var_dump($_SESSION['data']);
		if ( $mysqli->affected_rows > 0 )
			return new Lesson($result->fetch_assoc());
		else
			throw new Exception("Lesson with such ID doesn't exist!");
	}

	public function getDate($lang = "ru") 
	{
		return $this->data['date_'.$lang];
	}

	public function getVideoLink($language = 'ru') 
	{
		$video = $this->data['video_' . $language];
	    $pos_1 = strpos($video,'com');
	    $str = substr($video, 0 , $pos_1 + 4 );
	   
	    $str .= 'embed/';
	    
	    $pos_2  = strpos($video , 'v=');
	    $str .= substr($video , $pos_2 + 2 , strlen($video) - $pos_2 - 2 );

	    if ( date('Y-m-d H:i:s') < strftime("%Y-%m-%d %H:%M:%S" , strtotime($this->data['date_'.$language])) )
	    	return;

	    return $str;
	}

	public function getSummary($language = 'ru') 
	{
		return $this->data['summary_' . $language];
	}

	public function getLinks($language = 'ru') 
	{
		return $this->data['links_' . $language];
	}

	public function getTitle($language = 'ru') 
	{
		return $this->data['title_' . $language];
	}

	public function getClass()
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = sprintf("SELECT * FROM os_lesson_classes WHERE id_lesson = %s", $this->data['id']);
		$res = $mysqli->query($sql);
		if($res->num_rows != 0) {
			$preResultArray = array();
			while($row = $res->fetch_assoc()) {
				$preResultArray[] = $row['id_class'];
			}
			$query_string = implode(',', $preResultArray);
			$sql_class = sprintf("SELECT * FROM os_class_manager WHERE id IN (%s)", $query_string);
			$res_class = $mysqli->query($sql_class);
			if($res_class->num_rows != 0) {
				$preResultArray = array();
				while($row_class = $res_class->fetch_assoc()) {
					$preResultArray[] = $row_class['class_name'];
				}
				$query_string = implode(',', $preResultArray);
				//print("<br>$query_string<br>");
				return $query_string;
			}
		}
		return 0;
	}

	public function getSubject($lang = 'ru') 
	{
		return $this->data['name_'.$lang];
	}

	public function getLockStatus()
	{
		return $this->data['lock_status'];
	}

	public function getTrTest($language = 'ru') 
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$id = $this->data['id'];
		$sql = "SELECT * FROM os_lesson_test WHERE id_lesson='$id' AND type=4 AND lang='$language'";
		$result = $mysqli->query($sql);
		if($result == false)
			return false;
		$row = $result->fetch_assoc();
		return $row['id_test'];
	}
	public function getContrTest($language = 'ru') 
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$id = $this->data['id'];
		
		$sql = "SELECT * FROM os_lesson_test WHERE id_lesson='$id' AND type=5 AND lang='$language'";
		$result = $mysqli->query($sql);
		//var_dump($result);
		if($result == false)
			return false;
		$row = $result->fetch_assoc();
		//var_dump($row);
		return $row['id_test'];
	}
	public function getHW() 
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$id = $this->data['id'];
		$sql = "SELECT * FROM os_lesson_homework WHERE id_lesson='$id'";
		//print($sql);
		$result = $mysqli->query($sql);
		if($result == false)
			return false;
		$row = $result->fetch_assoc();
		return $row['id'];
	}
	public function getJournal($id_user){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$id = $this->data['id'];
		$sql = "SELECT * FROM os_journal WHERE id_l='$id' AND id_s=$id_user";
		$result = $mysqli->query($sql);
		if($result == false)
			return false;
		$row = $result->fetch_assoc();
		return $row['id'];
	}	
	public function getNameById($id, $lang='ru')
	{
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = sprintf("SELECT title_%s FROM os_lessons WHERE id='$id'",$lang);
		$result = $mysqli->query($sql);
		//var_dump($result);
		if($result == false)
			return false;
		$row_local = $result->fetch_assoc();
		$tstr = sprintf("title_%s",$lang);
		return $row_local[$tstr];
	}
	public function delete_lesson($id){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$sql = "DELETE FROM os_lessons WHERE id='$id'";
		$result = $mysqli->query($sql);
	}
}


 ?>