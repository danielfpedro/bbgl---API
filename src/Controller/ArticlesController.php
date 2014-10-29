<?php

App::src('Controller', 'AppController');

class ArticlesController extends AppController
{

	function __construct()
	{
		Parent::__construct();
		$this->loadModel(['Article']);
	}

	public function index()
	{
		$articles = $this
			->Article
				->select(['Article.title', 'Comment.comment'])
				->join([
					'table' => 'comments',
					'alias' => 'Comment',
					'conditions' => [
						'Comment.article_id' => 'Article.id'
					]
				])
				->where([
					'Article.title LIKE' => ':like_titulo'
				])
				->bindData(['like_titulo' => '%títudslo%'])
				->all();
		return ['Article'=> $articles];
	}

	public function view($id = null)
	{
		if (empty($id)) {
			return Controller::errorResponse('Você não informou o ID do artigo');
		}
		$article = $this->Article->select(['title', 'text', 'created'])->first();
		return ['Article'=> $article];
	}

	public function add()
	{
		if (empty($this->post)) {
			return Controller::errorResponse('Você não informou os dados!');
		}
		$this->post['created'] = Date('Y-m-d H:i:s');
		$this->Article->create($this->post);
		if ($this->Article->save()) {
			return ['message' => 'Sucesso!'];
		} else {
			return Controller::errorResponse($this->Article->error);
		}
	}

	public function edit($id = null)
	{
		if (!is_numeric($id)) {
			return Controller::errorResponse('Você não informou ou informou incorretamente o ID do artigo para editar');
		}
		if (empty($this->put)) {
			return Controller::errorResponse('Você não informou os dados para a edição');
		}

		$this->put['id'] = $id;
		$this->put['modified'] = Date('Y-m-d H:i:s');
		$this->Article->create($this->put);
		if ($this->Article->save()) {
			return ['message' => 'sucesso'];
		} else {
			return Controller::errorResponse($this->Article->error);
		}
	}

	public function delete($id = null)
	{
		if (!is_numeric($id)) {
			return Controller::errorResponse('Você não informou o ID do artigo para deletar');
		}
		if ($this->Article->delete($id)) {
			return ['message' => 'sucesso'];
		} else {
			return Controller::errorResponse($this->Article->error);
		}
	}

	public function coco($param1, $param2, $param3, $teytey)
	{
		return [$param1, $param2, $param3, $teytey];
	}
}

?>