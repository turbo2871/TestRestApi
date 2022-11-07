<?php

require_once PROJECT_ROOT_PATH."/Model/Database.php";

class UserModel extends Database
{
    public function getUsers($limit)
    {
        $paramsArr = array(
            array('param' => 1, 'value' => $limit),
        );

        return $this->select("SELECT * FROM users ORDER BY id ASC LIMIT ?", $paramsArr);
    }

    public function getUser($userDataObj, $limit)
    {
        $paramsArr = array(
            array('param' => 1, 'value' => $userDataObj->username),
            array('param' => 2, 'value' => $userDataObj->password),
            array('param' => 3, 'value' => $limit),
        );

        return $this->select("SELECT * FROM users WHERE username = ? AND password = ? LIMIT ?", $paramsArr);
    }

    public function getUserByEmail($userDataObj, $limit)
    {
        $paramsArr = array(
            array('param' => 1, 'value' => $userDataObj->username),
            array('param' => 2, 'value' => $userDataObj->user_email),
            array('param' => 3, 'value' => $limit),
        );

        return $this->select("SELECT * FROM users WHERE username = ? AND user_email = ? LIMIT ?", $paramsArr);
    }

    public function registerUser($userDataObj)
    {
        $paramsArr = array(
            array('param' => 1, 'value' => $userDataObj->username),
            array('param' => 2, 'value' => $userDataObj->password),
            array('param' => 3, 'value' => $userDataObj->user_email),
        );

        return $this->insert("INSERT INTO users (username, password, user_email) VALUES (? ,? ,?)", $paramsArr);
    }
}