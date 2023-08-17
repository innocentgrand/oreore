<?php
namespace Ore;

class Core {

	CONST DEBUG_PRINT_LOG_NAME = "DebugLog.log";
	
	public function __construct()
	{
		
	}

	public function lvt($d)
	{
		$dir = dirname(dirname(__DIR__)) . "/logs/";
		if (is_dir($dir))
		{
			$path = $dir . self::DEBUG_PRINT_LOG_NAME;
			file_put_contents($path, var_export($d, true)."\n\n", FILE_APPEND); 
		}
	}

	public function vt($d)
	{
		echo "<pre>";
		var_dump($d);
		echo "</pre>";
	}
}
