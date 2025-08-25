<?php
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . '/../../auth.php';
header("Content-Type: application/json");

// Chỉ cho admin
if (!checkAuth('admin')) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$fullname = $data['fullname'] ?? null;
$role = $data['role'] ?? null;
$avatar = $data['avatar'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE users SET fullname = ?, role = ?, avatar = ? WHERE id = ?");
    $stmt->execute([$fullname, $role, $avatar, $id]);

    echo json_encode(["success" => "Cập nhật thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
