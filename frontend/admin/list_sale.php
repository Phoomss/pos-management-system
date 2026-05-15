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
    <title>ประวัติรายการขาย | ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
</head>

<body class="hold-transition">
    <div class="wrapper">
        <?php include_once('./sidenav.php') ?>

        <div class="main-content flex-grow-1 d-flex flex-column">
            <?php include_once('../layout/header.php') ?>

            <div class="content-wrapper">
                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">รายการขาย</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="index.php">แดชบอร์ด</a></li>
                                <li class="breadcrumb-item active">ประวัติการขาย</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Main content -->
                <div class="container-fluid p-0">
                    <?php
                    $act = $_GET['act'] ?? '';
                    if ($act == 'view') {
                        include('./order_detail.php');
                    } else {
                        include('./list_order.php');
                    } ?>
                </div>
            </div>

            <?php include_once('../layout/footer.php') ?>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>
</body>

</html>
