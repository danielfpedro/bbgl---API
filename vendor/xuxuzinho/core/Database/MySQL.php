<?php
/**
*
*/

require('DataBase.php');

class MySQL extends Database
{
	
	public $connection;

	function __construct($db = null)
	{
		$db = (empty($db)) ? 'default' : $db;

		require('../config/database.php');

		$this->connection = new PDO(
			'mysql:host='.$database[$db]['host'].';dbname='.$database[$db]['name'].';charset='.$database[$db]['charset'],
			$database[$db]['user'],
			$database[$db]['password']
		);
	}

	public function query($query){
		$q = $this->connection->query($query);
		return $q->fetch(PDO::FETCH_ASSOC);
	}
	public function insert($table, $param){
		$fields = [];
		$values = [];
		foreach ($param as $key => $value) {
			$fields[] = $key;
			$values[] = (gettype($value) == 'string') ? "'".$value."'": $value;
		}

		$fields = join($fields);
		$values = join($values);

		$insert = "INSERT INTO ".$table." (".$fields.") VALUES(".$values.")";

		return $this->connection->query($insert);
	}
}
?>