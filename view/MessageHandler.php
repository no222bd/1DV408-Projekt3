<?php

namespace view;

class MessageHandler {

    private static $MESSAGE = 'message';

    public function hasMessage() {
        return !empty($_COOKIE[self::$MESSAGE]);
    }

    public function setMessage($message) {
        setcookie(self::$MESSAGE, $message, 0);
    }

    public function getMessage() {
        $output = $_COOKIE[self::$MESSAGE];

        $this->removeMessage();

        return $output;
    }

    public function removeMessage() {
        setcookie(self::$MESSAGE, '', time() - 1);
    }
}
