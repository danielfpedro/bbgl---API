<?php
require('AppModel.php');

class Relationship extends AppModel
{

	public function setResponse($action, $account_id, $account_id2)
	{
		$query = "".
			"INSERT INTO relationships
				(response, account_id, account_id2, relacionou)
				VALUES ({$action}, {$account_id}, {$account_id2}, 1)";
		$result = mysql_query($query);
		$match = $this->setMatch($account_id, $account_id2, $action);
		if ($match > 0){
			$update = "".
				"UPDATE relationships SET combined = 1 WHERE (account_id = {$account_id} 
					AND account_id2 = {$account_id2}) OR 
					(account_id = {$account_id2} AND account_id2 = {$account_id})";
			mysql_query($update);
		}
		return $result;
	}

	public function setMatch($account_id, $account_id2, $action)
	{
		$action = ($action == 1) ? 2 : 1;
		$query = "".
			"SELECT count(*) AS total 
				FROM relationships 
				WHERE account_id = {$account_id2} AND account_id2 = {$account_id} AND response = {$action}";
		$result = mysql_query($query);
		$result = mysql_fetch_assoc($result);
		return $result['total'];
	}
	
}
?>
