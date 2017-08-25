<?php

namespace Controller;

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\Operations\Lookup;
use ApaiIO\ApaiIO;
use Util\ProductAPIExtension;
use Model\EO\OperationHandler;
use Model\BO\UserBO;


class ProductAPIController extends BaseController {

	private $userBO;
	private $operations;

	public function beforeRoute() {
		if (null == $this->f3->get('SESSION.user'))
			$this->f3->reroute('@user_login');
	}

	public function __construct() {
		parent::__construct();
		$this->userBO = new UserBO();
		$this->operations 	= new OperationHandler();

		$this->f3->set('navbar', 'views/dashboard/template-parts/navbar.htm');
	}

	public function renderSearchPage() {
		$this->f3->set('content', 'views/dashboard/search-view.htm');
		echo $this->view->render('layout.htm');
	}

	public function searchProduct() {
		$userSearch = array(
			'userId'		=>	$this->f3->get('SESSION.user')->getUserId(),
			'responseGroup'	=>	array('Images', 'Medium'),
			'searchQuery'	=>	$this->f3->get('QUERY'),
			'userSearchTerm'=>	$this->f3->get('GET.userSearchTerm'),
			'page'			=> 	null == $this->f3->get('GET.page') ? 1: $this->f3->get('GET.page')
			); 	

		$data = $this->userBO->searchByTitle($userSearch);

		$metaInfo = array(
			"totalPages"	=> $data->Items->TotalPages,
			"searchQuery"	=> $userSearch['searchQuery'],
			"userSearchTerm"=> $userSearch['userSearchTerm'],
			"totalResults"	=> $data->Items->TotalResults,
			"page"			=> $userSearch['page']
			);
		$this->f3->set('metaInfo', $metaInfo);
		$this->f3->set('items', $data->Items);
		$this->f3->set('content', 'views/dashboard/index.htm');
		echo $this->view->render('layout.htm');
	}

	public function refresh() {
		$searchQuery 	= $this->f3->get('QUERY');
		$userId 		= $this->f3->get('SESSION.user')->getUserId();
		$this->userBO->deleteUserSearchData($userId);
		$this->f3->reroute('@search_product?' . $searchQuery);
	}

}
