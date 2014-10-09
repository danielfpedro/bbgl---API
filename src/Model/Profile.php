<?php
require('AppModel.php');

class Profile extends AppModel
{
	public $distance_max = 40; // In Kilometers

	public function getProbablyMatch($id, $lat, $lng) {
		$query = "".
			"SELECT
			(6371 * acos(cos(radians({$lat})) * cos(radians(lat)) * cos(radians(lng) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians(lat) ) ) ) AS distance, 
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
HAVING
	distance < {$this->distance_max}
ORDER BY
	`relationships`.`response` DESC,
	rand()
LIMIT 20";

	
		$result = mysql_query($query) OR die(mysql_error());
		$return = [];
		while ($row = mysql_fetch_assoc($result)) {
			$return[] = $row;
		}
		return $return;
	}

	public function getOne($id){
		$query = "SELECT name, account_id, lat, lng FROM profiles WHERE id = {$id} LIMIT 1";
		$result = mysql_query($query) or die (mysql_error());
		return mysql_fetch_assoc($result);
	}

	public $rules = [];
}
?>