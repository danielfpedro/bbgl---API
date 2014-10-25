<?php

/**
* 
*/
class App
{
	
	static function vendor($namespace, $file){
		require(APP_FOLDER . 'vendors' . DS . $namespace . DS . $file . '.php');
	}
	static function src($namespace, $file){
		require(APP_FOLDER . 'src' . DS . $namespace . DS . $file . '.php');
	}
	static function lib($namespace, $file){
		require(XUXUZINHO_FOLDER . 'lib' . DS . $namespace . DS . $file . '.php');
	}
}

?>