<?php
require('AppModel.php');

class Article extends AppModel
{
	public $rules = [
		'title'=> [
			'required',
			'notEmpty',
			'maxLength'=> [20]
		]
	];
}
?>