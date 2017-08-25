<?php

namespace Model\VO;

class UserVO {
	private $userId;
	private $firstName;
	private $lastName;
	private $email;
	private $username;
	private $password;
	private $associateTag;
	private $mobile;
	private $active;
	private $createdAt;
	private $updatedAt;

	public function setUserId($userId) { $this->userId = $userId; }
	public function getUserId() { return $this->userId; }

	public function setFirstName($firstName) { $this->firstName = $firstName;}
	public function getFirstName() { return $this->firstName; }

	public function setLastName($lastName) { $this->lastName = $lastName; }
	public function getLastName() { return $this->lastName; }

	public function setEmail($email) { $this->email = $email; }
	public function getEmail() { return $this->email; }

	public function setUsername($username) { 
		$this->username = $username; 
	}
	public function getUsername() { return $this->username; }

	public function setPassword($password, $isEncrypt = false) { 
		if ($isEncrypt) 
			$this->password = $password;
		else 
			$this->password = md5($password);
	}
	public function getPassword() { return $this->password; }

	public function setAssociateTag($associateTag) { $this->associateTag = $associateTag; }
	public function getAssociateTag() { return $this->associateTag; }

	public function setMobile($mobile) { $this->mobile = $mobile; }
	public function getMobile() { return $this->mobile; }

	public function setActive($active) { $this->active = $active; }
	public function isActive() { return $this->active; }

	public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
	public function getCreatedAt() { return $this->createdAt; }

	public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
	public function getUpdatedAt() { return $this->updatedAt; }

}