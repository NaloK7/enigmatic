<?php

require_once("./db.php");
class UserModel extends DB
{
    function connection($email, $password)
    {

        $con = $this->connectTo();
    }
    function print($param)
    {
        return $param . 'ok';
    }
}

if (isset($_GET['param'])) {
    $userModel = new UserModel();
    echo $userModel->print($_GET['param']);
} else {
    echo "No parameter provided";
}
