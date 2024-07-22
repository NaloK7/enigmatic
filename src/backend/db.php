<?php


class DB
{
    private $servername;
    private $username;
    private $password;
    private $dbname;

    function __construct($serv = "localhost", $user = "SUPER_ADMIN", $pass = "vDCTGM@anD.asVVo", $db = "enigmatic")
    {
        $this->servername = $serv;
        $this->username = $user;
        $this->password = $pass;
        $this->dbname = $db;
    }

    protected function connectTo()
    {
        $connect = new PDO("mysql:host=$this->servername; dbname=$this->dbname", $this->username, $this->password);

        return $connect;
    }
}
