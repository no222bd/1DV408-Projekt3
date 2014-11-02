<?php

namespace login\view;

// Handles the user feedback
class Message {

	private $currentMessage;
	
	// Check if message exists
	public function hasMessage() {
		return isset($this->currentMessage);
	}

	public function saveMessage($message) {
		$this->currentMessage = $message;
	}

	public function getMessage() {
		$output = $this->currentMessage;
			
		unset($this->currentMessage);

		return $output;
	}
}