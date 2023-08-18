<?php
namespace Ore\Controller;

use Ore\Core;
use Ore\View\View;
use Exception;

class Ctrl extends Core
{

	protected $useView = true;
	private $_viewPath;
	private $_viewFilePathName;
	protected $_assign;
	protected $viewStatic = false;

	public function getUseView()
	{
		return $this->useView;
	}

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

	protected function viewJson($data = [])
	{
		if ($data) {
			echo json_encode($data);
		}
		else
		{
			echo json_encode($this->_assig);
		}
		exit;
	}

	protected function isViewStatic()
	{
		try {
			$view = new View($this->_assign);
			$view->setPath($this->_viewFilePathName);
			return $view->isStaticFileValid();
		}
		catch(Exception $e) {
			throw $e;
		}
		return false;
	}

	protected function view($render = true)
	{
		try {
			if ($render)
			{
				if (!file_exists($this->_viewFilePathName))
				{
					throw new Exception("Not Foud View  " . $this->_viewFilePathName);
				}
				$view = new View($this->_assign);
				$view->setPath($this->_viewFilePathName);
				if ($this->viewStatic)
				{
					$view->viewStatic();
				}
				else
				{
					$view->view();
				}
			}
		}
		catch(Exception $e) {
			throw $e;
		}
		return;
	}

}
