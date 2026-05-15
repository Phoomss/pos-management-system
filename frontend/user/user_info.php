<?php
include_once '../../backend/config/condb.php';

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query_user = "SELECT u.*, r.name as role_name 
                FROM users u 
                INNER JOIN roles r ON u.role_id = r.id 
                WHERE u.id = $user_id";
$rs_user = mysqli_query($conn, $query_user) or die("Error: " . mysqli_error($conn));

if (mysqli_num_rows($rs_user) > 0) {
    $row = mysqli_fetch_array($rs_user);
} else {
    echo "No user found.";
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ส่วนตัว | ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>

        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper">
                <div class="mb-4">
                    <h1 class="h3 fw-bold mb-1">ข้อมูลส่วนตัว</h1>
                    <p class="text-muted small">จัดการข้อมูลบัญชีและรหัสผ่านของคุณ</p>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                                <h5 class="card-title fw-bold">แก้ไขข้อมูลส่วนตัว</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="../../backend/user_db.php" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="user" value="edit_profile_user">
                                    <input type="hidden" name="u_id" value="<?php echo $row['id']; ?>">

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-uppercase">ชื่อ-นามสกุล</label>
                                        <input name="u_name" type="text" class="form-control" required value="<?php echo htmlspecialchars($row['fullname']); ?>" minlength="3">
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-uppercase">ชื่อผู้ใช้งาน</label>
                                            <input name="u_username" type="text" class="form-control" required value="<?php echo htmlspecialchars($row['username']); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-uppercase">เบอร์โทร</label>
                                            <input name="u_phone" type="text" class="form-control" required value="<?php echo htmlspecialchars($row['phone']); ?>">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase">สิทธิ์การใช้งาน</label>
                                        <input disabled class="form-control bg-light border-0" value="<?php echo htmlspecialchars($row['role_name']); ?>">
                                    </div>

                                    <div class="p-3 bg-light rounded-3 mb-4">
                                        <label class="form-label small fw-bold text-uppercase mb-2 text-primary">เปลี่ยนรหัสผ่าน</label>
                                        <input id="u_password" name="u_password" type="password" class="form-control bg-white" placeholder="ทิ้งว่างไว้หากไม่ต้องการเปลี่ยน">
                                        <div class="form-text small mt-2">หากต้องการเปลี่ยนรหัสผ่าน ให้ระบุรหัสผ่านใหม่ลงในช่องด้านบน</div>
                                    </div>

                                    <button type="submit" class="btn btn-primary px-5 shadow-sm">บันทึกการแก้ไข</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center p-4">
                            <div class="card-body">
                                <img src="../user-info.png" class="rounded-circle shadow mb-3" width="120" height="120">
                                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($row['fullname']); ?></h4>
                                <p class="text-muted"><?php echo htmlspecialchars($row['role_name']); ?></p>
                                <hr class="my-4 opacity-50">
                                <div class="d-flex justify-content-around">
                                    <div class="text-center">
                                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Username</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['username']); ?></div>
                                    </div>
                                    <div class="text-center border-start ps-4">
                                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Phone</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['phone']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>
</body>

</html>
