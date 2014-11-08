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
		$data = json_decode($this->put);
		$data->id = $id;
		
		$this->Profile->create($data);
		if ($this->Profile->save()) {
			return ['message' => 'sucesso'];
		} else {
			return Controller::errorResponse($this->Profile->error);
		}
	}

}

?>