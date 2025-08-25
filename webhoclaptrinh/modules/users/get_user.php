<?php
require_once __DIR__ . "/../../config.php";
header("Content-Type: application/json");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, fullname, email, role, avatar, created_at FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($user ?: ["error" => "Không tìm thấy user"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
