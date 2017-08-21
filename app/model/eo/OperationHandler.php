<?php

namespace Model\EO;

use \Base;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\Operations\Lookup;
use ApaiIO\ApaiIO;
use Util\ProductAPIExtension;

class OperationHandler {

	private $f3;

	protected $apaiIO;

	public function __construct() {
		$this->f3 = Base::instance();

		$conf = new GenericConfiguration();
		$client = new \GuzzleHttp\Client();
		$request = new \ApaiIO\Request\GuzzleRequest($client);

		$conf
		    ->setCountry($this->f3->get(COUNTRY_DEFAULT))
		    ->setAccessKey($this->f3->get(AWS_API_KEY))
		    ->setSecretKey($this->f3->get(AWS_API_SECRET_KEY))
		    ->setAssociateTag($this->f3->get(AWS_ASSOCIATE_TAG))
		    ->setRequest($request);
		$this->apaiIO = new ApaiIO($conf);
	}

	public function searchByTitle($title) {
		$search = new Search();
		$search->setCategory('All');
		$search->setKeywords($title);
		$formattedResponse = $this->apaiIO->runOperation($search);
		return simplexml_load_string($formattedResponse);
	}

	

	
}