<?php

class Query {
	public $fields = '*';
	public $order;
	public $limit;
	public $conditions = '1 = 1';
	public $mainTable;
	
	public function select($table)
	{
		$this->mainTable = $table;
		$execute = "SELECT {$this->fields} FROM $this->mainTable";
		return $this;
	}
	public function fields($fields)
	{
		$result = array();
		foreach($fields as $key=> $field){
			if (!empty($key)){
				$result[] = "{$key} AS {$field}";
			} else {
				$result[] = "{$field}";
			}
		}

		$this->fields = join($result, ", ");
		return $this;
	}
	public function limit($limit){
		$this->limit = $limit;
		return $this;
	}
	public function order($values)
	{
		foreach($values as $key => $value){
			$value = (!empty($value))? $value: 'ASC';
			$this->order .= $key . ' ' . $value;
		}
		return $this;
	}
	public function where($conditions)
	{
		$and = array();
		$or = array();
		foreach($conditions as $key=> $condition){
			if ($key == 'OR'){
				$this->conditions .= ' OR ';
			} else {
				$this->conditions .= ' AND ';
			}
			if (is_array($condition)) {
				$this->arrangeArray($condition);
			} else {
				$this->conditions .= $this->arrangeCondition($key, $condition);
			}
		}

/*		$result = array();
		foreach($result as $value){
		}*/
		
/*		if (!empty($resultsOr)){
			$this->conditions = join($resultsOr, ' OR ');
		}*/
		return $this;
	}
	private function arrangeArray($values){
		$this->conditions .= ' (';
		$total = count($values);
		$i = 0;
		foreach($values as $k => $value){
			if ($i > 0) {
				$this->conditions .= ' AND ';
			}
			$this->conditions .= $this->arrangeCondition($k, $value);
			if (($total - 1) == $i){
				$this->conditions .= ')';
			}
			$i++;
		}
		unset($value);
	}
	private function arrangeCondition($key, $value){
		$ex = explode(" ", trim($key));
		if (empty($ex[1])) {
			$sinal = "=";
		} else {
			$key = $ex[0];
			$sinal = $ex[1];
		}
		return "{$key} {$sinal} {$value}";	
	}
	public function join($table, $condition)
	{
//		$this->join[] = array('table'=> $table, 'condition'=>)
		
		return $this;
	}
	public function execute()
	{
		$result = '';
		$result .= "SELECT {$this->fields} ";
		$result .= "FROM {$this->mainTable} ";
		if (!empty($this->conditions)) {
			$result .= "WHERE {$this->conditions} ";
		}
		if (!empty($this->limit)) {
			$result .= "LIMIT {$this->limit} ";
		}
		return $result;
	}
	
}

$q = new Query();
echo $q
	->select('articles')
	->fields(array('title'=> 'titulo', 'name'))
	->where(array(
		'titulo'=> 'teste',
		array('titulo'=> 'meste', 'text'=> 'bumb'),
		'text LIKE'=> '%ontem%',
		'OR'=> array(
			'titulo'=> 'jones',
			'text'=> 'Manos'
		),
		'OR'=> array(
			'titulo'=> 'tey',
			'text'=> 'toc',
			'OR'=> array(
				'titulo'=> 'dentro OR',
				'text'=> 'dentro OR',
			)
		),
	))
	->order(array('dtCadastro'=> 'DESC', 'TEY'))
	->limit('1, 10')
	->execute();
?>