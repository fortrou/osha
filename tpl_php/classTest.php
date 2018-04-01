<?php
	class Test{
		private $id;
		private $name;
		private $table_convert = array();
		private $test_zip;
		
		static function createTest($lesson,$type, $lang){
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			
			switch ($lang){
				case 'ua':
					switch($type){
						case 4:
							$name = "Тренувальний тест";
						break;
						case 5:
							$name = "Контрольний тест";
						break;
					}
				break;
				case 'ru':
					switch($type){
						case 4:
							$name = "Тренировочный тест";
						break;
						case 5:
							$name = "Контрольный тест";
						break;
					}
				break;
			}

			$name = htmlspecialchars($name);
			$type = htmlspecialchars($type);
			$lang = htmlspecialchars($lang);

			
			$sql = "INSERT INTO os_tests(name,type,lang,less_id)
			VALUES('$name','$type','$lang','$lesson')";
			$result = $mysqli->query($sql);
			//print("<br>$sql<br>");
			//var_dump($result);
			/** Все данные теста, либо ложь **/
			$sql = "SELECT * FROM os_tests WHERE name='$name' AND less_id='$lesson'";
			$result = $mysqli->query($sql);
			//var_dump($result);
			$row = $result->fetch_assoc();
			if($row['name'] != ""){
				foreach($row as $key=>$value){
					$_SESSION['test'][$key] = $value;
				}
				//var_dump($_SESSION['test']);
				//print("<br>");
				return new Test($_SESSION['test']['name'],$_SESSION['test']['id']);
			}
			else{
				return false;
			}
		}
 
    }
?>