<?php
header("Access-Control-Allow-Origin: *");
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
