<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ cho phép user hoặc admin
if (!checkAuth('admin') && !checkAuth('user')) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? null;
$total_amount = $data['total_amount'] ?? null;

if (!$user_id || !$total_amount) {
    echo json_encode(["error" => "user_id và total_amount là bắt buộc"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO orders 
        (user_id, total_amount, status, payment_status, payment_method, transaction_id, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([
        $user_id,
        $total_amount,
        $data['status'] ?? 'pending',
        $data['payment_status'] ?? 'pending',
        $data['payment_method'] ?? null,
        $data['transaction_id'] ?? null
    ]);

    echo json_encode([
        "message" => "Tạo đơn hàng thành công",
        "id" => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
