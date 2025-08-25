<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . '/../../auth.php';
header("Content-Type: application/json");

// Chỉ cho admin
if (!checkAuth('admin')) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, fullname, email, role, avatar, created_at FROM users");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
