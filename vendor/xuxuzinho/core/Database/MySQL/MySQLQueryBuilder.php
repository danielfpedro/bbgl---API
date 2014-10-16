<?php
class MySQLQueryBuilder
{
	
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
		return mysql_query($insert);
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