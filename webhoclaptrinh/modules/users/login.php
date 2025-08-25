<?php
require_once __DIR__ . "/../../config.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

if (!$email || !$password) {
    echo json_encode(["error" => "Thiếu email hoặc mật khẩu"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']); 
        echo json_encode(["success" => "Đăng nhập thành công", "user" => $user]);
    } else {
        echo json_encode(["error" => "Sai email hoặc mật khẩu"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
