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

		$data = ['id'=> 26, 'title'=> 'kdjsjkdas'];
		$Article = new Article;

		$articles = $Article
			->select(['title', 'comment'])
			->join([
				'table'=> ['comments'=> 'Comment'],
				'conditions'=> [
					'Comment.article_id'=> 'Article.id'
				]
			])
			->all();

		print_r($articles);

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