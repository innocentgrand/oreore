<?php
namespace Ore\Utils;
use Exception;

class ConfigLoader 
{
	private $_configDir = "";
	private $_appConfDir = "";

	public function __construct($base)
	{
		$this->_configDir = $base . "/config/";
		if (!is_dir($this->_configDir)) 
		{
			throw new Exception("log dir NOT FOUNT.");
		}
		if (is_dir($base . "/src/app/config/"))
		{
			$this->_appConfDir = $base . "/src/app/config/";
		}
	}

	public function canRead($name)
	{
		return file_exists($this->_appConfDir.$name) ? true : file_exists($this->_configDir.$name);
	}

	public function load($name)
	{
		$baseArr = [];
		if (file_exists($this->_configDir.$name))
		{
			$jsonStr = file_get_contents($this->_configDir.$name);
			try {
				$baseArr = json_decode($jsonStr, true);
				if (!$baseArr) 
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

		$appArr = [];
		if (file_exists($this->_appConfDir.$name))
		{
			$jsonStr = file_get_contents($this->_appConfDir.$name);
			try {
				$appArr = json_decode($jsonStr, true);
				if (!$appArr) 
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

		return array_replace_recursive($baseArr, $appArr);

	}


}
