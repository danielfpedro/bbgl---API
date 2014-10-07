<?php
require('AppModel.php');

class Profile extends AppModel
{

	public function getProbablyMatch($id) {
		$query = "".
			"SELECT
	`accounts`.`id` AS `id`,
	`profiles`.`name` AS `name`,
	`relationships`.`response` AS `response`
FROM
	(
		(
			`accounts`
			LEFT JOIN `relationships` ON (
				(
					`accounts`.`id` = `relationships`.`account_id2`
				)
			)
		)
		JOIN `profiles` ON (
			(
				`accounts`.`id` = `profiles`.`account_id`
			)
		)
	)
WHERE
	(
		(`accounts`.`id` <> {$id})
		AND (
			NOT (
				`accounts`.`id` IN (
					SELECT
						`relationships`.`account_id2`
					FROM
						`relationships`
					WHERE
						(
							`relationships`.`relacionou` = 1
						)
				)
			)
		)
	)
ORDER BY
	`relationships`.`response` DESC,
	rand()
LIMIT 20";

		$result = mysql_query($query);
		$return = [];
		while ($row = mysql_fetch_assoc($result)) {
			$return[] = $row;
		}
		return $return;
	}

	public function getOne($id){
		$query = "SELECT name, account_id FROM profiles WHERE id = {$id} LIMIT 1";
		$result = mysql_query($query) or die (mysql_error());
		return mysql_fetch_assoc($result);
	}

	public $rules = [];
}
?>