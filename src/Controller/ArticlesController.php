<?php
require('AppController.php');

class ArticlesController extends AppController
{
	public function index()
	{
		return 'oi';
	}
	public function edit($id)
	{
		echo 'editando id: ' . $id;
		print_r($this->put);
	}
	public function add()
	{
		print_r($this->post);
	}
	public function view($id)
	{
		echo "Vendo id: {$id}";
	}
	public function delete($id)
	{
		echo "Deletando id: {$id}";
	}
}

?>		