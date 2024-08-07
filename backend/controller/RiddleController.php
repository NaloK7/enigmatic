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

        $currentTimestamp = time();
        $currentDay = date('Y-m-d 00:00:00', $currentTimestamp);

        $expDate = $this->query->queryIsUnlocked($bookId, $userId);

        $expirationDay = $expDate['expiration'];
        if ($currentDay >= $expirationDay) {

            $solved = $this->query->queryIsSolved($bookId, $riddlePos, $userId);
            if ($solved == 1) {
                // DELETE blocked line corresponding
                $this->query->updateLocked($bookId, $userId);
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
        // get expiration date
        $key = $_ENV['JWT_KEY'];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $userId = $decoded->user_id;

        if ($userId) {
            $currentTimestamp = time();
            $currentDay = date('Y-m-d 00:00:00', $currentTimestamp);

            $expDate = $this->query->queryIsUnlocked($bookId, $userId);
            // there is a expiration date
            if ($expDate) {
                $expirationDay = $expDate['expiration'];

                // expiration date is passed
                if ($currentDay >= $expirationDay) {
                    $response = $currentDay;
                    // remove line in db
                } else {
                    $response = $expirationDay;
                }
            }
            // there is no expiration date
            else {
                $response = $currentDay;
            }
        } else {
            // Bad Request
            http_response_code(400);
        }
        echo json_encode($response);
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

    function checkAnswer($riddleId, $answerToCheck)
    {

        if (is_string($answerToCheck) && $answerToCheck != "") {
            $answerToCheck = strtolower($this->sanitize($answerToCheck));
        }

        $response = $this->query->queryGetAnswer($riddleId);
        $answer = $response['solution'];
        if ($answer == "") {
            // Bad Request
            http_response_code(208);
        } else {
            if ($answer == $answerToCheck) {
                http_response_code(200);
                echo json_encode($answer);
            } else {
                // No Content
                http_response_code(204);
            }
        }
    }

    function getAnswer($riddleId)
    {
        $response = $this->query->queryGetAnswer($riddleId);
        echo json_encode($response);
    }

    function getExplanation($riddleId)
    {
        $response = $this->query->queryGetExplanation($riddleId);
        echo json_encode($response);
    }
}
