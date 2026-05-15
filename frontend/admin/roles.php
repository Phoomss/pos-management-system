<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../../backend/config/condb.php';

$result = $conn->query("SELECT * FROM roles");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสิทธิ์การใช้งาน | ข้าวมันไก่น้องนัน</title>
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
                        <h1 class="h3 fw-bold mb-1">สิทธิ์การใช้งาน</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">แดชบอร์ด</a></li>
                                <li class="breadcrumb-item active">สิทธิ์การใช้งาน</li>
                            </ol>
                        </nav>
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-shield-alt me-2"></i> เพิ่มสิทธิ์ใหม่
                    </button>
                </div>

                <!-- Roles Table Card -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4" width="80">#</th>
                                                <th>ชื่อสิทธิ์การใช้งาน</th>
                                                <th class="text-center pe-4" width="200">จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0) { 
                                                $l = 0;
                                                foreach ($result as $row_role) { ?>
                                                <tr>
                                                    <td class="ps-4 text-muted fw-medium"><?php echo ++$l; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                                                <i class="fas fa-lock small"></i>
                                                            </div>
                                                            <div class="fw-bold text-dark"><?php echo e($row_role['name']); ?></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center pe-4">
                                                        <div class="btn-group shadow-sm">
                                                            <a href="./role_edit.php?r_id=<?php echo $row_role['id']; ?>" class="btn btn-white btn-sm border" title="แก้ไข">
                                                                <i class="fas fa-edit text-warning"></i>
                                                            </a>
                                                            <a href="../../backend/role_db.php?r_id=<?php echo $row_role['id']; ?>&role=del" class="btn btn-white btn-sm border del-btn" title="ลบ">
                                                                <i class="fas fa-trash text-danger"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } } else { ?>
                                                <tr>
                                                    <td colspan="3" class="text-center py-5">
                                                        <p class="text-muted mb-0">ไม่พบข้อมูลสิทธิ์การใช้งานในระบบ</p>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm bg-light">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i> เกี่ยวกับสิทธิ์การใช้งาน</h6>
                                <p class="small text-muted mb-0">
                                    สิทธิ์การใช้งานเป็นตัวกำหนดการเข้าถึงส่วนต่างๆ ของระบบ:
                                    <ul class="small text-muted mt-2">
                                        <li><strong>admin:</strong> เข้าถึงได้ทุกส่วนของระบบ รวมถึงการจัดการข้อมูลและรายงาน</li>
                                        <li><strong>user:</strong> เข้าถึงได้เฉพาะหน้าจอขายสินค้าและข้อมูลส่วนตัว</li>
                                    </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="../../backend/role_db.php" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="role" value="add">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">เพิ่มสิทธิ์การใช้งาน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-uppercase">ชื่อสิทธิ์การใช้งาน</label>
                            <input name="r_name" type="text" required class="form-control bg-light border-0" placeholder="เช่น manager, staff" minlength="3">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>
</body>

</html>
