<?php
namespace Ore;

use Ore\Core;
use Ore\Utils\ConfigLoader;
use Ore\Trait\Log;

class Router extends Core
{
	use Log;

	private $_routerConfig = [];

	public function setRouterConfig($name)
	{
		try {
			$config = new ConfigLoader(dirname(dirname(__DIR__)));
			$this->_routerConfig = $config->load($name);
		}
		catch(Exception $e)
		{
			
		}
		
	}

	public function setPath($p)
	{
		$this->writeLog("a");
		$this->vt($p);
	}
}
