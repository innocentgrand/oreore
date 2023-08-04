<?php
namespace Ore\Database;

use Ore\Core;
use Ore\Const\Consts;
use Ore\Database\Access;
use PDO;
use Exception;

class PostgreSQL extends Core implements Access 
{
	private static $_db;
	private $_stmt;
	private $table;

	public function __construct($con)
	{
		parent::__construct();
		
		self::$_db = $con;	

	}
	public function begin()
	{
	}
	
	public function commit()
	{
	}

	public function rollback()
	{
	}

	public function inTran()
	{
	}

	public function tableExists($tebleName)
	{
	}

	public function createTable($structure)
	{
	}

	public function select($table, $colum = array(), $where = array(), $order = array(), $limit = array())
	{
		return $this->makeSelectStr($table, $colum, $where, $order, $limit);
	}

	public function makeSelectStr($table, $colum = array(), $where = array(), $order = array(), $limit = array())
	{
		$sql = "SELECT ";
		$columnStr = "";
		foreach($colum as $c)
		{
			if ($columnStr === "")
			{
				$columnStr = "{$c}";
			}
			else
			{
				$columnStr .= ", {$c}";
			}
		}


		$sql .= $columnStr;
		$sql .= " FROM {$table} ";

		if ($where)
		{
			if (is_array($where))
			{
				$wd = $this->makeWhereData($where);
				$sql .= " WHERE " . $wd["str"];
			}
		}
		$this->_stmt = self::$_db->prepare($sql);
		if ($wd)
		{
			foreach($wd["prepare"] as $mark => $value)
			{
				$this->_stmt->bindvalue($mark, $value);
			}
		}
		$this->_stmt->execute();
		$d = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
		$this->_stmt->closeCursor();
		return $d;
	}

	public function makeWhereData($where = array())
	{
		$wstr = "";
		$prepare = [];
		foreach($where as $c => $v)
		{
			if($wstr === "")
			{
				$wstr .= " {$c} = :{$c}_prepare";
				$prepare[":{$c}_prepare"] = $v;
			}
			else
			{
				$wstr .= " AND {$c} = :{$c}_prepare";
				$prepare[":{$c}_prepare"] = $v;
			}
		}
		return ["str" => $wstr, "prepare" => $prepare];
	}
}
