<!DOCTYPE html>
<html lang="en">

<?php
include('../backend/config/condb.php');
csrf_verify();

if (isset($_POST['user']) && $_POST['user'] == 'add') {
    $fullname = $_POST['u_name'];
    $username = $_POST['u_username'];
    $password = $_POST['u_password'];
    $phone = $_POST['u_phone'];
    $role = (int)$_POST['r_id'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users_table (u_name, u_username, u_password, u_phone, r_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $fullname, $username, $hashedPassword, $phone, $role);

    if ($stmt->execute()) {
        redirect_with_swal('success', 'สำเร็จ', 'เพิ่มข้อมูลผู้ใช้งานเรียบร้อยแล้ว', '../frontend/admin/users.php');
    } else {
        redirect_with_swal('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถเพิ่มข้อมูลได้', '../frontend/admin/users.php');
    }
    $stmt->close();
}
// ... rest of the file ...
 elseif (isset($_POST['user']) && $_POST['user'] == 'update') {
        $userId = (int)$_POST['u_id'];
        $fullname = $_POST['u_name'];
        $username = $_POST['u_username'];
        $phone = $_POST['u_phone'];
        $role = (int)$_POST['r_id'];
        $password = $_POST['u_password'];

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users_table SET u_name = ?, u_username = ?, u_password = ?, u_phone = ?, r_id = ? WHERE u_id = ?");
            $stmt->bind_param("ssssii", $fullname, $username, $hashedPassword, $phone, $role, $userId);
        } else {
            $stmt = $conn->prepare("UPDATE users_table SET u_name = ?, u_username = ?, u_phone = ?, r_id = ? WHERE u_id = ?");
            $stmt->bind_param("ssiii", $fullname, $username, $phone, $role, $userId);
        }

        if ($stmt->execute()) {
            echo "<script>
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'ข้อมูลผู้ใช้งานถูกอัปเดตเรียบร้อยแล้ว' }).then(() => { window.location = '../frontend/admin/users.php'; });
            </script>";
        } else {
            echo "<script>
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถอัปเดตข้อมูลได้' }).then(() => { window.location = '../frontend/admin/users.php'; });
            </script>";
        }
        $stmt->close();

    } elseif (isset($_GET['user']) && $_GET['user'] == 'del') {
        $userId = (int)$_GET['u_id'];
        $stmt = $conn->prepare("DELETE FROM users_table WHERE u_id = ?");
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            echo "<script>
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'ข้อมูลผู้ใช้งานถูกลบเรียบร้อยแล้ว' }).then(() => { window.location = '../frontend/admin/users.php'; });
            </script>";
        } else {
            echo "<script>
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถลบข้อมูลได้' }).then(() => { window.location = '../frontend/admin/users.php'; });
            </script>";
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
            $stmt = $conn->prepare("UPDATE users_table SET u_name = ?, u_username = ?, u_password = ?, u_phone = ? WHERE u_id = ?");
            $stmt->bind_param("ssssi", $fullname, $username, $hashedPassword, $phone, $userId);
        } else {
            $stmt = $conn->prepare("UPDATE users_table SET u_name = ?, u_username = ?, u_phone = ? WHERE u_id = ?");
            $stmt->bind_param("sssi", $fullname, $username, $phone, $userId);
        }

        if ($stmt->execute()) {
            echo "<script>
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'ข้อมูลโปรไฟล์ของคุณถูกอัปเดตเรียบร้อยแล้ว' }).then(() => { window.location = '$redirect'; });
            </script>";
        } else {
            echo "<script>
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถอัปเดตโปรไฟล์ได้' }).then(() => { window.location = '$redirect'; });
            </script>";
        }
        $stmt->close();
    }
    ?>


</body>

</html>