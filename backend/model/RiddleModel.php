<?php

require_once("db.php");

class RiddleModel extends DB
{
    /**
     * Retrieve all riddles for a specific user.
     *
     * Fetches all riddles from the database for the given user across multiple books.
     *
     * @param int $userId The ID of the user.
     *
     * @return array The fetched riddles data.
     */
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

    /**
     * Check if a user has finished all riddles in a specific section.
     * 
     * @param int $bookId The ID of the section to check.
     * @param int $userId The ID of the user to check.
     * @return bool Returns true if the user has finished all riddles in the section, false otherwise.
     */
    function queryisFinished($bookId, $userId)
    {
        $con = $this->connectTo();
        $finish = false;

        $queryTotal = $con->prepare("SELECT total_riddle FROM section WHERE id = :bookId");
        $queryTotal->bindParam(":bookId", $bookId);
        $queryTotal->execute();
        $total = $queryTotal->fetch(PDO::FETCH_ASSOC);

        $query = $con->prepare("SELECT s.user_id FROM solve AS s LEFT JOIN riddle AS r ON r.id = s.riddle_id WHERE s.user_id = :userId AND r.section_id = :bookId");
        $query->bindParam(":bookId", $bookId);
        $query->bindParam(":userId", $userId);
        $query->execute();
        $count = $query->rowCount();
        if ($count > 0) {
            http_response_code(200);
            $totalSolved = $query->fetchAll(PDO::FETCH_ASSOC);
            $finish = count($totalSolved) >= $total['total_riddle'];
        } else {
            // No Content
            http_response_code(204);
        }
        return $finish;
    }
    /**
     * Retrieve the position of the first unsolved riddle in a specific book for the user.
     *
     * @param int $bookId
     * @param int $userId.
     *
     * @return array|null The position of the first unsolved riddle, or null if not found.
     */
    // todo rename first
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

    /**
     * Check if a book is unlocked for the user.
     *
     * If the book is locked retrieves the expiration date.
     * Otherwise, return the current date.
     * 
     * @param int $bookId
     * @param int $userId.
     *
     * @return array The expiration date of the book OR the current date.
     */
    function queryIsUnlocked($bookId, $userId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT expiration FROM blocked WHERE user_id = :userId AND section_id = :bookId ");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':bookId', $bookId);
        $query->execute();
        $count = $query->rowCount();
        if ($count == 1) {
            http_response_code(200);
            $date = $query->fetch(PDO::FETCH_ASSOC);
        } else {
            // Blocked
            http_response_code(200);
            $currentTimestamp = time();
            $currentDay = date('Y-m-d 00:00:00', $currentTimestamp);
            $date = ["expiration" => $currentDay];
        }
        return $date;
    }

    /**
     * Deletes the entry in the blocked table that corresponds to the book_id and user_id.
     *
     * @param int $bookId
     * @param int $userId.
     *
     * @return void
     */
    // todo rename remove
    function updateLocked($bookId, $userId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("DELETE FROM  blocked WHERE user_id = :userId AND section_id = :bookId ");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':bookId', $bookId);
        $query->execute();
    }

    /**
     * Verifies if the user has solved a specific riddle in the given book.
     *
     * @param int $bookId
     * @param int $riddlePos The position of the riddle in the book.
     * @param int $userId.
     *
     * @return bool True if the riddle is solved, otherwise false.
     */
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

    /**
     * Retrieve the details of a specific riddle based on its position in the book.
     *
     * @param int $bookId The ID of the book.
     * @param int $riddlePos The position of the riddle in the book.
     *
     * @return array|null The riddle data, or null if not found.
     */
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

    /**
     * Retrieve the correct answer to the specified riddle.
     *
     * @param int $riddleId
     *
     * @return array|null The correct answer data, or null if not found.
     */
    function queryGetAnswer($riddleId)
    {
        $con = $this->connectTo();
        $query = $con->prepare("SELECT solution FROM solution AS s LEFT JOIN have_solution AS hs ON s.id = hs.solution_id WHERE hs.riddle_id = :riddleId");
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

    /**
     * Retrieve the explanation for the solution to the specified riddle.
     *
     * @param int $riddleId
     *
     * @return array|null The explanation data, or null if not found.
     */
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
