<?php 


/**
* Loading,checking word documents
*/
class Doc extends Attach
{
	static protected $size = 5 * 1024 * 1024;

	static protected $mime = array( 
				0 => 'application/vnd.oasis.opendocument.text',
				1 => 'application/msword', 
				2 => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' );
	
	static protected $up_dir = '../upload/docs/';
}

 ?>