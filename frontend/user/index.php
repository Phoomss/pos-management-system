<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../../backend/config/condb.php';

// Fetch products logic
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = "WHERE is_active = 1 AND deleted_at IS NULL";
if (!empty($search)) {
    $where_clause .= " AND name LIKE '%$search%'";
}

$query_total = "SELECT COUNT(id) AS total FROM products $where_clause";
$result_total = $conn->query($query_total);
$total_rows = $result_total->fetch_assoc()['total'];

$page_rows = 12; 
$last_page = ceil($total_rows / $page_rows);
$pagenum = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
if ($pagenum < 1) $pagenum = 1; else if ($pagenum > $last_page && $last_page > 0) $pagenum = $last_page;
$limit = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;

$nquery = "SELECT * FROM products $where_clause ORDER BY id DESC $limit";
$product_results = $conn->query($nquery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าจอขายสินค้า | ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.25rem;
        }
        .pos-card {
            border-radius: 1rem;
            overflow: hidden;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .pos-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .pos-card img {
            height: 140px;
            object-fit: cover;
        }
        .pos-card-body {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .cart-sticky {
            position: sticky;
            top: 90px;
            height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>
        
        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper p-3 p-lg-4">
                <div class="row g-4">
                    <!-- Left: Product Selection -->
                    <div class="col-lg-8 col-xl-9">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h1 class="h4 fw-bold mb-0">เมนูอาหาร</h1>
                            <form action="" method="GET" class="d-flex" style="width: 300px;">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0" placeholder="ค้นหาเมนู..." value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                            </form>
                        </div>

                        <div class="product-grid">
                            <?php if ($product_results->num_rows > 0): ?>
                                <?php while ($rs_prd = $product_results->fetch_assoc()): ?>
                                    <a href="index.php?p_id=<?php echo $rs_prd['id']; ?>&act=add" class="text-decoration-none">
                                        <div class="card pos-card shadow-sm">
                                            <?php if($rs_prd['image']): ?>
                                                <img src="../../uploads/<?php echo $rs_prd['image']; ?>" alt="Product">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height:140px;">
                                                    <i class="fas fa-image fa-2x opacity-25"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="pos-card-body">
                                                <div class="fw-bold text-dark mb-1 text-truncate"><?php echo e($rs_prd['name']); ?></div>
                                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                                    <span class="text-primary fw-bold">฿<?php echo number_format($rs_prd['price'], 2); ?></span>
                                                    <div class="btn btn-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:28px; height:28px;">
                                                        <i class="fas fa-plus small"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted">ไม่พบเมนูที่คุณต้องการ</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($last_page > 1): ?>
                            <nav class="mt-5">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $last_page; $i++): ?>
                                        <li class="page-item <?php echo ($pagenum == $i) ? 'active' : ''; ?>">
                                            <a class="page-link shadow-sm mx-1 rounded" href="index.php?pn=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>

                    <!-- Right: Cart -->
                    <div class="col-lg-4 col-xl-3">
                        <div class="cart-sticky">
                            <?php include('cart.php'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>
</body>

</html>
