<?php
require_once "../../config.php";
require_once "../../auth.php";
header("Content-Type: application/json");

if (!function_exists('requireRole')) {
    function requireRole(array $roles) {
        // Example implementation, adjust as needed
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            echo json_encode(["error" => "Unauthorized"]);
            exit;
        }
        return $_SESSION['user'] ?? null;
    }
}

$user = requireRole(['admin']); // chỉ admin được xóa

$id = $_GET['id'] ?? 0;
if (!$id) {
    echo json_encode(["error" => "Thiếu id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
