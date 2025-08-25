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

$id = $data['id'] ?? null;
$title = $data['title'] ?? null;
$description = $data['description'] ?? null;
$price = $data['price'] ?? null;
$image = $data['image'] ?? null;
$category_id = $data['category_id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE courses
        SET title = :title, description = :description, price = :price, 
            image = :image, category_id = :category_id
        WHERE id = :id
    ");
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':price' => $price,
        ':image' => $image,
        ':category_id' => $category_id,
        ':id' => $id
    ]);

    echo json_encode(["success" => true, "message" => "Cập nhật khóa học thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
