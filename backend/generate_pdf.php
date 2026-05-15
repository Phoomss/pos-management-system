<?php
require('../fpdf/fpdf.php'); // Adjust the path as necessary
include_once('./config/condb.php'); // Include the database connection file

$order_id = (int)$_GET['order_id'];

// SQL query to fetch order details
$sql = "SELECT d.*, p.*, u.u_name, o.o_date, o.pay_amount2, od_status
        FROM order_details_table AS d
        INNER JOIN products_table AS p ON d.p_id = p.p_id
        INNER JOIN orders_table AS o ON d.o_id = o.o_id
        INNER JOIN users_table AS u ON o.u_id = u.u_id
        WHERE d.o_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$querypay = $stmt->get_result();

$row = $querypay->fetch_assoc(); // Fetch the first row for order information
if (!$row) {
    die("Order not found.");
}

// Calculate the total order amount
$total_order_amount = 0;
$items = [];
$items[] = $row; // Add the first row we just fetched

while ($item = $querypay->fetch_assoc()) {
    $items[] = $item;
}

foreach ($items as $item) {
    $total_order_amount += $item['total'];
}
$pay_amount2 = $row['pay_amount2'];

// Create a PDF document
$pdf = new FPDF('P', 'cm', array(10.2, 29.7));
$pdf->AddPage();

// Add Thai font
$pdf->AddFont('Sarabun-Bold', '', 'Sarabun-Bold.php');
$pdf->AddFont('Sarabun-Thin', '', 'Sarabun-Thin.php');
$width = $pdf->GetPageWidth();

// Header
$pdf->SetFont('Sarabun-Bold', '', 16);
$pdf->Cell($width - 2, 0, iconv('UTF-8', 'TIS-620', 'ข้าวมันไก่น้องนัน'), 0, 1, 'C');

// Order Info
$pdf->SetFont('Sarabun-Thin', '', 12);
$pdf->Cell(0, 1.5, iconv('UTF-8', 'TIS-620', 'รายละเอียดคำสั่งซื้อ'), 0, 1, 'C');
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', date('d/m/y H:i:s', strtotime($row['o_date']))), 0, 1, 'C');
$pdf->Cell(0, 1, 'Order ID: ' . $order_id, 0, 1);
$pdf->Cell(0, 1, 'Customer: ' . iconv('UTF-8', 'TIS-620', $row['u_name']), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'สถานะ: ' . $row['od_status']), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'ยอดเงินรวม: ') . number_format($total_order_amount, 2) . iconv('UTF-8', 'TIS-620', ' บาท'), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'ยอดเงินที่รับชำระ: ') . number_format($pay_amount2, 2) . iconv('UTF-8', 'TIS-620', ' บาท'), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'เงินทอน: ') . number_format($pay_amount2 - $total_order_amount, 2) . iconv('UTF-8', 'TIS-620', ' บาท'), 0, 1);

// Product details as text
$pdf->Cell(0, 2, iconv('UTF-8', 'TIS-620', 'รายการสินค้า'), 0, 1, 'C');

$i = 0;
foreach ($items as $rspay) {
    $i++;
    $product_details = $i . ". " . iconv('UTF-8', 'TIS-620', 'สินค้า: ') . iconv('UTF-8', 'TIS-620', $rspay['p_name']) . "\n" .
        iconv('UTF-8', 'TIS-620', 'จำนวน: ') . $rspay['qty'] . "\n" .
        iconv('UTF-8', 'TIS-620', 'ราคา: ') . number_format($rspay['total'], 2) . iconv('UTF-8', 'TIS-620', ' บาท');
    $pdf->MultiCell(0, 0.9, $product_details);
}

// Output PDF
$pdf->Output('I', 'Order_Details_' . $order_id . '.pdf');
$stmt->close();

