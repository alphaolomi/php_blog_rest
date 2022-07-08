<?php

declare(strict_types=1);

header('access-control-allow-origin: *');
header('content-type: application/json');

include_once '../../config/database.php';
include_once '../../models/category.php';

$database = new Database();
$conn = $database->connect();

$category = new Category($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $category->get_categories();
    $row_count = $stmt->rowCount();

    $res = ["status" => "success", "results" => $row_count, "data" => []];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $category = [
            "id" => $row['id'],
            "name" => $row['name'],
            "created_at" => $row['created_at']
        ];
        array_push($res['data'], $category);
    }

    http_response_code(200);
    echo json_encode($res);
} else {
    http_response_code(400);
    echo json_encode(["status" => "fail", "message" => "access denied"]);
}
