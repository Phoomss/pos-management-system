<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('../../backend/config/condb.php');

// 1. Summary Stats
// Today's Sales
$today = date('Y-m-d');
$q_today = "SELECT SUM(total_amount) as total FROM orders WHERE DATE(created_at) = '$today'";
$res_today = $conn->query($q_today);
$stat_today = $res_today->fetch_assoc()['total'] ?? 0;

// Monthly Sales
$month = date('Y-m');
$q_month = "SELECT SUM(total_amount) as total FROM orders WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month'";
$res_month = $conn->query($q_month);
$stat_month = $res_month->fetch_assoc()['total'] ?? 0;

// Total Orders Today
$q_orders = "SELECT COUNT(id) as count FROM orders WHERE DATE(created_at) = '$today'";
$res_orders = $conn->query($q_orders);
$stat_orders = $res_orders->fetch_assoc()['count'] ?? 0;

// Total Products
$q_products = "SELECT COUNT(id) as count FROM products WHERE deleted_at IS NULL";
$res_products = $conn->query($q_products);
$stat_products = $res_products->fetch_assoc()['count'] ?? 0;


// 2. Chart Data
// Daily Sales (Last 7 Days)
$query = "SELECT DATE(created_at) AS sales_date, SUM(total_amount) AS total
          FROM orders 
          WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          GROUP BY sales_date
          ORDER BY sales_date ASC;";
$result = $conn->query($query);
$dates = []; $sales = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = date('d M', strtotime($row['sales_date']));
    $sales[] = (float)$row['total'];
}

// Order Types (Takeaway vs Dine-in)
$query_types = "SELECT order_type, COUNT(id) as count FROM orders GROUP BY order_type";
$result_types = $conn->query($query_types);
$type_labels = []; $type_counts = [];
while ($row = $result_types->fetch_assoc()) {
    $type_labels[] = $row['order_type'];
    $type_counts[] = (int)$row['count'];
}

// Monthly Sales (Last 6 Months)
$query_monthly = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS sales_month, SUM(total_amount) AS total
                  FROM orders 
                  GROUP BY sales_month
                  ORDER BY sales_month ASC LIMIT 6;";
$result_monthly = $conn->query($query_monthly);
$months = []; $sales_monthly = [];
while ($row = $result_monthly->fetch_assoc()) {
    $months[] = date('M Y', strtotime($row['sales_month'] . "-01"));
    $sales_monthly[] = (float)$row['total'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด | ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>

        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper">
                <div class="mb-4">
                    <h1 class="h3 fw-bold mb-1">ภาพรวมระบบ</h1>
                    <p class="text-muted small mb-0">ข้อมูลประจำวันที่ <?php echo date('d/m/Y'); ?></p>
                </div>

                <!-- Stats Grid -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon bg-white bg-opacity-25"><i class="fas fa-coins text-white"></i></div>
                                    <span class="badge bg-white bg-opacity-25 rounded-pill text-white">Today</span>
                                </div>
                                <h6 class="text-white text-opacity-75 small">ยอดขายวันนี้</h6>
                                <h3 class="fw-bold mb-0">฿<?php echo number_format($stat_today, 2); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon bg-success-subtle text-success"><i class="fas fa-shopping-basket"></i></div>
                                    <span class="badge bg-success-subtle text-success rounded-pill">Orders</span>
                                </div>
                                <h6 class="text-muted small">จำนวนออเดอร์</h6>
                                <h3 class="fw-bold mb-0"><?php echo $stat_orders; ?> <small class="fw-normal text-muted fs-6">บิล</small></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon bg-warning-subtle text-warning"><i class="fas fa-calendar-alt"></i></div>
                                    <span class="badge bg-warning-subtle text-warning rounded-pill">Month</span>
                                </div>
                                <h6 class="text-muted small">ยอดขายเดือนนี้</h6>
                                <h3 class="fw-bold mb-0">฿<?php echo number_format($stat_month, 2); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon bg-info-subtle text-info"><i class="fas fa-utensils"></i></div>
                                    <span class="badge bg-info-subtle text-info rounded-pill">Menu</span>
                                </div>
                                <h6 class="text-muted small">เมนูทั้งหมด</h6>
                                <h3 class="fw-bold mb-0"><?php echo $stat_products; ?> <small class="fw-normal text-muted fs-6">เมนู</small></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header border-0 bg-transparent py-4 ps-4">
                                <h5 class="card-title fw-bold">เทรนด์ยอดขายรายวัน (7 วันล่าสุด)</h5>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <canvas id="salesChart" style="max-height: 350px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header border-0 bg-transparent py-4 ps-4">
                                <h5 class="card-title fw-bold">สัดส่วนออเดอร์</h5>
                            </div>
                            <div class="card-body px-4 pb-4 d-flex align-items-center">
                                <canvas id="orderTypeChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header border-0 bg-transparent py-4 ps-4">
                                <h5 class="card-title fw-bold">ผลประกอบการรายเดือน</h5>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <canvas id="monthlyChart" style="max-height: 250px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>
    
    <script>
        // Global Chart Options
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. Daily Sales Chart
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: <?php echo json_encode($sales); ?>,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], drawBorder: false } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Order Type Donut
        new Chart(document.getElementById('orderTypeChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($type_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($type_counts); ?>,
                    backgroundColor: ['#4f46e5', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });

        // 3. Monthly Sales Bar
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'ยอดขายรวม',
                    data: <?php echo json_encode($sales_monthly); ?>,
                    backgroundColor: '#e0e7ff',
                    hoverBackgroundColor: '#4f46e5',
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false, drawBorder: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>

</html>
