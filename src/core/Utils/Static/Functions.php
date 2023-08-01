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

	static public function backtrace2string($trace)
	{
		$r = "";
		$i = 0;
		foreach($trace as $d)
		{
			$r .= sprintf("#%d %s(Line:%s) %s\n", $i, $d["file"], $d["line"], $d["function"]);
		}
		return $r;
	}
	
}
