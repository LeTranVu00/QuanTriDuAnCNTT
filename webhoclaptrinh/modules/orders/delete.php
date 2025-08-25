<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ admin được xóa đơn
if (!checkAuth('admin')) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "id là bắt buộc để xóa"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode([
        "message" => "Xóa đơn hàng thành công",
        "rows_deleted" => $stmt->rowCount()
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
