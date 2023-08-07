<?php
namespace Ore\Model;

use Ore\Core;
use Ore\Database\Connection;
use Ore\Database\Access;
use Exception;

class Model extends Core
{
	private static $_db;
	protected $table = "";
	protected $columns;

	public function __construct($config)
	{
		parent::__construct();
		if (!self::$_db)
		{
			self::$_db = new Connection();
			if (!empty($config["Connection"]["Host"]) && !empty($config["Connection"]["Port"]))
			{
				if ($config["Connection"]["Host"] != "" && $config["Connection"]["Port"] != "")
				{
					self::$_db->setOption($config["Connection"]["Host"], $config["Connection"]["Port"]);
				}
			}
			self::$_db->create($config["DB"], $config["Connection"]["DB"], $config["Connection"]["User"], $config["Connection"]["Pass"]);
		}
	}

	public function getData($where, $column = [])
	{
		$columnData = [];
		if ($this->columns && !$column) 
		{
			$columnData = $this->columns;
		}
		else if ($column)
		{
			$columnData = $column;
		}
		$d = self::$_db->getDb()->select($this->table, $columnData, $where); 
		return is_array($d) && count($d) > 0 ?  $d[0] : null;
	}
	

	public function getDatas($where = [], $column = [])
	{
		$columnData = [];
		if ($this->columns && !$column) 
		{
			$columnData = $this->columns;
		}
		else if ($column)
		{
			$columnData = $column;
		}
		$d = self::$_db->getDb()->select($this->table, $columnData, $where); 
		return $d;
	}

}
