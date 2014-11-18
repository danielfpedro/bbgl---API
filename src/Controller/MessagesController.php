<?php

App::src('Controller', 'AppController');

class MessagesController extends AppController
{

	function __construct()
	{
		Parent::__construct();
		$this->loadModel(['Message']);
	}

	public function index()
	{
		$message = $this
			->Message
			->select(['*'])
			->all();
		return $message;
	}

	public function add()
	{
		$data = json_decode($this->http_stream);

		$this->post['created'] = Date('Y-m-d h:i:s');
		$this->Message->create($data);
		if ($this->Message->save()) {
			return ['message' => 'mensagem salva com sucesso!'];
		} else {
			return $this->errorResponse($this->Message->error);
		}
	}
}

?>