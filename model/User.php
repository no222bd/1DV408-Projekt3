<?php

namespace model;

class User {

    private $userId;
    private $username;
    private $password;
    private $isAdmin;

    public function __construct($username, $password, $isAdmin = false) {
        $this->username = $username;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
    }

    public function equals(\model\User $otherUser) {
        if ($this->username !== $otherUser->username)
            return false;

        if ($this->password !== $otherUser->password)
            return false;

        return true;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getIsAdmin() {
        if ($this->isAdmin == 1)
            return true;
        else
            return false;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    // Hitta och byr till ovan getIsAdmin
    public function isAdministrator() {
        return $this->isAdmin;
    }
}
