<?php 


/**
* Archive loading
*/
class Archive extends Attach
{
	protected static $size = 10 * 1024 * 1024;

	protected static $mime = array( 
				0 => 'application/x-rar-compressed',
				1 => 'application/octet-stream', 
				2 => 'application/zip' );
	
	protected static $up_dir = '../upload/zip/';

}

 ?>