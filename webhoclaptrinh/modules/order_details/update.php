<?php
require_once '../../config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['id'])) {
    echo json_encode(["error" => "id là bắt buộc để update"]);
    exit;
}

$sql = "UPDATE order_details SET order_id=?, course_id=?, price=? WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $data['order_id'],
    $data['course_id'],
    $data['price'],
    $data['id']
]);

echo json_encode(["success" => true]);
