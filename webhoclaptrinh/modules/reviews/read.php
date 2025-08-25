<?php
require_once "../../config.php";

$id = $_GET["id"] ?? null;
if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

$sql = "SELECT r.*, u.fullname, c.title as course_title
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN courses c ON r.course_id = c.id
        WHERE r.id = ?";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([$id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($review);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
