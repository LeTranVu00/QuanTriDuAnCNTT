<?php
require_once '../../config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["error" => "Missing id"]);
    exit;
}

$fields = [];
$params = [];
if (isset($data['message'])) {
    $fields[] = "message = ?";
    $params[] = $data['message'];
}
if (isset($data['is_read'])) {
    $fields[] = "is_read = ?";
    $params[] = $data['is_read'];
}
if (isset($data['link'])) {
    $fields[] = "link = ?";
    $params[] = $data['link'];
}
$params[] = $data['id'];

$sql = "UPDATE notifications SET " . implode(", ", $fields) . " WHERE id = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute($params);

echo json_encode(["success" => $success]);
