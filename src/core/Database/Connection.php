<?php
namespace Ore\Database;

use Ore\Core;
use Ore\Const\Consts;
use PDO;
use Exception;
use Ore\Database\PostgreSQL;

class Connection extends Core
{
	private static $_db;
	private static $_type;

	protected $_host;
	protected $_port;

	public function setOption($host, $port) {
		$this->_host = $host;
		$this->_port = $port;
	}

	public function create($dbType, $db, $user, $pass)
	{
		try {
			if (self::$_db)
			{
				return;
			}
			switch($dbType) {
				case "PostgreSQL":
					if ($this->_port == '' || $this->_port == 'default' || $this->_port == null)
					{
						$port = 5432;
					}
					if ($this->_host == null)
					{
						$host = "localhost";
					}
					$dsn = "pgsql:dbname={$db} host={$host} port={$port}";
					$db = new PDO($dsn, $user, $pass);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
					self::$_db = new PostgreSQL($db);
					self::$_type = Consts::DB_TYPE_PGSQL;
					break;
				case "MySQL":
				case "MariaDB":
				case "MSSQL":
					throw new Exception("I'm sorry. Not yet supported. [" . $dbType .  "]");
					break;
				default:
					throw new Exception("Not yet supported by that DB.");
					break;
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function getDb()
	{
		return self::$_db;
	}
	
}
