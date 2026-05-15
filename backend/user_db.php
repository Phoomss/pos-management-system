<?php
include_once('../backend/config/condb.php');
csrf_verify();

if (isset($_POST['user']) && $_POST['user'] == 'add') {
    $fullname = $_POST['u_name'];
    $username = $_POST['u_username'];
    $password = $_POST['u_password'];
    $phone = $_POST['u_phone'];
    $role = (int)$_POST['r_id'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, phone, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $fullname, $username, $hashedPassword, $phone, $role);

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ', 'เพิ่มข้อมูลผู้ใช้งานเรียบร้อยแล้ว', '../frontend/admin/users.php');
    } else {
        redirect_with_swal('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถเพิ่มข้อมูลได้', '../frontend/admin/users.php');
    }
    $stmt->close();
} elseif (isset($_POST['user']) && $_POST['user'] == 'update') {
    $userId = (int)$_POST['u_id'];
    $fullname = $_POST['u_name'];
    $username = $_POST['u_username'];
    $phone = $_POST['u_phone'];
    $role = (int)$_POST['r_id'];
    $password = $_POST['u_password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname = ?, username = ?, password = ?, phone = ?, role_id = ? WHERE id = ?");
        $stmt->bind_param("ssssii", $fullname, $username, $hashedPassword, $phone, $role, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname = ?, username = ?, phone = ?, role_id = ? WHERE id = ?");
        $stmt->bind_param("sssii", $fullname, $username, $phone, $role, $userId);
    }

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ', 'ข้อมูลผู้ใช้งานถูกอัปเดตเรียบร้อยแล้ว', '../frontend/admin/users.php');
    } else {
        redirect_with_swal('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถอัปเดตข้อมูลได้', '../frontend/admin/users.php');
    }
    $stmt->close();
} elseif (isset($_GET['user']) && $_GET['user'] == 'del') {
    $userId = (int)$_GET['u_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ', 'ข้อมูลผู้ใช้งานถูกลบเรียบร้อยแล้ว', '../frontend/admin/users.php');
    } else {
        redirect_with_swal('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถลบข้อมูลได้', '../frontend/admin/users.php');
    }
    $stmt->close();
} elseif (isset($_POST['user']) && ($_POST['user'] == "edit_profile" || $_POST['user'] == "edit_profile_user")) {
    $userId = (int)$_POST['u_id'];
    $fullname = $_POST['u_name'];
    $username = $_POST['u_username'];
    $phone = $_POST['u_phone'];
    $password = $_POST['u_password'];
    $redirect = ($_POST['user'] == "edit_profile") ? '../frontend/admin/user_info.php' : '../frontend/user/user_info.php';

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname = ?, username = ?, password = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $fullname, $username, $hashedPassword, $phone, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname = ?, username = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $fullname, $username, $phone, $userId);
    }

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ', 'ข้อมูลโปรไฟล์ของคุณถูกอัปเดตเรียบร้อยแล้ว', $redirect);
    } else {
        redirect_with_swal('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถอัปเดตโปรไฟล์ได้', $redirect);
    }
    $stmt->close();
}
?>