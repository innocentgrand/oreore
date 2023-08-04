<?php
namespace Ore\Database;

interface Access 
{
	public function begin();
	public function commit();
	public function rollback();
	public function inTran();
	public function tableExists($tebleName);
	public function createTable($structure);
	public function select($table, $colum = array(), $where = array(), $order = array(), $limit = array());
	public function makeSelectStr($table, $colum = array(), $where = array(), $order = array(), $limit = array());
	public function makeWhereData($where = array());	
}
