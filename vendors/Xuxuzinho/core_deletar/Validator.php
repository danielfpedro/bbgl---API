<?php

class Validator
{
	private $language_default = 'eng-US';
	public $language = 'eng-US';
	public $errors = array();
	public $rules;
	
	private $args;
	
	public $messages;
	
	function __construct()
	{

	}
	public function validate($values)
	{
		$return = true;
		foreach($values as $key => $value){
			if (!empty($this->rules[$key])) {
				foreach($this->rules[$key] as $k => $rule){
					if (is_array($rule)) {
						$args[] = $value;
						$this->args = array_merge($args, $rule);
						if (!call_user_func_array(array('self', $k), $this->args)) {
							$this->writeError($key,  $k);
							$return = false;
						}
					} else {
						if (!self::$rule($value)) {
							$this->writeError($key,  $rule);
							$return = false;
						}
					}
				}
			}
		}
		
		return $return;
	}
	
	public function setRules($rules)
	{
		$this->rules = $rules;
	}

	private function writeError($field, $type)
	{
		$this->messages = array(
			'eng-US'=> array(
				'notEmpty'=> 'Can\'t be blank',
				'numeric'=> 'Must be numeric',
				'email'=> 'Invalid email',
				'between'=> 'Number must be between ' . $this->args[1] . ' and ' . $this->args[2]
			),
			'pt-BR'=> array(
				'notEmpty'=> 'Não pode ficar em branco',
				'numeric'=> 'Deve ser numérico',
			)
		);
		if (!empty($this->messages[$this->language][$type])) {
			$this->errors[$field]['messages'][] = $this->messages[$this->language][$type];
		} else {
			$this->errors[$field]['messages'][] = $this->messages[$this->language_default][$type];
		}
	}
	public function setLanguage($language)
	{
		$this->language = $language;
	}
	//Validation Methods
	public static function between($value, $min = null, $max = null)
	{
		if ($value >= $min && $value <= $max) {
			return true;
		}
		return false;
	}
	
	public static function notEmpty($value)
	{
		if (!empty($value)) {
			return true;
		}
		return false;
	}
	public static function numeric($value)
	{
		if (is_numeric($value)) {
			return true;
		}
		return false;
	}
	public static function email($value)
	{
		if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}
	//Validation Methods
}
class Article
{
	public $rules = array(
		'title'=> array(
			'notEmpty',
		),
		'text'=> array(
			'notEmpty'
		),
		'email'=> array(
			'email'
		),
		'numero'=> array(
			'numeric',
			'between'=> array(1, 5)
		),
		'outro'=> array(
			'between'=> array(1,2)
		)
	);
}
$post = array(
	'title'=> 'Ola gente',
	'text'=> '',
	'email'=> 'daniel@gmail.com',
	'numero'=> 6,
	'outro'=> 10
);

$article = new Article;
$validator = new Validator();
$validator->setRules($article->rules);
$validator->setLanguage('pt-BR');
if ($validator->validate($post)) {
	echo 'Validou jóia';
} else{
	echo 'Não validou jóia não <br>';
	print_r($validator->errors);
}
?>