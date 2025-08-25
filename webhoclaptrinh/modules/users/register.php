<?php
require_once __DIR__ . "/../../config.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$fullname = $data['fullname'] ?? null;
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$role = $data['role'] ?? 'user';
$avatar = $data['avatar'] ?? null;

if (!$fullname || !$email || !$password) {
    echo json_encode(["error" => "Thiếu dữ liệu bắt buộc"]);
    exit;
}

try {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role, avatar, created_at) 
                           VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$fullname, $email, $hashedPassword, $role, $avatar]);

    echo json_encode(["success" => "Đăng ký thành công"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
