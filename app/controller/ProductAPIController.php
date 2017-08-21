<?php

namespace Controller;

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\Operations\Lookup;
use ApaiIO\ApaiIO;
use Util\ProductAPIExtension;


class ProductAPIController extends BaseController {

	public function __construct() {
		parent::__construct();

		if (empty($this->f3->get('SESSION.user')))
			$this->f3->reroute('@user_login');
	}

	public function renderSearchPage() {
		$this->f3->set('content', 'views/dashboard/search-view.htm');
		echo $this->view->render('layout.htm');
	}

	public function searchProduct() {

		$keywords = $this->f3->get('POST.searchQuery');
		$category = 'DVD';
		$actor    = 'Bruce Willis';

		$conf = new GenericConfiguration();
		$client = new \GuzzleHttp\Client();
		$request = new \ApaiIO\Request\GuzzleRequest($client);

		$conf
		    ->setCountry($this->f3->get(COUNTRY_DEFAULT))
		    ->setAccessKey($this->f3->get(AWS_API_KEY))
		    ->setSecretKey($this->f3->get(AWS_API_SECRET_KEY))
		    ->setAssociateTag($this->f3->get(AWS_ASSOCIATE_TAG))
		    ->setRequest($request);
		$apaiIO = new ApaiIO($conf);

		$search = new Search();
		$search->setCategory($category);
		$search->setActor($actor);
		$search->setKeywords($keywords);
		$search->setResponseGroup(array('Large'));

		$customProductAPI = new ProductAPIExtension();
		$customProductAPI->setTitle($keywords);
		$customProductAPI->setResponseGroup(array('Large'));
		$customProductAPI->setCategory('All');

		$formattedResponse = $apaiIO->runOperation($customProductAPI);

		$data = simplexml_load_string($formattedResponse);
		// echo '<pre>' . var_export($data, true) . '</pre>';
		// echo '<pre>' . var_export($data->Items->Item[0]) . '</pre>';
		foreach ($data->Items->Item as $item) {
			var_dump($item->ItemAttributes->Title);
		}
		// var_dump($formattedResponse);
	}

}
