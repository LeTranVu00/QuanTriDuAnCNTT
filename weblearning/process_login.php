<?php
session_start();
require 'config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); // Đăng nhập thành công → về trang chủ
        exit();
    } else {
        // Sai mật khẩu
        echo "<script>
                alert('Tài khoản hoặc mật khẩu không chính xác!');
                window.location.href = 'login.html';
              </script>";
        exit();
    }
} else {
    // Không tìm thấy tài khoản
    echo "<script>
            alert('Tài khoản hoặc mật khẩu không chính xác!');
            window.location.href = 'login.html';
          </script>";
    exit();
}

$stmt->close();
$conn->close();
?>
