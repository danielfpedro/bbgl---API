<?php

date_default_timezone_set('America/Sao_Paulo');

$framework_name = 'mayhem';

if (!getenv('PRODUCTION')) {
	$base_url_replace = 'bbgl';
}

$base = dirname(__FILE__);
$base = str_replace(basename($base), '', $base);

define('DS', DIRECTORY_SEPARATOR);
define('APP_FOLDER', $base);
define('FRAMEWORK_FOLDER', $base . 'vendor' . DS . 'mayhem' . DS);


?>