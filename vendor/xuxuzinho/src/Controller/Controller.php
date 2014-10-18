<?php

class Controller
{
	function __construct ()
	{
	}

	public function loadModel($models = [])
	{
		foreach ($models as $key => $model) {
			require("src/Model/{$model}.php");
			$this->$model = new $model;
		}
	}

	public function methodIs($methods = [])
	{
		foreach ($methods as $method) {
			if ($this->method === strtoupper($method)) {
				return true;
			}
		}
		return false;
	}

}

?>