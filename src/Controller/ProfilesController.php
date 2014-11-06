<?php

App::src('Controller', 'AppController');

class ProfilesController extends AppController
{

	function __construct()
	{
		Parent::__construct();
		$this->loadModel(['Profile']);
	}

	public function view($id)
	{
		$profile = $this
			->Profile
			->select(['*'])
			->where(['account_id' => ':id'])
			->bindData(['id' => $id])
			->first();
		return $profile;
	}

	public function edit($id)
	{
		return ['oi'];
		$this->put['id'] = $id;
		$this->Article->create($this->put);
		if ($this->Article->save()) {
			return ['message' => 'sucesso'];
		} else {
			return Controller::responseError($this->Article->error);
		}
	}

}

?>