<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: text/html; charset=utf-8');

class Dispatch
{
	public $method;

	public $get;
	public $post;
	public $put;

	public $controller;
	public $action;
	public $args = [];
	
	// public function setMethod($method)
	// {
	// 	$this->method = $method;
	// }
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
		
		$controller = '';
		$action = '';
		$args = [];

		$total = count($uri);

		if($total > 1) {
			if (is_numeric($uri[1])) {
				$args[] = $uri[1];
				unset($uri[1]);
			}
		}

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


		if (empty($action)) {
			if (!empty($args[0])){
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
		}

		$this->controller = $controller;
		$this->action = $action;
		$this->args = $args;
	}

	public function run()
	{
		define('DS', DIRECTORY_SEPARATOR);
		define('CONTROLLER', 'src' . DS . 'Controller' . DS);

		$controller_name = ucfirst($this->controller) . 'Controller';
		$controller_file = CONTROLLER . $controller_name . '.php';

		if (file_exists($controller_file)){
			require($controller_file);
			$controller_ojb = new $controller_name;
			if (method_exists($controller_ojb, $this->action)) {
				$controller_ojb->method = $this->method;
				$controller_ojb->get = $this->get;
				$controller_ojb->post = $this->post;
				$controller_ojb->delete = $this->delete;
				$controller_ojb->put = $this->put;

				echo call_user_method_array($this->action, $controller_ojb, $this->args);
			} else {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}
	public function pageNotfound()
	{
		echo 'Page not found.';
	}
}

$dispatch = new Dispatch();
if (!$dispatch->run()) {
	$dispatch->pageNotfound();
}

?>