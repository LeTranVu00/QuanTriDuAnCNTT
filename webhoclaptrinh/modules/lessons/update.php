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

if (!$data || !isset($data["id"])) {
    echo json_encode(["error" => "id là bắt buộc"]);
    exit;
}

try {
    $sql = "UPDATE lessons
            SET course_id = :course_id,
                title = :title,
                video_url = :video_url,
                description = :description,
                lesson_order = :lesson_order,
                duration = :duration,
                is_free = :is_free,
                attachment_url = :attachment_url,
                updated_at = NOW()
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id" => $data["id"],
        ":course_id" => $data["course_id"] ?? null,
        ":title" => $data["title"] ?? null,
        ":video_url" => $data["video_url"] ?? null,
        ":description" => $data["description"] ?? null,
        ":lesson_order" => $data["lesson_order"] ?? null,
        ":duration" => $data["duration"] ?? null,
        ":is_free" => $data["is_free"] ?? 0,
        ":attachment_url" => $data["attachment_url"] ?? null
    ]);

    echo json_encode(["message" => "Cập nhật bài học thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
