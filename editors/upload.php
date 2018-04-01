<?
function getex($filename) {
	return end(explode(".", $filename));
}
if($_FILES['upload']){
	//var_dump($_FILES);
	if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name'])) ){
		$message = "Вы не выбрали файл";
	}
	else if ($_FILES['upload']["size"] == 0 OR $_FILES['upload']["size"] > 8050000){
		$message = "Размер файла не соответствует нормам";
	}
	else if (($_FILES['upload']["type"] != "image/jpg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png")){
		$message = "Допускается загрузка только картинок JPG и PNG.";
	}
	else if (!is_uploaded_file($_FILES['upload']["tmp_name"])){
		$message = "Что-то пошло не так. Попытайтесь загрузить файл ещё раз.";
	}
	else{
		$name = md5(microtime()).'.'.getex($_FILES['upload']['name']);
		//print("<br>$name<br>");
		move_uploaded_file($_FILES['upload']['tmp_name'], "../upload/photos_statics/".$name);
		$full_path = 'http://online-shkola.com.ua/upload/photos_statics/'.$name;
		$message = "Файл ".$_FILES['upload']['name']." загружен";
		$size=@getimagesize('../upload/photos_statics/'.$name);
		if($size[0]<5 || $size[1]<5){
			unlink('../upload/photos_statics/'.$name);
			$message = "Файл не является допустимым изображением";
			$full_path="";
		}
	}
	$callback = $_REQUEST['CKEditorFuncNum'];
	echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$callback.'", "'.$full_path.'", "'.$message.'" );</script>';
}
?>