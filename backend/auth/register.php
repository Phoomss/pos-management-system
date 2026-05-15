<?php
include("../config/condb.php");
csrf_verify();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['u_name'];
    $username = $_POST['u_username'];
    $password = $_POST['u_password'];
    $phone = $_POST['u_phone'];
    $role = 2; // Default user role

    $checkUser = $conn->prepare("SELECT u_id FROM users_table WHERE u_username = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        $checkUser->close();
        redirect_with_swal('error', 'ชื่อผู้ใช้งานซ้ำ', 'ชื่อผู้ใช้งานนี้ถูกใช้แล้ว กรุณาใช้ชื่อผู้ใช้งานอื่น!', '../../register.php');
    } else {
        $checkUser->close();
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users_table (u_name, u_username, u_password, u_phone, r_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $fullname, $username, $hashedPassword, $phone, $role);

        if ($stmt->execute()) {
            $stmt->close();
            redirect_with_swal('success', 'สมัครเข้าใช้งานสำเร็จ!', 'คุณได้ทำการสมัครสมาชิกเรียบร้อยแล้ว', '../../index.php');
        } else {
            $error = $stmt->error;
            $stmt->close();
            redirect_with_swal('error', 'เกิดข้อผิดพลาด', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . addslashes($error), '../../register.php');
        }
    }
}
$conn->close();
?>
