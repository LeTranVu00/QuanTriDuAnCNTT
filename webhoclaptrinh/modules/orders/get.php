<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");


// Admin xem tất cả, user chỉ xem đơn của chính mình
$user = getAuthUser();
if (!$user) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

try {
    if (!isset($_GET['id'])) {
        if ($user['role'] === 'admin') {
            $sql = "SELECT o.*, u.fullname, u.email
                    FROM orders o
                    JOIN users u ON o.user_id = u.id
                    ORDER BY o.created_at DESC";
            $stmt = $pdo->query($sql);
        } else {
            $sql = "SELECT o.*, u.fullname, u.email
                    FROM orders o
                    JOIN users u ON o.user_id = u.id
                    WHERE o.user_id = ?
                    ORDER BY o.created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user['id']]);
        }
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // GET single order
    $sql = "SELECT o.*, u.fullname, u.email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Nếu user thường, chỉ được xem đơn của mình
    if ($user['role'] !== 'admin' && $order['user_id'] != $user['id']) {
        echo json_encode(["error" => "Forbidden"]);
        exit;
    }

    echo json_encode($order);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
