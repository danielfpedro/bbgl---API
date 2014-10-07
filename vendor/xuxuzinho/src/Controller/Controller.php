<?php

class Controller
{
	protected $config;
	public $get;
	public $post;

	function __construct ()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');  	

		require('../config/app.php');
		$this->config = $config;
		$this->get = $_GET;
		$this->post = $_POST;
	}
}

?>