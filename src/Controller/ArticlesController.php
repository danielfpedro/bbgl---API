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
		$articles = $this->Article->select(['title'])->all();
		return ['Article'=> $articles];
	}

	public function view($id)
	{
		$query = $this
			->Article
				->select(['title'])
				->where(['id'=> ':id'])
				->bindData(['id'=> $id])
				->all();

		

		return ['Article'=> $article];
	}

	public function add()
	{
		$this->Article->create($this->post);
		$this->Article->save();

		return ['message'=> 'Salvo com sucesso!'];
	}

	public function edit($id)
	{
		$data = $this->put;
		$data['id'] = $id;

		$this->Article->create($data);
		$this->Article->save();

		return ['message'=> 'Editado com sucesso!'];
	}

	public function delete($id)
	{
		$this->Article->delete($id);
		return ['message'=> 'Arquivo deletado com sucesso'];
	}
}

?>