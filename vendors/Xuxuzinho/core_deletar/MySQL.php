<?php

class MySQL {
	private $host;
	private $user;
	private $password;
	private $database;
	
	function __construct($values){
		$this->host = $values['host'];
		$this->user = $values['user'];
		$this->password = $values['password'];
		$this->database = $values['database'];
		
		$this->connect();
	}
	public function select($method, $query) {
		$retorno = array();
		$query = trim($query);
		
		if($method == 'first') {
			$query .= ' LIMIT 1 ';
			$rs = mysql_query($query) or die (mysql_error());
			$values = mysql_fetch_assoc($rs);
		}else {
			$rs = mysql_query($query) or die (mysql_error());
			while ($row = mysql_fetch_assoc($rs)) {
				$values[] = $row;
			};
		}
		return $values;
	}
	public function queryRaw($query) {
		$result = mysql_query($query) or die (mysql_error());
		$result = mysql_fetch_assoc($result);
		return $result;
	}
	public function save($table, $param) {
		$fields = array();
		$values = array();
		foreach($param as $key => $value){
			$fields[] = $key;
			$values[] = (gettype($value) == 'string')? "'".$value."'": $value;
		}
		$insert = 'INSERT INTO ' . $table . ' ('.join($fields, ', ').') VALUES ('.join($values, ', ').')';
		return $this->query($insert);
	}
	public function update($table, $param, $conditions) {
		$fields = array();
		$values = '';
		foreach($param as $key => $value){
			$v = (gettype($value) == 'string')? "'".$value."'": $value;
			$values .= $key . ' = ' . $v . ', ';
		}
		$upd = 'UPDATE ' . $table . ' SET ' . $values . ' WHERE ' . $conditions;
		return $this->query($upd);
	}
}
?>