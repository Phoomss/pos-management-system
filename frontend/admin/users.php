<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../../backend/config/condb.php';

$result = $conn->query(
    "SELECT u.id, u.fullname, u.username, u.phone, r.name as role_name 
     FROM users u 
     INNER JOIN roles r ON u.role_id = r.id 
     WHERE u.deleted_at IS NULL"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้งาน | ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>

        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper">
                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">จัดการผู้ใช้งาน</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">แดชบอร์ด</a></li>
                                <li class="breadcrumb-item active">ผู้ใช้งาน</li>
                            </ol>
                        </nav>
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-user-plus me-2"></i> เพิ่มผู้ใช้งาน
                    </button>
                </div>

                <!-- Users Table Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4" width="80">#</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>ชื่อผู้ใช้งาน</th>
                                        <th>เบอร์โทรศัพท์</th>
                                        <th>สิทธิ์การใช้งาน</th>
                                        <th class="text-center pe-4" width="200">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0) { 
                                        $l = 0;
                                        foreach ($result as $row) { ?>
                                        <tr>
                                            <td class="ps-4 text-muted fw-medium"><?php echo ++$l; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user small"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark"><?php echo e($row['fullname']); ?></div>
                                                </div>
                                            </td>
                                            <td><code class="text-primary bg-primary-subtle px-2 py-1 rounded small"><?php echo e($row['username']); ?></code></td>
                                            <td><?php echo e($row['phone']); ?></td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3">
                                                    <?php echo e($row['role_name']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group shadow-sm">
                                                    <a href="./user_edit.php?u_id=<?php echo $row['id']; ?>" class="btn btn-white btn-sm border" title="แก้ไข">
                                                        <i class="fas fa-user-edit text-warning"></i>
                                                    </a>
                                                    <a href="../../backend/user_db.php?u_id=<?php echo $row['id']; ?>&user=del" class="btn btn-white btn-sm border del-btn" title="ลบ">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <p class="text-muted mb-0">ไม่พบข้อมูลผู้ใช้งานในระบบ</p>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="../../backend/user_db.php" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="user" value="add">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">เพิ่มผู้ใช้งานใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase">ชื่อ-นามสกุล</label>
                            <input name="u_name" type="text" required class="form-control bg-light border-0" placeholder="ระบุชื่อจริง-นามสกุล" minlength="3">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase">ชื่อผู้ใช้งาน</label>
                                <input name="u_username" type="text" required class="form-control bg-light border-0" placeholder="Username">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase">เบอร์โทรศัพท์</label>
                                <input name="u_phone" type="text" required class="form-control bg-light border-0" placeholder="08x-xxx-xxxx">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase">ระดับสิทธิ์</label>
                            <select name="r_id" class="form-select bg-light border-0" required>
                                <?php
                                $roles = $conn->query("SELECT id, name FROM roles");
                                while ($role = $roles->fetch_assoc()) {
                                    echo "<option value='{$role['id']}'>{$role['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-uppercase">รหัสผ่าน</label>
                            <input name="u_password" type="password" required class="form-control bg-light border-0" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">สร้างบัญชีผู้ใช้</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>
</body>

</html>
