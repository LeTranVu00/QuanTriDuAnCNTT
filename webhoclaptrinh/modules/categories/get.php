<?php
require_once __DIR__ . "/../../config.php";
header("Content-Type: application/json");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        echo json_encode($category);
    } else {
        echo json_encode(["error" => "Không tìm thấy category"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
