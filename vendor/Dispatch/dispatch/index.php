<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTION');

class Dispatch
{
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$get = $_GET;
		$post = $_POST;
		parse_str(file_get_contents("php://input"), $put);
		$delete = $put;
		
		$uri = parse_url($_SERVER['REQUEST_URI']);
		$uri = $uri['path'];
		$uri = explode('/', $uri);
		$uri = array_filter($uri);
		array_shift($uri);
		
		$controller;
		$action;
		$args = [];
		
		$uri_temp = [];
		foreach($uri as $value){
			if (is_numeric($value)){
				$args[] = $value;
			} else {
				$uri_temp[] = $value;
			}
		}
		$uri = $uri_temp;
		$total = count($uri);
		
		$controller = $uri[0];

		if ($total >= 2) {
			$action = $uri[1];
		} else{
			switch ($method) {
				case 'GET':
					if (!empty($args[0])){
						$action = 'view';
					} else {
						$action = 'index';
					}
				break;
				case 'POST':
					$action = 'add';
				break;
				case 'PUT':
					$action = 'edit';
				break;
				case 'DELETE':
					$action = 'delete';
				break;
			}
		}
		
		$response = ['controller'=> $controller, 'action'=> $action, 'args'=> $args];
		echo json_encode($response);
	}
}

$app = new Dispatch();

?>