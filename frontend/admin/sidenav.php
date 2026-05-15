<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar shadow shadow-lg">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Brand -->
        <div class="user-panel">
            <div class="info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="../user-info.png" class="rounded-circle shadow-sm" alt="User Image">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <a href="#" class="d-block mb-0">แผงควบคุมระบบ</a>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-sidebar flex-column">
                <li class="nav-header">จัดการการขาย</li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo $current_page == "index.php" ? "active" : ""; ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>ยอดขาย</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="list_sale.php" class="nav-link <?php echo $current_page == "list_sale.php" ? "active" : ""; ?>">
                        <i class="fas fa-receipt"></i>
                        <span>รายการขาย</span>
                    </a>
                </li>

                <li class="nav-header">จัดการข้อมูล</li>
                <li class="nav-item">
                    <a href="products.php" class="nav-link <?php echo $current_page == "products.php" ? "active" : ""; ?>">
                        <i class="fas fa-utensils"></i>
                        <span>เมนูอาหาร</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link <?php echo $current_page == "users.php" ? "active" : ""; ?>">
                        <i class="fas fa-users-cog"></i>
                        <span>ผู้ใช้งาน</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="roles.php" class="nav-link <?php echo $current_page == "roles.php" ? "active" : ""; ?>">
                        <i class="fas fa-shield-alt"></i>
                        <span>สิทธิ์การใช้งาน</span>
                    </a>
                </li>

                <li class="nav-header mt-4">ตั้งค่า</li>
                <li class="nav-item">
                    <a href="user_info.php" class="nav-link <?php echo $current_page == "user_info.php" ? "active" : ""; ?>">
                        <i class="fas fa-user-circle"></i>
                        <span>ข้อมูลส่วนตัว</span>
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a href="../../backend/auth/logout.php" class="nav-link text-danger-emphasis bg-danger-subtle mx-2" onclick="return confirm('ยืนยันออกจากระบบ?');">
                        <i class="fas fa-power-off text-danger"></i>
                        <span>ออกจากระบบ</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
