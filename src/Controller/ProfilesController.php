<?php

require('AppController.php');
require('../vendor/xuxuzinho/core/Database/MySQL.php');
require('../src/Model/Profile.php');

class ProfilesController extends AppController
{
	function __construct(){
		parent::__construct();
		$this->Profile = new Profile();
	}
	public function probablyMatches($id, $lat, $lng){
		echo json_encode($this->Profile->getProbablyMatch($id, $lat, $lng));

	}

	//RESTful logic below
	public function view ($id) {
		$profile = $this->Profile->getOne($id);
		echo json_encode($profile);
	}
}

?>		