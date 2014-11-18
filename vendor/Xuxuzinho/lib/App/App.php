<?php
class App
{
	static function component($namespace, $file){
		require_once(APP_FOLDER . 'src' . DS . 'component' . DS . $namespace . DS . $file . '.php');
	}
	static function vendor($namespace, $file){
		require_once(APP_FOLDER . 'vendor' . DS . $namespace . DS . $file . '.php');
	}
	static function src($namespace, $file){
		require_once(APP_FOLDER . 'src' . DS . $namespace . DS . $file . '.php');
	}
	static function lib($namespace, $file){
		require_once(XUXUZINHO_FOLDER . 'lib' . DS . $namespace . DS . $file . '.php');
	}
}

?>