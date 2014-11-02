<?php

namespace login\model;

// Handles client identification data
class UserControl {

	private static $sessionLocation = 'userAgent';

	// Saves and compares the supplied UserAgent-string
	public function checkUserAgent($userAgentString) {
		
		// Save the UserAgent-string
		if(!isset($_SESSION[self::$sessionLocation])) {
			$_SESSION[self::$sessionLocation] = $userAgentString;
			return true;
		}

		// Compare saved and supplied UserAgent-string
		return $_SESSION[self::$sessionLocation] == $userAgentString;
	}
}