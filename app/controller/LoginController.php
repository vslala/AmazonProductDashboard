<?php

namespace Controller;

use Model\BO\UserBO;

class LoginController extends BaseController {

	private $userBO;

	public function __construct() {
		parent::__construct();
		$this->userBO = new UserBO();
	}

	public function beforeRoute() {
		if (null == $this->f3->get('SESSION.user')) {
			
		}
	}

	public function renderLoginPage() {
		$this->f3->set('content', 'views/login/index.htm');
		echo $this->view->render('layout.htm');
	}

	public function authUser() {
		$authUser = $this->userBO->authUser($this->f3->get('POST'));
		$this->f3->set('SESSION.user', $authUser);
		$this->f3->reroute('@user_dashboard');
	}

	public function logout() {
		$this->f3->set('SESSION.user', null);
		$this->f3->reroute('@user_login');
	}
}