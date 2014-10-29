<?php
App::lib('Database\MySQL', 'Database');

class QueryBuilder {

	public $dataToBind;

	public $query;

	public $error;

	public $lastId;

	private $on;
	private $join = [];
	private $groupBy;
	private $orderBy = [];
	private $conditions;
	private $offset;
	private $limit;
	private $having;
	
	private $type;

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
			$this->$type .= $this->arrangeCondition($key, $condition, $type);
		}
	}

	public function having($conditions)
	{
		$this->conditions($conditions, 'having');
		return $this;
	}

	private function arrangeCondition($key, $value, $type){
		$ex = explode(" ", trim($key));
		if (empty($ex[1])) {
			$sinal = "=";
		} else {
			$key = $ex[0];
			$sinal = $ex[1];
		}

		return "{$key} {$sinal} {$value}";	
	}

	public function join($values = [])
	{
		$type = (!empty($values['type']))? $values['type'] : 'INNER';
		$type .= ' JOIN';
		
		$table = $values['table'];
		if (!empty($values['alias'])) {
			$table .= ' AS ' . $values['alias'];
		}

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

	public static function string($value)
	{
		return "'{$value}'";
	}

	public function save()
	{

		if (!empty($this->data)) {
			if (array_key_exists($this->primaryKey, $this->data)){
				$query = $this->update($this->data);
			} else {
				$query = $this->add($this->data);

			}

			try {
				$pdo = Database::connect($this->connectionValues);
				$stmt = $pdo->prepare($query);
				$stmt->execute($this->data);

				$this->rowsAffected = $stmt->rowCount();

				return true;
			} catch (PDOException $e) {
				$this->error = $e->getMessage();
				return false;
			}
		} else {
			$this->error = 'Nenhum dado foi passado para salvar';
			return false;
		}
	}

	private function add()
	{
		$fields = [];
		$values = [];
		foreach ($this->data as $key => $value) {
			$fields[] = $key;
			$values[] = ":{$key}";
		}
		$fields = join($fields, ', ');
		$values = join($values, ', ');
		$query = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
		return $query;
	}
	private function update()
	{
		$fields = [];
		$values = [];
		foreach ($this->data as $key => $value) {
			$values[] = "{$key} = :{$key}";
		}

		$values = join($values, ', ');
		$query = "UPDATE {$this->table} SET {$values} WHERE {$this->primaryKey} = :{$this->primaryKey}";

		return $query;
		
	}
	public function delete($id)
	{
		$query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :{$this->primaryKey}";

		try {
			$pdo = Database::connect($this->connectionValues);
			$stmt = $pdo->prepare($query);
			$stmt->execute([$this->primaryKey => $id]);
			$this->rowsAffected = $stmt->rowCount();
			return true;
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return false;
		}
	}

	public function bindData($data){
		$this->dataToBind = $data;
		return $this;
	}

	public function first()
	{
		try {
			$pdo = Database::connect($this->connectionValues);

			$stmt = $pdo->prepare($this->query);
			$stmt->execute($this->dataToBind);

			$fetch = $stmt->fetch(PDO::FETCH_OBJ);
			return ($stmt->rowCount() > 0 ? $fetch : null);
		} catch (PDOException $e) {
			throw $e;
		}
	}

	public function all()
	{
		$pdo = Database::connect($this->connectionValues);

		try {
			$stmt = $pdo->prepare($this->query);
			$stmt->execute($this->dataToBind);

			return $stmt->fetchAll(PDO::FETCH_OBJ);	
		} catch (PDOException $e) {
			throw $e;
		}	
	}

}
?>