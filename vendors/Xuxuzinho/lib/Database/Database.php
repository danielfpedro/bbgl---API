<?php
class Database
{
	public static $error;

	public static function connect($default = 'default')
	{
		require(APP_FOLDER . 'config' . DS . 'datasources.php');
		
		$data = $datasources[$default];

		try {
			$conn = new PDO("mysql:host={$data['host']};dbname={$data['database']};charset={$data['charset']}", $data['user'], $data['password']);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch(PDOException $e) {
			throw $e;
		}
	}

}
?>