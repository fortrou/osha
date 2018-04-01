<?php 


/**
* Load file class - img, documents, etc..
*/
class File
{
	
	static public function isValidImg($img)
	{
		$name = $img['name'];
	    $type = $img['type'];
	    $size = $img['size'];
	    
	    $blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5");
	    foreach($blacklist as $item)
	    {
	        if(preg_match("/$item\$/i",$name)) return false;
	    }
	    
	    if($type != "image/gif" && $type != "image/jpg" && $type != "image/png" && $type != "image/jpeg") return false;

	    if($size > 5 * 1024 * 1024) return false;
	    
	    return true;
	}

	static public function LoadImg($img,$login)
	{
		$type = $img['type'];
	  
	    $uploaddir = 'upload/avatars/';

	    $name = md5(microtime()).'.'.substr($type,strlen("image/"));
	    $uploadfile = $uploaddir.$name;
	    //var_dump($uploadfile);
	    // img_resize($img['tmp_name'] , $img['tmp_name'], 300 , 400);
	    
	    if(move_uploaded_file($img['tmp_name'],$uploadfile)) 
	    	return $name;
	    else 
	    	return false;
	}

	static public function LoadUpdImg($img,$login)
	{
		$type = $img['type'];
	  
	    $uploaddir = '../upload/avatars/';

	    $name = md5(microtime()).'.'.substr($type,strlen("image/"));
	    $uploadfile = $uploaddir.$name;
	    //var_dump($uploadfile);
	    // img_resize($img['tmp_name'] , $img['tmp_name'], 300 , 400);
	    
	    if(move_uploaded_file($img['tmp_name'],$uploadfile)) 
	    	return $name;
	    else 
	    	return false;
	}
}

 ?>