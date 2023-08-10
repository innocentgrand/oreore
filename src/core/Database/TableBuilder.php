<?php
namespace Ore\Database;

use Ore\Core;

class TableBuilder extends Core
{

	private $_createTable = "";
	private $_tableName = "";
	private $_struct;
	private $_primaryStruct;	
	private $_indexStruct;	

	public function __construct($table)
	{
		parent::__construct();
		$this->_tableName = $table;
	}

	public function getCreateString()
	{
		$t = "";
		$tarr = [];
		$p = "";
		$i = "";
		$index = 0;
		foreach($this->_struct as $column => $struct)
		{
			if ($t === "")
			{
				$t .= "CREATE TABLE {$this->_tableName} (\n";
			}
			$tarr[$index] = "";
			$tarr[$index] .= "{$column} ";
			if (!empty($struct["type"]))
			{
				$tarr[$index] .= $struct["type"];
			}
			if (!empty($struct["size"]))
			{
				$tarr[$index] .= "(" . $struct["size"] . ")";
			}
			if ($struct["null"])
			{
				$tarr[$index] .= " NULL";	
			}
			else
			{
				$tarr[$index] .= " NOT NULL";
			}
			if (!empty($struct["default"]))
			{
				$tarr[$index] .= " DEFAULT '{$struct["default"]}'";
			}
			$index++;
		}
		$t .= implode(",\n", $tarr);
		$t .= "\n)";

		if ($this->_primaryStruct)
		{
			$p .= "ALTER TABLE {$this->_tableName} ADD CONSTRAINT {$this->_tableName}_pkey PRIMARY KEY (";
			$p .= implode(",", $this->_primaryStruct); 
			$p .= ")";
		}
		
		if ($this->_indexStruct)
		{
			$i .= "CREATE INDEX {$this->_tableName}_idx ON {$this->_tableName} (";
			$i .= implode(",", $this->_indexStruct); 
			$i .= ")";
		}

		return ["table" => $t, "pkey" => $p, "index" => $i];
	}

	public function getAlterStrings()
	{
		$alters = [];
		$i = 0;
		$columns = [];
		foreach($this->_struct as $column => $struct)
		{
			$alters[$i] = "ALTER TABLE {$this->_tableName}";
			if (!empty($struct["rename"]))
			{
				$alters[$i] .= " rename column {$column} TO {$struct["rename"]}";
			}
			else
			{
				if (!empty($struct["type"]))
				{
					$alters[$i] .= " ADD column {$column} " . $struct["type"];
					$columns[$i] = $column;
				}
				if (!empty($struct["size"]))
				{
					$alters[$i] .= "(" . $struct["size"] . ")";
				}
				if (!empty($struct["default"]))
				{
					$alters[$i] .= " DEFAULT '{$struct["default"]}'";
				}
			}
			$i++;
		}
		return array("alters" => $alters, "columns" => $columns);
	}

	public function getStruct()
	{
		return $this->_struct;
	}
	
	public function getStructPrimary()
	{
		return $this->_primaryStruct;
	}
	
	public function getStructIndex()
	{
		return $this->_indexStruct;
	}

	public function add($column, $type, $size = null, $isNull = false)
	{
		if (empty($this->_struct[$column]))
		{
			if ($size === null)
			{
				$this->_struct[$column] = ["type" => $type];
			}
			else
			{
				$this->_struct[$column] = ["type" => $type, "size" => $size];
			}
			if ($isNull)
			{
				$this->_struct[$column]["null"] = true;
			}
			else
			{
				$this->_struct[$column]["null"] = false;
			}
		}
		return $this;	
	}

	public function alter($column)
	{
		if (empty($this->_struct[$column]))
		{
			$this->_struct[$column] = null;
		}
		return $this;
	}

	public function alterAdd($type, $size = null)
	{
		$key = array_key_last($this->_struct);
		$this->_struct[$key]["type"] = $type;
		if ($size !== null)
		{
			$this->_struct[$key]["size"] = $size;
		}
		return $this;
	}

	public function rename($name)
	{
		$key = array_key_last($this->_struct);
		$this->_struct[$key]["rename"] = $name;
		return $this;
	}

	public function isPrimary()
	{
		$key = array_key_last($this->_struct);
		$this->_primaryStruct[] = $key;
		return $this;
	}
	
	public function isIndex()
	{
		$key = array_key_last($this->_struct);
		$this->_indexStruct[] = $key;
		return $this;
	}
	
	public function default($d)
	{
		$key = array_key_last($this->_struct);
		$this->_struct[$key]['default'] = $d;
		return $this;
	}
}
