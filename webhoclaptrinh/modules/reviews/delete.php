<?php
require_once "../../config.php";
require_once "../../auth.php";
header("Content-Type: application/json");

$user = getCurrentUser();
if (!$user) {
    echo json_encode(["error" => "Bạn chưa đăng nhập"]);
    exit;
}

$id = $_GET["id"] ?? null;
if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

// check quyền
try {
    $stmt = $pdo->prepare("SELECT user_id FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$review) {
        echo json_encode(["error" => "Không tìm thấy review"]);
        exit;
    }

    if ($user['role'] !== 'admin' && $review['user_id'] != $user['id']) {
        echo json_encode(["error" => "Bạn không có quyền xóa review này"]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
