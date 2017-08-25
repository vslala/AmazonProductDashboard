<?php

namespace Model\BO;

use Model\dao\UserDAO;
use Model\VO\UserVO;
use Model\EO\OperationHandler;
use Util\Logger;

class UserBO {

	private $userDAO;
	private $operations;

	public function __construct() {
		$this->userDAO = new UserDAO();
	}

	public function saveUser($params) {
		$user = $this->buildUserVO($params);
		$this->userDAO->persistUser($user);
	}

	public function authUser($params) {
		$user = new UserVO();
		$validUser = new UserVO();
		$user->setUsername($params['username']);
		$user->setPassword($params['password']);
		$userFromDB = $this->userDAO->getUserByUsername($user);

		if (null != $userFromDB) {
			$validUser = $userFromDB;
		}
		return $validUser;
	}

	private function buildUserVO($params) {		
		$firstName 		=	explode(' ', $params['fullName'])[0];
		$lastName 		=	explode(' ', $params['fullName'])[1];
		$email			= 	$params['email'];
		$username		= 	$params['username'];
		$password		= 	md5($params['password']);
		$associateTag	=	$username . '-21';
		$mobile			=	$params['mobile'];
		$active			=	true;
		$createdAt		=	date('Y-m-d H:i:s');
		$updatedAt		=	$createdAt;

		$user = new UserVO();
		$user->setFirstName($firstName);
		$user->setLastName($lastName);
		$user->setEmail($email);
		$user->setUsername($username);
		$user->setPassword($password);
		$user->setAssociateTag($associateTag);
		$user->setMobile($mobile);
		$user->setActive($active);
		$user->setCreatedAt($createdAt);
		$user->setUpdatedAt($updatedAt);
		return $user;
	}

	private function validateUser($userFromDB, $user) {
		return $userFromDB->getPassword() == md5($user->getPassword());
	}

	public function searchByTitle($userSearch) {
		$this->operations = new OperationHandler();
		$data = $this->userDAO->fetchUserSearchResultFromDatabase($userSearch['userId'], $userSearch['searchQuery'], json_encode($userSearch['responseGroup']));

		if (null == $data) {
			$data = $this->operations->searchByTitle($userSearch['userSearchTerm'], $userSearch['responseGroup'], $userSearch['page']);
			Logger::write("Saving the data into cache!");
			$this->userDAO->saveSearchResult($userSearch['userId'], $userSearch['searchQuery'], json_encode($userSearch['responseGroup']), $data);

			return $this->convertSimpleXMLDocToObjectArray($data);
		}
		
		if ($this->isValidData($data))
			return $this->convertSimpleXMLDocToObjectArray($data);
		/*
			if result from db is null or invalid
			then delete invalid data in db
			then send service request to amazon to fetch data 
			then persist it in database
		*/
		if (! $this->isValidData($data)) {
			Logger::write("Found Bad Request From Database!");
			Logger::write("Deleting Bad Data From Database");
			$this->userDAO->deleteUserSearchData($userSearch['userId']);
			Logger::write("Making Amazon Service Call");
			$data = $this->operations->searchByTitle($userSearch['userSearchTerm'], $userSearch['responseGroup'], $userSearch['page']);
		}

		/*
			if service data in invalid
			then abort operation
		*/
		if (! $this->isValidData($data)) {
			Logger::write("Invalid Data From Amazon Service");
			$data = null;
			return $data;
		}
		
		Logger::write("Saving the data into cache!");
		$this->userDAO->saveSearchResult($userSearch['userId'], $userSearch['searchQuery'], json_encode($userSearch['responseGroup']), $data);

		return $this->convertSimpleXMLDocToObjectArray($data);
	}

	private function convertSimpleXMLDocToObjectArray($xmlStr) {
		$xmlStr = is_array($xmlStr) ? $xmlStr[0]['dump'] : $xmlStr;
		$simpleXMLStr = simplexml_load_string($xmlStr);
		return json_decode(json_encode((array) $simpleXMLStr));
	}

	private function isValidData($data) {
		if (null == $data)
			return false;
		if (is_array($data)) {
			Logger::write("Request: " . $this->convertSimpleXMLDocToObjectArray($data[0]['dump'])->Items->Request->IsValid);
			return $this->convertSimpleXMLDocToObjectArray($data[0]['dump'])->Items->Request->IsValid == 'True';
		}
		Logger::write("Request: " . $this->convertSimpleXMLDocToObjectArray($data)->Items->Request->IsValid);
		return $this->convertSimpleXMLDocToObjectArray($data)->Items->Request->IsValid == 'True';		
	}

	public function deleteUserSearchData($userId) {
		return $this->userDAO->deleteUserSearchData($userId);
	}
}