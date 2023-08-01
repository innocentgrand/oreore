<?php
namespace Ore\Trait;

use Exception;
use Ore\Utils\Static\Functions;

trait Log {

	protected $_logFilePathBase = "";
	protected $_logType = "INFO";

	private function getLogDir()
	{
		$this->_logFilePathBase = dirname(dirname(dirname(__DIR__))) . "/logs/";
		if (!is_dir($this->_logFilePathBase))
		{
			throw new Exception("logs directry not found.");
		}
	}

	public function setLogTypeStr($type)
	{
		$this->_logType = $type;
	}

	public function writeLog($data, $name = "default.log", $format = null)
	{
		try {
			if (!$this->_logFilePathBase)
			{
				$this->getLogDir();
			}
			$path = $this->_logFilePathBase.$name;
			
			if ($format === null)
			{
				$logText = sprintf("[%s] [%s] %s\n", $this->_logType, Functions::date("Y-m-d H:i:s", true), $data);
			}

			file_put_contents($path, $logText, FILE_APPEND);
		}
		catch(Exception $e) {
			error_log($e->getMessage());
			error_log($e->getTraceAsString());
		}
	}

	public function writeErrorLog($data, $name = "error.log")
	{
		try {
			$trace = debug_backtrace();	
			if (!$this->_logFilePathBase)
			{
				$this->getLogDir();
			}
			$path = $this->_logFilePathBase.$name;
			$traceString = Functions::backtrace2string($trace);
			$logText = sprintf("[%s] [%s] %s\n\n[Trace]\n%s\n", "ERROR", Functions::date("Y-m-d H:i:s", true), $data, $traceString);

			file_put_contents($path, $logText, FILE_APPEND);
		}
		catch(Exception $e) {
			error_log($e->getMessage());
			error_log($e->getTraceAsString());
		}

	}

}

