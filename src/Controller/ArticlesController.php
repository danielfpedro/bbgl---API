<?php

require('AppController.php');
require('../vendor/xuxuzinho/core/Database/MySQL.php');
require('../src/Model/Article.php');

class ArticlesController extends AppController
{
	public function add(){

	}
	public function view($id)
	{
		$obj = [['name'=> 'Daniel', 'Cidade'=> 'Volta Redonda'], ['name'=> 'Daniel', 'Cidade'=> 'Volta Redonda']];
		return json_encode($obj);
	}
}

?>		