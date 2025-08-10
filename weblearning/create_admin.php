<?php
require 'config.php'; // Gọi file kết nối CSDL

// Tài khoản mẫu
$username = 'admin';
$password = password_hash('123456', PASSWORD_DEFAULT); // Mã hóa mật khẩu

// Câu lệnh thêm user
$sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

// Thực thi
if ($conn->query($sql) === TRUE) {
    echo "Tạo tài khoản admin thành công!";
    echo "<br>Username: admin";
    echo "<br>Password: 123456";
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();
?>
