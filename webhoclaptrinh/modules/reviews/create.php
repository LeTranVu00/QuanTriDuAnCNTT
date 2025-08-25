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
if (!isset($data["course_id"], $data["rating"])) {
    echo json_encode(["error" => "course_id, rating là bắt buộc"]);
    exit;
}

$course_id = $data["course_id"];
$rating = $data["rating"];
$comment = $data["comment"] ?? null;

$sql = "INSERT INTO reviews (user_id, course_id, rating, comment, created_at)
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([$user['id'], $course_id, $rating, $comment]);
    echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
