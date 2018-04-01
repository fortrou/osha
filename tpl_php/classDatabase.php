<?php

/**
* Database class 
*/
class Database
{
	private $connection;

	private static $_instance;

	public static function getInstance() {
		if ( !self::$_instance ) self::$_instance = new self();

		return self::$_instance;
	}

	private function __construct() {
		// $this->connection = new mysqli( 'localhost' , 'root' , 'qq', 'os' );
		//$this->connection = new mysqli( 'localhost' , 'sozonen_skola' , 'sozonen_skola' , 'sozonen_skola');
		$this->connection = new mysqli( 'localhost' , 'cnarpzjy_db' , 'q6Vo$Efbey=S' , 'cnarpzjy_db');
		// $this->connection->query("SET NAMES 'cp1251'");
		$this->connection->query("SET NAMES 'utf8';"); 
		$this->connection->query("SET CHARACTER SET 'utf8';"); 
		$this->connection->query("SET SESSION collation_connection = 'utf8_general_ci';"); 
		
		if ( mysqli_connect_error() )
			throw new Exception("Failed to connect to DB:" . mysqli_connect_error());
	}

	private function __clone () {
	}

	public function getConnection() {
		return $this->connection;
	}

	public function clear($value) {
		if(!$this->connection) throw new Exception("You didn't connect to the DB");
	
		return $this->connection->real_escape_string(trim(strip_tags($value)));
	}

	public static function update($table = '', $params = array(), $where = array()) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		if($table == '' || count($params) == 0 || count($where) == 0 ) return false;
		$part_1 = '';
		$part_2 = '';
		foreach($params as $key => $value) {
			if($value == '') {
				$part_1 .= $key . " = '',";
			} else if(is_int($value)) {
				$part_1 .= $key . " = $value,";
			} else {
				$part_1 .= $key . " = '$value',";
			}
		}
		$part_1 = rtrim($part_1,',');
		foreach($where as $key => $value) {
			if(is_int($value)) {
				$part_2 .= $key . " = $value AND ";
			} else {
				$part_2 .= $key . " = '$value' AND ";
			}
		}
		$part_2 = rtrim($part_2, ' AND ');
		$sql = "UPDATE {$table} SET {$part_1} WHERE {$part_2}";
		print("<br>UPDATE SQL --- $sql<br>");
		$res = $mysqli->query($sql);
		if($mysqli->affected_rows == 0) return false;
		return $mysqli->affected_rows;
	}
	public static function delete($table = '',$where = array()) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		if($table == '' || count($where) == 0 ) return false;
		$part_1 = '';
		foreach($where as $key => $value) {
			if(is_int($value)) {
				$part_1 .= $key . " = $value AND ";
			} else {
				$part_1 .= $key . " = '$value' AND ";
			}
		}
		$part_1 = rtrim($part_1, ' AND ');
		$sql = "DELETE FROM {$table} WHERE {$part_1}";
		print("<br>DELETE SQL --- $sql<br>");
		$res = $mysqli->query($sql);
		if($mysqli->affected_rows == 0) return false;
		return true;
	}
}

?>
