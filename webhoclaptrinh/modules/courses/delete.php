<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . '/../../auth.php';

// ✅ Chỉ cho admin
$auth = checkAuth($pdo);
if (!$auth || $auth['role'] !== 'admin') {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Nhận id
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(["success" => true, "message" => "Xóa khóa học thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
