<?php
require_once __DIR__ . "/config.php";

// Lấy user hiện tại (demo: query param user_id, có thể thay bằng session/token)
function getCurrentUser() {
    global $pdo;
    $user_id = $_GET['user_id'] ?? null;
    if (!$user_id) return null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Kiểm tra quyền admin
function isAdmin($user) {
    return $user && $user['role'] === 'admin';
}

// Yêu cầu đăng nhập
function requireLogin() {
    $user = getCurrentUser();
    if (!$user) {
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }
    return $user;
}

// Yêu cầu quyền admin
function requireAdmin() {
    $user = requireLogin();
    if ($user['role'] !== 'admin') {
        echo json_encode(["error" => "Forbidden - Admin only"]);
        exit;
    }
    return $user;
}

// Định nghĩa hàm checkAuth nếu chưa có
if (!function_exists('checkAuth')) {
    function checkAuth($pdo) {
        // Ví dụ: kiểm tra session và lấy thông tin user từ database
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: false;
    }
}


// Định nghĩa hàm getAuthUser nếu chưa có
if (!function_exists('getAuthUser')) {
    function getAuthUser() {
        // Ví dụ: kiểm tra session hoặc token để lấy thông tin user
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }
}
