<?php
require_once __DIR__ . "/../../config.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Xử lý preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$testUsers = [
    'admin@example.com' => [
        'id' => 1,
        'email' => 'admin@example.com',
        'fullname' => 'Admin User',
        'role' => 'admin'
    ],
    'user@example.com' => [
        'id' => 2,
        'email' => 'user@example.com', 
        'fullname' => 'Regular User',
        'role' => 'user'
    ]
];

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

// =================== LOGIN THẬT TỪ DATABASE ===================
if ($email && $password) {
    try {
        $stmt = $pdo->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Giả sử bạn muốn tạo token (nếu không thì bỏ)
            $token = base64_encode(random_bytes(16));

            echo json_encode([
                "success" => true,
                "message" => "Đăng nhập thành công",
                "user" => [
                    "id" => $user['id'],
                    "fullname" => $user['fullname'],
                    "email" => $user['email'],
                    "role" => $user['role']
                ],
                "token" => $token
            ]);
            exit;
        } else {
            echo json_encode(["success" => false, "error" => "Email hoặc mật khẩu không đúng"]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => "Lỗi hệ thống: " . $e->getMessage()]);
        exit;
    }
}

// =================== LOGIN GIẢ (TEST MODE) ===================
if ($email && isset($testUsers[$email])) {
    $user = $testUsers[$email];
    $token = base64_encode(random_bytes(16));

    echo json_encode([
        "success" => true,
        "message" => "Đăng nhập thành công (TEST MODE)",
        "user" => $user,
        "token" => $token
    ]);
} else {
    echo json_encode(["success" => false, "error" => "Email hoặc mật khẩu không đúng (TEST MODE)"]);
}
