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

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $token = (str_replace('Bearer ', '', $headers['Authorization']));
}


try {
    // route
    if (isset($_GET['action'])) {
        $action = new UserController();
        $data = json_decode(file_get_contents('php://input'), true);
        // login
        if ($_GET['action'] == 'login') {
            $action->login($data['email'], $data['password']);
        }
        // inscription
        elseif ($_GET['action'] == 'inscription') {
            $action->inscription($data['email'], $data['password']);
        }
        // all riddles
        else if ($_GET['action'] == 'books') {
            $action->getAllRiddles();
        }
        // last unsolved
        elseif ($_GET['action'] == 'last') {
            $action->getLastRiddle($data['bookId'], $token);
        }
        // isBlocked
        elseif ($_GET['action'] == 'blocked') {

            $action->isBlocked($data['bookId'], $token);
        }
    }
} catch (\Throwable $th) {
    //throw $th;
}
