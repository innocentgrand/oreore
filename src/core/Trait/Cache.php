<?php
namespace Ore\Trait;

use Exception;

trait Cache
{
	protected $_cacheFilePathBase = "";

	private function getCacheDir()
	{
		$this->_cacheFilePathBase = dirname(dirname(dirname(__DIR__))) . "/cache/";
		if (!is_dir($this->_cacheFilePathBase))
		{
			throw new Exception("cache directry not found.");
		}
	}

	public function checkAndCreateCacheDirectory($plusPath)
	{
		try {
			if (!$this->_cacheFilePathBase)
			{
				$this->getCacheDir();
			}

			if (!is_dir($this->_cacheFilePathBase.$plusPath))
			{
				mkdir($this->_cacheFilePathBase.$plusPath, 0777, true);
			}
			$this->_cacheFilePathBase = $this->_cacheFilePathBase.$plusPath;

		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	public function writeCacheFile($data, $name = "")
	{
		try {
			if (!$this->_cacheFilePathBase)
			{
				$this->getCacheDir();
			}
			$path = $this->_cacheFilePathBase.$name;
			
			file_put_contents($path, $data);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function getCacheFile($name)
	{
		try {
			if (!$this->_cacheFilePathBase)
			{
				$this->getCacheDir();
			}
			$path = $this->_cacheFilePathBase.$name;
			
			return file_get_contents($path);
		}
		catch(Exception $e) {
			throw $e;
		}
		
	}

	public function getCacheFileTime($name)
	{
		try {
			if (!$this->_cacheFilePathBase)
			{
				$this->getCacheDir();
			}
			$path = $this->_cacheFilePathBase.$name;
			return filemtime($path);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function getCacheFilePath($name)
	{
		try {
			if (!$this->_cacheFilePathBase)
			{
				$this->getCacheDir();
			}
			$path = $this->_cacheFilePathBase.$name;
			return $path;	
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function deleteCacheFile($name)
	{
		try {
			if (!$this->_cacheFilePathBase)
			{
				$this->getCacheDir();
			}
			$path = $this->_cacheFilePathBase.$name;
			unlink($path);	
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	public function checkCacheValid($name, $time = 0)
	{
		try {
			if (!$this->_cacheFilePathBase)
			{
				$this->getCacheDir();
			}
			$path = $this->_cacheFilePathBase.$name;

			if (file_exists($path))
			{
				if ($time === 0)
				{
					return true;
				}
				$ftime = filemtime($path);
				$ftime += $time;
				return time() < $ftime;
			}
			return false;
			
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
