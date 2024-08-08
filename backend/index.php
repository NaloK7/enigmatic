<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once('./vendor/autoload.php');
require_once('./controller/UserController.php');
require_once('./controller/RiddleController.php');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$url = explode("/", $_SERVER['REQUEST_URI']);
$iri = end($url);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $token = (str_replace('Bearer ', '', $headers['Authorization']));
    // todo check token validity
}


try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = new UserController();
        $data = json_decode(file_get_contents('php://input'), true);

        // login
        if ($iri == 'login') {
            $user->login($data['email'], $data['password']);
        }
        // inscription
        elseif ($iri == 'inscription') {
            $user->inscription($data['email'], $data['password']);
        } elseif (isset($token)) {
            $riddle = new RiddleController();
            // GET all riddles
            if ($iri == 'books') {
                $riddle->getAllRiddles($token);
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
    //throw $th;
}
