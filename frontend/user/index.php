<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>

    <!-- Additional Styles for Design Improvements -->
    <style>
        @media (max-width: 768px) {
            .card-img-top {
                height: 150px !important;
            }

            .card-body {
                padding: 10px !important;
            }
        }
    </style>
</head>

<?php
include '../../backend/config/condb.php';

// Fetch the total count of products
$query = "SELECT COUNT(id) AS total FROM products WHERE is_active = 1 AND deleted_at IS NULL";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$rows = $row['total'];

$page_rows = 6;  // Number of products per page
$last = ceil($rows / $page_rows);

if ($last < 1) {
    $last = 1;
}

$pagenum = 1;
if (isset($_GET['pn'])) {
    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if ($pagenum < 1) {
    $pagenum = 1;
} else if ($pagenum > $last) {
    $pagenum = $last;
}
$limit = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;

// Fetch paginated products
$nquery = "SELECT * FROM products WHERE is_active = 1 AND deleted_at IS NULL ORDER BY id DESC $limit";
$product_results = $conn->query($nquery);

// Pagination controls
$paginationCtrls = '';
if ($last != 1) {
    if ($pagenum > 1) {
        $previous = $pagenum - 1;
        $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '" class="btn btn-info">Previous</a> &nbsp; ';

        for ($i = $pagenum - 4; $i < $pagenum; $i++) {
            if ($i > 0) {
                $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '" class="btn btn-primary">' . $i . '</a> &nbsp; ';
            }
        }
    }

    $paginationCtrls .= '<a href="" class="btn btn-danger">' . $pagenum . '</a> &nbsp; ';

    for ($i = $pagenum + 1; $i <= $last; $i++) {
        $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '" class="btn btn-primary">' . $i . '</a> &nbsp; ';
        if ($i >= $pagenum + 4) {
            break;
        }
    }

    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $next . '" class="btn btn-info">Next</a> ';
    }
}

$conn->close();
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('../layout/header.php') ?>
        <?php include_once('./sidenav.php') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header" style="background-color: #f8f9fa;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">ขายสินค้า</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="card">
                    <div class="card-header bg-black">
                        <h3 class="card-title">สินค้า IN STOCK</h3>
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-7">
                                    <br>
                                    <?php if (mysqli_num_rows($product_results) > 0) { ?>
                                        <div class="row">
                                            <?php while ($rs_prd = mysqli_fetch_array($product_results)) { ?>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                                        <img width="100%" src="../../uploads/<?php echo e($rs_prd['image']); ?>" class="card-img-top" alt="<?php echo e($rs_prd['name']); ?>" style="height: 200px; object-fit: cover;">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-truncate"><?php echo e($rs_prd['name']); ?></h5>
                                                            <p class="card-text text-muted"><?php echo number_format($rs_prd['price'], 2); ?> บาท</p>
                                                            <!-- Add to Cart -->
                                                            <a href="index.php?p_id=<?php echo e($rs_prd['id']); ?>&act=add" class="btn btn-success"><i class="fa fa-shopping-cart"></i> หยิบลงตระกร้า</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <p>ไม่มีสินค้าในสต็อก</p>
                                    <?php } ?>
                                </div>
                                <div class="col-md-5">
                                    <?php include('cart.php'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <center>
                            <div id="pagination_controls">
                                <?php echo $paginationCtrls; ?>
                            </div>
                        </center>
                    </div>
            </section>
        </div>

        <?php include_once('../layout/footer.php') ?>
    </div>

    <?php include_once('../layout/config/script.php') ?>
</body>

</html>