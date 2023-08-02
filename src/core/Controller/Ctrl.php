<?php
namespace Ore\Controller;

use Ore\Core;
use Exception;

class Ctrl extends Core
{


	private $_viewPath;
	private $_viewFilePathName;
	protected $_assign;

	public function setViewPath($path)
	{
		$this->_viewPath = $path;
	}

	public function setViewFileName($name)
	{
		$this->_viewFilePathName = $this->_viewPath . $name;
	}

	public function set($name, $data)
	{
		$this->_assign[$name] = $data;
	}

	protected function view($render = true)
	{
		try {
			if ($render)
			{
				if ($this->_assign)
				{
					extract($this->_assign, EXTR_SKIP);
				}
				if (!file_exists($this->_viewFilePathName))
				{
					throw new Exception("Not Foud View  " . $this->_viewFilePathName);
				}
				require($this->_viewFilePathName);
			}
		}
		catch(Exception $e) {
			throw $e;
		}
		return;
	}

}
