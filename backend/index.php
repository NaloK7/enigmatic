<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once('./vendor/autoload.php');
require_once('./controller/UserController.php');

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
}


try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = new UserController();
        $data = json_decode(file_get_contents('php://input'), true);

        // login
        if ($iri == 'login') {
            $action->login($data['email'], $data['password']);
        }
        // inscription
        elseif ($iri == 'inscription') {
            $action->inscription($data['email'], $data['password']);
        }
        if (isset($token)) {
            // all riddles
            if ($iri == 'books') {
                $action->getAllRiddles($token);
            }
            // last unsolved
            elseif ($iri == 'last') {
                $action->getLastRiddle($data['bookId'], $token);
            }
            // isBlocked
            elseif ($iri == 'blocked') {
                $action->isBlocked($data['bookId'], $token);
            }
            // check answer
            else if ($iri == 'checkAnswer') {
                $action->checkAnswer($data['riddleId'], $data['answer']);
            }
            // post riddle solve
            else if ($iri == 'solve') {
                $action->validRiddle($data['riddleId'], $token);
            }
        }
    }
} catch (\Throwable $th) {
    //throw $th;
}
