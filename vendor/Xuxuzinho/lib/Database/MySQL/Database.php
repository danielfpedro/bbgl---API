<?php
class Database
{
	public static $error;

	public static function connect($default = 'default')
	{
		require(APP_FOLDER . 'config' . DS . 'datasources.php');
		
		$data = $datasources[$default];

		try {
			$type = $data['type'];
			unset($data['type']);
			$user = $data['user'];
			unset($data['user']);
			$password = $data['password'];
			unset($data['password']);

			$string_conn = [];
			foreach ($data as $key => $value) {
				$string_conn[] = "{$key}={$value}";
			}
			$string_conn = join($string_conn, ';');
			$string_conn = "{$type}:{$string_conn}";

			$conn = new PDO($string_conn, $user, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			return $conn;
		} catch(PDOException $e) {
			throw $e;
		}
	}

}
?>