<?php

namespace Model\BO;

use Model\dao\UserDAO;
use Model\VO\UserVO;

class UserBO {

	private $userDAO;

	public function __construct() {
		$this->userDAO = new UserDAO();
	}

	public function saveUser($params) {
		$user = $this->buildUserVO($params);
		$this->userDAO->persistUser($user);
	}

	public function authUser($params) {
		$user = new UserVO();
		$validUser = null;
		$user->setUsername($params['username']);
		$user->setPassword($params['password']);
		$userFromDB = $this->userDAO->getUserByUsername($user);

		if (!empty($userFromDB)) {
			$validUser = $this->validateUser($userFromDB, $user) ? $userFromDB : '';
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
}