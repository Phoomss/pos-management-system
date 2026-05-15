<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../../backend/config/condb.php';

// Fetch products from the database
$result = $conn->query("SELECT * FROM products WHERE deleted_at IS NULL");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเมนูอาหาร | ข้าวมันไก่น้องนัน</title>
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
                        <h1 class="h3 fw-bold mb-1">จัดการเมนูอาหาร</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">แดชบอร์ด</a></li>
                                <li class="breadcrumb-item active">เมนูอาหาร</li>
                            </ol>
                        </nav>
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-2"></i> เพิ่มเมนูใหม่
                    </button>
                </div>

                <!-- Products Table Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4" width="80">#</th>
                                        <th width="120">รูปภาพ</th>
                                        <th>ชื่อเมนู</th>
                                        <th>รายละเอียด</th>
                                        <th class="text-end">ราคา</th>
                                        <th class="text-center pe-4" width="200">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0) { 
                                        $l = 0;
                                        foreach ($result as $row_product) { ?>
                                        <tr>
                                            <td class="ps-4 text-muted fw-medium"><?php echo ++$l; ?></td>
                                            <td>
                                                <div class="rounded-3 overflow-hidden shadow-sm" style="width: 80px; height: 60px;">
                                                    <?php if($row_product['image']): ?>
                                                        <img src="../../uploads/<?php echo $row_product['image']; ?>" class="w-100 h-100 object-fit-cover" alt="Product">
                                                    <?php else: ?>
                                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted small">No Image</div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo e($row_product['name']); ?></div>
                                            </td>
                                            <td>
                                                <div class="text-muted small text-truncate" style="max-width: 300px;">
                                                    <?php echo e($row_product['detail']); ?>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                ฿<?php echo number_format($row_product['price'], 2); ?>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group shadow-sm">
                                                    <a href="./product_edit.php?p_id=<?php echo $row_product['id']; ?>" class="btn btn-white btn-sm border" title="แก้ไข">
                                                        <i class="fas fa-edit text-warning"></i>
                                                    </a>
                                                    <a href="../../backend/product_db.php?p_id=<?php echo $row_product['id']; ?>&product=del" class="btn btn-white btn-sm border del-btn" title="ลบ">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted mb-3"><i class="fas fa-utensils fa-3x opacity-25"></i></div>
                                                <p class="mb-0">ไม่พบข้อมูลเมนูอาหารในระบบ</p>
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form action="../../backend/product_db.php" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product" value="add">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">เพิ่มเมนูอาหารใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">ชื่อเมนู <span class="text-danger">*</span></label>
                                    <input name="p_name" type="text" required class="form-control" placeholder="ระบุชื่อเมนูอาหาร" minlength="3">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">ราคา <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">฿</span>
                                        <input name="p_price" type="number" min="0" step="0.01" required class="form-control border-start-0" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-semibold">รายละเอียดเมนู</label>
                                    <textarea name="p_detail" rows="4" class="form-control" placeholder="ระบุรายละเอียดส่วนประกอบหรือข้อมูลเพิ่มเติม"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">รูปภาพสินค้า</label>
                                <div class="mb-3">
                                    <div class="ratio ratio-4x3 bg-light border rounded-3 d-flex align-items-center justify-content-center overflow-hidden mb-2">
                                        <img id="imgPreview" src="#" alt="Preview" style="display:none; object-fit: cover;">
                                        <div id="uploadPlaceholder" class="text-center text-muted p-2">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <div class="small">คลิกเพื่อเลือกรูปภาพ</div>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control form-control-sm" name="p_image" id="p_image" onchange="previewImage(this);">
                                </div>
                                <div class="alert alert-info py-2 small border-0 mb-0">
                                    <i class="fas fa-info-circle me-1"></i> รองรับไฟล์ JPG, PNG ความละเอียดแนะนำ 800x600 px
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">บันทึกรายการ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imgPreview').attr('src', e.target.result).show();
                    $('#uploadPlaceholder').hide();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
