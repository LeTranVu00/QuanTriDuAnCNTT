<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";

// Kiểm tra login
$auth = checkAuth($pdo);
if (!$auth) {
    echo json_encode(["error" => "Vui lòng đăng nhập"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$content = $data['content'] ?? null;

if (!$id || !$content) {
    echo json_encode(["error" => "Thiếu id hoặc content"]);
    exit;
}

try {
    // Lấy thông tin comment
    $stmt = $pdo->prepare("SELECT user_id FROM comments WHERE id = ?");
    $stmt->execute([$id]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        echo json_encode(["error" => "Comment không tồn tại"]);
        exit;
    }

    // Kiểm tra quyền
    if ($auth['role'] !== 'admin' && $auth['id'] != $comment['user_id']) {
        echo json_encode(["error" => "Bạn không có quyền sửa comment này"]);
        exit;
    }

    // Update
    $stmt = $pdo->prepare("UPDATE comments SET content = ? WHERE id = ?");
    $stmt->execute([$content, $id]);

    echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
