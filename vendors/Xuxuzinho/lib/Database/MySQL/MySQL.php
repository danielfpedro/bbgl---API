<?php

App::vendor('MySQLQueryBuilder', 'MySQLQueryBuilder');

class MySQL extends MySQLQueryBuilder
{
	private $host;
	private $user;
	private $password;
	private $database;
	private $charset;

	function __construct($default = 'default')
	{
		require(APP_FOLDER . 'config' . DS . 'datasource.php');
		
		$data = $datasource[$default];

		$this->host = $data['host'];
		$this->user = $data['user'];
		$this->password = $data['password'];
		$this->database = $data['database'];
		$this->charset = $data['charset'];

		$this->connect();
	}

	private function connect(){
		$link = mysql_connect($this->host, $this->user, $this->password) or die ('Error on connect to database: ' . mysql_error () . '<br>');
		mysql_set_charset($this->charset, $link);
		mysql_select_db($this->database, $link) or die('Error on select database: ' . mysql_error() . '<br>');
	}
}
?>