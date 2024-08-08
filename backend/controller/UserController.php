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

     /**
     * Validates the email and password according to predefined rules.
     *
     * @param string $email The email address to validate.
     * @param string $password The password to validate.
     *
     * @return bool True if the data is valid, otherwise false.
     */
    function rulesData($email, $password)
    {
        $valid = true;

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
        if ($this->rulesData($email, $password)) {
            $response = $this->query->queryLogin($email, $password);
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
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
