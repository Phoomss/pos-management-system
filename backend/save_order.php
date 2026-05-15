<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    include './config/condb.php';
    csrf_verify();

    // Check if user is logged in
    $u_id = $_SESSION['user_id'] ?? '';
    if (empty($u_id)) {
        echo "<script>
        Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: 'กรุณาเข้าสู่ระบบก่อนทำรายการ' })
        .then(() => { window.location='../../index.php'; });
        </script>";
        exit();
    }

    // Retrieve request parameters
    $od_status = $_POST['od_status'] ?? '';
    $q_order = (int)($_POST['q_order'] ?? 0);
    $table_number = (int)($_POST['table_number'] ?? 0);
    $pay_amount1 = (float)($_POST['pay_amount1'] ?? 0);
    $pay_amount2 = (float)($_POST['pay_amount2'] ?? 0);
    $order_date = date("Y-m-d H:i:s");

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into orders
        $stmt1 = $conn->prepare("INSERT INTO orders (user_id, order_type, queue_number, table_number, total_amount, paid_amount, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt1->bind_param("isiidds", $u_id, $od_status, $q_order, $table_number, $pay_amount1, $pay_amount2, $order_date);
        if (!$stmt1->execute()) {
            throw new Exception("Error inserting into orders: " . $stmt1->error);
        }
        $order_id = $conn->insert_id;
        $stmt1->close();

        // Check if the cart exists and is not empty
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
            throw new Exception("ไม่มีสินค้าในตะกร้า");
        }

        // Prepare statements for the loop
        $stmt_product = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, unit_price, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($_SESSION['cart'] as $p_id => $qty) {
            $stmt_product->bind_param("i", $p_id);
            $stmt_product->execute();
            $res_product = $stmt_product->get_result();
            if ($row_product = $res_product->fetch_assoc()) {
                $productPrice = $row_product['price'];
                $total = $productPrice * $qty;

                $stmt_detail->bind_param("iiidds", $order_id, $p_id, $qty, $productPrice, $total, $order_date);
                if (!$stmt_detail->execute()) {
                    throw new Exception("Error inserting into order_details: " . $stmt_detail->error);
                }
            }
        }
        $stmt_product->close();
        $stmt_detail->close();

        $conn->commit();
        unset($_SESSION['cart']);

        echo "<script>
            Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: 'สั่งซื้อเรียบร้อยแล้ว!' })
            .then(() => { window.location = '../frontend/user/list_sale.php'; });
        </script>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>
        Swal.fire({ icon: 'error', title: 'ข้อผิดพลาด!', text: '" . addslashes($e->getMessage()) . "' })
        .then(() => { window.location = '../frontend/user/index.php'; });
        </script>";
    }
    ?>
</body>
</html>