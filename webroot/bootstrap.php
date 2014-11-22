<?php

date_default_timezone_set('America/Sao_Paulo');

$base = dirname(__FILE__);
$base = str_replace(basename($base), '', $base);

define('DS', DIRECTORY_SEPARATOR);

$framework_name = 'mayhem';
define('APP_FOLDER', $base);
define('FRAMEWORK_FOLDER', $base . 'vendor' . DS . 'mayhem' . DS);

?>