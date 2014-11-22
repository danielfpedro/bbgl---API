<?php

date_default_timezone_set('America/Sao_Paulo');

$framework_name = 'mayhem';

if (!getenv('PRODUCTION')) {
	$base_url_replace = 'bbgl';
}

$app_folder = dirname(__FILE__);
$app_folder = str_replace(basename($app_folder), '', $app_folder);

define('DS', DIRECTORY_SEPARATOR);
define('APP_FOLDER', $app_folder);
define('FRAMEWORK_FOLDER', $app_folder . 'vendor' . DS . 'mayhem' . DS);


?>