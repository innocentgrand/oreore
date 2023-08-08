<?php
namespace Ore\Model;

use Ore\Core;
use Ore\Database\Connection;
use Ore\Database\Access;
use Ore\Database\TableBuilder;
use Exception;

class Model extends Core
{
	private static $_db;
	protected $table = "";
	protected $columns;
	protected $migrate = false;

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
		
		$className = basename(strtr(get_class($this), "\\", "/"));
		if($this->migrate)
		{
			$migrateClass = "OApp\\Migration\\" . $className . "Migrate";
			$migrate = new $migrateClass(self::$_db->getDb());
			if ($migrate->checkBuilder())
			{
				$migrate->migrate();
				$migrate->execute();
			}
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

	public function save($saveData, $where = [])
	{
		
	}

}
