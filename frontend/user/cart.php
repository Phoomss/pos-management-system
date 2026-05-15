<?php
error_reporting(error_reporting() & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../../backend/config/condb.php';

// Action handling stays the same to preserve business logic
$p_id = isset($_GET['p_id']) ? mysqli_real_escape_string($conn, $_GET['p_id']) : '';
$act = isset($_GET['act']) ? mysqli_real_escape_string($conn, $_GET['act']) : '';

if ($act == 'add' && !empty($p_id)) {
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]++;
    } else {
        $_SESSION['cart'][$p_id] = 1;
    }
}

if ($act == 'remove' && !empty($p_id)) {
    unset($_SESSION['cart'][$p_id]);
}

if ($act == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    csrf_verify();
    if (isset($_POST['amount'])) {
        foreach ($_POST['amount'] as $p_id => $amount) {
            if ($amount >= 1) $_SESSION['cart'][$p_id] = $amount;
            else unset($_SESSION['cart'][$p_id]);
        }
    }
}
?>

<div class="card h-100 border-0 shadow-sm d-flex flex-column rounded-4 overflow-hidden">
    <div class="card-header bg-dark py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title text-white mb-0 fw-bold">
                <i class="fas fa-shopping-basket me-2"></i> ตะกร้าสินค้า
            </h5>
            <span class="badge bg-primary rounded-pill"><?php echo array_sum($_SESSION['cart'] ?? []); ?> รายการ</span>
        </div>
    </div>
    
    <div class="card-body p-0 flex-grow-1 overflow-auto pos-cart-body bg-white">
        <form id="frmcart" name="frmcart" method="post" action="?act=update">
            <?php echo csrf_field(); ?>
            <div class="table-responsive">
                <table class="table table-borderless table-hover align-middle">
                    <thead class="bg-light sticky-top" style="z-index: 10;">
                        <tr>
                            <th class="ps-3 py-2 small">สินค้า</th>
                            <th class="py-2 small text-center" width="80">จำนวน</th>
                            <th class="py-2 small text-end pe-3" width="90">รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $p_id => $qty) {
                                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                                $stmt->bind_param("i", $p_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($row = $result->fetch_assoc()) {
                                    $sum = $row['price'] * $qty;
                                    $total += $sum;
                                    ?>
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="fw-bold text-dark small"><?php echo htmlspecialchars($row['name']); ?></div>
                                            <div class="d-flex align-items-center mt-1">
                                                <span class="text-muted smaller">฿<?php echo number_format($row['price'], 2); ?></span>
                                                <a href="?p_id=<?php echo $p_id; ?>&act=remove" class="text-danger ms-2 smaller opacity-50 hover-opacity-100" title="ลบรายการ">
                                                    <i class="fas fa-times-circle"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <input type="number" name="amount[<?php echo $p_id; ?>]" value="<?php echo $qty; ?>" 
                                                class="form-control form-control-sm text-center border-0 bg-light rounded-pill px-0 fw-bold" 
                                                style="width: 45px; margin: 0 auto;"
                                                onchange="this.form.submit()" min="1">
                                        </td>
                                        <td class="text-end pe-3 py-3 fw-bold text-dark small">
                                            <?php echo number_format($sum, 2); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $stmt->close();
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="opacity-25 mb-2"><i class="fas fa-shopping-cart fa-3x"></i></div>
                                    <p class="text-muted small">ยังไม่มีสินค้าในตะกร้า</p>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <div class="card-footer bg-light border-0 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted fw-medium">ยอดรวมทั้งหมด</span>
            <h3 class="text-primary mb-0 fw-bold">฿<?php echo number_format($total, 2); ?></h3>
        </div>
        
        <?php if ($total > 0): ?>
            <button type="button" onclick="window.location='confirm.php';" class="btn btn-primary w-100 py-3 fw-bold shadow-sm d-flex justify-content-between align-items-center px-4">
                <span>ชำระเงิน</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        <?php else: ?>
            <button disabled class="btn btn-secondary w-100 py-3 fw-bold opacity-50">
                กรุณาเลือกสินค้า
            </button>
        <?php endif; ?>
    </div>
</div>
