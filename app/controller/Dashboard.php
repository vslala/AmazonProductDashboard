<?php

namespace Controller;

class Dashboard extends BaseController {

	public function beforeRoute() {
		if (null == $this->f3->get('SESSION.user'))
			$this->f3->reroute('@user_login');
	}

	public function __construct() {
		parent::__construct();

		$this->f3->set('navbar', 'views/dashboard/template-parts/navbar.htm');
	}

	public function renderDashboardHome() {
		$this->f3->set('content', 'views/dashboard/index.htm');
		echo $this->view->render('layout.htm');
	}
}