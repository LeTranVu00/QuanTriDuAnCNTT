<?php
require_once "../../config.php";

$sql = "SELECT r.*, u.fullname, c.title as course_title
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN courses c ON r.course_id = c.id
        ORDER BY r.created_at DESC";

$stmt = $pdo->query($sql);

try {
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reviews);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
