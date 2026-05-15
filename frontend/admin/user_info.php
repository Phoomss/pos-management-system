<?php
include_once '../../backend/config/condb.php';

// Check if user is logged in and the session variable is set
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: ../../index.php");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ผู้จัดการระบบ | ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>

        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper">
                <!-- Page Header -->
                <div class="mb-4">
                    <h1 class="h3 fw-bold mb-1">ข้อมูลส่วนตัวผู้ดูแลระบบ</h1>
                    <p class="text-muted small">จัดการข้อมูลบัญชีและรหัสผ่านสำหรับสิทธิ์แอดมิน</p>
                </div>

                <div class="row g-4">
                    <!-- Left: Profile Edit Form -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                                <h5 class="card-title fw-bold mb-0">แก้ไขข้อมูลบัญชี</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="../../backend/user_db.php" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="user" value="edit_profile">
                                    <input type="hidden" name="u_id" value="<?php echo $row['id']; ?>">

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">ชื่อ-นามสกุล</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-id-card text-muted"></i></span>
                                            <input name="u_name" type="text" class="form-control bg-light border-0" required placeholder="ชื่อ-นามสกุล" value="<?php echo htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8'); ?>" minlength="3">
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-uppercase text-muted">ชื่อผู้ใช้งาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                                <input name="u_username" type="text" class="form-control bg-light border-0" required placeholder="ชื่อผู้ใช้งาน" value="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-uppercase text-muted">เบอร์โทรศัพท์</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i class="fas fa-phone text-muted"></i></span>
                                                <input name="u_phone" type="text" class="form-control bg-light border-0" required placeholder="เบอร์โทร" value="<?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">ระดับสิทธิ์ปัจจุบัน</label>
                                        <div class="d-flex align-items-center bg-dark bg-opacity-10 p-3 rounded-3">
                                            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                                <i class="fas fa-shield-alt small"></i>
                                            </div>
                                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['role_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <input disabled name="r_id" type="hidden" value="<?php echo htmlspecialchars($row['role_name'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>

                                    <hr class="my-4 opacity-50">

                                    <div class="p-4 bg-primary bg-opacity-10 rounded-4 mb-4 border border-primary border-opacity-10">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-key me-2"></i> ความปลอดภัยและรหัสผ่าน</h6>
                                        <label class="form-label small fw-bold text-uppercase text-primary text-opacity-75">รหัสผ่านใหม่ (ระบุเมื่อต้องการเปลี่ยนเท่านั้น)</label>
                                        <input id="u_password" name="u_password" type="password" class="form-control border-primary border-opacity-25" placeholder="••••••••">
                                        <div class="form-text small mt-2">คำแนะนำ: ใช้รหัสผ่านที่ประกอบด้วยตัวอักษรและตัวเลขอย่างน้อย 8 ตัวเพื่อความปลอดภัย</div>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> บันทึกการเปลี่ยนแปลงข้อมูล
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Profile Card Summary -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm overflow-hidden h-100">
                            <div class="bg-primary py-5 text-center">
                                <div class="position-relative d-inline-block">
                                    <img src="../user-info.png" class="rounded-circle border border-4 border-white shadow" width="140" height="140">
                                    <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-2" title="Online Status"></span>
                                </div>
                            </div>
                            <div class="card-body p-4 text-center mt-n4">
                                <h4 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                <p class="text-muted mb-4"><?php echo htmlspecialchars($row['role_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                
                                <div class="row g-3 py-3 border-top border-bottom">
                                    <div class="col-6 border-end text-center">
                                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Username</div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Phone</div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-2">
                                    <div class="alert alert-warning border-0 small shadow-sm">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        คุณกำลังเข้าถึงในฐานะผู้ดูแลระบบ การเปลี่ยนแปลงข้อมูลสำคัญจะมีผลต่อความปลอดภัยของระบบโดยรวม
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
