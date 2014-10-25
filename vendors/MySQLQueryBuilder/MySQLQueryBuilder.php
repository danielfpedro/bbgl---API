<?php

class MySQLQueryBuilder {

	public $query;
	public $table;
	public $alias;

	public $error;

	public $lastId;

	private $on;
	private $fields = '*';
	private $join = [];
	private $groupBy;
	private $orderBy = [];
	private $conditions;
	private $offset;
	private $limit;
	private $having;
	
	private $type;


	public function save($data)
	{
		if (array_key_exists('id', $data)){
			$query = $this->update($data);
		} else {
			$query = $this->add($data);

		}

		if (mysql_query($query)){
			return true;
		} else {
			$this->error = mysql_error();
			return false;
		}	
	}

	private function update($data)
	{
		$id = $data['id'];
		unset($data['id']);

		$values = [];
		foreach ($data as $key => $value) {
			$value = (gettype($value) === 'string'? "'{$value}'" : $value);

			$values[] = "{$key} = {$value}";
		}

		$values = join($values, ', ');

		$query = "UPDATE {$this->table} SET {$values} WHERE id = {$id}";

		return $query;
	}
	private function add($data)
	{
		$fields = [];
		$values = [];
		foreach ($data as $key => $value) {
			$fields[] = $key;
			$values[] = (gettype($value) === 'string'? "'{$value}'" : $value);
		}

		$fields = join($fields, ', ');
		$values = join($values, ', ');

		$query = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";

		$this->lastId = mysql_insert_id();

		return $query;
	}

	public function setTable($table, $alias = null)
	{
		$this->table = $table;
		$this->alias = $alias;
	}
	public function select($fields)
	{
		$result = [];
		foreach($fields as $key => $field){
			if (!is_numeric($key)){
				$result[] = "{$key} AS {$field}";
			} else {
				$result[] = "{$field}";
			}
		}

		$this->fields = join($result, ", ");

		$this->query .= "SELECT {$this->fields} FROM {$this->table} ";
		$this->query .= (!empty($this->alias) ? "{$this->alias} ": '');

		return $this;
	}
	public function orderBy($values)
	{
		foreach($values as $key => $value){
			if (is_numeric($key)){
				$this->orderBy[] = "{$value} ASC";	
			} else {
				$this->orderBy[] = "{$key} {$value}";	
			}
		}

		if (!empty($this->orderBy)) {
			$this->query .= "ORDER BY " . join($this->orderBy, ', ') . ' ';
		}
		return $this;
	}

	public function offset($offset){
		$this->offset = $offset;

		if (!empty($this->offset)) {
			$this->query .= "LIMIT {$this->offset}, ";
		}
		return $this;
	}

	public function limit($limit){
		$this->limit = $limit;

		if (empty($this->offset)) {
			$this->query .= 'LIMIT ';
		}
		$this->query .= "{$this->limit} ";
		return $this;
	}

	public function where($conditions)
	{
		$this->conditions($conditions, 'conditions');

		$this->query .= "WHERE {$this->conditions} ";

		return $this;
	}
	public function conditions($conditions, $type = 'conditions')
	{
		//Importante por que no ON do JOIN Podem ser mais de um entao toda vez que começa é necessario zerar;
		$this->$type = '';
		$i = 0 ;
		foreach($conditions as $key => $value){
			$flag = ($i == 0)? true: false;
			$this->tey($key, $value, $flag,  $type);
			$i++;
		}
		return $this;
	}
	private function tey($key = null, $condition, $start = true, $type)
	{
		if ($key === 'OR'){
			$this->$type .= ' OR ';
		} else {
			if (!$start){
				$this->$type .= ' AND ';
			}
		}
		if ($key === 'OR' && is_array($condition)){
			$i = 0;
			foreach($condition as $k=> $v){
				$flag = ($i == 0)? true: false;
				$this->tey($k, $v, $flag, $type);
				$i++;
			}
		}elseif (is_array($condition)){
			$this->$type .= ' (';
			$i = 0;
			foreach($condition as $k=> $v){
				$flag = ($i == 0)? true: false;
				$this->tey($k, $v, $flag, $type);
				$i++;
			}
			$this->$type .= ' )';
		}else {
			$this->$type .= $this->arrangeCondition($key, $condition);
		}
	}
	public function having($conditions)
	{
		$this->conditions($conditions, 'having');
		return $this;
	}
	private function arrangeCondition($key, $value){
		$ex = explode(" ", trim($key));
		if (empty($ex[1])) {
			$sinal = "=";
		} else {
			$key = $ex[0];
			$sinal = $ex[1];
		}

		$value = (gettype($value) === 'string'? "'{$value}'": $value);

		return "{$key} {$sinal} {$value}";	
	}
	public function join($values = [])
	{
		$type = (!empty($values['type']))? $values['type'] : 'INNER';
		$type .= ' JOIN';
		
		$table = $values['table'];
		$table = (!is_array($values['table']))? $values['table'] : key($table) . ' ' . $table[key($table)];
		
		if(!empty($values['conditions'])){
			$this->conditions($values['conditions'], 'on');
			$on = ' ON (' . $this->on . ') ';
		} else {
			$on = '';
		}
		
		$this->join[] = $type . ' '.$table . $on;

		foreach($this->join as $value){
			$this->query .= $value;
		}

		return $this;
	}
	
	public function groupBy($values)
	{
		$this->groupBy = join($values, ', ');

		$this->query .= "GROUP BY {$this->groupBy} ";

		return $this;
	}

	public function first()
	{
		$query = mysql_query($this->query);
		if ($query === false){
			$this->error = mysql_error();
			return false;
		};

		$result = mysql_fetch_assoc($query);

		if (empty($result)) {
			return [];
		} else {
			return $result;
		}
	}

	public function all()
	{
		$query = mysql_query($this->query);
		if($query === false) {
			$this->error = mysql_error();
			return false;
		}
		$values = [];
		while ($row = mysql_fetch_assoc($query)) {
			$values[] = $row;
		}
		return $values;
	}
	
}
?>