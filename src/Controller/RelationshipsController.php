<?php

require('AppController.php');
require('../vendor/xuxuzinho/core/Database/MySQL.php');
require('../src/Model/Relationship.php');

class RelationshipsController extends AppController
{
	public function add(){
		$this->Relationship = new Relationship();
		return $this->Relationship->setResponse($this->get['action'], $this->get['account_id'], $this->get['account_id2']);
	}
}

?>		