<?php
namespace Ore\Database;

interface Access 
{
	public function begin();
	public function commit();
	public function rollback();
	public function inTran();
	public function tableExists($tableName);
	public function columnExists($tableName, $column);
	public function query($sql);
	public function execute($sql);
	public function count($table, $where = array());
	public function select($table, $colum = array(), $where = array(), $order = array(), $limit = array());
	public function insert($table, $set);
	public function update($table, $set, $where = array());
	public function makeSelectData($table, $colum = array(), $where = array(), $order = array(), $limit = array());
	public function makeWhereData($where = array());	
}
