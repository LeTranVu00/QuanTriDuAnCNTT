<?php
require_once '../../config.php';
header("Content-Type: application/json");

// Lấy tất cả order_details
if (!isset($_GET['id'])) {
    $sql = "SELECT od.*, o.id AS order_id, c.title AS course_title
            FROM order_details od
            JOIN orders o ON od.order_id = o.id
            JOIN courses c ON od.course_id = c.id";
    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// Lấy chi tiết 1 order_detail
$sql = "SELECT od.*, o.id AS order_id, c.title AS course_title
        FROM order_details od
        JOIN orders o ON od.order_id = o.id
        JOIN courses c ON od.course_id = c.id
        WHERE od.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
