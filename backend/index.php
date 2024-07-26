<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once('./vendor/autoload.php');
require_once('./controller/UserController.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// Start session
// session_start();

// // Function to get the Authorization header

// $headers = apache_request_headers();
// if (isset($headers['Authorization'])) {
//     var_dump(str_replace('Bearer ', '', $headers['Authorization']));
// }


// // Function to decode the JWT token (you need to install a JWT library)
// function decodeJwtToken($token)
// {
//     // You should use a library for decoding JWT, for example, Firebase JWT
//     // Assuming you have a library like Firebase JWT installed
//     // Use the appropriate method for your library to decode the token
//     $decoded = null;
//     try {
//         // $decoded = \Firebase\JWT\JWT::decode($token, $_ENV['JWT_SECRET'], array('HS256'));
//         $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
//     } catch (Exception $e) {
//         // Handle token decoding errors
//     }
//     return $decoded;
// }



try {
    // route
    if (isset($_GET['action'])) {
        $action = new UserController();
        $data = json_decode(file_get_contents('php://input'), true);
        // var_dump($data);
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
