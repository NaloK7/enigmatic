<?php

require_once("db.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserModel extends DB
{
    /**
     * Registers a new user in the database.
     *
     * Checks if the email is already registered. If not, inserts the user into the database
     * and generates a JWT token for the new user.
     *
     * @param string $email The email address of the user.
     * @param string $password The hashed password of the user.
     *
     * @return array|null The response containing the JWT token, or null if registration failed.
     */
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
            http_response_code(202);
        }
        $con = null;
        return $response;
    }

    /**
     * Authenticates a user by checking their email and password.
     *
     * Verifies the user's email and password against the database. If the credentials
     * are correct, generates and returns a JWT token.
     *
     * @param string $email The email address of the user.
     * @param string $password The password of the user.
     *
     * @return array|null The response containing the JWT token, or null if authentication failed.
     */
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

    /**
     * Check if a user with the given email is registered in the database.
     *
     * @param string $email The email address to check for registration.
     * @return bool Returns true if the user is registered, false otherwise.
     */
    function queryIsRegister($email)
    {
        $con = $this->connectTo();
        $response = null;
        $state = $con->prepare("SELECT id FROM user WHERE email = :email");
        $state->bindParam(':email', $email);
        $state->execute();
        $count = $state->rowCount();
        if ($count > 0) {
            http_response_code(200);
            $data = $state->fetch(PDO::FETCH_ASSOC);
            $response = [
                "userId" => $data['id'],
                "token" => $this->generateJWT($data['id'], $email)
            ];
        } else {
            // No content
            http_response_code(204);
        }
        return $response;
    }

    function queryPostToken($userId, $token)
    {
        $con = $this->connectTo();
        $state = $con->prepare("INSERT INTO confirm_user (id_user, token) VALUES (:userId, :token)");
        $state->bindParam(':userId', $userId);
        $state->bindParam(':token', $token);
        $state->execute();
    }

    function queryUpdateUser($userId, $password, $token)
    {
        $con = $this->connectTo();

        $state = $con->prepare("SELECT id_user, token FROM confirm_user WHERE id_user = :userId AND token = :token");
        $state->bindParam(':userId', $userId);
        $state->bindParam(':token', $token);
        $state->execute();
        $data = $state->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {

            $update = $con->prepare("UPDATE user SET password=:password WHERE id = :userId");
            $update->bindParam(':password', $password);
            $update->bindParam(':userId', $userId);
            $update->execute();

            $delete = $con->prepare("DELETE FROM confirm_user WHERE id_user = :userId");
            $delete->bindParam(':userId', $userId);
            $delete->execute();
        }
    }

    /**
     * Generates a JWT token for the authenticated user.
     *
     * Creates a JWT token using the user's ID and email, with a 24-hour expiration time.
     *
     * @param int $userId The ID of the user.
     * @param string $email The email address of the user.
     *
     * @return string The generated JWT token.
     */
    function generateJWT($userId, $email)
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

    /**
     * Marks a riddle as solved by a user in the database.
     *
     * Inserts a record into the solve table indicating that the specified user has solved
     * the specified riddle.
     *
     * @param int $riddleId The ID of the riddle.
     * @param int $userId The ID of the user.
     *
     * @return void
     */
    function querySolvedBy($riddleId, $userId)
    {
        $con = $this->connectTo();
        // query if already solved
        $query = $con->prepare("INSERT INTO solve(user_id, riddle_id) VALUES (:userId, :riddleId)");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':riddleId', $riddleId);
        $query->execute();
        $count = $query->rowCount();
        if ($count == 1) {
            http_response_code(200);
        } else {
            // Blocked
            http_response_code(202);
        }
    }

    /**
     * Locks a book for a user by adding an entry to the blocked table with an expiration date.
     *
     * @param int $bookId The ID of the book to lock.
     * @param int $userId The ID of the user.
     * @param string $expiration The expiration date of the lock.
     *
     * @return void
     */
    function queryLockBook($bookId, $userId, $expiration)
    {
        $con = $this->connectTo();

        $query = $con->prepare("INSERT INTO blocked(user_id, section_id, expiration) VALUES (:userId, :bookId, :expiration)");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':bookId', $bookId);
        $query->bindParam(':expiration', $expiration);
        $query->execute();
        $count = $query->rowCount();
        if ($count == 1) {
            http_response_code(200);
        } else {
            // Blocked
            http_response_code(202);
        }
    }
}
