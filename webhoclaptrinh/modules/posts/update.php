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
if (!isset($data['id'])) {
    echo json_encode(["error" => "Thiếu post id"]);
    exit;
}

// check quyền: user chỉ được sửa bài của chính mình
try {
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->execute([$data['id']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo json_encode(["error" => "Không tìm thấy bài viết"]);
        exit;
    }

    if ($user['role'] !== 'admin' && $post['user_id'] != $user['id']) {
        echo json_encode(["error" => "Bạn không có quyền sửa bài viết này"]);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE posts
        SET title=?, content=?, image=?, category_id=?
        WHERE id=?
    ");
    $stmt->execute([
        $data['title'],
        $data['content'],
        $data['image'] ?? null,
        $data['category_id'],
        $data['id']
    ]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
