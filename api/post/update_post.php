<?php

declare(strict_types=1);

header('access-control-allow-origin: *');
header('access-control-allow-methods: put');
header('access-control-allow-headers: access-control-allow-method,access-control-allow-headers,content-type,authorization,x-requested-with');
header('content-type: application/json');

include_once '../../models/post.php';
include_once '../../config/database.php';

$database = new Database();
$conn = $database->connect();

$post = new Post($conn);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'));

    $id = isset($_GET['id']) ? $_GET['id'] : "";

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['status' => 'fail', 'message' => 'missing id']);
        die();
    }

    $post->id = $id;

    if (empty($data->author) || empty($data->title) || empty($data->body) || empty($data->category_id)) {
        http_response_code(400);
        echo json_encode(['status' => 'fail', 'message' => 'missing data']);
        die();
    }

    $post->author = $data->author;
    $post->title = $data->title;
    $post->body = $data->body;
    $post->category_id = $data->category_id;

    if ($post->update_post()) {
        $post_data = [
            "id" => $post->id,
            "category_id" => $post->category_id,
            "category_name" => $post->category_name,
            "title" => $post->title,
            "body" => html_entity_decode($post->body),
            "author" => $post->author,
            "created_at" => $post->created_at
        ];

        echo json_encode(['status' => 'success', 'data' => $post_data]);
    } else {
        $res['message'] = 'Post not updated';
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'fail', 'message' => 'access denied']);
}
