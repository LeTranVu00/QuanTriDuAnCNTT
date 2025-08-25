<?php
require_once '../../config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['message'])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$user_id = $data['user_id'];
$message = $data['message'];
$is_read = isset($data['is_read']) ? $data['is_read'] : 0;
$related_type = isset($data['related_type']) ? $data['related_type'] : null;
$related_id = isset($data['related_id']) ? $data['related_id'] : null;
$link = isset($data['link']) ? $data['link'] : null;

$stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, is_read, created_at, related_type, related_id, link)
                       VALUES (?, ?, ?, NOW(), ?, ?, ?)");
$success = $stmt->execute([$user_id, $message, $is_read, $related_type, $related_id, $link]);

echo json_encode(["success" => $success, "id" => $pdo->lastInsertId()]);
