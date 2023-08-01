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
			$this->writeErrorLog($e->getMessage());
		}
		
	}

	public function setPath($p)
	{
		$r = [];
		$arg = "";
		foreach(explode("/", $p) as $pd)
		{
			if ($pd)
			{
				if (empty($r["Ctrl"]))
				{
					$r["Ctrl"] = ucfirst($pd);
				}
				else if (empty($r["Method"]))
				{
					$r["Method"] = ucfirst($pd);
				}
				else if (empty($r["Args"]))
				{
					$arg = $pd;
					$r["Args"][$arg] = null;
				}
				else if (!empty($r["Args"]))
				{
					if ($arg == "")
					{
						$arg = $pd;
						$r["Args"][$arg] = null;
					}
					else 
					{
						$r["Args"][$arg] = $pd;
						$arg = "";
					}
				}
			}
		}
		return $r;
	}
}
