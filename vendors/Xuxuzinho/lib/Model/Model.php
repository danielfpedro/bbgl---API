<?php

App::vendor('MySQLQueryBuilder', 'MySQLQueryBuilder');
App::lib('Database', 'Database');

class Model extends MySQLQueryBuilder
{

	public $alias;

	public $primaryKey = 'id';

	public $pdo;
	public $stmt;
	public $error;

	function __construct ($default = 'default')
	{
		$name = get_called_class();
		$this->setAlias($name);

		$this->pdo = Database::connect($default);
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

		if (array_key_exists('id', $this->data)){
			$query = $this->update($this->data);
		} else {
			$query = $this->add($this->data);

		}

		$this->query($query);
		$this->execute($this->data);
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
		$query = "UPDATE {$this->table} SET {$values} WHERE id = :id";

		return $query;
		
	}
	public function delete($id)
	{
		$query = "DELETE FROM {$this->table} WHERE id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->execute(['id'=> $id]);
	}

	public function all()
	{
		$this->stmt = $this->pdo->prepare($this->query);
		$stmt->execute($this->dataToBind);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	public function first()
	{
		$this->stmt = $this->pdo->prepare($this->query);
		$this->stmt->execute($this->dataToBind);
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}
	public function bindData($data){
		$this->dataToBind = $data;
		return $this;
	}
}

?>