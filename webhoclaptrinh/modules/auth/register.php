<?php
require_once __DIR__ . "/../../config.php";

// ========================================================
// CODE MỚI: xử lý khi form HTML submit trực tiếp (dùng $_POST)
// ========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fullname'])) {
    $fullname = $_POST['fullname'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $role = 'user';

    if (!$fullname || !$email || !$password) {
        die("Vui lòng nhập đầy đủ thông tin");
    }

    try {
        // Kiểm tra email đã tồn tại
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            die("Email đã được sử dụng");
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Tạo user mới
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$fullname, $email, $hashedPassword, $role]);

        // ✅ Đăng ký thành công → chuyển hướng login
        header("Location: login.html");
        exit();

    } catch (PDOException $e) {
        die("Lỗi hệ thống: " . $e->getMessage());
    }
}

// ========================================================
// CODE CŨ: xử lý API JSON (fetch từ JS) → vẫn giữ nguyên
// ========================================================
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Xử lý preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Chỉ hỗ trợ phương thức POST"]);
    exit;
}

// Lấy dữ liệu từ request JSON
$data = json_decode(file_get_contents("php://input"), true);
$fullname = $data['fullname'] ?? null;
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$role = $data['role'] ?? 'user';

if (!$fullname || !$email || !$password) {
    echo json_encode(["error" => "Vui lòng điền đầy đủ thông tin"]);
    exit;
}

try {
    // Kiểm tra email đã tồn tại
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo json_encode(["error" => "Email đã được sử dụng"]);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Tạo user mới
    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$fullname, $email, $hashedPassword, $role]);

    echo json_encode([
        "success" => true,
        "message" => "Đăng ký thành công"
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Lỗi hệ thống: " . $e->getMessage()]);
}
