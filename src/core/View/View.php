<?php
namespace Ore\View;

use Ore\Core;
use Ore\Trait\Cache;
use Ore\View\Compiler;
use Exception;

class View extends Core
{
	use Cache;

	protected $_assign;
	protected $_path;

	public function __construct($assign = [])
	{
		parent::__construct();
		$this->_assign = $assign;
	}

	public function setPath($path)
	{
		$this->_path = $path;
	}

	public function view()
	{
		
		$ftimeBase = filemtime($this->_path);
		$baseDir = dirname(dirname(__DIR__)) . "/app/";
		$cachePlusDir = str_replace($baseDir, "", $this->_path);
		$baseFileName = basename($cachePlusDir);
		$cachePlusDir = str_replace($baseFileName, "", $cachePlusDir);
		$cacheFileName = str_replace(".tpl", ".dat", $baseFileName);
		
		$this->checkAndCreateCacheDirectory($cachePlusDir);

		if ($this->checkCacheValid($cacheFileName))
		{
			$cacheTime = $this->getCacheFileTime($cacheFileName);
			if ($cacheTime < $ftimeBase)
			{
				$d = $this->compile();
				$this->writeCacheFile($d, $cacheFileName);
			}
			if ($this->_assign)
			{
				extract($this->_assign, EXTR_SKIP);
			}
		}
		else
		{
			$d = $this->compile();
			$this->writeCacheFile($d, $cacheFileName);
			if ($this->_assign)
			{
				extract($this->_assign, EXTR_SKIP);
			}
		}
		require($this->getCacheFilePath($cacheFileName));
	}

	public function compile()
	{
		$data = file_get_contents($this->_path);
		$c = new Compiler($data);
		return $c->compile();
	}

}
