<?php

session_start();
require '../tpl_php/autoload.php';

/*print('<br>');
var_dump($_POST);
print('<br>');
*/

try {
	$user = User::auth( $_POST['name'] , $_POST['ocenka'] );
	//print_r($_SESSION);
	if($user == false){
		header('Location:../index.php');
	}
	else{
		header('Location:../schedule/calendar.php');
	}
	//$user ? print('Success auth!') : $_SESSION['message'] = "Ошибка авторизации";
	

} catch (Exception $e) {
	print($e->getMessage());
}

?>