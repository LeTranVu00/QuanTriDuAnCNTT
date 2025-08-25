<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../../config.php";

try {
    $sql = "SELECT c.*, u.fullname 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC";
    $stmt = $pdo->query($sql);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($comments, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
