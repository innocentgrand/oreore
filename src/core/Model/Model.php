<?php
namespace Ore\Model;

use Ore\Core;
use Ore\Database\Connection;
use Ore\Database\Access;
use Ore\Database\TableBuilder;
use Ore\Migration\DatabaseMigrateInterface;
use Ore\Migration\DatabaseAlterInterface;
use Exception;

class Model extends Core
{
	private static $_db;
	protected $table = "";
	protected $columns;
	protected $migrateList = [];
	protected $id = "id";
	protected $autoTime = true;
	protected $autoCreateDate = "created";
	protected $autoUpdateDate = "updated";

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
		if($this->migrateList)
		{
			foreach($this->migrateList as $migrateClassName)
			{
				$migrateClass = "OApp\\Migration\\" . $migrateClassName;
				$migrate = new $migrateClass(self::$_db->getDb());
				if ($migrate->checkBuilder())
				{
					if ($migrate instanceof DatabaseMigrateInterface)
					{
						$migrate->migrate();
						$migrate->execute();
					}
					if ($migrate instanceof DatabaseAlterInterface)
					{
						$migrate->alter();
						$migrate->executeAlter();
					}
				}
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

		if (!$where)
		{
			if (!empty($saveData[$this->id])) {
				$where = [
					$this->id => $saveData[$this->id],
				];
			}
		}
		$count = self::$_db->getDb()->count($this->table, $where);
		if ($count === 0)
		{
			if (!empty($saveData[$this->id])) {
				unset($saveData[$this->id]);
			}
			if ($this->autoTime)
			{
				$saveData[$this->autoCreateDate] = "NOW()";
				$saveData[$this->autoUpdateDate] = "NOW()";
			}
			$id = self::$_db->getDb()->insert($this->table, $saveData);
		}
		else
		{
			if (!empty($saveData[$this->id])) {
				unset($saveData[$this->id]);
			}
			if ($this->autoTime)
			{
				$saveData[$this->autoUpdateDate] = "NOW()";
			}
			self::$_db->getDb()->update($this->table, $saveData, $where);
			$id = $where[$this->id];
		}
		return $id;
	}

	public function delete($id)
	{
		$where = [
			$this->id => $id,
		];
		self::$_db->getDb()->delete($this->table, $where);
		return true;
	}

}
