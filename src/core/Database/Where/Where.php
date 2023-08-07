<?php
namespace Ore\Database\Where;

class Where
{
	private $_string = "";
	private $_prepareArray = [];

	public function lastWhere()
	{
		return $this->_string;
	}

	public function getPrepare()
	{
		return $this->_prepareArray;
	}
	
	public function and()
	{
		$this->_string .= " AND ";
		return $this;
	}

	public function or()
	{
		$this->_string .=  " OR ";
		return $this;
	}

	public function leftParent()
	{
		$this->_string .= "(";
		return $this;
	}

	public function rightParent()
	{
		$this->_string .= ")";
		return $this;
	}

	public function terms($colum, $value, $mark = "=")
	{
		$sql = " {$colum} {$mark}  :{$colum}_prepare";
		$this->_prepareArray[":{$colum}_prepare"] = $value;
		$this->_string .= $sql;
		return $this;
	}

	public function like($colum, $val, $bottom = true, $top = false)
	{
		$like = " {$colum} LIKE ";
		$like .= ":{$colum}_prepare";
		if ($bottom)
		{
			$this->_prepareArray[":{$colum}_prepare"] = $val . '%';
		}
		if ($top)
		{
			$this->_prepareArray[":{$colum}_prepare"] = '%' . $this->_prepareArray[":{$colum}_prepare"];
		}
		$this->_string .= $like;
		return $this;
	}


}
