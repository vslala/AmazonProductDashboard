<?php

namespace Controller;

use Model\BO\UserBO;

class RegisterController extends BaseController {
	
	private $userBO;
	private $path;

	public function beforeRoute() {
		
	}

	public function __construct() {
		parent::__construct();
		$this->userBO = new UserBO();

		$this->path = $this->f3->get('PATH');

		if ($this->path == '/register'
					&& null != $this->f3->get('SESSION.user'))
			$this->f3->reroute('@user_dashboard');
	}

	public function renderRegisterView() {
		$this->f3->set('content', 'views/register/index.htm');
		echo $this->view->render('layout.htm');
	}

	public function newUser() {
		var_dump($this->f3->get('POST'));
		$flag = $this->userBO->saveUser($this->f3->get('POST'));
		if ($flag) 
			$this->f3->set('content', 'views/register/register-confirm.htm');
		else 
			$this->f3->set('content', 'views/register/register-error.htm');
	}
}