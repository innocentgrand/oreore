<?php
namespace Ore\Utils\Static;

class Functions
{
	static public function date($format, $microtime = false)
	{
		$msStr = "";
		if ($microtime)
		{
			$msStr = substr(explode(".", microtime(true))[1], 0, 3);
		}
		return date($format) . $msStr;
	}
}
