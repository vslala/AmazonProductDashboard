<?php

namespace Util;

use ApaiIO\Operations\AbstractOperation;

class ProductAPIExtension extends AbstractOperation {

	public function getName() {
		return 'ItemSearch';
	}

	public function setTitle($title) {
		$this->parameters['Title'] = $title;
		return $this;
	}
}