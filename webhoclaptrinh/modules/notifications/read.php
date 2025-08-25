<?php
require_once '../../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "Missing id"]);
    exit;
}

$stmt = $pdo->prepare("SELECT n.*, u.fullname 
                       FROM notifications n
                       JOIN users u ON n.user_id = u.id
                       WHERE n.id = ?");
$stmt->execute([$id]);
echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
