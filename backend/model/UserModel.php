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
        $count = $check->rowCount();

        if ($count == 0) {
            $query = $con->prepare("INSERT INTO user (`email`, `password`)
                                    VALUES (:email, :password)");
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->execute();
            $countAdd = $query->rowCount();
            if ($countAdd > 0) {
                http_response_code(200);
                $userId = $con->lastInsertId();
                $response = [
                    "token" => $this->generateJWT($userId, $email)
                ];
            } else {
                // No Content
                http_response_code(204);
            }
        } else {
            // Unauthorized
            http_response_code(401);
        }
        $con = null;
        return $response;
    }


    function queryLogin($email, $password)
    {

        $con = $this->connectTo();

        $state = $con->prepare("SELECT id, password FROM user WHERE email = :email");
        $state->bindParam(':email', $email);
        $state->execute();
        $count = $state->rowCount();
        if ($count > 0) {
            $data = $state->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $data['password'])) {
                http_response_code(200);
                $response = [
                    "token" => $this->generateJWT($data['id'], $email)
                ];
            } else {
                // No Content
                http_response_code(204);
            }
        } else {
            // Unauthorized
            http_response_code(401);
        }
        $con = null;
        header('Content-Type: application/json');
        return $response;
    }

    private function generateJWT($userId, $email)
    {
        $key = $_ENV['JWT_KEY'];
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600 * 24,
            "user_id" => $userId,
            "email" => $email

        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    function queryAllRiddles()
    {
        $con = $this->connectTo();
        $bookNumber = 4;
        $datas = [];
        for ($i = 1; $i <= $bookNumber; $i++) {
            $query = $con->prepare("SELECT id, section_id, position, title FROM riddle WHERE section_id = :bookNumber ORDER BY position");
            $query->bindParam(":bookNumber", $i);
            $query->execute();
            $count = $query->rowCount();
            if ($count > 0) {
                http_response_code(200);
                $data = $query->fetchAll(PDO::FETCH_ASSOC);
                $datas[] = $data;
            } else {
                // No Content
                http_response_code(204);
            }
        }
        return $datas;
    }

    function queryIsBlocked($bookId, $userId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT expiration FROM blocked WHERE user_id = :userId AND section_id = :bookId ");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':bookId', $bookId);
        $query->execute();
        $count = $query->rowCount();
        if ($count == 0) {
            http_response_code(200);
        } else {
            // Blocked
            http_response_code(202);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        }
    }

    function queryGetLastRiddle($bookId, $userId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT r.position, r.title, r.wording FROM riddle as r LEFT JOIN solve as s ON r.id = s.riddle_id AND s.user_id = :userId WHERE s.riddle_id IS NULL AND r.section_id = :bookId ORDER BY r.position ASC LIMIT 1");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':bookId', $bookId);
        $query->execute();
        $count = $query->rowCount();
        if ($count == 1) {
            http_response_code(200);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            // No Content
            http_response_code(204);
        }
    }
}
