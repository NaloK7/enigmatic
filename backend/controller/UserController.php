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

    /**
     * Validates the email according to predefined rules.
     *
     * @param string $email The email address to validate.
     *
     * @return bool True if the data is valid, otherwise false.
     */
    function emailCheck($email)
    {
        if (!empty($email)) {
            if (preg_match("/^[a-zA-Z]+[0-9a-zA-Z]*[.]*[0-9a-zA-Z]*(@)[a-z0-9A-Z.-]+[.]+([a-zA-Z]{2,})$/", $email)) {
                $email = $this->sanitize($email);
            }
        } else {
            $email = "";
        }
        return $email;
    }

    /**
     * Validates the password according to predefined rules.
     *
     * @param string $password The password to validate.
     *
     * @return bool True if the data is valid, otherwise false.
     */
    function passwordCheck($password)
    {

        if (!empty($password)) {
            if (preg_match("/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[@&#{([-|_\\)\]=}%?\/]).{12,}$/", $password)) {
                $password = $this->sanitize($password);
            }
        } else {
            $password = "";
        }
        return $password;
    }

    /**
     * Handles user registration by validating input data, hashing the password,
     * and calling the `queryInscription` method to create a new user.
     *
     * @param string $email The email address of the user.
     * @param string $password The password for the user.
     *
     * @return void
     */
    function inscription($email, $password)
    {
        $email = $this->emailCheck($email);
        $password = $this->passwordCheck($password);

        if ($email != "" && $password != "") {
            // password hash
            $password = password_hash($password, PASSWORD_DEFAULT);

            $response = $this->query->queryInscription($email, $password);
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
    }



    /**
     * Handles user login by validating input data and calling the `queryLogin`
     * method to authenticate the user.
     *
     * @param string $email The email address of the user.
     * @param string $password The password of the user.
     *
     * @return void
     */
    function login($email, $password)
    {
        $email = $this->emailCheck($email);
        $password = $this->passwordCheck($password);

        if ($email != "" && $password != "") {
            $response = $this->query->queryLogin($email, $password);
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
    }

    function forget($email)
    {
        $email = $this->emailCheck($email);
        if ($email != "") {
            // generate token if register
            $data = $this->query->queryIsRegister($email);
            if ($data) {
                // var_dump($data);
                //post token
                $this->query->queryPostToken($data['userId'], $data['token']);
                // send url with token
                mail($email, "reset", "http://localhost:5173/reset?token={$data['token']}");
            }
        } else {
            //Bad Request: invalid email format
            http_response_code(400);
        }
    }

    function updateUser($userId, $email, $password, $token)
    {
        $password = $this->passwordCheck($password);

        if ($password != "") {
            // password hash
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $this->query->queryUpdateUser($userId, $passwordHash, $token);
            $this->login($email, $password);
        } else {
            // Bad Request
            http_response_code(400);
        }
    }

    /**
     * Marks a riddle as solved by the user.
     *
     * Decodes the JWT token to get the user ID and calls the `querySolvedBy` method to
     * mark the riddle as solved.
     *
     * @param int $riddleId The ID of the riddle.
     * @param string $token The JWT token of the user.
     *
     * @return void
     */
    function solvedBy($riddleId, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        if ($userId) {
            $this->query->querySolvedBy($riddleId, $userId);
        } else {
            // Bad Request
            http_response_code(400);
        }
    }

    /**
     * Locks a book for a user by setting an expiration date.
     *
     * Decodes the JWT token to get the user ID and calls the `queryLockBook` method
     * to lock the book with an expiration date.
     *
     * @param int $bookId The ID of the book to lock.
     * @param string $token The JWT token of the user.
     *
     * @return void
     */
    function lockBook($bookId, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        $currentTimestamp = time();
        $oneMonthLater = strtotime('+30 day', $currentTimestamp);
        $expiration = date('Y-m-d 00:00:00', $oneMonthLater);

        if ($userId) {
            $this->query->queryLockBook($bookId, $userId, $expiration);
        } else {
            // Bad Request
            http_response_code(400);
        }
    }
}
