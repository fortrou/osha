<?php

	require_once("../autoload_light.php");
	require_once("../functions.php");
	$sql = "DELETE FROM os_homework_docs WHERE file_name=''";
	$res = $mysqli->query($sql);
	usleep(20000);
	//log here

?>