<?php
session_start();
error_reporting(E_ALL);

echo $_SERVER['PHP_SELF'];
exit();


if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
    header("Access-Control-Allow-Headers', 'Authorization, X-Authorization, Origin, Accept, Content-Type, X-Requested-With, X-HTTP-Method-Override");
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];
require('bootstrap.php');

require(FRAMEWORK_FOLDER . 'lib' . DS . 'App' . DS . 'App.php');

App::lib('Dispatcher', 'Dispatcher');

$disp = new Dispatcher($method, $_SERVER['REQUEST_URI']);

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
	http_response_code(404);
	echo json_encode(['error'=> 'Page Not Found 3']);
}