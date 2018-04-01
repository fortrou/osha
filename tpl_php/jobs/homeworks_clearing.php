<?php
	require_once("../autoload_light.php");
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$date_till = '2017-05-30';
	$date_from = '2016-05-01';
	$sql = sprintf("DELETE FROM os_homework_docs 
						  WHERE id_hw 
						  	 IN ( 
						  	    SELECT id 
						  	      FROM os_homeworks 
						  	     WHERE id_hw 
						  	        IN (
						  	           SELECT id 
						  	             FROM os_lesson_homework 
						  	            WHERE id_lesson 
						  	               IN (
						  	               	  SELECT id 
						  	               	  	FROM os_lessons
						  	               	   WHERE (date_ru > '$date_from' 
						  	               	   	  OR date_ua > '$date_from')
						  	               	   	 AND (date_ru < '$date_till'
						  	               	   	  OR date_ua < '$date_till')
						  	               )))");
	print("<br>$sql<br>");
	$res = $mysqli->query($sql);
	$sql = sprintf("DELETE FROM os_homeworks 
						  	WHERE id_hw 
						  	   IN (
						  	      SELECT id 
						  	        FROM os_lesson_homework 
						  	       WHERE id_lesson 
						  	          IN (
						  	         	 SELECT id 
						  	               FROM os_lessons
						  	              WHERE (date_ru > '$date_from' 
						  	               	 OR date_ua > '$date_from')
						  	               	AND (date_ru < '$date_till'
						  	               	 OR date_ua < '$date_till')
						  	             ))");
	print("<br>$sql<br>");
	$res = $mysqli->query($sql);
	print("<br>$iter<br>");
?>