<?php
require_once('autoload.php');

if ( $_POST['id'] )
{
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$query = "SELECT id, name, surname, patronymic 
			  FROM os_users
			  WHERE class = '";

	$query .= $_POST['id'] . "' AND level = 1";
	//print("<br>$query<br>");

	$result = $mysqli->query($query);

	$pupils = array();

	while ( $row = $result->fetch_assoc() ) 
	{
		$pupils[$row['id']] = array ( 
			'second' => $row['surname'] ,
			'third' => $row['name']
			);
	}

	print(json_encode($pupils));
}



 ?>