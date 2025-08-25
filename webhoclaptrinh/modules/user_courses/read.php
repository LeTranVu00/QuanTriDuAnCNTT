<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../auth.php";
header("Content-Type: application/json");

// Chỉ cho admin
if (!checkAuth('admin')) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

try {
    $stmt = $pdo->query("
        SELECT uc.user_id, u.fullname, uc.course_id, c.title, uc.created_at
        FROM user_courses uc
        JOIN users u ON uc.user_id = u.id
        JOIN courses c ON uc.course_id = c.id
        ORDER BY uc.created_at DESC
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
