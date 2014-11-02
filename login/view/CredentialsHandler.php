<?php

namespace login\view;

require_once('login/model/CookieScrambler.php');

// Handles the user credentials cookies
class CredentialsHandler {

	// $_COOKIE[] keys
	private static $usernameLocation = 'username';
	private static $passwordLocation = 'password';
	private $cookieScrambler;	

	public function __construct() {
		$this->cookieScrambler = new \login\model\CookieScrambler();
	}

	// Check if cookie exists for botj username and password
	public function cookieExist() {
		return !empty($_COOKIE[self::$usernameLocation]) && !empty($_COOKIE[self::$passwordLocation]);
	}

	// Save username and password in cookies and save timestamp on file
	public function saveCredentials(array $userCredentials) {
		$filename = $userCredentials[0] . '.txt';

		$endTime = time() + 60;
		file_put_contents($filename, $endTime);

		setcookie(self::$usernameLocation, $userCredentials[0], $endTime);
		setcookie(self::$passwordLocation, $this->cookieScrambler->encryptCookie($userCredentials[1]), $endTime);
	}

	// Check that cookie not being used past it endtime
	public function isValidCookie() {
		$filename = $this->getUsername() . '.txt';
			
		if(@file_get_contents($filename) == false)
			return false;
		else 
			return time() < file_get_contents($filename);
	}

	public function getUsername() {
		return $_COOKIE[self::$usernameLocation];
	}

	public function getCredentials() {
		return array($_COOKIE[self::$usernameLocation], $this->cookieScrambler->decryptCookie($_COOKIE[self::$passwordLocation]));
	}

	// Remove cookies for username and password
	public function clearCredentials() {
		setcookie(self::$usernameLocation, '', time() - 1);
		setcookie(self::$passwordLocation, '', time() - 1);
	}
}