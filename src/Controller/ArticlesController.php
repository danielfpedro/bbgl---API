<?php

App::src('Controller', 'AppController');
App::src('Model', 'Article');
// App::lib('Database\MySQL', 'MySQL');
/**
* 
*/
class ArticlesController extends AppController
{

	public $Article;

	public function index()
	{
		$Article = new Article;

		$articles = $Article
			->select(['title'])
			->where(['title LIKE'=> Article::string('%copia%')])
			->all();

		foreach ($articles as $article) {
			echo "{$article->title} <br>";
		}

		return 'Index';
	}

	public function view($id)
	{
		return ['response'=> 'View id' . $id];
	}

	public function add()
	{
		echo 'Add';
	}

	public function edit($id)
	{
		return ['response'=> 'Editando id' . $id];
	}

	public function delete($id)
	{
		echo 'Delete id: ' . $id;
	}
}

?>