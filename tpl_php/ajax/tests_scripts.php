<?php
	require_once('../autoload.php');
	session_start();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if ($_POST["flag"] == '1') {
		$sql = sprintf("UPDATE os_journal SET is_first=0 WHERE id='%s'",$_POST["j_id"]);
		$res = $mysqli->query($sql);
	}
	if ($_POST["flag"] == '2') {
		echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
		$_SESSION['testGet'] = $_POST['test_id'];
		unset($_SESSION['data_collection']);
		unset($_SESSION['string_answs']);
		echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
	}

?>