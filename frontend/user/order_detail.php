<?php
include_once('../../backend/config/condb.php'); 
$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

// SQL query to fetch order details
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
    <title>รายละเอียดคำสั่งซื้อ #<?php echo $order_id; ?> | ข้าวมันไก่น้องนัน</title>
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
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <h1 class="h3 fw-bold mb-0">รายละเอียดคำสั่งซื้อ</h1>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                #<?php echo $order_id; ?>
                            </span>
                            <?php if($row['order_type'] == 'ทานที่ร้าน'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">ทานที่ร้าน (โต๊ะ <?php echo $row['table_number']; ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">กลับบ้าน</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small mb-0">
                            <i class="far fa-calendar-alt me-1"></i> ทำรายการเมื่อ: <?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="list_sale.php" class="btn btn-white border shadow-sm px-4">
                            <i class="fas fa-arrow-left me-2"></i> ย้อนกลับ
                        </a>
                        <a href="../../backend/generate_pdf.php?order_id=<?php echo $order_id; ?>" class="btn btn-danger shadow-sm px-4">
                            <i class="fas fa-file-pdf me-2"></i> ดาวน์โหลดใบเสร็จ
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Left: Items Table -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="card-header bg-white py-3 border-bottom-0">
                                <h5 class="card-title fw-bold mb-0">รายการอาหาร</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4" width="80">ลำดับ</th>
                                                <th>เมนูอาหาร</th>
                                                <th class="text-end">ราคา/หน่วย</th>
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
                                                            <div class="rounded-3 overflow-hidden me-3 shadow-sm" style="width: 50px; height: 40px;">
                                                                <?php if($rspay['image']): ?>
                                                                    <img src="../../uploads/<?php echo $rspay['image']; ?>" class="w-100 h-100 object-fit-cover">
                                                                <?php else: ?>
                                                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                                        <i class="fas fa-utensils text-muted opacity-25"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($rspay["product_name"]); ?></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">฿<?php echo number_format($rspay["unit_price"], 2); ?></td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border px-3"><?php echo $rspay["quantity"]; ?></span>
                                                    </td>
                                                    <td class="text-end pe-4 fw-bold">฿<?php echo number_format($rspay['total_price'], 2); ?></td>
                                                </tr>
                                                <?php
                                            }
                                            include('../../backend/convertnumtothai.php');
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Thai Baht Text -->
                        <div class="mt-3 ps-2">
                            <span class="text-muted small">ตัวอักษร:</span>
                            <span class="text-primary fw-medium ms-1">( <?php echo Convert($total); ?> )</span>
                        </div>
                    </div>

                    <!-- Right: Summary & Info -->
                    <div class="col-lg-4">
                        <!-- Order Status Info -->
                        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-white text-opacity-75 small text-uppercase fw-bold">คิวรับบริการ</span>
                                    <i class="fas fa-clock text-white text-opacity-50"></i>
                                </div>
                                <div class="h1 fw-bold mb-0">#<?php echo $row['queue_number'] ?? '0'; ?></div>
                                <hr class="bg-white opacity-25 my-3">
                                <div class="d-flex align-items-center">
                                    <img src="../user-info.png" class="rounded-circle border border-2 border-white border-opacity-25 me-2" width="32" height="32">
                                    <div class="small">
                                        <span class="text-white text-opacity-75">พนักงาน:</span>
                                        <span class="fw-bold ms-1"><?php echo htmlspecialchars($row['fullname']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">สรุปยอดชำระเงิน</h5>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">รวมค่าอาหาร</span>
                                    <span class="fw-bold text-dark">฿<?php echo number_format($total, 2); ?></span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">ภาษีมูลค่าเพิ่ม (0%)</span>
                                    <span class="text-dark">฿0.00</span>
                                </div>

                                <hr class="my-4 opacity-50">

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="h5 fw-bold mb-0 text-dark">ยอดสุทธิ</span>
                                    <span class="h3 fw-bold mb-0 text-primary">฿<?php echo number_format($total, 2); ?></span>
                                </div>

                                <div class="bg-light rounded-3 p-3 mb-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-muted fw-bold text-uppercase">รับเงินมา</span>
                                        <span class="fw-bold text-success">฿<?php echo number_format($row['paid_amount'], 2); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted fw-bold text-uppercase">เงินทอน</span>
                                        <span class="fw-bold text-danger">฿<?php echo number_format($row['paid_amount'] - $total, 2); ?></span>
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