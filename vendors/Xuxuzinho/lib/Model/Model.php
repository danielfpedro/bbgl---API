<?php

App::vendor('MySQLQueryBuilder', 'MySQLQueryBuilder');

class Model extends MySQLQueryBuilder
{

	public $alias;

	public $pdo;
	public $stmt;
	public $error;

	function __construct ($default = 'default')
	{
		$name = get_called_class();
		$this->setAlias($name);

		require(APP_FOLDER . 'config' . DS . 'datasource.php');
		
		$data = $datasource[$default];

		$this->host = $data['host'];
		$this->user = $data['user'];
		$this->password = $data['password'];
		$this->database = $data['database'];
		$this->charset = $data['charset'];

		try {
			$conn = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->user, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo = $conn;
		} catch(PDOException $e) {
			$this->error = $e->getMessage();
		}
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
		$this->bind($this->data);
		$this->execute();
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
	public function delete($table, $data)
	{
		$this->data = $data;
		$query = "DELETE FROM {$table} WHERE id = :id";

		$this->query($query);
		$this->bind($data);
	}
	public function query($query)
	{
		$this->stmt = $this->pdo->prepare($query);
		return $this;
	}
	public function bind($data)
	{
		foreach ($data as $key => $value) {
			$this->stmt->bindValue($key, $value);
		}

	}

	public function all()
	{
		$this->query($this->query)->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function first()
	{
		$this->query($this->query)->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function execute()
	{
		$this->stmt->execute();
		$this->rowCount = $this->stmt->rowCount();	
		return $this;
	}
}

?>