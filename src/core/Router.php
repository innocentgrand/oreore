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
		if ($this->_routerConfig)
		{
			foreach($this->_routerConfig as $path => $routeData)
			{
				if (preg_match("#^{$path}#", $p))
				{
					$rebasePath = preg_replace("#^{$path}#", "", $p);
					return $this->configPath($rebasePath, $routeData);
				}
			}
		}

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

	
	protected function configPath($p, $rd)
	{
		$r = [];
		if ($rd["AppDir"])
		{
			$r["AppDir"] = $rd["AppDir"];
		}
		foreach(array_reverse($rd["path"]) as $basePath => $d)
		{
			if (preg_match("#^{$basePath}#", $p))
			{
				$rebase = preg_replace("#^{$basePath}#", "", $p);
				$arg = "";
				if ($d["Ctrl"])
				{
					$r["Ctrl"] = $d["Ctrl"];
				}
				foreach(explode("/", $rebase) as $pd)
				{
					if (empty($r["Method"]))
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
				break;
			}
		}
		return $r;
	}

}
