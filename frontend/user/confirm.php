<?php
error_reporting(error_reporting() & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../../backend/config/condb.php';

$user_id = $_SESSION['user_id'] ?? '';

// Fetch user information
$row_user = [];
if ($user_id != '') {
    $sql_user = "SELECT * FROM users WHERE id='$user_id'";
    $query_user = mysqli_query($conn, $sql_user);
    if ($query_user) {
        $row_user = mysqli_fetch_assoc($query_user);
    }
}

// Get next queue number
$queue_number = 1;
$sql_last_q_order = "SELECT MAX(queue_number) AS max_q_order FROM orders WHERE DATE(created_at) = CURDATE()";
$query_last_q_order = mysqli_query($conn, $sql_last_q_order);
if ($query_last_q_order) {
    $row_last_q_order = mysqli_fetch_assoc($query_last_q_order);
    if ($row_last_q_order && !is_null($row_last_q_order['max_q_order'])) {
        $queue_number = $row_last_q_order['max_q_order'] + 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการสั่งซื้อ | ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>

        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper">
                <div class="mb-4">
                    <h1 class="h3 fw-bold mb-1">ยืนยันการสั่งซื้อ</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="index.php">ขายสินค้า</a></li>
                            <li class="breadcrumb-item active">สรุปรายการ</li>
                        </ol>
                    </nav>
                </div>

                <form id="frmcart" name="frmcart" method="post" action="../../backend/save_order.php">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="u_id" value="<?php echo htmlspecialchars($user_id); ?>">

                    <div class="row g-4">
                        <!-- Left: Order Summary -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                                    <h5 class="card-title fw-bold mb-0">สรุปรายการอาหาร</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4" width="80">ลำดับ</th>
                                                    <th>รายการสินค้า</th>
                                                    <th class="text-end">ราคา/หน่วย</th>
                                                    <th class="text-center">จำนวน</th>
                                                    <th class="text-end pe-4">รวม (บาท)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                $i = 0;
                                                if (!empty($_SESSION['cart'])) {
                                                    foreach ($_SESSION['cart'] as $p_id => $qty) {
                                                        $p_id = intval($p_id);
                                                        $sql = "SELECT * FROM products WHERE id = $p_id";
                                                        $query = mysqli_query($conn, $sql);
                                                        if ($row = mysqli_fetch_array($query)) {
                                                            $sum = $row['price'] * $qty;
                                                            $total += $sum;
                                                            echo "<tr>";
                                                            echo "<td class='ps-4 text-muted'>" . ++$i . "</td>";
                                                            echo "<td><div class='fw-bold text-dark'>" . htmlspecialchars($row["name"]) . "</div></td>";
                                                            echo "<td class='text-end'>฿" . number_format($row["price"], 2) . "</td>";
                                                            echo "<td class='text-center'><span class='badge bg-light text-dark border px-3'>" . $qty . "</span><input type='hidden' name='amount[$p_id]' value='$qty'/></td>";
                                                            echo "<td class='text-end pe-4 fw-bold'>฿" . number_format($sum, 2) . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted small'>ไม่มีสินค้าในตะกร้า</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot class="bg-light">
                                                <tr>
                                                    <td colspan="4" class="ps-4 py-3 fw-bold text-dark">ยอดรวมสุทธิ</td>
                                                    <td class="text-end pe-4 py-3 fw-bold text-primary h4 mb-0">฿<?php echo number_format($total, 2); ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="index.php" class="btn btn-light px-4 border shadow-sm">
                                <i class="fas fa-chevron-left me-2"></i> กลับไปแก้ไขรายการ
                            </a>
                        </div>

                        <!-- Right: Payment & Info -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm bg-dark text-white mb-4">
                                <div class="card-body p-4">
                                    <h6 class="text-white text-opacity-50 small text-uppercase fw-bold mb-3">พนักงานผู้ทำรายการ</h6>
                                    <div class="d-flex align-items-center mb-4">
                                        <img src="../user-info.png" class="rounded-circle me-3" width="48" height="48">
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($row_user['fullname'] ?? 'ไม่พบข้อมูล'); ?></div>
                                            <div class="small text-white text-opacity-50"><?php echo htmlspecialchars($row_user['phone'] ?? '-'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 bg-white bg-opacity-10 rounded-3 mb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small text-white text-opacity-75">ลำดับคิวแนะนำ</span>
                                            <span class="h4 mb-0 fw-bold">#<?php echo $queue_number; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4">ข้อมูลการชำระเงิน</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-uppercase">รูปแบบการสั่งซื้อ</label>
                                        <select class='form-select' name='od_status' id="od_status" required>
                                            <option value='' disabled selected>เลือกรูปแบบ...</option>
                                            <option value='ทานที่ร้าน'>ทานที่ร้าน</option>
                                            <option value='กลับบ้าน'>กลับบ้าน</option>
                                        </select>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-uppercase">ลำดับคิว</label>
                                            <input type="number" name="q_order" id="q_order" required class="form-control fw-bold text-center" value="<?php echo $queue_number; ?>">
                                        </div>
                                        <div class="col-6" id="table_number_wrapper">
                                            <label class="form-label small fw-bold text-uppercase">เลขโต๊ะ</label>
                                            <select name="table_number" id="table_number" class="form-select fw-bold text-center">
                                                <option value="" disabled selected>-</option>
                                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-uppercase text-primary">ยอดรับเงิน (บาท)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white border-0">฿</span>
                                            <input type="number" min="<?php echo $total; ?>" name="pay_amount2" id="pay_amount2" required class="form-control form-control-lg border-primary border-2 fw-bold text-primary" placeholder="0.00">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-muted">เงินทอน</label>
                                        <input type="text" name="change_amount" id="change_amount" readonly class="form-control form-control-lg bg-light border-0 fw-bold text-muted" value="0.00">
                                    </div>

                                    <input type="hidden" name="pay_amount1" id="pay_amount1" value="<?php echo $total; ?>">
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow">
                                        <i class="fas fa-check-circle me-2"></i> ยืนยันการสั่งซื้อ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const odStatus = document.getElementById('od_status');
            const tableWrapper = document.getElementById('table_number_wrapper');
            const tableSelect = document.getElementById('table_number');

            odStatus.addEventListener('change', function() {
                if (this.value === 'กลับบ้าน') {
                    tableWrapper.style.opacity = '0.3';
                    tableSelect.disabled = true;
                    tableSelect.value = '';
                } else {
                    tableWrapper.style.opacity = '1';
                    tableSelect.disabled = false;
                }
            });

            const payInput = document.getElementById('pay_amount2');
            const totalAmount = parseFloat(document.getElementById('pay_amount1').value);
            const changeInput = document.getElementById('change_amount');

            payInput.addEventListener('input', function() {
                const paid = parseFloat(this.value);
                if (!isNaN(paid) && paid >= totalAmount) {
                    const change = paid - totalAmount;
                    changeInput.value = change.toLocaleString(undefined, {minimumFractionDigits: 2});
                    changeInput.classList.remove('text-muted');
                    changeInput.classList.add('text-success');
                } else {
                    changeInput.value = '0.00';
                    changeInput.classList.add('text-muted');
                    changeInput.classList.remove('text-success');
                }
            });
        });
    </script>
</body>

</html>
