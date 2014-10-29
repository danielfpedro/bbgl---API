<?php
session_start();
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS, HEAD");
//header("Content-Type: application/json");

require('bootstrap.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'OPTIONS'){
	exit();
}

require(XUXUZINHO_FOLDER . 'lib' . DS . 'App' . DS . 'App.php');

App::lib('Dispatcher', 'Dispatcher');

$disp = new Dispatcher($method);

if (!empty($disp->controller) && !empty($disp->action) ){

	$controller_folder = APP_FOLDER . 'src' . DS . 'Controller' . DS;
	$controller_name = ucfirst($disp->controller) . 'Controller';
	$controller_file = $controller_folder . $controller_name . '.php';

	if (file_exists($controller_file)) {
		
		App::src('Controller', $controller_name);
		$app = new $controller_name();

		if (method_exists($app, $disp->action)) {
			if ($app->responseType) {
				echo json_encode(call_user_func_array([$app, $disp->action], $disp->args));
			}
			
		} else {
			http_response_code(404);
			echo json_encode(['error'=> 'Page Not Found']);
		}

	} else {
		http_response_code(404);
		echo json_encode(['error'=> 'Page Not Found']);
	}

} else {
	http_response_code(404);
	echo json_encode(['error'=> 'Page Not Found']);
}

exit();
?>