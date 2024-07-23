<?php

require_once('./controller/UserController.php');

try {
    // route
    if (isset($_GET['action'])) {
        $action = new UserController();
        // connection
        if ($_GET['action'] == 'connection') {
            $action->queryCheckConnection($_POST['email'], $_POST['password']);
        }
        // inscription
        elseif ($_GET['action'] == "inscription") {
            $action->inscription($_POST['email'], $_POST['password']);
        }
    }
} catch (\Throwable $th) {
    //throw $th;
}
