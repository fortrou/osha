<?php

if ( $_POST )
{
	require 'classDatabase.php';

	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if ( $_POST['start_month'] < 10 ) 
		$start_month = '0' . strval($_POST['start_month']);
	else
		$start_month = strval($_POST['start_month']);

	if ( $_POST['start_day'] < 10 )
		$start_day = '0' . strval($_POST['start_day']);
	else
		$start_day = strval($_POST['start_day']);

	if ( $_POST['end_month'] < 10 ) 
			$end_month = '0' . strval($_POST['end_month']);
		else
			$end_month = strval($_POST['end_month']);

	if ( $_POST['end_day'] < 10 )
			$end_day = '0' . strval($_POST['end_day']);
		else
			$end_day = strval($_POST['end_day']);


	$end_date = strval($_POST['start_year']) . '-' . $end_month . '-' . $end_day; 
	$date = strval($_POST['start_year']) . '-' . $start_month . '-' . $start_day;

	/*$query = "SELECT a.id, b.name, DATE(date) as days, 
			  TIME(date) as hours, title_" . $_POST['language'] . 
			  " as title 
			  FROM os_lessons as a
			  JOIN os_subjects as b
			  ON  a.subject = b.id
			  WHERE '$date' <= DATE(date) ";*/
	$query = sprintf("SELECT a.id, b.name_%s AS name, DATE(date_%s) as days, 
				  TIME(date_%s) as hours, title_%s as title 
				  FROM os_lessons as a
				  JOIN os_subjects as b
				  ON  a.subject = b.id
				  WHERE class = (SELECT id FROM os_class_manager WHERE is_opened='1')",
				  $_POST['language'],$_POST['language'],$_POST['language'],$_POST['language']);

	/*if ( !$_POST['isDiary'] ) 
		$query .= "AND '$end_date' >= DATE(date)";
	else
		$query .= "AND '$end_date' > DATE(date)";*/

	//print("<br>$query<br>");

	$res = $mysqli->query($query);
	//var_dump($res);
	while ( $row = $res->fetch_assoc() )
	{
		$timing[strval($row['days'])][strval($row['hours'])] = array (
			'name' => $row['name'] ,
			'theme' => $row['title'] ,
			'id' => $row['id'] );
	}

	print(json_encode($timing));

}
else
	print(json_encode( array ( 'error' => '1') ) );
?>