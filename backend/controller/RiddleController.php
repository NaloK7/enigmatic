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

     /**
     * Retrieve all riddles.
     *
     * Use JWT token to get riddles status for a user.
     *
     * @param string $token JWT token
     * 
     * @return void Outputs all riddle data in JSON format.
     */
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

     /**
     * Check and retrieve a riddle.
     *
     * If the riddle is solved, it updates the book's locked status and fetches the riddle.
     * If the riddle is unsolved, it retrieves the last unsolved riddle position 
     * for the user and fetches that riddle.
     * 
     * @param int $bookId The ID of the book containing the riddle.
     * @param int $riddlePos The position of the riddle in the book.
     * @param string $token JWT token
     *
     * @return  JSON riddle details.
     */
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

     /**
     * Check if the book is unlocked for the user.
     *
     * Verifies the expiration date for unlocking the book.
     * If the expiration date has passed, it returns the current day; 
     * otherwise, it returns the expiration day.
     *
     * @param int $bookId.
     * @param string $token JWT token.
     *
     * @return void Outputs the expiration day or current day in JSON format.
     */
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

     /**
     * Retrieves the position of the last unsolved riddle in the specified book for a User.
     *
     * @param int $bookId.
     * @param string $token JWT token.
     *
     * @return void Outputs the position of the last unsolved riddle in JSON format.
     */
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

     /**
     * Check if the provided answer is correct for the given riddle.
     *
     * @param int $riddleId.
     * @param string $answerToCheck.
     *
     * @return void Outputs the correct answer in JSON format if it matches, otherwise sets appropriate HTTP status.
     */
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

     /**
     * Retrieve the correct answer for a given riddle.
     *
     * @param int $riddleId.
     *
     * @return void Outputs the riddle answer in JSON format.
     */
    function getAnswer($riddleId)
    {
        $response = $this->query->queryGetAnswer($riddleId);
        echo json_encode($response);
    }

     /**
     * Retrieve the explanation for the solution to a given riddle.
     *
     * @param int $riddleId.
     *
     * @return void Outputs the riddle explanation in JSON format.
     */
    function getExplanation($riddleId)
    {
        $response = $this->query->queryGetExplanation($riddleId);
        echo json_encode($response);
    }
}
