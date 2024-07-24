<?php
session_start();
require_once('./model/UserModel.php');
require_once('Controller.php');

class UserController extends Controller
{
    function inscription($email, $password, $confirmPass)
    {
        if ($this->rulesData($email, $password, $confirmPass)) {
            // password hash
            $password = password_hash($password, PASSWORD_DEFAULT);

            $query = new UserModel();
            return $query->queryInscription($email, $password);
        };
    }

    function rulesData($email, $password, $confirmPass)
    {
        $emailError =  false;
        $passwordError = false;
        $confirmPassError = false;

        if (!empty($email)) {
            if (preg_match("/^[a-zA-Z]+[0-9a-zA-Z]*[.]*[0-9a-zA-Z]*(@)[a-z0-9A-Z.-]+[.]+([a-zA-Z]{2,})$/", $email)) {
                $email = $this->sanitize($email);
            }
        } else {
            $emailError = true;
        }
        if (!empty($password)) {
            if (preg_match("/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[@&#{([-|_\\)\]=}%?\/]).{12,}$/", $password)) {
                $password = $this->sanitize($password);
            }
        } else {
            $passwordError = true;
        }
        if ($password != $confirmPass) {
            $confirmPassError = true;
        }

        return !$emailError & !$passwordError & !$confirmPassError;
    }
}
