<?php 
header('Content-Type: text/html; charset=utf-8');
 
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APP_DIR', 'src');
define('CONTROLLER', ROOT . DS . APP_DIR . DS . 'Controller');
define('MODEL', ROOT . DS . APP_DIR . DS . 'Model');

require('../vendor/xuxuzinho/core/Dispatcher/Dispatcher.php');

$dispatcher = new Dispatcher;

//require ('../src/Controller/'.$dispatcher->controller.'.php');

// $action = $dispatcher->action;
// echo (!empty($app->$action())) ? json_encode($app->$action()) : '';


?>