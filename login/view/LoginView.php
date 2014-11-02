<?php

namespace login\view;

require_once('login/view/Message.php');

class LoginView {

	private $loginModel;
	private $message;

	// $_POST[] locations
	private static $usernameLocation = 'username';
	private static $passwordLocation = 'password';
	private static $loginLocation = 'login';
	private static $logoutLocation = 'logout';
	private static $rememberMeLocation = 'rememberMe';

	public function __construct($loginModel) { //login\model\LoginModel
		$this->loginModel = $loginModel;
		$this->message = new \login\view\Message();
	}

	// Check if user pressed the Login-button 
	public function didUserLogin() {
		return isset($_POST[self::$loginLocation]);
	}

	// Check if user pressed the Logout-button
	public function didUserLogout() {
		return isset($_POST[self::$logoutLocation]);
	}

	// Check if user has supplied enough data
	public function hasValidInput() {
		if(!empty($_POST[self::$usernameLocation]) && !empty($_POST[self::$passwordLocation])) {
			return true;
		} elseif(empty($_POST[self::$usernameLocation])) {
			$this->message->saveMessage('Användarnamn saknas');
		} else {
			$this->message->saveMessage('Lösenord saknas');
		}
	}
	
	public function getCredentials() {
		return array($_POST[self::$usernameLocation], $_POST[self::$passwordLocation]);
	}

	public function setRemberMeLoginMessage() {
		$this->message->saveMessage('Inloggning lyckades och vi kommer ihåg dig nästa gång');
	}

	public function setLoginMessage() {
		$this->message->saveMessage('Inloggning lyckades');
	}

	public function setFailMessage() {
		$this->message->saveMessage('Felaktigt användarnamn och/eller lösenord');
	}

	public function setLogoutMessage() {
		$this->message->saveMessage('Du har nu loggat ut');
	}

	public function setCookieLoginMessage() {
		$this->message->saveMessage('Inloggning lyckades via cookies');
	}

	public function setFaultyCookieMessage() {
		$this->message->saveMessage('Felaktig information i cookie');
	}

	// Check if user wants to be remembered
	public function doRememberMe() {
		return isset($_POST[self::$rememberMeLocation]);
	}
	
	public function getUserAgent() {
		return $_SERVER['HTTP_USER_AGENT'];
	}

	// Login HTML
	public function getLoginHTML() {
		$output = '
			<h2>Frågesport für alle!</h2>		
			<div class="login">									
			<form id="authentication" action="' . $_SERVER['PHP_SELF'] .' " method="post">';

		if($this->message->hasMessage())
			$output .= '<p class="message">' . $this->message->getMessage() . '</p>';				

		$output .= '
					<label>Användarnamn</label>
						<input type="text" name="' . self::$usernameLocation . '"';

		if(isset($_POST[self::$usernameLocation]))
			$output .= ' value="' . $_POST[self::$usernameLocation] . '"';

		$output .= '/>
					
					
					<label>Lösenord</label>
						<input type="password" name="' . self::$passwordLocation . '"/>
					
					<div>
					<label>Håll mig inloggad</label>
						<input type="checkbox" name="' . self::$rememberMeLocation . '"/>
						</div>
					
			
					<a href="?action=register" class="linkbutton">Registrera</a>
					<input type="submit" name="' . self::$loginLocation . '" value="Logga in"/>

				</form>
			</div>';
			
		
		return $output;
	}

	// // Logout HTML
	// public function getLogoutHTML() {
	// 	$output = '<h2>' . $this->loginModel->getUsername() . ' är inloggad</h2>';

	// 	$output .= '
	// 		<form action="' . $_SERVER['PHP_SELF'] .' " method="post">';
				
	// 	if($this->message->hasMessage())
	// 		$output .= '<p>' . $this->message->getMessage() . '</p>';	

	// 	$output .= '
	// 			<input type="submit" name="' . self::$logoutLocation . '" value="Logga ut"/>
	// 		</form>';

	// 	return $output;
	// }
}