<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once('./controller/UserController.php');
try {
    // route
    if (isset($_GET['action'])) {
        $action = new UserController();
        // connection
        if ($_GET['action'] == 'login') {
            echo "connection";
            // $action->queryCheckConnection($_POST['email'], $_POST['password']);
        }
        // inscription
        elseif ($_GET['action'] == "inscription") {

            $data = json_decode(file_get_contents('php://input'), true);
            $action->inscription($data['email'], $data['password']);
        }
    }
} catch (\Throwable $th) {
    //throw $th;
}
