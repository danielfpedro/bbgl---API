<?php
require('vendor/xuxuzinho/src/Model/Model.php');

class AppModel extends Model
{

	function __construct(){
		parent::__construct();
		$conn = mysql_connect('mysql.bbgl.kinghost.net', 'bbgl', '5JmzuRr7LMT4') or die (mysql_error()); 
		mysql_set_charset('utf8', $conn);
		mysql_select_db('bbgl', $conn) or die (mysql_error());
	}
	
}
?>