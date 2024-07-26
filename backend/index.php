<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once('./vendor/autoload.php');
require_once('./controller/UserController.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // route
    if (isset($_GET['action'])) {
        $action = new UserController();
        $data = json_decode(file_get_contents('php://input'), true);
        // connection
        if ($_GET['action'] == 'login') {
            $action->login($data['email'], $data['password']);
        }
        // inscription
        elseif ($_GET['action'] == "inscription") {


            $action->inscription($data['email'], $data['password']);
        }
    }
} catch (\Throwable $th) {
    //throw $th;
}
