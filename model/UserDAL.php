<?php

namespace model;

require_once('model/SuperDAL.php');
require_once('model/User.php');


class UserDAL extends \model\SuperDAL {

    public function makeAdminById($userId) {

        $this->connectToDB();

        $sql = 'UPDATE ' . self::$tableName . '
                SET ' . self::$isAdminField . '= TRUE
                WHERE ' . self::$userIdField . '= :user_Id';

        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute(array('user_Id' => $userId));
    }

    public function deleteUserById($userId) {

        $this->connectToDB();

        $sql = 'DELETE 
                FROM ' . self::$tableName . '
                WHERE ' . self::$userIdField . '= :user_Id';

        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute(array('user_Id' => $userId));
    }

    public function getUserById($userId) {

        $this->connectToDB();

        $sql = 'SELECT *
                FROM ' . self::$tableName . '
                WHERE ' . self::$userIdField . ' = :user_Id';

        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute(array('user_Id' => $userId));

        $result = $stmt->fetch();

        $user = new \model\User($result[self::$usernameField], $result[self::$passwordField], $result[self::$isAdminField]);
        $user->setUserId($result[self::$userIdField]);
        //$user->setUserType();

        return $user;
    }

    public function getUsers() {

        $this->connectToDB();

        $sql = 'SELECT *
                FROM ' . self::$tableName;

        $stmt = $this->dbConnection->query($sql);

        $users = array();

        while ($row = $stmt->fetch()) {
            $user = new \model\User($row[self::$usernameField], $row[self::$passwordField], $row[self::$isAdminField]);
            $user->setUserId($row[self::$userIdField]);
            //$user->setUserType($row[self::$userTypeIdField]);

            $users[] = $user;
        }

        return $users;
    }

    public function getUsersOnly() {

        $this->connectToDB();

        $sql = 'SELECT *
                FROM ' . self::$tableName . '
                WHERE ' . self::$isAdminField . '= :is_Admin';

        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute(array(
            'is_Admin' => 'FALSE')
        );

        $users = array();

        while ($row = $stmt->fetch()) {
            $user = new \model\User($row[self::$usernameField], $row[self::$passwordField], $row[self::$isAdminField]);
            $user->setUserId($row[self::$userIdField]);
            //$user->setUserType($row[self::$userTypeIdField]);

            $users[] = $user;
        }

        return $users;
    }

    public function getArrayOfUsernames() {

        $this->connectToDB();

        // Ändra så att endast usernames hämtas(används i \login\model\register)

        $sql = 'SELECT *
                FROM ' . self::$tableName;

        $stmt = $this->dbConnection->query($sql);

        $usernames = array();

        while ($row = $stmt->fetch()) {
            $usernames[] = $row[self::$usernameField];
        }

        return $usernames;
    }

    public function getUsersDataArray() {

        $this->connectToDB();

        $sql = 'SELECT *
                FROM ' . self::$tableName;

        $stmt = $this->dbConnection->query($sql);

        $users = array();

        while ($row = $stmt->fetch()) {
            $users[$row[self::$usernameField]] = $row[self::$passwordField];
        }

        return $users;
    }

    public function saveUser(\model\User $user) {

        $this->connectToDB();

        $sql = 'INSERT INTO ' . self::$tableName . ' (' . self::$usernameField . ', ' . self::$passwordField . ', ' . self::$isAdminField . ') 
                VALUES (:username, :password, :is_Admin)';

        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute(array(
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'is_Admin' => $user->getIsAdmin())
        );
    }
}
