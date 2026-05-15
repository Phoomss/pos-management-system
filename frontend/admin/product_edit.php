<?php
include_once '../../backend/config/condb.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure p_id is set and is numeric to prevent SQL injection
if (isset($_GET['p_id']) && is_numeric($_GET['p_id'])) {
    $p_id = $_GET['p_id'];

    $query_product = "SELECT * FROM products WHERE id = $p_id";
    $rs_product = mysqli_query($conn, $query_product) or die("Error: " . mysqli_error($conn));

    if (mysqli_num_rows($rs_product) > 0) {
        $row = mysqli_fetch_array($rs_product);
    } else {
        echo "No product found with this ID.";
        exit();
    }
    $conn->close();
} else {
    echo "Invalid Product ID";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขเมนูอาหาร | ข้าวมันไก่น้องนัน</title>
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
                            <h1 class="h3 fw-bold mb-1">แก้ไขเมนูอาหาร</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="index.php">แดชบอร์ด</a></li>
                                    <li class="breadcrumb-item"><a href="products.php">จัดการเมนูอาหาร</a></li>
                                    <li class="breadcrumb-item active">แก้ไขเมนู</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="products.php" class="btn btn-white border shadow-sm px-4">
                            <i class="fas fa-arrow-left me-2"></i> ย้อนกลับ
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Left Column: Product Details Form -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                                <h5 class="card-title fw-bold mb-0">รายละเอียดข้อมูลสินค้า</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="../../backend/product_db.php" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product" value="edit">
                                    <input type="hidden" name="p_id" value="<?php echo $row['id']; ?>">
                                    <input name="file1" type="hidden" id="file1" value="<?php echo $row['image']; ?>" />

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">ชื่อเมนูอาหาร <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-utensils text-muted"></i></span>
                                            <input name="p_name" type="text" class="form-control bg-light border-0" required placeholder="ระบุชื่อเมนู" value="<?php echo htmlspecialchars($row['name']); ?>" minlength="3">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">ราคาจำหน่าย <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0">฿</span>
                                            <input name="p_price" type="number" step="0.01" class="form-control bg-light border-0" min="0" required placeholder="0.00" value="<?php echo $row['price']; ?>">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">รายละเอียดเพิ่มเติม</label>
                                        <textarea name="p_detail" class="form-control bg-light border-0" rows="5" placeholder="ระบุส่วนประกอบหรือรายละเอียดอื่นๆ..."><?php echo htmlspecialchars($row['detail']); ?></textarea>
                                    </div>

                                    <hr class="my-4 opacity-50">

                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                            <i class="fas fa-save me-2"></i> บันทึกการเปลี่ยนแปลง
                                        </button>
                                        <a href="products.php" class="btn btn-light px-4 py-2 border">ยกเลิก</a>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Image Management -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                                <h5 class="card-title fw-bold mb-0">รูปภาพสินค้า</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-uppercase text-muted d-block mb-3">รูปภาพปัจจุบัน</label>
                                    <div class="text-center bg-light rounded-4 p-3 border border-dashed">
                                        <?php if($row['image']): ?>
                                            <img src="../../uploads/<?php echo $row['image']; ?>" class="rounded-3 shadow-sm img-fluid" style="max-height: 250px; object-fit: contain;" alt="Product">
                                        <?php else: ?>
                                            <div class="py-5 text-muted">
                                                <i class="fas fa-image fa-3x opacity-25 mb-2"></i>
                                                <p class="small mb-0">ไม่มีรูปภาพสินค้าในขณะนี้</p>
                                            </div>
                                        <?php endif; ?>
                                        <input type="hidden" name="mem_img2" value="<?php echo $row['image']; ?>">
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-uppercase text-muted">อัปโหลดรูปภาพใหม่</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="p_image" id="p_image" onchange="readURL(this);">
                                    </div>
                                    
                                    <!-- New Image Preview -->
                                    <div id="newImgPreviewContainer" style="display:none;">
                                        <label class="form-label small fw-bold text-uppercase text-primary">ตัวอย่างรูปใหม่:</label>
                                        <div class="rounded-4 overflow-hidden border border-primary border-2 shadow-sm">
                                            <img id="blah" src="#" alt="New Preview" class="img-fluid w-100" style="max-height: 200px; object-fit: cover;" />
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary border-0 small mt-3 mb-0">
                                        <i class="fas fa-info-circle me-2"></i> หากต้องการเปลี่ยนรูปภาพ ให้เลือกไฟล์ใหม่ที่นี่ ระบบจะทำการเปลี่ยนให้อัตโนมัติหลังกดบันทึก
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Card -->
                        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-check small"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-primary">สถานะการแสดงผล</h6>
                                        <p class="small text-primary text-opacity-75 mb-0">เมนูนี้กำลังแสดงผลในหน้าจอขายสินค้า</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form> <!-- End Form -->
                </div>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>

    <script>
        // Image preview script
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('newImgPreviewContainer').style.display = 'block';
                    document.getElementById('blah').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
