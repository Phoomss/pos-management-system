<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../user-info.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <?php
                if (isset($_SESSION['fullname'])) {
                    echo '<a href="#" class="d-block">' . htmlspecialchars($_SESSION["fullname"]) . '</a>';
                } else {
                    echo '<a href="#" class="d-block">Guest</a>';
                }
                ?>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">จัดการการขาย</li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo $menu == "index.php" ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>ยอดขาย</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="list_sale.php" class="nav-link <?php echo $menu == "list_sale.php" ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>รายการขาย</p>
                    </a>
                </li>
                <li class="nav-header">จัดการระบบ</li>
                <li class="nav-item">
                    <a href="products.php" class="nav-link <?php echo $menu == "products.php" ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>จัดการเมนูอาหาร</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link <?php echo $menu == "users.php" ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>จัดการผู้ใช้งาน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="roles.php" class="nav-link <?php echo $menu == "roles.php" ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>จัดการสถานะผู้ใช้งาน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="user_info.php" class="nav-link <?php echo $menu == "user_info.php" ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>ข้อมูลส่วนตัว</p>
                    </a>
                </li>
                <li class="nav-header">ออกจากระบบ</li>
                <li class="nav-item">
                    <a href="../../backend/auth/logout.php" class="nav-link" onclick="return confirm('ยืนยันออกจากระบบ !!');">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">ออกจากระบบ</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>