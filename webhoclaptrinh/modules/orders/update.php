<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ admin mới được update đơn
if (!checkAuth('admin')) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "id là bắt buộc để update"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE orders
        SET status=?, payment_status=?, payment_method=?, transaction_id=?, updated_at=NOW()
        WHERE id=?
    ");
    $stmt->execute([
        $data['status'] ?? 'pending',
        $data['payment_status'] ?? 'pending',
        $data['payment_method'] ?? null,
        $data['transaction_id'] ?? null,
        $id
    ]);

    echo json_encode([
        "message" => "Cập nhật đơn hàng thành công",
        "rows_affected" => $stmt->rowCount()
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
