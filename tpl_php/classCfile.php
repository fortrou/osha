<?php
	class Cfile
	{
		//static protected $size = 5 * 1024 * 1024;

		/*static protected $mime = array( 
			0 => 'application/vnd.oasis.opendocument.text',
			1 => 'application/msword', 
			2 => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ,
			3 => 'application/x-rar-compressed',
			4 => 'application/octet-stream', 
			5 => 'application/zip'
		);*/

		//static protected $up_dir = '../upload/hworks/';

		static public function isSecure( $file )
		{
			$size = 5 * 1024 * 1024;
			$mime = array( 
				0 => 'application/vnd.oasis.opendocument.text',
				1 => 'application/msword', 
				2 => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ,
				3 => 'application/x-rar-compressed',
				4 => 'application/octet-stream', 
				5 => 'application/zip',
				6 => 'application/pdf',
				7 => 'application/x-bittorrent',
				9 => 'application/vnd.ms-powerpoint',
				10 => 'application/vnd.oasis.opendocument.spreadsheet',
				11 => 'application/vnd.ms-excel',
				12 => 'image/jpg',
				13 => 'image/png',
				14 => 'image/jpeg',
				15 => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				16 => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				17 => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				18 => 'text/plain',
				19 => 'application/rtf',
				20 => 'image/gif',
				21 => 'image/bmp',
				22 => 'audio/mpeg3',
				23 => 'audio/x-mpeg-3',
				24 => 'video/mpeg',
				25 => 'video/x-mpeg',
				26 => 'audio/x-ms-wma'
			);
			if ( $file['size'] > $size ) return false;
			if ( !in_array($file['type'], $mime ) ) return false;

			return true;
		}

		static public function Load( $file )
		{
			$up_dir = '../upload/hworks/';
			$type = $file['type'];

		    $name = md5(microtime()).strrchr($file['name'] , '.');
		    $uploadfile = $up_dir.$name;

		    return move_uploaded_file($file['tmp_name'],$uploadfile) ? $name : false;
		}
		static public function Load_hw( $file )
		{
			$up_dir = '../../upload/hworks/';
			$type = $file['type'];

		    $name = md5(microtime()).strrchr($file['name'] , '.');
		    $uploadfile = $up_dir.$name;

		    return move_uploaded_file($file['tmp_name'],$uploadfile) ? $name : false;
		}

	}


?>