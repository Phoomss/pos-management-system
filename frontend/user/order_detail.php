<?php
include_once('../../backend/config/condb.php'); // Include the database connection file
$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

// SQL query to fetch order details
$sql = "SELECT d.*, p.name as product_name, p.image, u.fullname, o.created_at as order_date, o.paid_amount, o.order_type
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
    <title>ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
    <style>
        /* Your existing styles */
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('../layout/header.php') ?>
        <?php include_once('./sidenav.php') ?>
        <div class="content-wrapper center">
            <section class="content">
                <div class="container-fluid">
                    <div class="text-center pt-3">
                        <h4>รายการสั่งซื้อ<br>
                            วัน/เดือน/ปี : <?php echo date('d/m/y', strtotime($row['created_at'])); ?></br>
                            Order Id : <?php echo $order_id; ?> </br>
                            ผู้ทำรายการ : <?php echo $row['fullname']; ?> <br />
                            สถานะ :<?php echo $row['order_type']; ?>
                        </h4>
                    </div>
                    <a href="../../backend/generate_pdf.php?order_id=<?php echo $order_id; ?>" class="btn btn-danger mb-3">ดาวน์โหลด PDF</a>
                    <table class="table table-hover table-bordered table-striped">
                        <thead class="bg-black">
                            <tr>
                                <td width="5%" align="center">ลำดับสินค้า</td>
                                <td width="35%" align="center">สินค้า</td>
                                <td width="10%" align="center">ราคา/หน่วย</td>
                                <td width="10%" align="center">จำนวน</td>
                                <td width="15%" align="center">รวม(บาท)</td>
                            </tr>
                        </thead>
                        <?php
                        $total = 0;
                        $i = 0; // Initialize item counter
                        // Reset pointer since fetch_assoc moved it
                        mysqli_data_seek($querypay, 0);
                        foreach ($querypay as $rspay) {
                            $total += $rspay['total_price']; // Calculate total
                            echo "<tr>";
                            echo "<td>" . ++$i . "</td>";
                            echo "<td>" . htmlspecialchars($rspay["product_name"]) . "</td>";
                            echo "<td align='right'>" . number_format($rspay["unit_price"], 2) . "</td>";
                            echo "<td align='right'>" . $rspay["quantity"] . "</td>"; // Display quantity
                            echo "<td align='right'>" . number_format($rspay['total_price'], 2) . "</td>";
                            echo "</tr>";
                        }
                        include('../../backend/convertnumtothai.php');
                        ?>
                        <tr>
                            <td></td>
                            <td align='right' colspan="3">
                                <b>ราคารวม ( <?php echo Convert($total); ?> )</b>
                                <br>
                                <b>ยอดเงินที่รับชำระ ( <?php echo Convert($row['paid_amount']); ?> )</b>
                                <br>
                                <?php
                                $pay_amount3 = $row['paid_amount'] - $total;
                                ?>
                                <b>เงินทอน ( <?php echo Convert($pay_amount3); ?> )</b>
                            </td>
                            <td align='right' colspan='2'>
                                <b><?php echo number_format($total, 2); ?> บาท</b>
                                <br>
                                <b><?php echo number_format($row['paid_amount'], 2); ?> บาท</b>
                                <br>
                                <b><?php echo number_format($pay_amount3, 2); ?> บาท</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </section>
        </div>

        <?php include_once('../layout/footer.php') ?>
        <?php include_once('../layout/config/script.php') ?>
    </div>
</body>

</html>