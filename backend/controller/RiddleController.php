<?php
// session_start();
require_once('./model/RiddleModel.php');
require_once('Controller.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RiddleController extends Controller
{
    public $query;

    public function __construct()
    {
        $this->query = new RiddleModel();
    }

    function getAllRiddles($token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;
        if ($userId) {
            $response = $this->query->queryAllRiddles($userId);
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
    }

    function checkRiddle($bookId, $riddlePos, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;
        $locked = $this->bookUnlocked($bookId, $token);
        // book UNLOCKED
        if (!$locked) {
            $solved = $this->query->queryIsSolved($bookId, $riddlePos, $userId);
            if ($solved == 1) {
                // riddle solved → get this riddle
                $response = $this->query->queryOneRiddle($bookId, $riddlePos);
            } else {
                // riddle unsolved → get last id
                $lastPos = $this->query->queryGetLastRiddlePos($bookId, $userId);
                $newPos = $lastPos['position'];
                $response = $this->query->queryOneRiddle($bookId, $newPos);
            }
        }

        echo json_encode($response);
    }

    function bookUnlocked($bookId, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        if ($userId) {
            $response = $this->query->queryIsUnlocked($bookId, $userId);
        } else {
            // Bad Request
            http_response_code(400);
        }
        return $response;
    }

    function getLastRiddlePos($bookId, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        if ($userId) {
            $response = $this->query->queryGetLastRiddlePos($bookId, $userId);
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
    }

    // function checkAnswer($riddleId, $answerToCheck)
    // {
    //     if (is_string($answerToCheck) && $answerToCheck != "") {
    //         $answerToCheck = strtolower($this->sanitize($answerToCheck));
    //     }

    //     $response = $this->query->getAnswer($riddleId);
    //     if ($response == "") {
    //         // Bad Request
    //         http_response_code(208);
    //     } else {
    //         if ($response == $answerToCheck) {
    //             http_response_code(200);
    //         } else {
    //             // No Content
    //             http_response_code(204);
    //         }
    //     }
    // }

    function validRiddle($riddleId, $token)
    {
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        if ($userId) {
            $response = $this->query->queryValidRiddle($riddleId, $userId);
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
    }
}
