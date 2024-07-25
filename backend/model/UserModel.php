<?php

require_once("db.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
                    "token" => $this->generateJWT($userId, $email)
                ];
                // todo set proper code
                // query error
            } else {
                $response = [
                    "status" => 401
                ];
            }
            // todo set proper code
            // mail already used
        } else {
            $response = [
                "status" => 402
            ];
        }
        $con = null;
        return $response;
    }


    function queryLogin($email, $password, $remember)
    {

        $status = false;
        $con = $this->connectTo();

        $state = $con->prepare("SELECT id, password FROM user WHERE email = :email");
        $state->bindParam(':email', $email);
        $state->execute();
        $count = $state->rowCount();
        if ($count > 0) {
            $data = $state->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $data['password'])) {
                $response = [
                    "status" => 200,
                    "token" => $this->generateJWT($data['id'], $email, $remember)
                ];
                // todo set proper code
                // wrong password
            } else {
                $response = [
                    "status" => 402
                ];
            }
            // todo set proper code
            // email not register
        } else {
            $response = [
                "status" => 403
            ];
        }
        $con = null;

        return $response;
    }

    private function generateJWT($userId, $email, $remember = false)
    {
        // todo setup time properly
        $time = 3600 * 5; // 5 hours validity
        if ($remember) {
            $time = 3600 * 24 * 7; // 7 days validity
        }
        $key = $_ENV['JWT_KEY'];
        $payload = [
            'iat' => time(),
            'exp' => time() + $time,
            "user_id" => $userId,
            "email" => $email

        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}
