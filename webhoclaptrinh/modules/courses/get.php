<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../config.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT c.*, cat.name AS category_name
        FROM courses c
        LEFT JOIN categories cat ON c.category_id = cat.id
        WHERE c.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {
        echo json_encode($course, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "Không tìm thấy khóa học"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
