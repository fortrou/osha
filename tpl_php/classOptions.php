<?php
	/**
	 * class Options
	 * Регулирование опций, отвечающих за работу школы
	 *
	 * dev by fortrou
	 **/

class Options {
	private $table_name = 'os_options';
	private $option_map = array( 1 => "date", 
								 2 => "int_value",
								 3 => "value" );
	private $separator_map = array( 1 => "li",
									2 => "option" );
	private $high_separator_map = array( 1 => "ul",
										 2 => "select" );

	function __construct() {}

	/**
	 * get_optionList - получение списка опций
	 * $list_type - как будет отдан список
	 *   1 - list(default), 2 - select, 3 - json, 4 - array
	 *
	 **/
	public function get_optionList($list_type = 1) {
		$db 		  = Database::getInstance();
		$mysqli 	  = $db->getConnection();
		$return_value = "";
		if($list_type < 1 ||  $list_type > 4)  throw new InvalidArgumentException('Incorrect_list_type');
		$sql = "SELECT * FROM os_options";
		$res = $mysqli->query($sql);
		if($res->num_rows == 0) throw new Exception('Incorrect_options_amount');
		if(in_array($list_type, array(1, 2))) {
			$separator = $this->separator_map[$list_type];
		}
		while($row = $res->fetch_assoc()) {
			if($row['option_type'] == 1) {
				$return_value .= sprintf('<%s><form method="post" action="">
												<input type="datetime" value="%s" name="option_value"> <span>%s</span>
												<input type="hidden" name="option_name" value="%s">
												<input type="submit" name="save_changes" value="сохранить">  
											</form></%s>', 
										$separator, $row['option_date'], $row['option_ru_name'], $row['option_name'], $separator);
			} else if($row['option_type'] == 2) {
				$return_value .= sprintf('<%s><form method="post" action="">
												<input type="text" value="%s" name="option_value"> <span>%s</span>
												<input type="hidden" name="option_name" value="%s">
												<input type="submit" name="save_changes" value="сохранить"> 
											</form></%s>', 
										$separator, $row['option_int_value'], $row['option_ru_name'], $row['option_name'], $separator);
			} else if($row['option_type'] == 3) {
				$return_value .= sprintf('<%s><form method="post" action="">
												<input type="text" value="%s" name="option_value"> <span>%s</span>
												<input type="hidden" name="option_name" value="%s">
												<input type="submit" name="save_changes" value="сохранить"> 
											</form></%s>', 
										$separator, $row['option_value'], $row['option_ru_name'], $row['option_name'], $separator);
			}
		}
		//print("<br>$return_value<br>");
		return $return_value;
	}

	public function get_option($option_name = '') {
		$db 		  = Database::getInstance();
		$mysqli 	  = $db->getConnection();
		$return_value = "";

		if($option_name == '') throw new InvalidArgumentException('Incorrect_option_name');
		$sql = sprintf("SELECT * FROM os_options WHERE option_name = '%s'", $option_name);
		//print("<br>$sql<br>	");
		$res = $mysqli->query($sql);
		if($res->num_rows == 0) {
			throw new InvalidArgumentException('Incorrect_option_name');
		}
		$row = $res->fetch_assoc();
		if(in_array($row['option_type'], array(1, 2, 3))) {
			$return_value = $row['option_' . $this->option_map[$row['option_type']]];
		}
		return $return_value;
	}
	public function redact_option($option_name = '', $value) {
		$db 		  = Database::getInstance();
		$mysqli 	  = $db->getConnection();
		$return_value = "";

		if($option_name == '') throw new InvalidArgumentException('Incorrect_option_name');
		$sql = sprintf("SELECT * FROM os_options WHERE option_name = '%s'", $option_name);
		//print("<br>$sql<br>	");
		$res = $mysqli->query($sql);
		if($res->num_rows == 0) {
			throw new InvalidArgumentException('Incorrect_option_name');
		}
		$row = $res->fetch_assoc();
		$option_type = $this->option_map[$row['option_type']];
		$update = $db->update('os_options', array("option_" . $option_type => $value), array("id" => $row['id']));
		return $return_value;
	}
}


?>