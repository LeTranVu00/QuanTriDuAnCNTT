<?php
require_once '../../config.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
