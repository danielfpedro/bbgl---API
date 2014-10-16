<?php
require('AppController.php');
require('src/Model/Article.php');

class ArticlesController extends AppController
{
	public function index($id)
	{
		echo $id;
	}
}

?>		