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

	public function getMatches($account_id)
	{
		$this->Relationship = new Relationship();
		$matches = $this->Relationship->getMatches($account_id);
		return json_encode($matches);
	}
}

?>		