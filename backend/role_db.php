<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>

    <!-- Load SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php
include('../backend/config/condb.php');
csrf_verify();

// Handle Create Operation
if (isset($_POST['role']) && $_POST['role'] == "add") {
    $name = $_POST['r_name'];

    $query = "INSERT INTO roles (name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $name);

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ!', 'เพิ่มข้อมูลเรียบร้อยแล้ว!', '../frontend/admin/roles.php');
    } else {
        redirect_with_swal('error', 'ผิดพลาด!', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล!', '../frontend/admin/roles.php');
    }
    $stmt->close();
}
// Handle Update Operation
elseif (isset($_POST['role']) && $_POST['role'] == "edit") {
    $id = (int)$_POST['r_id'];
    $name = $_POST['r_name'];

    $query = "UPDATE roles SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $name, $id);

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ!', 'อัปเดตข้อมูลเรียบร้อยแล้ว!', '../frontend/admin/roles.php');
    } else {
        redirect_with_swal('error', 'ผิดพลาด!', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล!', '../frontend/admin/roles.php');
    }
    $stmt->close();
} elseif (isset($_GET['role']) && $_GET['role'] == "del") {
    $id = (int)$_GET['r_id'];

    $query = "DELETE FROM roles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ!', 'ลบข้อมูลเรียบร้อยแล้ว!', '../frontend/admin/roles.php');
    } else {
        error_log("Delete error: " . $stmt->error);
        redirect_with_swal('error', 'ผิดพลาด!', 'เกิดข้อผิดพลาดในการลบข้อมูล!', '../frontend/admin/roles.php');
    }
    $stmt->close();
}
?>
</body>

</html>