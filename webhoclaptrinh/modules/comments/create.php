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

// Nhận dữ liệu từ request
$data = json_decode(file_get_contents("php://input"), true);

$content = $data['content'] ?? null;
$commentable_id = $data['commentable_id'] ?? null;
$commentable_type = $data['commentable_type'] ?? null;

if (!$content || !$commentable_id || !$commentable_type) {
    echo json_encode(["error" => "Thiếu content, commentable_id hoặc commentable_type"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO comments (user_id, content, commentable_id, commentable_type, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $auth['id'],
        $content,
        $commentable_id,
        $commentable_type
    ]);

    echo json_encode([
        "success" => true,
        "id" => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
