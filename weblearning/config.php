<?php
// Thông tin kết nối
$servername = "localhost"; // hoặc 127.0.0.1
$username = "root";        // tài khoản MySQL
$password = "";            // mật khẩu MySQL (nếu có)
$dbname = "weblearning";  // tên CSDL

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
//echo "Kết nối thành công!";

?>
