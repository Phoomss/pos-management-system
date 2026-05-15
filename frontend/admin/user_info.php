<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
    <style>
        .content-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .form-control {
            margin-bottom: 1rem;
        }

        .custom-file-label::after {
            content: "Browse";
        }

        #blah {
            display: none;
            margin-top: 1rem;
        }
    </style>
</head>

<?php
include '../../backend/config/condb.php';

// Check if user is logged in and the session variable is set
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$query_user = "SELECT u.id, u.fullname, u.username, u.phone, u.password, u.role_id, r.name as role_name 
                FROM users u 
                INNER JOIN roles r ON u.role_id = r.id 
                WHERE u.id = $user_id";
$rs_user = mysqli_query($conn, $query_user) or die("Error: " . mysqli_error($conn));

if (mysqli_num_rows($rs_user) > 0) {
    $row = mysqli_fetch_array($rs_user);
} else {
    echo "No user found with this ID.";
    exit();
}
$conn->close();
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('../layout/header.php') ?>
        <?php include_once('./sidenav.php') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper center">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">โปรไฟล์ผู้ใช้งาน</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ข้อมูลโปรไฟล์</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../../backend/user_db.php" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="user" value="edit_profile">
                                        <input type="hidden" name="u_id" value="<?php echo $row['id']; ?>">

                                        <!-- Full Name -->
                                        <div class="form-group">
                                            <label for="u_name">ชื่อ-นามสกุล</label>
                                            <input name="u_name" type="text" class="form-control" required placeholder="ชื่อ-นามสกุล" value="<?php echo htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8'); ?>" minlength="3">
                                        </div>

                                        <!-- Username -->
                                        <div class="form-group">
                                            <label for="u_username">ชื่อผู้ใช้งาน</label>
                                            <input name="u_username" type="text" class="form-control" required placeholder="ชื่อผู้ใช้งาน" value="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <!-- Phone -->
                                        <div class="form-group">
                                            <label for="u_phone">เบอร์โทร</label>
                                            <input name="u_phone" type="text" class="form-control" required placeholder="เบอร์โทร" value="<?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <!-- User Role -->
                                        <div class="form-group">
                                            <label for="r_id">สถานะผู้ใช้งาน</label>
                                            <input disabled name="r_id" type="text" class="form-control" readonly value="<?php echo htmlspecialchars($row['role_name'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="u_password">รหัสผ่าน (ใส่เฉพาะถ้าต้องการเปลี่ยน)</label>
                                            <input id="u_password" name="u_password" type="password" class="form-control" placeholder="รหัสผ่าน">
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary btn-block">อัปเดตข้อมูลผู้ใช้งาน</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include_once('../layout/footer.php') ?>
        <?php include_once('../layout/config/script.php') ?>

        <script>
            // Optional JavaScript
        </script>
    </div>
</body>

</html>