<?php

require_once("db.php");
class UserModel extends DB
{
    function queryInscription($email, $password)
    {
        $con = $this->connectTo();

        $check = $con->prepare("SELECT id FROM user WHERE email = :email");
        $check->bindParam(':email', $email);
        $check->execute();
        // $response = $check->fetch(PDO::FETCH_ASSOC);
        $count = $check->rowCount();

        if ($count == 0) {
            $query = $con->prepare("INSERT INTO user (`email`, `password`)
                                    VALUES (:email, :password)");
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->execute();
            $countAdd = $query->rowCount();
            // user add
            if ($countAdd > 0) {
                $userId = $con->lastInsertId();
                $response = [
                    "status" => 200,
                    "user_id" => $userId
                ];
                // invalid input
            } else {
                $response = [
                    "status" => 400
                ];
            }
            // mail already used
        } else {
            $response = [
                "status" => 401
            ];
        }
        $con = null;
        return $response;
    }


    function connection($email, $password)
    {

        $status = false;
        $con = $this->connectTo();

        $state = $con->prepare("SELECT id, password FROM user WHERE email = :email");
        $state->bindParam(':email', $email);
        $state->execute();
        $count = $state->rowCount();
        if ($count > 0) {
            $result = $state->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $result['password'])) {
                $_SESSION['name'] = "user";
                $_SESSION['idUser'] = $result['id'];
                $status = true;
            }
        }
        $con = null;

        return $status;
    }
}
