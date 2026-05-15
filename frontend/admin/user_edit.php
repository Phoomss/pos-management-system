<?php
include_once '../../backend/config/condb.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure u_id is set and is numeric to prevent SQL injection
if (isset($_GET['u_id']) && is_numeric($_GET['u_id'])) {
    $u_id = $_GET['u_id'];

    $query_user = "SELECT u.id, u.fullname, u.username, u.phone, u.password, u.role_id, r.name as role_name 
                    FROM users u INNER JOIN roles r ON u.role_id = r.id 
                    WHERE u.id = $u_id";
    $rs_user = mysqli_query($conn, $query_user) or die("Error: " . mysqli_error($conn));

    if (mysqli_num_rows($rs_user) > 0) {
        $row = mysqli_fetch_array($rs_user);
    } else {
        echo "No user found with this ID.";
        exit();
    }
} else {
    echo "Invalid User ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้งาน | ข้าวมันไก่น้องนัน</title>
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
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="h3 fw-bold mb-1">แก้ไขข้อมูลผู้ใช้งาน</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.php">แดชบอร์ด</a></li>
                                    <li class="breadcrumb-item"><a href="users.php">จัดการผู้ใช้งาน</a></li>
                                    <li class="breadcrumb-item active">แก้ไขข้อมูล</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="users.php" class="btn btn-white border shadow-sm px-4">
                            <i class="fas fa-arrow-left me-2"></i> ย้อนกลับ
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Left: Edit Form -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                                <h5 class="card-title fw-bold mb-0">แบบฟอร์มแก้ไขข้อมูล</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="../../backend/user_db.php" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="user" value="update">
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
                                        <label class="form-label small fw-bold text-uppercase text-muted">สิทธิ์การใช้งาน</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-shield-alt text-muted"></i></span>
                                            <select id="r_id" name="r_id" class="form-select bg-light border-0" required>
                                                <?php
                                                $roles = $conn->query("SELECT id, name FROM roles");
                                                while ($role = $roles->fetch_assoc()) {
                                                    $selected = $role['id'] == $row['role_id'] ? 'selected' : '';
                                                    echo "<option value='{$role['id']}' $selected>{$role['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <hr class="my-4 opacity-50">

                                    <div class="p-4 bg-primary bg-opacity-10 rounded-4 mb-4 border border-primary border-opacity-10">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-key me-2"></i> ความปลอดภัยและรหัสผ่าน</h6>
                                        <label class="form-label small fw-bold text-uppercase text-primary text-opacity-75">รหัสผ่านใหม่ (ระบุเมื่อต้องการเปลี่ยนเท่านั้น)</label>
                                        <input id="u_password" name="u_password" type="password" class="form-control border-primary border-opacity-25" placeholder="••••••••">
                                        <div class="form-text small mt-2">คำแนะนำ: ทิ้งว่างไว้หากไม่ต้องการเปลี่ยนแปลงรหัสผ่านเดิม</div>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> บันทึกการแก้ไขข้อมูล
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary Card -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm overflow-hidden text-center h-100">
                            <div class="card-header bg-dark py-5">
                                <img src="../user-info.png" class="rounded-circle border border-4 border-white shadow-sm" width="120" height="120">
                            </div>
                            <div class="card-body p-4 mt-n5">
                                <div class="bg-white rounded-4 shadow-sm p-4 pt-5 position-relative" style="margin-top: -30px;">
                                    <h4 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                    <p class="text-muted mb-4"><?php echo htmlspecialchars($row['role_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                                <span class="small text-muted fw-bold text-uppercase">Username</span>
                                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                                <span class="small text-muted fw-bold text-uppercase">Phone</span>
                                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-2">
                                        <div class="alert alert-info border-0 small text-start">
                                            <i class="fas fa-info-circle me-2"></i> การแก้ไขข้อมูลนี้จะมีผลทันทีหลังจากบันทึก พนักงานสามารถใช้ข้อมูลใหม่ในการเข้าสู่ระบบได้ทันที
                                        </div>
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
