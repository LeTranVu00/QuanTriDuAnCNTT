<?php
require_once '../../config.php';
header("Content-Type: application/json");

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "id là bắt buộc để xóa"]);
    exit;
}

$sql = "DELETE FROM order_details WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

echo json_encode(["success" => true]);
