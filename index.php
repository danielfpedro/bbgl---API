<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

class Dispatch
{
	public $method;

	public $get;
	public $post;
	public $put;

	public $controller;
	public $action;
	public $args;
	
	public function setMethod($method)
	{
		$this->method = $method;
	}
	function __construct()
	{
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->get = $_GET;
		$this->post = $_POST;
		parse_str(file_get_contents("php://input"), $this->put);
		$this->delete = $this->put;
		
		$this->getData();
	}
	public function getData()
	{
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
		$controller = $uri[0];		
		unset($uri[0]);
		$uri = array_values($uri);

		if (!empty($uri[0])) {
			$action = $uri[0];
			unset($uri[0]);
			$uri = array_values($uri);
		}

		if (!empty($uri)){
			foreach ($uri as $value) {
				$args[] = $value;
			}
		}


		if (empty($action) && !empty($args[0])){
			switch ($this->method) {
				case 'GET':
					$action = 'view';
				break;
				case 'PUT':
					$action = 'edit';
				break;
				case 'DELETE':
					$action = 'delete';
				break;
			}
		} else {
			switch ($this->method) {
				case 'GET':
					$action = 'index';
				break;
				case 'POST':
					$action = 'add';
				break;
			}
		}

		$this->controller = $controller;
		$this->action = $action;
		$this->args = $args;
	}
}

$dispatch = new Dispatch();

define('DS', DIRECTORY_SEPARATOR);
define('CONTROLLER', 'src' . DS . 'Controller' . DS);

$controller_name = ucfirst($dispatch->controller) . 'Controller';
$controller_file = CONTROLLER . $controller_name . '.php';

$action_name = $dispatch->action;

$args = $dispatch->args;

if (file_exists($controller_file)){
	require($controller_file);
	$controller_ojb = new $controller_name;
	if (method_exists($controller_ojb, $action_name)) {
		call_user_method_array($action_name, $controller_ojb, $args);
	} else {
		echo 'Page Not Found';
	}
} else {
	echo 'Page Not Found';
}
//$router = new Router(, $dispatch->action, $dispatch->args);

?>