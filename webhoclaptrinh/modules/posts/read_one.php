<?php
require_once "../../config.php";

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("
        SELECT p.*, u.fullname as author, c.name as category
        FROM posts p
        JOIN users u ON p.user_id = u.id
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
