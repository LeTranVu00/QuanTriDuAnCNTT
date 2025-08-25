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
    $stmt = $pdo->prepare("DELETE FROM lessons WHERE id = ?");
    $stmt->execute([$data["id"]]);

    echo json_encode(["message" => "Xóa bài học thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
