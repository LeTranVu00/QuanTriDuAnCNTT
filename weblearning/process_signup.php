<?php
require 'config.php';

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Kiểm tra mật khẩu nhập lại
if ($password !== $confirm_password) {
    echo "<script>
            alert('Mật khẩu nhập lại không khớp!');
            window.history.back();
          </script>";
    exit();
}

// Kiểm tra username hoặc email đã tồn tại chưa
$check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $username, $email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "<script>
            alert('Tên người dùng hoặc email đã tồn tại!');
            window.location.href = 'signup.html';
          </script>";
    exit();
}

// Mã hóa mật khẩu
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Thêm tài khoản
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
    echo "<script>
            alert('Đăng ký thành công! Mời bạn đăng nhập.');
            window.location.href = 'login.html';
          </script>";
} else {
    echo "<script>
            alert('Có lỗi xảy ra, vui lòng thử lại!');
            window.location.href = 'signup.html';
          </script>";
}

$stmt->close();
$conn->close();
?>
