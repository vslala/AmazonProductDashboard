<?php

namespace Controller;

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\Operations\Lookup;
use ApaiIO\ApaiIO;
use Util\ProductAPIExtension;
use Model\EO\OperationHandler;


class ProductAPIController extends BaseController {

	private $operations;

	public function __construct() {
		parent::__construct();

		if (empty($this->f3->get('SESSION.user')))
			$this->f3->reroute('@user_login');

		$this->operations = new OperationHandler();
	}

	public function renderSearchPage() {
		$this->f3->set('content', 'views/dashboard/search-view.htm');
		echo $this->view->render('layout.htm');
	}

	public function searchProduct() {
		$data = $this->operations->searchByTitle($this->f3->get('POST.searchQuery'));
		echo '<ul>';
		foreach ($data->Items->Item as $item) {
			echo '<li><a href="'. $item->DetailPageURL .'">' . $item->ItemAttributes->Title . '</a></li>' ;
		}
		
		echo '</ul>';
		// $this->f3->set('data', $data->Items);
		// $this->f3->set('content', 'views/dashboard/search-view.htm');
		// $this->view->render('layout.htm');
	}

}
