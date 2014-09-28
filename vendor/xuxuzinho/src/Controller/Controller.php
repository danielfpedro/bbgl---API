<?php

class Controller
{
	protected $config;

	public $get;
	public $post;

	function __construct ()
	{
		require('../config/app.php');
		$this->config = $config;
		$this->post = $_POST;
	}
}

?>