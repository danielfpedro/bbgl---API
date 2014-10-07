<?php

require('AppController.php');
require('../vendor/xuxuzinho/core/Database/MySQL.php');
require('../src/Model/FakeData.php');

class FakeDataController extends AppController
{
	public function generateProfiles($total, $restart){
		$fake_data = new FakeData();
		$fake_data->generateFakeProfiles($total, $restart);
	}		
}

?>		