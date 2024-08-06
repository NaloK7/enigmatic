<?php
// session_start();
require_once('./model/UserModel.php');
require_once('Controller.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends Controller
{
    // new UserModel()
    public $query;

    public function __construct()
    {
        $this->query = new UserModel();
    }

    function inscription($email, $password)
    {
        if ($this->rulesData($email, $password)) {
            // password hash
            $password = password_hash($password, PASSWORD_DEFAULT);

            $response = $this->query->queryInscription($email, $password);
        } else {
            // Bad Request
            http_response_code(400);
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
            $response = $this->query->queryLogin($email, $password);
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
    }

    function solvedBy($riddleId, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        if ($userId) {
            $response = $this->query->querySolvedBy($riddleId, $userId);
        } else {
            // Bad Request
            http_response_code(400);
        }
    }
    function lockBook($bookId, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        $currentDate = new DateTime();
        $expiration = $currentDate->modify('+1 month');


        if ($userId) {
            $this->query->queryLockBook($bookId, $userId, $expiration);
        } else {
            // Bad Request
            http_response_code(400);
        }
    }
}
