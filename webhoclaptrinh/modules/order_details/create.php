<?php
require_once '../../config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['order_id']) || empty($data['course_id']) || empty($data['price'])) {
    echo json_encode(["error" => "order_id, course_id và price là bắt buộc"]);
    exit;
}

$sql = "INSERT INTO order_details (order_id, course_id, price, created_at) 
        VALUES (?, ?, ?, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $data['order_id'],
    $data['course_id'],
    $data['price']
]);

echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
