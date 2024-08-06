<?php

require_once("db.php");

class RiddleModel extends DB
{

    function queryAllRiddles($userId)
    {
        $con = $this->connectTo();
        $bookNumber = 4;
        $datas = [];
        for ($i = 1; $i <= $bookNumber; $i++) {
            $query = $con->prepare("SELECT r.id, r.section_id, r.position, r.title, s.riddle_id, s.user_id FROM riddle AS r LEFT JOIN solve AS s ON r.id = s.riddle_id AND s.user_id = :userId WHERE section_id = :bookNumber ORDER BY position");

            $query->bindParam(":bookNumber", $i);
            $query->bindParam(":userId", $userId);
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

    function queryGetLastRiddlePos($bookId, $userId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT r.position FROM riddle as r LEFT JOIN solve as s ON r.id = s.riddle_id AND s.user_id = :userId WHERE s.riddle_id IS NULL AND r.section_id = :bookId ORDER BY r.position ASC LIMIT 1");

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

    function queryIsUnlocked($bookId, $userId)
    {
        echo 'vu';
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
        }
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function queryIsSolved($bookId, $riddlePos, $userId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT s.user_id FROM solve AS s LEFT JOIN riddle AS r ON r.id = s.riddle_id WHERE s.user_id = :userId AND r.section_id = :bookId AND r.position = :riddlePos");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':bookId', $bookId);
        $query->bindParam(':riddlePos', $riddlePos);
        $query->execute();
        $count = $query->rowCount();
        if ($count > 0) {
            http_response_code(200);
            $solved = true;
        } else {
            // todo set proper code for empty result OK
            http_response_code(200);
            $solved = false;
        }

        return $solved;
    }

    function queryOneRiddle($bookId, $riddlePos)
    {
        $con = $this->connectTo();

        $query = $con->prepare("SELECT r.id AS riddleId, r.section_id, r.position, r.title, r.wording FROM riddle AS r WHERE r.section_id = :bookId AND r.position = :riddlePos");

        $query->bindParam(':riddlePos', $riddlePos);
        $query->bindParam(':bookId', $bookId);
        $query->execute();

        $count = $query->rowCount();
        if ($count == 1) {
            http_response_code(200);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            // No Content
            http_response_code(200);
        }
    }

    function queryGetAnswer($riddleId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT solution FROM solution AS s LEFT JOIN have_solution AS hs ON s.id = hs.riddle_id WHERE hs.riddle_id = :riddleId");
        $query->bindParam(':riddleId', $riddleId);
        $query->execute();
        $count = $query->rowCount();
        if ($count == 1) {
            http_response_code(200);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            // No Content
            http_response_code(200);
        }
    }

    function queryGetExplanation($riddleId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT explanation FROM riddle WHERE id = :riddleId");
        $query->bindParam(':riddleId', $riddleId);
        $query->execute();
        $count = $query->rowCount();
        if ($count == 1) {
            http_response_code(200);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            // No Content
            http_response_code(200);
        }
    }
}
