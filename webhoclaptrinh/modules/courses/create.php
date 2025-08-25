<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . '/../../auth.php';

// ✅ Chỉ cho admin
$auth = checkAuth($pdo);
if (!$auth || $auth['role'] !== 'admin') {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Nhận dữ liệu
$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'] ?? null;
$description = $data['description'] ?? null;
$price = $data['price'] ?? 0;
$image = $data['image'] ?? null;
$category_id = $data['category_id'] ?? null;

if (!$title || !$price || !$category_id) {
    echo json_encode(["error" => "Thiếu dữ liệu bắt buộc (title, price, category_id)"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO courses (title, description, price, image, category_id, created_at)
        VALUES (:title, :description, :price, :image, :category_id, NOW())
    ");
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':price' => $price,
        ':image' => $image,
        ':category_id' => $category_id
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Thêm khóa học thành công",
        "id" => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
