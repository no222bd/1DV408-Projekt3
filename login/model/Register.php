<?php

namespace login\model;

require_once('model/User.php');
require_once('model/UserDAL.php');

class Register {

	private $userDAL;

	public function __construct() {
		$this->userDAL = new \model\UserDAL();
	}

	public function passwordsMatch($password, $repeatedPassword) {

		if($password !== $repeatedPassword)
			return false;
		else
			return true;
	}

	public function usernameAvalible($username) {

		$existingUsernames = $this->userDAL->getArrayOfUsernames();

		foreach ($existingUsernames as $existing) {
			if(strtolower($existing) == strtolower($username))
				return false;
		}

		return true;
	}

	public function registerUser($username, $password) {

		$user = new \model\User($username, $password);
		$this->userDAL->saveUser($user);
	}
}