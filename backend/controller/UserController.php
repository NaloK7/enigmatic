<?php
session_start();
require_once('./model/UserModel.php');
require_once('Controller.php');

class UserController extends Controller
{
    function inscription($email, $password)
    {
        if ($this->rulesData($email, $password)) {
            // password hash
            $password = password_hash($password, PASSWORD_DEFAULT);

            $query = new UserModel();
            $response = $query->queryInscription($email, $password);
        } else {
            // todo set proper code
            $response = ["status" => 400];
        }
        echo json_encode($response);
    }

    function rulesData($email, $password)
    {
        $valid =  true;

        if (!empty($email)) {
            if (preg_match("/^[a-zA-Z]+[0-9a-zA-Z]*[.]*[0-9a-zA-Z]*(@)[a-z0-9A-Z.-]+[.]+([a-zA-Z]{2,})$/", $email)) {
                $email = $this->sanitize($email);
            }
        } else {
            $valid = false;
        }
        if (!empty($password)) {
            if (preg_match("/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[@&#{([-|_\\)\]=}%?\/]).{12,}$/", $password)) {
                $password = $this->sanitize($password);
            }
        } else {
            $valid = false;
        }

        return $valid;
    }

    function login($email, $password)
    {
        if ($this->rulesData($email, $password)) {

            $query = new UserModel();
            $response = $query->queryLogin($email, $password);
        } else {
            // todo set proper code
            $response = ["status" => 401];
        }
        echo json_encode($response);
    }
}
