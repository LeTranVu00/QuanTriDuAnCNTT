<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ admin
$user = requireAdmin();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["message" => "Xóa category thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
