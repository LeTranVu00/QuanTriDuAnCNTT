<?php
require_once __DIR__ . "/../../config.php";
header("Content-Type: application/json");

try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
