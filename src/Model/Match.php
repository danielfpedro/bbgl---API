<?php
require('AppModel.php');

class Match extends AppModel
{

	public function getChat($account_id)
	{
		$query = "".
			"SELECT Profile.name 
				FROM profiles Profile 
				WHERE Profile.account_id IN (SELECT account_id)";
	}

}
?>