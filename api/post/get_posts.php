<?php

declare(strict_types=1);

header('access-control-allow-origin: *');
header('content-type: application/json');

include_once '../../models/post.php';
include_once '../../config/database.php';

$database = new Database();
$conn = $database->connect();

$post = new Post($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $stmt = $post->get_posts();
    $row_count = $stmt->rowCount();;

    $res = ["status" => "success", "results" => $row_count, "data" => []];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $post = [
            "id" => $row['id'],
            "category_id" => $row['category_id'],
            "category_name" => $row['category_name'],
            "title" => $row['title'],
            "body" => html_entity_decode($row['body']),
            "author" => $row["author"],
            "created_at" => $row['created_at']
        ];
        array_push($res['data'], $post);
    }

    http_response_code(200);
    echo json_encode($res);
} else {
    http_response_code(400);
    echo json_encode(["status" => "fail", "message" => "access denied"]);
}
