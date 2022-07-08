<?php

declare(strict_types=1);

header('access-control-allow-origin: *');
header('access-control-allow-methods: get');
header('content-type: application/json');

include_once '../../models/post.php';
include_once '../../config/database.php';

$database = new Database();
$conn = $database->connect();

$post = new Post($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = isset($_GET['id']) ? $_GET['id'] : "";

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(["status" => "fail", "message" => "missing Id"]);
        die();
    }

    $post->id = $id;

    $post->get_post();

    $post_data = [
        "id" => $post->id,
        "category_id" => $post->category_id,
        "category_name" => $post->category_name,
        "title" => $post->title,
        "body" => html_entity_decode($post->body),
        "author" => $post->author,
        "created_at" => $post->created_at
    ];

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $post_data]);
} else {
    http_response_code(400);
    echo json_encode(["status" => "fail", "messaeg" => "access denied"]);
}
