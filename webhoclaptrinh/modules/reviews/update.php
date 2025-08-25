<?php
require_once "../../config.php";
require_once "../../auth.php";
header("Content-Type: application/json");

$user = getCurrentUser();
if (!$user) {
    echo json_encode(["error" => "Bạn chưa đăng nhập"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data["id"])) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

$id = $data["id"];
$rating = $data["rating"] ?? null;
$comment = $data["comment"] ?? null;

try {
    // check quyền
    $stmt = $pdo->prepare("SELECT user_id FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$review) {
        echo json_encode(["error" => "Không tìm thấy review"]);
        exit;
    }

    if ($user['role'] !== 'admin' && $review['user_id'] != $user['id']) {
        echo json_encode(["error" => "Bạn không có quyền sửa review này"]);
        exit;
    }

    $sql = "UPDATE reviews SET rating = ?, comment = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$rating, $comment, $id]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
