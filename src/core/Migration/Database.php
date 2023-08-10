<?php
namespace Ore\Migration;

use Ore\Core;
use Ore\Database\TableBuilder;
use Ore\Trait\Cache;

class Database extends Core 
{
	use Cache;

	CONST CACHE_FILE = "migrate_database.dat";
	private static $_db;
	protected $table = "";
	protected $_createTabelStrings = [];
	protected $_builder;
	private $_className;

	public function __construct($db)
	{
		parent::__construct();
		if (!self::$_db)
		{
			self::$_db = $db;
		}
		$this->_className = basename(strtr(get_class($this), "\\", "/"));
		if ($this->checkCacheValid(self::CACHE_FILE))
		{
			$data = $this->getCacheFile(self::CACHE_FILE);
			$json = json_decode($data, true);
			if (!empty($json[$this->_className]))
			{
				return;
			}
		}
		$this->_builder = new TableBuilder($this->table);
	}

	public function checkBuilder()
	{
		return $this->_builder instanceof TableBuilder;
	}
	
	public function execute()
	{
		if (!$this->_builder)
		{
			return;
		}

		try {
			if (!self::$_db->tableExists($this->table))
			{
				$s = $this->_builder->getCreateString();
				self::$_db->begin();	
				if ($table = $s["table"])
				{
					self::$_db->execute($table);
				}
				if ($primary = $s["pkey"])
				{
					self::$_db->execute($primary);
				}
				if ($index = $s["index"])
				{
					self::$_db->execute($index);
				}
				self::$_db->commit();
			}
			$this->createTableCache();
		}
		catch(Exception $e)
		{
			self::$_db->rollback();
			throw $e;
		}
	}

	public function executeAlter()
	{
		if (!$this->_builder)
		{
			return;
		}

		try {
			if (self::$_db->tableExists($this->table))
			{
				$s = $this->_builder->getAlterStrings();
				self::$_db->begin();
				foreach($s["alters"] as $i => $alter)
				{
					if (!empty($s["columns"][$i]))
					{
						if (!self::$_db->columnExists($this->table, $s["columns"][$i]))
						{
							self::$_db->execute($alter);
						}
					}
					else
					{
						self::$_db->execute($alter);
					}
				}	
				self::$_db->commit();
			}
			$this->alterTableCache();
		}
		catch(Exception $e)
		{
			self::$_db->rollback();
			throw $e;
		}
		
	}

	private function createTableCache()
	{
		if ($this->checkCacheValid(self::CACHE_FILE))
		{
			$data = $this->getCacheFile(self::CACHE_FILE);
			$json = json_decode($data, true);
			$json[$this->_className][] = $this->_builder->getCreateString();
		}
		else
		{
			$json[$this->_className][] = $this->_builder->getCreateString();
		}
		$this->writeCacheFile(json_encode($json), self::CACHE_FILE);
	}

	private function alterTableCache()
	{
		if ($this->checkCacheValid(self::CACHE_FILE))
		{
			$data = $this->getCacheFile(self::CACHE_FILE);
			$json = json_decode($data, true);
			$json[$this->_className][] = $this->_builder->getAlterStrings();
		}
		else
		{
			$json[$this->_className][] = $this->_builder->getAlterStrings();
		}
		$this->writeCacheFile(json_encode($json), self::CACHE_FILE);
	}

}
