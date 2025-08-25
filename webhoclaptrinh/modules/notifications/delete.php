<?php
require_once '../../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "Missing id"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ?");
$success = $stmt->execute([$id]);

echo json_encode(["success" => $success]);
