<?php

namespace login\model;

require_once('model/UserDAL.php');

class LoginModel {

	private static $userIdLocation = 'userId';
	private static $usernameLocation = 'username';
	private static $passwordLocation = 'password';
	private static $loginStatusLocation = 'loggedIn';

	// Check if the supplied credentials are valid

	public function checkCredentials(array $credentials) {

		$userDAL = new \model\UserDAL();
		$users = $userDAL->getUsers();

		$username = $credentials[0];
		$password = $credentials[1];

		foreach ($users as $user) {
			if($user->getUsername() == $username && $user->getPassword() == $password) {
				// \view\QuizzyMaster::saveUserId($user->getUserId());
				// \view\QuizzyMaster::saveUsername($user->getUsername());
				$_SESSION[self::$userIdLocation] = $user->getUserId();
				$_SESSION[self::$usernameLocation] = $username;
				$_SESSION[self::$loginStatusLocation] = true;
				return true;
			}
		}

		return false;
	}

	// Returns the loggedin user
	public function getLoggedInUser() {
		if(isset($_SESSION[self::$userIdLocation])) {
			$userDAL = new \model\UserDAL();
			return $userDAL->getUserById($_SESSION[self::$userIdLocation]);
		}
	}

	// Set the loginststatus to logged out 
	public function setStatusToLogout() {
		$_SESSION[self::$loginStatusLocation] = false;
	}

	// Check if the user is logged in or not
	public function isLoggedIn() {
		if(isset($_SESSION[self::$loginStatusLocation]))
			return $_SESSION[self::$loginStatusLocation];
		else
			return false;
	}

	// Return the username of current user
	public function getUsername() {
		return $_SESSION[self::$usernameLocation];
	}
}