<?php

class Controller
{
	private $config;
	public $get;
	public $post;

	function __construct ()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
		header("Content-Type: application/json");	

		$this->get = $_GET;
		$this->post = $_POST;
	}
}

?>