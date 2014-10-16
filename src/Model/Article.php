<?php
require('AppModel.php');
//require('vendor/xuxuzinho/core/Database/MySQL/MySQL.php');

class Article extends AppModel
{
	public function get()
	{
		$db = new MySQL();

		$db->save('profiles', [
			'name'=> 'joásãsçãsp´sa',
			'birth_date'=> '2010-10-10',
			'images_folder'=> 'lkjklfs',
			'created'=> Date('y-m-d h:i:s'),
			'account_id'=> 1,
		]);
	}
}
?>
