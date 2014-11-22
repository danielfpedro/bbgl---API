<?php
session_start();
//error_reporting(E_ALL);


$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
    header("Access-Control-Allow-Headers', 'Authorization, X-Authorization, Origin, Accept, Content-Type, X-Requested-With, X-HTTP-Method-Override");
}
// Access-Control headers are received during OPTIONS requests
if ($method == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

require('bootstrap.php');

if (isset($base_url_replace)) {
	$pattern = "/^\/{$base_url_replace}\//";
	$uri = preg_replace($pattern, '', $uri);
}

require(FRAMEWORK_FOLDER . 'lib' . DS . 'App' . DS . 'App.php');

exit();
App::lib('Dispatcher', 'Dispatcher');

$disp = new Dispatcher($method, $uri);

if (!empty($disp->controller) && !empty($disp->action) ){

	echo 'aqui';

	$controller_folder = APP_FOLDER . 'src' . DS . 'Controller' . DS;
	$controller_name = ucfirst($disp->controller) . 'Controller';
	$controller_file = $controller_folder . $controller_name . '.php';

	if (file_exists($controller_file)) {
		App::src('Controller', $controller_name);
		$app = new $controller_name();
		if (method_exists($app, $disp->action)) {
			if ($app->responseType) {
				echo json_encode(call_user_func_array([$app, $disp->action], $disp->args));
			} else {
				echo call_user_func_array([$app, $disp->action], $disp->args);
			}
		} else {
			http_response_code(404);
			echo json_encode(['error'=> 'Page Not Found 1']);
		}
	} else {
		http_response_code(404);
		echo json_encode(['error'=> 'Page Not Found 2']);
	}
} else {
	echo 'aqui else';
	http_response_code(404);
	echo json_encode(['error'=> 'Page Not Found 3']);
}