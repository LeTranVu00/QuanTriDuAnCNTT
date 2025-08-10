<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$birthdate = $_POST['birthdate'] ?? null;
$registered_courses = $_POST['registered_courses'] ?? '';

// Kiểm tra user đã có thông tin chưa
$sql = "SELECT username FROM user_profiles WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Cập nhật
    $update_sql = "UPDATE user_profiles 
                   SET fullname=?, email=?, phone=?, address=?, birthdate=?, registered_courses=? 
                   WHERE username=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssss", $fullname, $email, $phone, $address, $birthdate, $registered_courses, $username);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    // Thêm mới
    $insert_sql = "INSERT INTO user_profiles (username, fullname, email, phone, address, birthdate, registered_courses) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sssssss", $username, $fullname, $email, $phone, $address, $birthdate, $registered_courses);
    $insert_stmt->execute();
    $insert_stmt->close();
}

$stmt->close();
$conn->close();

echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href='account.php';</script>";
?>
