<?php
require ('../autoload.php');
if ( $_POST )
{
	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if($_POST['month'] < 10)
		$month = '0'.$_POST['month'];
	else
		$month = $_POST['month'];
	$start_day = '01';
	$end_day = $_POST['end_day'];
	$end_day = '30';
	//$end_day = '36';
	$year = $_POST['year'];
	$lang = $_POST['language'];
	$beg_date = "$year-$month-$start_day";
	$end_date = "$year-$month-$end_day";
	$class = $_POST['class_n'];

	$sql = sprintf("SELECT a.id, b.name_%s AS name, DATE(a.date_%s) as days, 
			  TIME(a.date_%s) as hours, title_%s FROM os_lessons as a
			  JOIN os_subjects as b
			  ON  a.subject = b.id WHERE a.id IN(SELECT id_lesson FROM os_lesson_classes WHERE id_class=(SELECT id FROM os_class_manager WHERE is_opened='1'))",$lang,$lang,$lang,$lang);
	//print($sql);
	$res = $mysqli->query($sql);
	if($res->num_rows == 0) exit();
	$result = array();
	$iter = 0;
	while ($row = $res->fetch_assoc()){
		$result[$row['id']] = array (
			"name" => $row['name'],
			"days" => $row['days'],
			"hours" => $row['hours'],
			"title" => $row['title_'.$lang]
		);
		$iter++;
	}
	//$result = var_dump($result);
	print(json_encode($result));

}

?>