<?php
namespace Src\System;

class DatabaseConnector {

    private $dbConnection = null;

    public function __construct()
    {
        $host = $_ENV['DATABASE_HOST'];
        $port = $_ENV['DATABASE_PORT'];
        $db   = $_ENV['DATABASE_NAME'];
        $user = $_ENV['DATABASE_USERNAME'];
        $pass = $_ENV['DATABASE_PASSWORD'];
        

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db", $user, $pass
            );
            $conn = new \PDO("mysql:host=$host;dbname=$db", $user, $pass);
            // Set PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}