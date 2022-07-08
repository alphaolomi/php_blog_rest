<?php

declare(strict_types=1);

class Category
{
    private $conn;
    private $categories_table = 'categories';

    public $id;
    public $name;
    public $created_at;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function get_categories()
    {
        $sql = "SELECT * FROM {$this->categories_table} ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function create_categories()
    {
        $sql = "INSERT INTO {$this->categories_table} (name) VALUES (:name)";
        $stmt = $this->conn->prepare($sql);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $stmt->bindParam(':name', $this->name);
        if ($stmt->execute()) {
            return true;
            echo "Error {$stmt->error()}";
        }
        return false;
    }
}
