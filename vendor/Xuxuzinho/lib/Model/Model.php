<?php

App::lib('Database\MySQL', 'QueryBuilder');

class Model extends QueryBuilder
{
	public $primaryKey;
	public $alias;

	public $connectionValues = 'default';

	function __construct ()
	{
		if (empty($this->primaryKey)) {
			$this->primaryKey = 'id';
		}
		$name = get_called_class();
		$this->alias = $name;
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

	public function getQuery()
	{
		return $this->query;
	}
}

?>