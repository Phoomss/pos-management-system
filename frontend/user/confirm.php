<?php
error_reporting(error_reporting() & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/config/condb.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .content-header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .form-control,
        .form-select {
            margin-bottom: 1rem;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .table thead th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .card-body {
            padding: 2rem;
        }

        .card-footer {
            background-color: #f1f1f1;
            padding: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
        }

        .btn-success {
            margin-top: 1rem;
        }

        @media (max-width: 755px) {
            .card-body {
                padding: 1rem;
            }

            .form-group {
                margin-bottom: 0.5rem;
            }
        }

        #table_number_wrapper {
            display: flex;
            /* ค่าเริ่มต้นเมื่อแสดงแถว */
        }
    </style>
</head>

<?php
$u_id = isset($_SESSION['u_id']) ? $_SESSION['u_id'] : '';

// Fetch user information
$row_user = [];
if ($u_id != '') {
    $sql_user = "SELECT * FROM users_table WHERE u_id='$u_id'";
    $query_user = mysqli_query($conn, $sql_user);
    if ($query_user) {
        $row_user = mysqli_fetch_assoc($query_user);
    } else {
        die("Database query failed: " . mysqli_error($conn));
    }
}

// ดึงลำดับคิวล่าสุด
$q_order = 1; // ค่าลำดับคิวเริ่มต้น
$sql_last_q_order = "SELECT MAX(q_order) AS max_q_order FROM orders_table";
$query_last_q_order = mysqli_query($conn, $sql_last_q_order);
if ($query_last_q_order) {
    $row_last_q_order = mysqli_fetch_assoc($query_last_q_order);
    if ($row_last_q_order && !is_null($row_last_q_order['max_q_order'])) {
        $q_order = $row_last_q_order['max_q_order'] + 1; // บวก 1 ให้กับลำดับคิวล่าสุด
    }
}
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('../layout/header.php') ?>
        <?php include_once('./sidenav.php') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">รายการสินค้าที่สั่งซื้อทั้งหมด</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="card card-gray">
                    <div class="card-header">
                        <h3 class="card-title">ยืนยันการสั่งซื้อ</h3>
                    </div>
                    <div class="card-body">
                        <form id="frmcart" name="frmcart" method="post" action="../../backend/save_order.php">
                            <?php echo csrf_field(); ?>
                            <?php if ($u_id != '' && !empty($row_user)) { ?>
                                <div class="form-group">
                                    <h4>
                                        ผู้ใช้ระบบ: <?php echo !empty($row_user['u_name']) ? htmlspecialchars($row_user['u_name']) : 'ไม่พบข้อมูลผู้ซื้อ'; ?> <br>
                                        เบอร์โทร: <?php echo !empty($row_user['u_phone']) ? htmlspecialchars($row_user['u_phone']) : 'ไม่พบเบอร์โทร'; ?>
                                    </h4>
                                </div>
                            <?php } ?>
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">ลำดับสินค้า</th>
                                        <th width="40%">สินค้า</th>
                                        <th width="10%">ราคา</th>
                                        <th width="10%">จำนวน</th>
                                        <th width="15%">รวม(บาท)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    $i = 0; // ตัวแปรนับลำดับสินค้า
                                    if (!empty($_SESSION['cart'])) {
                                        foreach ($_SESSION['cart'] as $p_id => $qty) {
                                            $p_id = intval($p_id); // แปลง $p_id เป็นจำนวนเต็มเพื่อความปลอดภัย
                                            $sql = "SELECT * FROM products_table WHERE p_id = $p_id";
                                            $query = mysqli_query($conn, $sql);
                                            if (!$query) {
                                                die("Query failed: " . mysqli_error($conn));
                                            }

                                            if ($query) {
                                                $row = mysqli_fetch_array($query);
                                                if ($row) { // Check if $row is not empty
                                                    $sum = $row['p_price'] * $qty; // คำนวณราคารวมของสินค้า
                                                    $total += $sum; // ราคารวมของตระกร้าสินค้า
                                                    echo "<tr>";
                                                    echo "<td>" . ++$i . "</td>"; // แสดงลำดับสินค้า
                                                    echo "<td>" . htmlspecialchars($row["p_name"]) . "</td>";
                                                    echo "<td align='right'>" . number_format($row["p_price"], 2) . "</td>";
                                                    echo "<td align='right'>";
                                                    echo "<input type='number' name='amount[$p_id]' value='$qty' size='2' class='form-control' min='0' readonly/></td>";
                                                    echo "<td align='right'>" . number_format($sum, 2) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                die("Database query failed: " . mysqli_error($conn));
                                            }
                                        }

                                        // แสดงราคารวม
                                        echo "<tr>";
                                        echo "<td colspan='4' class='text-center'><b>ราคารวม</b></td>";
                                        echo "<td align='right'><b>" . number_format($total, 2) . "</b></td>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>ไม่มีสินค้าในตะกร้า</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <?php if ($u_id != '' && !empty($row_user)) { ?>
                                <div class="form-group row align-content-center justify-content-center">
                                    <label for="od_status" class="col-sm-2 col-form-label">สถานะการสั่งซื้อ</label>
                                    <div class="col-sm-4">
                                        <select class='form-select p-2 rounded text-center' name='od_status' id="od_status" aria-label='Default select example' required>
                                            <option value='' disabled selected>เลือกรูปแบบการสั่งซื้อ</option>
                                            <option value='ทานที่ร้าน'>ทานที่ร้าน</option>
                                            <option value='กลับบ้าน'>กลับบ้าน</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row align-content-center justify-content-center">
                                    <label for="q_order" class="col-sm-2 col-form-label">ลำดับคิว</label>
                                    <div class="col-sm-4">
                                        <input type="number" name="q_order" id="q_order" required class="form-control" value="<?php echo $q_order; ?>" placeholder="กรุณากรอกลำดับคิว">
                                    </div>
                                </div>

                                <div class="form-group row align-content-center justify-content-center" id="table_number_wrapper">
                                    <label for="table_number" class="col-sm-2 col-form-label">เลขโต๊ะ</label>
                                    <div class="col-sm-4">
                                        <select name="table_number" id="table_number" class="form-control" >
                                            <option value="" disabled selected>กรุณาเลือกเลขโต๊ะ</option>
                                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row align-content-center justify-content-center">
                                    <label for="pay_amount1" class="col-sm-2 col-form-label">ยอดเงินที่ต้องชำระ</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="pay_amount1" id="pay_amount1" readonly class="form-control" value="<?php echo number_format($total, 2); ?>">
                                    </div>
                                </div>
                                <div class="form-group row align-content-center justify-content-center">
                                    <label for="pay_amount2" class="col-sm-2 col-form-label">ยอดเงินที่รับชำระ</label>
                                    <div class="col-sm-4">
                                        <input type="number" min="<?php echo $total; ?>" name="pay_amount2" id="pay_amount2" required class="form-control" placeholder="ยอดเงินที่รับชำระ">
                                    </div>
                                </div>
                                <!-- ฟิลด์ยอดเงินทอน -->
                                <div class="form-group row align-content-center justify-content-center">
                                    <label for="change_amount" class="col-sm-2 col-form-label">ยอดเงินทอน</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="change_amount" id="change_amount" readonly class="form-control" placeholder="ยอดเงินทอน">
                                    </div>
                                </div>

                                <div class="form-group row align-content-center justify-content-center">
                                    <div class="col-sm-4 offset-sm-2">
                                        <input type="hidden" name="u_id" value="<?php echo htmlspecialchars($u_id); ?>">
                                        <button type="submit" class="btn btn-primary btn-block">ยืนยันการสั่งซื้อ</button>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <a href="#" class="btn btn-success" onclick="window.print()">Print</a>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <?php include_once('../layout/footer.php') ?>
    </div>

    <?php include_once('../layout/config/script.php') ?>
</body>

<script>
    document.getElementById('od_status').addEventListener('change', function() {
        var tableNumberWrapper = document.getElementById('table_number_wrapper');
        if (this.value === 'กลับบ้าน') {
            // ซ่อนทั้งแถว "เลขโต๊ะ" ถ้าเลือก "กลับบ้าน"
            tableNumberWrapper.style.display = 'none';
        } else {
            // แสดงแถว "เลขโต๊ะ" ถ้าเลือก "ทานที่ร้าน"
            tableNumberWrapper.style.display = 'flex';
        }
    });

    document.getElementById('pay_amount2').addEventListener('input', function() {
        var totalAmount = parseFloat(document.getElementById('pay_amount1').value.replace(',', ''));
        var paidAmount = parseFloat(this.value);

        if (!isNaN(paidAmount) && paidAmount >= totalAmount) {
            var change = paidAmount - totalAmount;
            document.getElementById('change_amount').value = change.toFixed(2);
        } else {
            document.getElementById('change_amount').value = '';
        }
    });
</script>

</html>