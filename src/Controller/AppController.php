<?php

require('../vendor/xuxuzinho/src/Controller/Controller.php');

class AppController extends Controller
{
	function __construct() {
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');
	}
}

?>	