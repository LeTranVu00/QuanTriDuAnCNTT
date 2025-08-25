<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ admin mới được tạo
$user = requireAdmin();

$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? null;

if (!$name) {
    echo json_encode(["error" => "Thiếu tên category"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO categories (name, created_at) VALUES (?, NOW())");
    $stmt->execute([$name]);

    echo json_encode(["message" => "Thêm category thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
