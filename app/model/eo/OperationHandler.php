<?php

namespace Model\EO;

use \Base;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\Operations\Lookup;
use ApaiIO\ApaiIO;
use Util\ProductAPIExtension;
use Model\DAO\UserDAO;

class OperationHandler {

	private $f3;
	private $userDAO;

	protected $apaiIO;

	public function __construct() {
		$this->f3 = Base::instance();
		$this->userDAO = new UserDAO();

		$conf = new GenericConfiguration();
		$client = new \GuzzleHttp\Client();
		$request = new \ApaiIO\Request\GuzzleRequest($client);

		$conf
		    ->setCountry($this->f3->get(COUNTRY_DEFAULT))
		    ->setAccessKey($this->f3->get(AWS_API_KEY))
		    ->setSecretKey($this->f3->get(AWS_API_SECRET_KEY))
		    ->setAssociateTag(trim($this->f3->get('SESSION.user')->getAssociateTag()))
		    ->setRequest($request);
		$this->apaiIO = new ApaiIO($conf);
	}

	public function searchByTitle($title, $resGrp = array('Images', 'Medium'), $page = 1) {
		$search = new Search();
		$search->setCategory('All');
		$search->setKeywords($title);
		$search->setResponseGroup($resGrp);
		$search->setPage($page);
		$formattedResponse = $this->apaiIO->runOperation($search);
		return $formattedResponse;
	}
	
}