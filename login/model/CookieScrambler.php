<?php

namespace login\model;

// Encrypts/Decrypts a string of characters
class CookieScrambler {
	
	// The encryption/decryption key
	private static $key = 'DettaVarInteLätt';

	// Encrypt and return the supplied string 
	public function encryptCookie($cookieValue) {
		return mcrypt_encrypt(MCRYPT_BLOWFISH, self::$key, $cookieValue, MCRYPT_MODE_ECB);
	}

	// Decrypt and return the supplied string 
	public function decryptCookie($cookieValue) {
		return mcrypt_decrypt(MCRYPT_BLOWFISH, self::$key, $cookieValue, MCRYPT_MODE_ECB);
	}
}