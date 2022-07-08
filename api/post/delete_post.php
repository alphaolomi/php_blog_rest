<?php

declare(strict_types=1);

header('access-control-allow-origin: *');
header('access-control-allow-methods: delete');
header('access-control-allow-headers: access-control-allow-method,access-control-allow-headers,content-type,authorization,x-requested-with');
header('content-type: application/json');

include_once '../../models/post.php';
include_once '../../config/database.php';

$database = new Database();
$conn = $database->connect();

$post = new Post($conn);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $post->id = isset($_GET['id']) ? $_GET['id'] : die();

    if ($post->delete_post()) {
        http_response_code(204);
        echo json_encode(["status" => "success", "message" => "post deleted"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "fail", "message" => "server error"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "fail", "message" => "access denied"]);
}
