<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";

header("Content-Type: application/json");

// Chỉ cho admin
if (!checkAuth("admin")) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["course_id"]) || !isset($data["title"])) {
    echo json_encode(["error" => "course_id và title là bắt buộc"]);
    exit;
}

try {
    $sql = "INSERT INTO lessons (
                course_id, title, video_url, description,
                lesson_order, duration, is_free, attachment_url,
                created_at, updated_at
            ) VALUES (
                :course_id, :title, :video_url, :description,
                :lesson_order, :duration, :is_free, :attachment_url,
                NOW(), NOW()
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":course_id" => $data["course_id"],
        ":title" => $data["title"],
        ":video_url" => $data["video_url"] ?? null,
        ":description" => $data["description"] ?? null,
        ":lesson_order" => $data["lesson_order"] ?? null,
        ":duration" => $data["duration"] ?? null,
        ":is_free" => $data["is_free"] ?? 0,
        ":attachment_url" => $data["attachment_url"] ?? null
    ]);

    echo json_encode(["message" => "Tạo bài học thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
