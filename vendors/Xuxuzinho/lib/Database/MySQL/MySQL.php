<?php
App::vendor('MySQLQueryBuilder', 'MySQLQueryBuilder');

class MySQL extends MySQLQueryBuilder
// class MySQL
{
	// private $host;
	// private $user;
	// private $password;
	// private $database;
	// private $charset;
	
	public $table;

	public $pdo;
	public $stmt;
	public $error;

	function __construct($default = 'default')
	{
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

	// private function connect(){
	// 	$link = mysql_connect($this->host, $this->user, $this->password) or die ('Error on connect to database: ' . mysql_error () . '<br>');
	// 	mysql_set_charset($this->charset, $link);
	// 	mysql_select_db($this->database, $link) or die('Error on select database: ' . mysql_error() . '<br>');
	// }
}
?>