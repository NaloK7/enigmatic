<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once('./vendor/autoload.php');
require_once('./controller/UserController.php');
require_once('./controller/RiddleController.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$url = explode("/", $_SERVER['REQUEST_URI']);
$iri = end($url);


$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $token = (str_replace('Bearer ', '', $headers['Authorization']));
    $key = $_ENV['JWT_KEY'];
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    $date = time();
    // is token expired
    $tokenIsNotExp = $decoded->exp > $date;
}


try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
        $user = new UserController();
        $data = json_decode(file_get_contents('php://input'), true);

        // login
        if ($iri == 'login') {
            $user->login($data['email'], $data['password']);
        }
        // inscription
        elseif ($iri == 'inscription') {
            $user->inscription($data['email'], $data['password']);
        }
        // SEND MAIL
        else if ($iri == "forget") {
            $user->forget($data['email']);
        }
        // update password
        elseif ($iri == 'updateUser') {
            $user->updateUser($data['userId'], $data['email'], $data['password'], $data['token']);
        }
        // IF CONNECTED and TOKEN NOT EXPIRED
        elseif (isset($token) && $tokenIsNotExp) {

            $riddle = new RiddleController();
            // GET all riddles
            if ($iri == 'books') {
                $riddle->getAllRiddles($token);
            } elseif ($iri == "finish") {
                $riddle->isFinished($data['bookId'], $token);
            }
            // GET last riddle ID
            elseif ($iri == 'last') {
                $riddle->getLastRiddlePos($data['bookId'], $token);
            }
            // GET riddle
            elseif ($iri == 'riddle') {
                $riddle->checkRiddle($data['bookId'], $data['riddlePos'], $token);
            }
            // isBlocked
            elseif ($iri == 'isLocked') {
                $riddle->bookUnlocked($data['bookId'], $token);
            }
            // check answer
            elseif ($iri == 'checkAnswer') {
                $riddle->checkAnswer($data['riddleId'], $data['answer']);
            }
            // GET answer
            elseif ($iri == 'getAnswer') {
                $riddle->getAnswer($data['riddleId']);
            }
            // GET explanation
            elseif ($iri == 'explanation') {
                $riddle->getExplanation($data['riddleId']);
            }
            // POST solved by
            elseif ($iri == 'solve') {

                $user->solvedBy($data['riddleId'], $token);
            }
            // POST lock book
            elseif ($iri == 'lockBook') {
                $user->lockBook($data['bookId'], $token);
            }
        } else {
            // Unauthorized
            http_response_code(401);
        }
    }
} catch (\Throwable $th) {
    // Unauthorized
    http_response_code(401);
}
