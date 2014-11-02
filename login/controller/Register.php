<?php

namespace login\controller;

require_once('login/model/Register.php');
require_once('login/view/Register.php');

class Register {
	
	private $view;
	private $model;

	public function __construct() {
		$this->model = new \login\model\Register();
		$this->view = new \login\view\Register();
	}

	public function doRegister() {
		
		// If user has pressed the Register-button

		if($this->view->wantToRegister()) {
			
			// Get user input from the view

			$username = $this->view->getUsername();
			$password = $this->view->getPassword();
			$repeatedPassword = $this->view->getRepeatedPassword();

			if(strlen($username) > 2) {

				if(strlen($password) > 2) {

					if($this->model->usernameAvalible($username)) {
					
						if($this->model->passwordsMatch($password, $repeatedPassword)) {

							$this->model->registerUser($username, $password);
							
							$this->view->getLoginPage();

						} else {
							$this->view->setNonMatchingPasswordsMessage();
						}
					} else {
						$this->view->setUsernameOccupiedMessage();
					}
				} else {
					$this->view->setTooShortPasswordMessage();
				}
			} else {
				$this->view->setTooShortUsernameMessage();
			}
		}

		return $this->view->getRegisterHTML();
	}
}



						// Skriv ut meddelande om lyckad registrering
						// Spara användarnamn
						// \view\SharedView::saveNewUserName($this->registerView->getUsername());
						// \view\SharedView::activateSuccessMessage();