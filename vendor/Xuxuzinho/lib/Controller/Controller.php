<?php

class Controller
{

	public $get = [];
	public $post = [];
	public $put = [];
	public $delete = [];
	
	public $args = [];

	public $responseType = 'json';

	function __construct()
	{
		$this->method = $_SERVER['REQUEST_METHOD'];

		$this->get = $_GET;
		$this->post = $_POST;

		$this->http_stream = file_get_contents("php://input");

		switch ($this->method) {
			case 'PUT':
				$this->put = file_get_contents("php://input");
				break;
			case 'DELETE':
				$this->delete = file_get_contents("php://input");
				break;				
		}
	}

	public static function errorResponse($error)
	{
		http_response_code(400);
		return ['error' => $error];
	}

	public function loadModel($models = [])
	{
		foreach ($models as $key => $model) {
			App::src('Model', $model);
			$this->$model = new $model;
		}
	}

	public function methodIs($methods = [])
	{
		foreach ($methods as $method) {
			if ($this->method === strtoupper($method)) {
				return true;
			}
		}
		return false;
	}

}

?>