<?php

declare(strict_types=1);

class Post
{
    private $conn;
    private $post_table = 'posts';
    private $categories_table = 'categories';

    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function get_posts()
    {
        $sql = "SELECT 
            c.name AS category_name, 
            p.id, 
            p.category_id, 
            p.title, 
            p.body, 
            p.author, 
            p.created_at 
            FROM {$this->post_table} p 
            LEFT JOIN {$this->categories_table} c 
            ON p.category_id = c.id
            ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function get_post()
    {
        $sql = "SELECT 
            c.name AS category_name, 
            p.id, 
            p.category_id, 
            p.title, 
            p.body, 
            p.author, 
            p.created_at 
            FROM {$this->post_table} p 
            LEFT JOIN categories c 
            ON p.category_id = c.id
            WHERE p.id = :id
            LIMIT 0, 1";

        $stmt = $this->conn->prepare($sql);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->body = $row['body'];
        $this->created_at = $row['created_at'];
    }

    public function create_post()
    {
        $sql = "INSERT INTO {$this->post_table} (category_id, title, body, author) VALUES (:category_id, :title, :body, :author)";
        $stmt = $this->conn->prepare($sql);

        // sanitize data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // bind params
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->get_post();
            return true;
        }
        echo "Error {$stmt->error()} \n";
        return false;
    }

    public function update_post()
    {
        $sql = "UPDATE {$this->post_table} SET category_id=:category_id, title=:title, body=:body, author=:author WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // sanitize data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));


        // bind params
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            $this->get_post();
            return true;
        }
        echo "Error {$stmt->error()} \n";
        return false;
    }


    public function delete_post()
    {
        $sql = "DELETE FROM {$this->post_table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }
        echo "Error {$stmt->error()} \n";
        return false;
    }
}
