<?php

App::src('Controller', 'AppController');

class MatchesController extends AppController
{

	function __construct()
	{
		Parent::__construct();
		$this->loadModel(['Profile']);
	}

	public function getProbably()
	{
		$profiles = $this
			->Profile
			->select(['*'])
			->limit(10)
			->all();
		return $profiles;
	}

	public function getMatched()
	{
		$profiles = $this
			->Profile
			->select(['*'])
			->orderBy(['rand()'])
			->limit(5)
			->all();

		return $profiles;
	}

}

?>