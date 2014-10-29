<?php

App::vendor('MySQLQueryBuilder', 'MySQLQueryBuilder');
App::lib('Database', 'Database');

class Model extends MySQLQueryBuilder
{

	public $alias;

	public $dataToBind = [];
	public $pdo;
	public $error;

	public $data;

	public $connectionValues = 'default';

	function __construct ($default = 'default')
	{
		if (empty($this->primaryKey)) {
			$this->primaryKey = 'id';
		}
		$name = get_called_class();
		$this->setAlias($name);
	}

	public function setConnection($connectionValues)
	{
		$this->connectionValues = $connectionValues;
	}

	public function create($data)
	{
		foreach ($data as $key => $value) {
			$this->data[$key] = $value;
		}
	}

	public function setAlias($name)
	{
		$this->alias = $name;
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
	public function bindData($data){
		$this->dataToBind = $data;
		return $this;
	}
}

?>