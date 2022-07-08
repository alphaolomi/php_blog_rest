<?php

declare(strict_types=1);

header('access-control-allow-origin: *');
header('access-control-allow-methods: post');
header('access-control-allow-headers: access-control-allow-method,access-control-allow-headers,content-type,authorization,x-requested-with');
header('content-type: application/json');

include_once '../../models/post.php';
include_once '../../config/database.php';

$database = new Database();
$conn = $database->connect();

$post = new Post($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));

    if (empty($data->author) || empty($data->title) || empty($data->body) || empty($data->category_id)) {
        http_response_code(400);
        echo json_encode(["status" => "fail", 'message' => 'bad requet']);
        die();
    }

    $post->author = $data->author;
    $post->title = $data->title;
    $post->body = $data->body;
    $post->category_id = $data->category_id;

    if ($post->create_post()) {
        $res = ["status" => "success",];
        $res['data'] = [];

        $post_data = [
            "id" => $post->id,
            "category_id" => $post->category_id,
            "category_name" => $post->category_name,
            "title" => $post->title,
            "body" => html_entity_decode($post->body),
            "author" => $post->author,
            "created_at" => $post->created_at
        ];

        array_push($res['data'], $post_data);
        http_response_code(200);
        echo json_encode($res);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "fail", "message" => "server error"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "fail", "message" => "access denied"]);
}
