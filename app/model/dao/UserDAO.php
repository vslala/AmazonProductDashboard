<?php

namespace Model\DAO;

use Model\BaseModel;
use Model\VO\UserVO;

class UserDAO extends BaseModel {

	public function __construct() {
		parent::__construct();
	}

	public function persistUser($user) {
		$this->db->exec(
			"INSERT INTO bma_affiliates 
				(first_name, last_name, email, username, password, associate_tag, mobile, active, created_at, updated_at)
					VALUES (:firstName, :lastName, :email, :username, :password, :associateTag, :mobile, :active, :createdAt, :updatedAt)",
			array (
				':firstName' 	=> 	$user->getFirstName(),
				':lastName'		=>	$user->getLastName(),
				':email'		=> 	$user->getEmail(),
				':username'		=>	$user->getUsername(),
				':password'		=> 	$user->getPassword(),
				':associateTag'	=>	$user->getAssociateTag(),
				':mobile'		=> 	$user->getMobile(),
				':active'		=> 	$user->isActive(),
				':createdAt'	=>	$user->getCreatedAt(),
				':updatedAt'	=> 	$user->getUpdatedAt()
				)
			);
		$lastInsertId = $this->db->lastInsertId();
		echo $lastInsertId;
	}

	public function getUserByUsername($user) {
		$userFromDB = $this->db->exec('SELECT id as user_id, first_name, last_name, username, associate_tag, password, email, mobile, active, created_at, updated_at FROM bma_affiliates WHERE username = :username', 
			array(':username' => $user->getUsername())
			);
		return $this->mapUserFromDB($userFromDB);
	}

	private function mapUserFromDB($userFromDB) {
		$userFromDB = $userFromDB[0];
		$user = new UserVO();
		$user->setUserId($userFromDB['user_id']);
		$user->setFirstName($userFromDB['firstName']);
		$user->setLastName($userFromDB['lastName']);
		$user->setUsername($userFromDB['username']);
		$user->setPassword($userFromDB['password'], true);
		$user->setAssociateTag($userFromDB['associate_tag']);
		$user->setEmail($userFromDB['email']);
		$user->setMobile($userFromDB['mobile']);
		$user->setActive($userFromDB['active']);
		$user->setCreatedAt($userFromDB['created_at']);
		$user->setUpdatedAt($userFromDB['updated_at']);
		return $user;
	}

	public function fetchUserSearchResultFromDatabase($userId, $userSearchQuery, $responseGroup) {
		return $this->db->exec('SELECT dump FROM bma_affiliates_search WHERE user_id=:userId AND search_query=:searchQuery AND response_group=:resGrp',
			array(
				':userId'		=> $userId,
				':searchQuery'	=> $userSearchQuery,
				':resGrp'		=> $responseGroup
				)
			);
	}

	public function deleteUserSearchData($userId) {
		return $this->db->exec('DELETE FROM bma_affiliates_search WHERE user_id=:userId', 
			array(':userId' => $userId));
	}

	public function saveSearchResult($userId, $searchQuery, $responseGroup, $searchResult) {
		$this->db->exec('INSERT INTO bma_affiliates_search (user_id, search_query, response_group, dump) VALUES (:userId, :searchQuery, :resGrp, :searchResult) ',
			array(
				':userId'		=> $userId,
				':searchQuery'	=> $searchQuery,
				':resGrp'		=> $responseGroup,
				':searchResult'	=> $searchResult
				)
			);
	}

	
}