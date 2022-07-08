<?php

declare(strict_types=1);

class Database
{
    private $host = 'localhost';
    private $db_name = 'blog';
    private $user = 'foo';
    private $pass = 'foo';
    private $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error {$e->getMessage()}";
        }

        return $this->conn;
    }
}
