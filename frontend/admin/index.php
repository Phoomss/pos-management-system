<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('../../backend/config/condb.php');

// ดึงข้อมูลยอดขายจากฐานข้อมูล
$query = "SELECT DATE(od.created_at) AS sales_date, SUM(o.paid_amount) AS total_pay_amount
          FROM order_details od
          JOIN orders o ON od.order_id = o.id
          GROUP BY sales_date
          ORDER BY sales_date;";
$result = $conn->query($query);

$dates = [];
$sales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['sales_date'];
        $sales[] = $row['total_pay_amount'];
    }
}

// ดึงข้อมูลยอดขายรายเดือน
$query_monthly = "SELECT DATE_FORMAT(od.created_at, '%Y-%m') AS sales_month, SUM(o.paid_amount) AS total_pay_amount
                  FROM order_details od
                  JOIN orders o ON od.order_id = o.id
                  GROUP BY sales_month
                  ORDER BY sales_month;";
$result_monthly = $conn->query($query_monthly);

$months = [];
$sales_monthly = [];

if ($result_monthly->num_rows > 0) {
    while ($row = $result_monthly->fetch_assoc()) {
        $months[] = $row['sales_month'];
        $sales_monthly[] = $row['total_pay_amount'];
    }
}

// ดึงข้อมูลยอดขายตามประเภท
$query_types = "SELECT 
    DATE(created_at) AS order_date,
    SUM(CASE WHEN order_type = 'กลับบ้าน' THEN 1 ELSE 0 END) AS count_takeaway,
    SUM(CASE WHEN order_type = 'ทานที่ร้าน' THEN 1 ELSE 0 END) AS count_dinein
FROM 
    orders
GROUP BY 
    DATE(created_at)
ORDER BY 
    order_date DESC";

$result_types = $conn->query($query_types);

$order_dates = [];
$takeaway_counts = []; // เก็บข้อมูลจำนวนการซื้อกลับบ้าน
$dinein_counts = []; // เก็บข้อมูลจำนวนการทานที่ร้าน

if ($result_types->num_rows > 0) {
    while ($row = $result_types->fetch_assoc()) {
        $order_dates[] = $row['order_date'];          // เก็บวันที่
        $takeaway_counts[] = $row['count_takeaway'];  // เก็บจำนวนกลับบ้าน
        $dinein_counts[] = $row['count_dinein'];      // เก็บจำนวนทานที่ร้าน
    }
}


// ดึงข้อมูลยอดขายรายปี
$query_yearly = "SELECT YEAR(od.created_at) AS sales_year, SUM(o.paid_amount) AS total_pay_amount
                 FROM order_details od
                 JOIN orders o ON od.order_id = o.id
                 GROUP BY sales_year
                 ORDER BY sales_year;";
$result_yearly = $conn->query($query_yearly);

$years = [];
$sales_yearly = [];

if ($result_yearly->num_rows > 0) {
    while ($row = $result_yearly->fetch_assoc()) {
        $years[] = $row['sales_year'];
        $sales_yearly[] = $row['total_pay_amount'];
    }
}

$conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include_once('../layout/config/library.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        h1,
        h2 {
            color: #343a40;
        }

        .container-fluid {
            margin-top: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('../layout/header.php') ?>
        <?php include_once('./sidenav.php') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-3">Dashboard</h1>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title">ยอดขายต่อวัน</h2>
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title">อัตราการซื้อกลับบ้านและทานที่ร้าน</h2>
                                    <canvas id="orderTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title">ยอดขายต่อเดือน</h2>
                                    <canvas id="monthlySalesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title">ยอดขายต่อปี</h2>
                                    <canvas id="yearlySalesChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>

        <?php include_once('../layout/footer.php') ?>
    </div>

    <script>
        // JavaScript สำหรับ Chart.js
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'ยอดขายต่อวัน',
                    data: <?php echo json_encode($sales); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const monthlyCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'ยอดขายต่อเดือน',
                    data: <?php echo json_encode($sales_monthly); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const orderTypeCtx = document.getElementById('orderTypeChart').getContext('2d');
        const orderTypeChart = new Chart(orderTypeCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($order_dates); ?>, // ใช้วันที่เป็น labels
                datasets: [{
                        label: 'กลับบ้าน',
                        data: <?php echo json_encode($takeaway_counts); ?>, // จำนวนการซื้อแบบกลับบ้าน
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // สีสำหรับกลับบ้าน
                        borderColor: 'rgba(54, 162, 235, 1)', // เส้นขอบสำหรับกลับบ้าน
                        borderWidth: 1
                    },
                    {
                        label: 'ทานที่ร้าน',
                        data: <?php echo json_encode($dinein_counts); ?>, // จำนวนการซื้อแบบทานที่ร้าน
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // สีสำหรับทานที่ร้าน
                        borderColor: 'rgba(255, 99, 132, 1)', // เส้นขอบสำหรับทานที่ร้าน
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'อัตราการซื้อกลับบ้านและทานที่ร้าน (รายวัน)'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'วันที่'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'จำนวนการซื้อ'
                        },
                        beginAtZero: true
                    }
                }
            }
        });


        const yearlyCtx = document.getElementById('yearlySalesChart').getContext('2d');
        const yearlySalesChart = new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($years); ?>,
                datasets: [{
                    label: 'ยอดขายต่อปี',
                    data: <?php echo json_encode($sales_yearly); ?>,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <?php include_once('../layout/config/script.php') ?>
</body>

</html>
