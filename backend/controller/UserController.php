<?php
session_start();
require_once('./backend/model/UserModel.php');
require_once('Controller.php');

class UserController extends Controller
{
    function inscription($email, $password)
    {
        // check input 
        // sanitize
        // hash password
        $query = new UserModel();
        $response = $query->queryInscription($email, $password);
    }
    /**
     * checks for errors in email and password inputs, sanitizes
     * the inputs, and then checks the connection using a UserModel before loading the connection view.
     */
    function queryCheckConnection($email, $password)
    {
        $result = false;

        $emailError =  false;
        $passwordError = false;
        $requiredField = '*champ requis';

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


        if (!$emailError & !$passwordError) {
            $query = new UserModel();
            $result = $query->connection($email, $password);
        }

        return $result;
    }
}
