<?php
class Database
{
	public static $connection;

	public static function connect($default = 'default')
	{
		require(APP_FOLDER . 'config' . DS . 'datasource.php');
		
		$data = $datasource[$default];

		try {
			$conn = new PDO("mysql:host={$data['host']};dbname={$data['database']};charset={$data['charset']}", $data['user'], $data['password']);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch(PDOException $e) {
			self::$error = $e->getMessage();
		}
	}

}
?>