<?php
namespace Ore\Utils;
use Exception;

class ConfigLoader 
{
	private $_configDir = "";

	public function __construct($base)
	{
		$this->_configDir = $base . "/config/";
		if (!is_dir($this->_configDir)) 
		{
			throw new Exception("log dir NOT FOUNT.");
		}
	}

	public function load($name)
	{
		if (file_exists($this->_configDir.$name))
		{
			$jsonStr = file_get_contents($this->_configDir.$name);
			try {
				$arr = json_decode($jsonStr, true);
				if ($arr) 
				{
					return $arr;
				}
				else 
				{
					throw new Exception("JSON ERROR!");
				}
			}
			catch(Exception $e) {
				throw $e;
			}
			catch(ValueError $v) {
				throw $v;
			}
		}
		throw new Exception("ConfigFileNotFoud.");
	}


}
