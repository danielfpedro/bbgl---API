<?php
class Dispatcher
{

	public $args = [];

	function __construct($method)
	{
		$this->method = $method;
		$this->getValues();
	}

	public function getValues()
	{
		//GET URI
		$this->uri = parse_url($_SERVER['REQUEST_URI']);
		$this->uri = $this->uri['path'];
		$this->uri = explode('/', $this->uri);
		$this->uri = array_filter($this->uri);
		array_shift($this->uri);
		$this->uri = array_values($this->uri);

		$total = count($this->uri);


		//If nothing passes return false
		if ($total == 0){
			return false;
		}

		$this->controller = $this->uri[0];
		unset($this->uri[0]);

		if (!empty($this->uri[1])) {
			if (is_numeric($this->uri[1])) {
				switch ($this->method) {
					case 'GET':
						$this->action = 'view';
						break;
					case 'PUT':
						$this->action = 'edit';
						break;
					case 'DELETE':
						$this->action = 'delete';
						break;
				}
			} else {
				$this->action = $this->uri[1];
				unset($this->uri[1]);
			}
		} else {
			switch ($this->method) {
				case 'GET':
					$this->action = 'index';
					break;
				case 'POST':
					$this->action = 'add';
					break;
			}
		}

		if (!empty($this->uri)) {
			foreach ($this->uri as $key => $value) {
				$this->args[] = $value;
			}
		}
	}
}
?>