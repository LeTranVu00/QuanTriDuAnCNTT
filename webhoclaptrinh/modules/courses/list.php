<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../../config.php";

try {
    $stmt = $pdo->query("
        SELECT c.id, c.title, c.description, c.price, c.image, c.created_at,
               cat.name AS category_name
        FROM courses c
        LEFT JOIN categories cat ON c.category_id = cat.id
        ORDER BY c.created_at DESC
    ");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($courses, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
