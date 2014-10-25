<?php

App::src('Controller', 'AppController');
//App::lib('Database\MySQL', 'MySQL');
/**
* 
*/
class ArticlesController extends AppController
{

	public $Article;

	function __construct()
	{
		parent::__construct();
		print_r(PDO::getAvailableDrivers());
	}

	public function index()
	{
		echo 'Index';
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