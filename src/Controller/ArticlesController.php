<?php

App::src('Controller', 'AppController');

class ArticlesController extends AppController
{

	public function index()
	{
		$this->loadModel(['Article']);
		$articles = $this
			->Article
			->select(['*'])
			->all();
		return ['Article' => $articles];
	}

}

?>