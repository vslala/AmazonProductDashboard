<?php

namespace Controller;

use Model\BO\UserBO;

class RegisterController extends BaseController {
	
	private $userBO;

	public function __construct() {
		parent::__construct();

		$this->userBO = new UserBO();
	}

	public function renderRegisterView() {
		$this->f3->set('content', 'views/register/index.htm');
		echo $this->view->render('layout.htm');
	}

	public function newUser() {
		var_dump($this->f3->get('POST'));
		$this->userBO->saveUser($this->f3->get('POST'));

	}
}