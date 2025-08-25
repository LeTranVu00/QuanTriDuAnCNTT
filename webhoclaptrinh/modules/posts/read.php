<?php
require_once "../../config.php";

try {
    $stmt = $pdo->query("
        SELECT p.*, u.fullname as author, c.name as category
        FROM posts p
        JOIN users u ON p.user_id = u.id
        JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
