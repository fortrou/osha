<?php
	/**
	 * удаление лишних позиций журнала, исходя из списка
	 * dev by @fortrou
	 *
	 **/
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$year_num = get_currentYearNum();
	//$file = fopen("journal_items.txt", "r");
	$file_array = file("journal_items.txt"); 
	//var_dump($file_array);
	foreach ($file_array as $value) {
		$data = explode(',', $value);
		$sql = sprintf("DELETE FROM os_journal WHERE id_s = %s AND id_l = %s", $data[0], $data[1]);
		$res = $mysqli->query($sql);
		print("<br>$sql<br>");
		usleep(1000);
	}

?>