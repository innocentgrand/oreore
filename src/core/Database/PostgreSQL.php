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
		try {
			if (!$this->inTran())
			{
				self::$_db->beginTransaction();
			}
		}
		catch(PDOException $e) {
			throw $e;
		}
	}
	
	public function commit()
	{
		try {
			if ($this->inTran())
			{
				self::$_db->commit();
			}
		}
		catch(PDOException $e) {
			throw $e;
		}
	}

	public function rollback()
	{
		try {
			self::$_db->rollBack();
		}
		catch(PDOException $e) {
			throw $e;
		}
	}

	public function inTran()
	{
		try {
			return self::$_db->inTransaction();
		}
		catch(PDOException $e) {
			throw $e;
		}
	}

	public function tableExists($tableName)
	{
		try {
			$sql = "SELECT 1 AS hits FROM information_schema.tables WHERE table_name = '{$tableName}'";
			$this->_stmt = self::$_db->query($sql);
			$d = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->_stmt->closeCursor();
			if (!empty($d[0]['hits'])) {
				return true;
			}
			return false;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function query($sql)
	{
		try {
			$this->_stmt = self::$_db->query($sql);
			$d = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->_stmt->closeCursor();
			return $d;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function execute($sql)
	{
		try {
			$this->_stmt = self::$_db->query($sql);
			$this->_stmt->closeCursor();
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function select($table, $colum = array(), $where = array(), $order = array(), $limit = array())
	{
		return $this->makeSelectData($table, $colum, $where, $order, $limit);
	}

	public function makeSelectData($table, $colum = array(), $where = array(), $order = array(), $limit = array())
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
		if (!empty($wd))
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
