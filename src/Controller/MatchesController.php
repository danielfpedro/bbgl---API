<?php

require('AppController.php');
require('../vendor/xuxuzinho/core/Database/MySQL.php');
require('../src/Model/match.php');

class MatchesController extends AppController
{
	
	public function add()
	{
		$this->Match = new Match();
	}
}

?>		