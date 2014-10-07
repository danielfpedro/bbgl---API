<?php

/**
* 
*/
class Dispatcher
{

	public $default_route = true;

	public $controller;
	public $action;
	public $vars;

	function __construct()
	{
		$uri = parse_url($_SERVER['REQUEST_URI']);
		self::Connect($uri['path']);
	}

	public static function Connect($uri, $default_route = true)
	{
		$uri_exploded = explode('/', $uri);
		// ISSO AQUI É GAMBI, DEPOIS VER COMO FAZ PARA FUNCIONAR EM TODOS OS SERVIDORES POSSIVEIS
		unset($uri_exploded[0]);
		unset($uri_exploded[1]);
		//Limpa os valores em branco, caso ele coloque barra no final;
		$uri = array_filter(array_values($uri_exploded));

		$total = count($uri);
		// ISSO AQUI É GAMBI, DEPOIS VER COMO FAZ PARA FUNCIONAR EM TODOS OS SERVIDORES POSSIVEIS
	
		// Default routes test
		if ($default_route) {
			if (!self::connectDefaultRoutes($uri)) {
				echo 'Page not found';
				return 'erro 404';
			}
		} else {
			echo 'erro 404';
			return 'erro 404';
		}
	}

	public static function connectDefaultRoutes($uri)
	{
		$total = count($uri);
		$vars = [];
		$controller = $uri[0];
		if (!empty($uri[1])) {
			if (is_numeric($uri[1])) {
				$action = 'view';
				$vars[] = $uri[1];
			} else {
				$action = $uri[1];
			}
		} else {
			$action = 'index';
		}
		if ($total > 2) {
			$new_uri = $uri;
			unset($new_uri[0]);
			unset($new_uri[1]);
			$new_uri = array_values($new_uri);
			if (!empty($new_uri)) {
				foreach ($new_uri as $key => $value) {
					$vars[] = $value;
				}
			}
		}
		if (file_exists(self::getControllerPath($controller))) {
			require (self::getControllerPath($controller));
			$controller_name = self::getControllerClassName($controller);
			$controller_obj = new $controller_name;
			if (method_exists($controller_obj, $action)){
				echo call_user_method_array($action, $controller_obj, $vars);
				return true;
			}
		}
		return false;
	}
	public static function getControllerClassName($controller)
	{
		return ucfirst($controller) . 'Controller';
	}
	public static function getControllerName($controller)
	{
		return ucfirst($controller) . 'Controller.php';
	}
	public static function getControllerPath($controller)
	{
		return CONTROLLER . DS . self::getControllerName($controller);
	}

}

?>