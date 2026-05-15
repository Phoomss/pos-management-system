<?php
require('../fpdf/fpdf.php'); // Adjust the path as necessary
include_once('./config/condb.php'); // Include the database connection file

$order_id = (int)$_GET['order_id'];

// SQL query to fetch order details
$sql = "SELECT d.*, p.name, u.fullname, o.created_at, o.paid_amount, o.order_type
        FROM order_details AS d
        INNER JOIN products AS p ON d.product_id = p.id
        INNER JOIN orders AS o ON d.order_id = o.id
        INNER JOIN users AS u ON o.user_id = u.id
        WHERE d.order_id = ?";

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
    $total_order_amount += $item['total_price'];
}
$paid_amount = $row['paid_amount'];

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
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', date('d/m/y H:i:s', strtotime($row['created_at']))), 0, 1, 'C');
$pdf->Cell(0, 1, 'Order ID: ' . $order_id, 0, 1);
$pdf->Cell(0, 1, 'Customer: ' . iconv('UTF-8', 'TIS-620', $row['fullname']), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'สถานะ: ' . $row['order_type']), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'ยอดเงินรวม: ') . number_format($total_order_amount, 2) . iconv('UTF-8', 'TIS-620', ' บาท'), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'ยอดเงินที่รับชำระ: ') . number_format($paid_amount, 2) . iconv('UTF-8', 'TIS-620', ' บาท'), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'TIS-620', 'เงินทอน: ') . number_format($paid_amount - $total_order_amount, 2) . iconv('UTF-8', 'TIS-620', ' บาท'), 0, 1);

// Product details as text
$pdf->Cell(0, 2, iconv('UTF-8', 'TIS-620', 'รายการสินค้า'), 0, 1, 'C');

$i = 0;
foreach ($items as $rspay) {
    $i++;
    $product_details = $i . ". " . iconv('UTF-8', 'TIS-620', 'สินค้า: ') . iconv('UTF-8', 'TIS-620', $rspay['name']) . "\n" .
        iconv('UTF-8', 'TIS-620', 'จำนวน: ') . $rspay['quantity'] . "\n" .
        iconv('UTF-8', 'TIS-620', 'ราคา: ') . number_format($rspay['total_price'], 2) . iconv('UTF-8', 'TIS-620', ' บาท');
    $pdf->MultiCell(0, 0.9, $product_details);
}

// Output PDF
$pdf->Output('I', 'Order_Details_' . $order_id . '.pdf');
$stmt->close();

