<?php
require_once "../../config.php";
require_once "../../auth.php";
header("Content-Type: application/json");

$user = getCurrentUser();
if (!$user) {
    echo json_encode(["error" => "Bạn chưa đăng nhập"]);
    exit;
}
// chỉ user & admin được tạo bài viết
if (!in_array($user['role'], ['user', 'admin'])) {
    echo json_encode(["error" => "Bạn không có quyền tạo bài viết"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['title'], $data['content'], $data['category_id'])) {
    echo json_encode(["error" => "Thiếu trường bắt buộc"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO posts (title, content, image, user_id, category_id, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $data['title'],
        $data['content'],
        $data['image'] ?? null,
        $user['id'], // ép user_id = người đang đăng nhập
        $data['category_id']
    ]);
    echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
