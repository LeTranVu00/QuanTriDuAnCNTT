<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ cho admin hoặc user chính mình tạo
if (!checkAuth('admin') && !checkAuth('user')) {
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
    $stmt = $pdo->prepare("
        INSERT INTO user_courses (user_id, course_id, created_at)
        VALUES (?, ?, NOW())
    ");
    $stmt->execute([$user_id, $course_id]);

    echo json_encode(["message" => "Thêm user vào khóa học thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
