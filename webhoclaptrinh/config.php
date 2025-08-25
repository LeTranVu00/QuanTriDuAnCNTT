<?php
// CORS HEADERS PHẢI ĐẶT ĐẦU TIÊN
header("Access-Control-Allow-Origin: http://127.0.0.1:59495");
header("Access-Control-Allow-Origin: http://localhost:8000");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Xử lý preflight request NGAY SAU CORS HEADERS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Sau đó mới kết nối database
$host = "localhost";
$dbname = "webhoclaptrinh";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}