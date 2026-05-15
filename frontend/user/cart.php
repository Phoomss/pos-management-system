<?php
error_reporting(error_reporting() & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check database connection
include '../../backend/config/condb.php';

// Sanitize input
$p_id = isset($_GET['p_id']) ? mysqli_real_escape_string($conn, $_GET['p_id']) : '';
$act = isset($_GET['act']) ? mysqli_real_escape_string($conn, $_GET['act']) : '';

// Add product to cart
if ($act == 'add' && !empty($p_id)) {
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]++; // Increase quantity
    } else {
        $_SESSION['cart'][$p_id] = 1; // Set quantity to 1 if new item
    }
}

// Remove product from cart
if ($act == 'remove' && !empty($p_id)) {
    unset($_SESSION['cart'][$p_id]);
}

// Update cart quantities
if ($act == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    csrf_verify();
    if (isset($_POST['amount'])) {
        $amount_array = $_POST['amount'];
        foreach ($amount_array as $p_id => $amount) {
            if ($amount >= 1) {
                $_SESSION['cart'][$p_id] = $amount;
            } else {
                unset($_SESSION['cart'][$p_id]);
            }
        }
    }
}
?>

<div class="container mt-4">
    <h4 class="text-center">รายการสั่งซื้อ</h4>
    <form id="frmcart" name="frmcart" method="post" action="?act=update">
        <?php echo csrf_field(); ?>
        <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>สินค้า</th>
                    <th>ราคา</th>
                    <th>จำนวน</th>
                    <th>รวม(บาท)</th>
                    <th>ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                $ii = 0;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $p_id => $qty) {
                        $stmt = $conn->prepare("SELECT * FROM products_table WHERE p_id = ?");
                        $stmt->bind_param("i", $p_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($row = $result->fetch_assoc()) {
                            $sum = $row['p_price'] * $qty;
                            $total += $sum;

                            echo "<tr>";
                            echo "<td>" . ++$ii . "</td>";
                            echo "<td>" . htmlspecialchars($row['p_name']) . "</td>";
                            echo "<td class='text-right'>" . number_format($row['p_price'], 2) . "</td>";
                            echo "<td><input type='number' name='amount[$p_id]' value='$qty' class='form-control' min='1' onchange='this.form.submit()' /></td>";
                            echo "<td class='text-right'>" . number_format($sum, 2) . "</td>";
                            echo "<td><a href='?p_id=$p_id&act=remove' class='btn btn-danger btn-sm'>ลบ</a></td>";
                            echo "</tr>";
                        }
                        $stmt->close();
                    }

                    echo "<tr>";
                    echo "<td colspan='4' class='text-right'><b>ราคารวม</b></td>";
                    echo "<td class='text-right'>" . number_format($total, 2) . "</td>";
                    echo "<td></td>";
                    echo "</tr>";
                } else {
                    echo "<tr><td colspan='6' class='text-center'>ไม่มีสินค้าในตะกร้า</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="text-right">
            <button type="submit" name="button" id="button" class="btn btn-warning">อัพเดตรายการ</button>
            <button type="button" name="Submit2" onclick="window.location='confirm.php';" class="btn btn-primary">ทำรายการต่อไป</button>
        </div>
    </form>
</div>