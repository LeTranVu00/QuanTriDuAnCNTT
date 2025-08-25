<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ cho admin
if (!checkAuth('admin')) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? null;
$course_id = $data['course_id'] ?? null;

if (!$user_id || !$course_id) {
    echo json_encode(["error" => "user_id và course_id là bắt buộc"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);

    echo json_encode([
        "message" => "Xóa user khỏi khóa học thành công",
        "rows_deleted" => $stmt->rowCount()
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
