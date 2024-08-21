<?php

class DB
{
    private $servername;
    private $username;
    private $password;
    private $dbname;

    public function __construct()
    {
        $this->servername = $_ENV['DB_SERVER_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_NAME'];
    }

    protected function connectTo()
    {
        try {
        return new PDO(
                "mysql:host={$this->servername};dbname={$this->dbname}",
            $this->username,
            $this->password
        );
        }
            catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
