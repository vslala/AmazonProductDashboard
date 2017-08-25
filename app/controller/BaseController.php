<?php

namespace Controller;

use Respect\Validation\Validator as v;

class BaseController {

	protected $f3;
	protected $view;

	public function __construct() {
		$this->f3 = \Base::instance();
		$this->view = \View::instance();
	}

	public function beforeRoute() {

	}
}