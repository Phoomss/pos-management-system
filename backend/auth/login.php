<?php
include("../config/condb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['u_username'];
    $password = $_POST['u_password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Support both plain text (legacy) and hashed passwords for now
        if ($password === $user['password'] || password_verify($password, $user['password'])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["fullname"] = $user["fullname"];
            $_SESSION["role_id"] = (int)$user["role_id"];

            $redirect = ($user['role_id'] == 1) ? '../../frontend/admin/index.php' : '../../frontend/user/index.php';
            redirect_with_swal('success', 'สำเร็จ!', 'ยินดีต้อนรับ ' . $user['fullname'], $redirect);
        } else {
            redirect_with_swal('error', 'ผิดพลาด!', 'รหัสผ่านไม่ถูกต้อง', '../../index.php');
        }
    } else {
        redirect_with_swal('error', 'ผิดพลาด!', 'ไม่พบชื่อผู้ใช้งานนี้', '../../index.php');
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
</head>
<body>
</body>
</html>