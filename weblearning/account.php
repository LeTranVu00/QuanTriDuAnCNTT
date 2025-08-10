<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];

// Lấy thông tin từ user_profiles
$sql = "SELECT * FROM user_profiles WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
        }
        .profile-card {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        .profile-card h2 {
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-save {
            background-color: #4CAF50;
            color: white;
        }
        .btn-save:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="profile-card">
    <h2>Thông tin cá nhân</h2>
    <form action="process_account.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($profile['fullname'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($profile['address'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" name="birthdate" value="<?php echo htmlspecialchars($profile['birthdate'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Khóa học đã đăng ký</label>
            <input type="text" class="form-control" name="registered_courses" value="<?php echo htmlspecialchars($profile['registered_courses'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-save w-100">💾 Lưu thông tin</button>
    </form>
</div>

</body>
</html>
