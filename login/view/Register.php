<?php

namespace login\view;

class Register {
	
	private static $registerButton = 'registerButton';
	private static $username = 'username';
	private static $password = 'password';
	private static $repeatedPassword = 'repeatedPassword';

	private $message;

	public function wantToRegister(){
		return isset($_POST[self::$registerButton]);
	}

	public function getUsername(){
		return $_POST[self::$username];
	}

	public function getPassword(){
		return $_POST[self::$password];
	}

	public function getRepeatedPassword(){
		return $_POST[self::$repeatedPassword];
	}

	public function getLoginPage() {
		header('Location: ' . $_SERVER['PHP_SELF']);
	}

	public function setNonMatchingPasswordsMessage() {
		$this->message = 'Lösenorden matchar inte';
	}

	public function setUsernameOccupiedMessage() {
		$this->message = 'Användarnamnet är upptaget';
	}

	public function setTooShortUsernameMessage() {
		$this->message = 'Användarnamnet skall vara minst 6 tecken';
	}

	public function setTooShortPasswordMessage() {
		$this->message = 'Lösenordet skall vara minst 6 tecken';
	}

	public function getRegisterHTML() {
		
		$html = '<h2>Registrera användare</h2>
				
				<div class="login">

				<form method="post">';		//<!-- STRÄNGBEROENDE!!! -->
		
		if(isset($this->message))
			$html .= '<p class="message">' . $this->message . '</p>';
		
		$html .= '<label>Namn
					 	<input type="text" name="' . self::$username . '"';

		// Set value of username input tag
		if(isset($_POST[self::$username]))
			$html .= ' value="' . strip_tags($_POST[self::$username]) . '"';

		//echo $_SERVER['PHP_SELF'] . 'hej';

		$html .= '/>
					</label>
					<label>Lösenord
 						<input type="password" name="' . self::$password . '"/>
					</label>
					<label>Repetera lösenord
						<input type="password" name="' .self::$repeatedPassword. '"/>
					</label>

					<a href="' . \Settings::$ROOT_PATH . '">Tillbaka</a>
					<input type="submit" name="' . self::$registerButton . '" value="Registrera"/>
				</form>
			</div>';
		
		return $html;
	}
}