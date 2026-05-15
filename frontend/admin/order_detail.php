<?php
include_once('../../backend/config/condb.php'); 
$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

// SQL query to fetch order details with additional admin-useful info
$sql = "SELECT d.*, p.name as product_name, p.image, u.fullname, o.created_at as order_date, o.paid_amount, o.order_type, o.queue_number, o.table_number, o.total_amount
        FROM order_details AS d
        INNER JOIN products AS p ON d.product_id = p.id
        INNER JOIN orders AS o ON d.order_id = o.id
        INNER JOIN users AS u ON o.user_id = u.id
        WHERE d.order_id = $order_id";

$querypay = mysqli_query($conn, $sql) or die("Error : " . mysqli_error($conn));
$row = mysqli_fetch_assoc($querypay); // Fetch order data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคำสั่งซื้อ #<?php echo $order_id; ?> | Admin Dashboard</title>
    <?php include_once('../layout/config/library.php') ?>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>
        
        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper">
                <!-- Admin Page Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <h1 class="h3 fw-bold mb-0">ตรวจสอบคำสั่งซื้อ</h1>
                            <span class="badge bg-dark text-white border-0 px-3 py-2">
                                ID: <?php echo $order_id; ?>
                            </span>
                            <?php if($row['order_type'] == 'ทานที่ร้าน'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">ทานที่ร้าน (โต๊ะ <?php echo $row['table_number']; ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">กลับบ้าน</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small mb-0">
                            <i class="far fa-clock me-1"></i> วันที่ทำรายการ: <?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="list_sale.php" class="btn btn-white border shadow-sm px-4">
                            <i class="fas fa-list me-2 text-muted"></i> รายการทั้งหมด
                        </a>
                        <a href="../../backend/generate_pdf.php?order_id=<?php echo $order_id; ?>" class="btn btn-danger shadow-sm px-4">
                            <i class="fas fa-print me-2"></i> พิมพ์ใบเสร็จ
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Left: Items Detail -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-4">
                            <div class="card-header bg-white py-3 border-bottom-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title fw-bold mb-0">รายการสินค้าในบิลนี้</h5>
                                    <span class="text-muted small"><?php echo mysqli_num_rows($querypay); ?> รายการ</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4" width="60">#</th>
                                                <th>สินค้า</th>
                                                <th class="text-end">ราคาปกติ</th>
                                                <th class="text-center">จำนวน</th>
                                                <th class="text-end pe-4">รวม (บาท)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            $i = 0;
                                            mysqli_data_seek($querypay, 0);
                                            foreach ($querypay as $rspay) {
                                                $total += $rspay['total_price'];
                                                ?>
                                                <tr>
                                                    <td class="ps-4 text-muted"><?php echo ++$i; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-3 overflow-hidden me-3 border shadow-sm" style="width: 55px; height: 45px;">
                                                                <?php if($rspay['image']): ?>
                                                                    <img src="../../uploads/<?php echo $rspay['image']; ?>" class="w-100 h-100 object-fit-cover">
                                                                <?php else: ?>
                                                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                                                        <i class="fas fa-utensils small opacity-50"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($rspay["product_name"]); ?></div>
                                                                <div class="smaller text-muted">SKU: <?php echo str_pad($rspay['product_id'], 5, '0', STR_PAD_LEFT); ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end text-muted">฿<?php echo number_format($rspay["unit_price"], 2); ?></td>
                                                    <td class="text-center">
                                                        <span class="fw-bold px-3 py-1 bg-light rounded-pill border small">x <?php echo $rspay["quantity"]; ?></span>
                                                    </td>
                                                    <td class="text-end pe-4 fw-bold text-dark">฿<?php echo number_format($rspay['total_price'], 2); ?></td>
                                                </tr>
                                                <?php
                                            }
                                            include('../../backend/convertnumtothai.php');
                                            ?>
                                        </tbody>
                                        <tfoot class="border-top-0">
                                            <tr>
                                                <td colspan="4" class="ps-4 py-4 text-end text-muted fw-medium">ยอดรวมสินค้าทั้งหมด:</td>
                                                <td class="text-end pe-4 py-4 fw-bold h5 mb-0">฿<?php echo number_format($total, 2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="alert bg-primary bg-opacity-10 border-0 rounded-3 d-flex align-items-center p-3">
                            <i class="fas fa-comment-dots text-primary me-3 fa-lg"></i>
                            <div>
                                <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">จำนวนเงินเป็นตัวอักษร</div>
                                <div class="text-primary fw-bold mb-0"><?php echo Convert($total); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Transaction Summary -->
                    <div class="col-lg-4">
                        <!-- Employee Info -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4 text-center">
                                <h6 class="text-muted small text-uppercase fw-bold mb-3">พนักงานผู้รับผิดชอบ</h6>
                                <img src="../user-info.png" class="rounded-circle shadow-sm mb-3 border p-1" width="80" height="80">
                                <h5 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($row['fullname']); ?></h5>
                                <span class="badge bg-secondary-subtle text-secondary px-3">Authorized Seller</span>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="card-header bg-primary text-white py-3 border-0">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i> สรุปความเคลื่อนไหวเงินสด</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">ราคาขายรวม</span>
                                    <span class="fw-bold text-dark">฿<?php echo number_format($total, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">ส่วนลดพิเศษ</span>
                                    <span class="text-danger">- ฿0.00</span>
                                </div>
                                <hr class="my-4 opacity-50">
                                
                                <div class="p-3 bg-light rounded-3 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-muted fw-bold">เงินสดที่รับมา</span>
                                        <span class="h5 fw-bold text-success mb-0">฿<?php echo number_format($row['paid_amount'], 2); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small text-muted fw-bold">เงินทอนลูกค้า</span>
                                        <span class="h5 fw-bold text-danger mb-0">฿<?php echo number_format($row['paid_amount'] - $total, 2); ?></span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center bg-primary bg-opacity-10 p-3 rounded-3">
                                    <span class="fw-bold text-primary">รายได้สุทธิ (Net)</span>
                                    <span class="h3 fw-bold text-primary mb-0">฿<?php echo number_format($total, 2); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Audit Info -->
                        <div class="mt-4 px-2">
                            <div class="small text-muted mb-2"><i class="fas fa-info-circle me-1"></i> ข้อมูลสำหรับการตรวจสอบ</div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-muted border fw-normal py-2 px-3">Queue: #<?php echo $row['queue_number']; ?></span>
                                <span class="badge bg-light text-muted border fw-normal py-2 px-3">Station: POS-01</span>
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