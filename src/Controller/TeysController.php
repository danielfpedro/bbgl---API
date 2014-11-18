<?php

App::src('Controller', 'AppController');
App::lib('DataBase\MySQL', 'DataBase');

class TeysController extends AppController
{

	function __construct()
	{
		Parent::__construct();
		$this->loadModel(['Profile']);
	}

	public function teste()
	{
		
		$data = $this->Profile->select(['*'])->all();
		return $data;
	}

}

?>