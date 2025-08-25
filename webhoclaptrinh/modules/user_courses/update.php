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

$old_user_id = $data['old_user_id'] ?? null;
$old_course_id = $data['old_course_id'] ?? null;
$new_user_id = $data['new_user_id'] ?? null;
$new_course_id = $data['new_course_id'] ?? null;

if (!$old_user_id || !$old_course_id || !$new_user_id || !$new_course_id) {
    echo json_encode(["error" => "Thiếu dữ liệu bắt buộc"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE user_courses
        SET user_id = ?, course_id = ?
        WHERE user_id = ? AND course_id = ?
    ");
    $stmt->execute([$new_user_id, $new_course_id, $old_user_id, $old_course_id]);

    echo json_encode([
        "message" => "Cập nhật user_courses thành công",
        "rows_affected" => $stmt->rowCount()
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
