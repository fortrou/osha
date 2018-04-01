<?php 

/**
* Parent class for loading attachments
*/
class Attach
{
	static protected $size;

	static protected $mime = array();
	
	static protected $up_dir;

	static public function isSecure( $file )
	{
		if ( $file['size'] > static::$size ) return false;
		if ( !in_array($file['type'], static::$mime ) ) return false;

		return true;
	}

	static public function Load( $file )
	{
		$type = $file['type'];

	    $name = md5(microtime()).strrchr($file['name'] , '.');
	    $uploadfile = static::$up_dir.$name;

	    return move_uploaded_file($file['tmp_name'],$uploadfile) ? $name : false;
	}
}



 ?>